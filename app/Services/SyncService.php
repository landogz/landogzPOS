<?php

namespace App\Services;

use App\Models\PendingSyncQueue;
use App\Models\SyncLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SyncService
{
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
     * Pull updates from cloud (e.g. product/price updates from HQ). Called by scheduler.
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
