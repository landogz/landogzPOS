<?php

namespace App\Services;

use App\Models\BirSetting;
use App\Models\Company;
use App\Models\PendingSyncQueue;
use App\Models\SyncLog;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SyncService
{
    /**
     * Enqueue a transaction and its items for sync to cloud (push).
     * Call after creating or updating a transaction locally.
     */
    public function enqueueTransaction(Transaction $transaction): void
    {
        $transaction->load('items');
        $payload = $transaction->getAttributes();
        $this->normalizePayloadForSync($payload);
        PendingSyncQueue::create([
            'model_type' => 'transactions',
            'record_id' => $transaction->id,
            'action' => 'update',
            'payload' => $payload,
            'status' => PendingSyncQueue::STATUS_PENDING,
        ]);
        foreach ($transaction->items as $item) {
            $itemPayload = $item->getAttributes();
            $this->normalizePayloadForSync($itemPayload);
            PendingSyncQueue::create([
                'model_type' => 'transaction_items',
                'record_id' => $item->id,
                'action' => 'update',
                'payload' => $itemPayload,
                'status' => PendingSyncQueue::STATUS_PENDING,
            ]);
        }
    }

    /**
     * Ensure payload is JSON-serializable (e.g. Carbon to string).
     */
    protected function normalizePayloadForSync(array &$payload): void
    {
        foreach ($payload as $key => $value) {
            if ($value instanceof \DateTimeInterface) {
                $payload[$key] = $value->format('Y-m-d H:i:s');
            }
        }
    }

    /**
     * Enqueue a company for sync to cloud (push). Call after create/update.
     */
    public function enqueueCompany(Company $company): void
    {
        if (env('SYNC_MODE') !== 'direct_db' || ! config('database.connections.mysql_cloud.host')) {
            return;
        }
        $payload = $company->getAttributes();
        $this->normalizePayloadForSync($payload);
        PendingSyncQueue::create([
            'model_type' => 'companies',
            'record_id' => $company->id,
            'action' => 'update',
            'payload' => $payload,
            'status' => PendingSyncQueue::STATUS_PENDING,
        ]);
    }

    /**
     * Enqueue a user for sync to cloud (push). Call after create/update.
     */
    public function enqueueUser(User $user): void
    {
        if (env('SYNC_MODE') !== 'direct_db' || ! config('database.connections.mysql_cloud.host')) {
            return;
        }
        $payload = $user->getAttributes();
        $this->normalizePayloadForSync($payload);
        PendingSyncQueue::create([
            'model_type' => 'users',
            'record_id' => $user->id,
            'action' => 'update',
            'payload' => $payload,
            'status' => PendingSyncQueue::STATUS_PENDING,
        ]);
    }

    /**
     * Enqueue BIR settings for sync to cloud (push). Call after update on /dashboard/bir-settings.
     */
    public function enqueueBirSetting(BirSetting $birSetting): void
    {
        if (env('SYNC_MODE') !== 'direct_db' || ! config('database.connections.mysql_cloud.host')) {
            return;
        }
        $payload = $birSetting->getAttributes();
        $this->normalizePayloadForSync($payload);
        PendingSyncQueue::create([
            'model_type' => 'bir_settings',
            'record_id' => $birSetting->id,
            'action' => 'update',
            'payload' => $payload,
            'status' => PendingSyncQueue::STATUS_PENDING,
        ]);
    }

    /**
     * Push pending records to cloud. Called by scheduler (e.g. every 5 min).
     */
    public function pushToCloud(): void
    {
        $pending = PendingSyncQueue::where('status', PendingSyncQueue::STATUS_PENDING)->get();
        $branchId = config('app.branch_id');
        $synced = 0;
        $errors = [];

        foreach ($pending as $item) {
            try {
                if (env('SYNC_MODE') === 'direct_db' && config('database.connections.mysql_cloud.host')) {
                    $this->pushViaDirectDb($item);
                } else {
                    $this->pushViaApi($item);
                }
                $item->update([
                    'status' => PendingSyncQueue::STATUS_SYNCED,
                    'synced_at' => now(),
                ]);
                $synced++;
            } catch (\Throwable $e) {
                $item->increment('attempt_count');
                $item->update([
                    'status' => PendingSyncQueue::STATUS_FAILED,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $e->getMessage();
            }
        }

        if ($synced > 0 || !empty($errors)) {
            SyncLog::create([
                'branch_id' => $branchId,
                'direction' => 'push',
                'status' => empty($errors) ? 'success' : 'partial',
                'records_synced' => $synced,
                'error_message' => empty($errors) ? null : implode('; ', array_slice($errors, 0, 5)),
                'synced_at' => now(),
            ]);
        }
    }

    protected function pushViaApi(PendingSyncQueue $item): void
    {
        $url = rtrim(env('CLOUD_API_URL', ''), '/') . '/sync/receive';
        $token = env('CLOUD_API_TOKEN');
        if (!$url || !$token) {
            throw new \RuntimeException('CLOUD_API_URL and CLOUD_API_TOKEN are required for API sync.');
        }
        $response = Http::withToken($token)
            ->timeout(30)
            ->post($url, [
                'branch_id' => config('app.branch_id'),
                'model_type' => $item->model_type,
                'action' => $item->action,
                'payload' => $item->payload,
            ]);
        if (!$response->successful()) {
            throw new \RuntimeException('Sync receive failed: ' . $response->body());
        }
    }

    protected function pushViaDirectDb(PendingSyncQueue $item): void
    {
        $payload = is_string($item->payload) ? json_decode($item->payload, true) : $item->payload;
        if (!$payload) {
            return;
        }
        $table = $item->model_type;
        if ($item->action === 'delete') {
            $id = $payload['id'] ?? null;
            if ($id) {
                DB::connection('mysql_cloud')->table($table)->where('id', $id)->delete();
            }
            return;
        }
        DB::connection('mysql_cloud')->table($table)->upsert(
            [$payload],
            ['id'],
            array_keys($payload)
        );
    }

    /**
     * Pull companies, branches, users, and terminals from cloud DB into local (direct_db only).
     * Source of truth is online DB; all local nodes get the same master data.
     * Order: companies → branches → users → terminals (respects FKs).
     */
    public function pullMasterDataFromCloudDirectDb(): array
    {
        $host = config('database.connections.mysql_cloud.host');
        if (env('SYNC_MODE') !== 'direct_db' || ! $host) {
            return ['companies' => 0, 'branches' => 0, 'bir_settings' => 0, 'users' => 0, 'terminals' => 0];
        }

        $cloud = DB::connection('mysql_cloud');
        $counts = ['companies' => 0, 'branches' => 0, 'bir_settings' => 0, 'users' => 0, 'terminals' => 0];

        foreach (['companies', 'branches', 'bir_settings', 'users', 'terminals'] as $table) {
            try {
                $rows = $cloud->table($table)->get();
                $arr = $rows->map(fn ($r) => (array) $r)->toArray();
                if (! empty($arr)) {
                    $columns = array_keys($arr[0]);
                    DB::table($table)->upsert($arr, ['id'], $columns);
                }
                $counts[$table] = count($arr);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $this->syncCompanyLogosFromCloud($cloud);

        $total = array_sum($counts);
        if ($total > 0) {
            SyncLog::create([
                'branch_id' => config('app.branch_id'),
                'direction' => 'pull_master',
                'status' => 'success',
                'records_synced' => $total,
                'synced_at' => now(),
            ]);
        }

        return $counts;
    }

    /**
     * Download company logo files from cloud app URL to local storage (so /storage/companies/xxx.png works locally).
     */
    protected function syncCompanyLogosFromCloud($cloudConnection): void
    {
        $baseUrl = rtrim((string) env('CLOUD_APP_URL', ''), '/');
        if ($baseUrl === '') {
            return;
        }

        $companies = $cloudConnection->table('companies')
            ->whereNotNull('logo')
            ->where('logo', '!=', '')
            ->get();

        foreach ($companies as $row) {
            $logoPath = $row->logo;
            if (! is_string($logoPath) || $logoPath === '') {
                continue;
            }
            try {
                $url = $baseUrl . '/storage/' . ltrim($logoPath, '/');
                $response = Http::timeout(15)->get($url);
                if ($response->successful() && $response->body() !== '') {
                    Storage::disk('public')->put($logoPath, $response->body());
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }

    /**
     * Pull updates from cloud (e.g. product/price updates from HQ). Called by scheduler (API mode).
     */
    public function pullFromCloud(): void
    {
        $url = rtrim(env('CLOUD_API_URL', ''), '/') . '/sync/pull';
        $token = env('CLOUD_API_TOKEN');
        if (!$url || !$token) {
            return;
        }
        $lastSynced = SyncLog::where('direction', 'pull')->latest('synced_at')->value('synced_at');
        $response = Http::withToken($token)
            ->timeout(60)
            ->get($url, [
                'branch_id' => config('app.branch_id'),
                'last_synced' => $lastSynced?->toIso8601String(),
            ]);
        if (!$response->successful()) {
            return;
        }
        $data = $response->json('data', []);
        foreach ($data as $record) {
            $table = $record['table'] ?? null;
            $rows = $record['data'] ?? [];
            if (!$table || !is_array($rows)) {
                continue;
            }
            foreach ($rows as $row) {
                DB::table($table)->upsert([$row], ['id']);
            }
        }
        SyncLog::create([
            'branch_id' => config('app.branch_id'),
            'direction' => 'pull',
            'status' => 'success',
            'records_synced' => count($data),
            'synced_at' => now(),
        ]);
    }

    /**
     * Heartbeat to cloud (optional). Called by scheduler every minute.
     */
    public function heartbeat(): void
    {
        $url = rtrim(env('CLOUD_API_URL', ''), '/') . '/sync/heartbeat';
        $token = env('CLOUD_API_TOKEN');
        if (!$url || !$token) {
            return;
        }
        Http::withToken($token)->timeout(5)->post($url, [
            'branch_id' => config('app.branch_id'),
            'node_id' => config('app.node_id'),
        ]);
    }
}
