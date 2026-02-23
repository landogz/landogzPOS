<?php

namespace App\Console\Commands;

use App\Models\PendingSyncQueue;
use App\Models\Transaction;
use App\Services\SyncService;
use Illuminate\Console\Command;

class EnqueueUnsyncedTransactions extends Command
{
    protected $signature = 'sync:enqueue-unsynced-transactions';

    protected $description = 'Enqueue local transactions that were never pushed to cloud (e.g. created before sync was enabled).';

    public function handle(): int
    {
        if (env('SYNC_MODE') !== 'direct_db' || ! config('database.connections.mysql_cloud.host')) {
            $this->warn('Sync is not in direct_db mode or cloud DB not configured. No enqueue done.');
            return 0;
        }

        $alreadyEnqueuedIds = PendingSyncQueue::where('model_type', 'transactions')
            ->pluck('record_id')
            ->unique()
            ->values()
            ->all();

        $unsynced = Transaction::whereNotIn('id', $alreadyEnqueuedIds)->orderBy('id')->get();
        if ($unsynced->isEmpty()) {
            $this->info('No unsynced transactions found.');
            return 0;
        }

        $sync = app(SyncService::class);
        foreach ($unsynced as $transaction) {
            $sync->enqueueTransaction($transaction);
            $this->line("Enqueued transaction id={$transaction->id}, OR #{$transaction->or_number}.");
        }

        $this->info('Enqueued ' . $unsynced->count() . ' transaction(s). Run "php artisan schedule:run" or POST /api/v1/sync/push to push to cloud.');
        return 0;
    }
}
