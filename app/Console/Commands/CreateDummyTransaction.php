<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Services\SyncService;
use Illuminate\Console\Command;

class CreateDummyTransaction extends Command
{
    protected $signature = 'sync:create-dummy-transaction
                            {--branch_id=4 : Branch ID}
                            {--terminal_id=11 : Terminal ID}
                            {--enqueue : Enqueue for sync to cloud}';

    protected $description = 'Create a dummy transaction for a given branch/terminal (e.g. for testing sync to cloud).';

    public function handle(): int
    {
        $branchId = (int) $this->option('branch_id');
        $terminalId = (int) $this->option('terminal_id');
        $enqueue = $this->option('enqueue');

        $branch = Branch::find($branchId);
        if (! $branch) {
            $this->error("Branch id {$branchId} not found.");
            return 1;
        }

        $terminal = \App\Models\Terminal::where('id', $terminalId)
            ->where('branch_id', $branchId)
            ->first();
        if (! $terminal) {
            $this->error("Terminal id {$terminalId} not found for branch {$branchId}.");
            return 1;
        }

        $cashier = User::where('branch_id', $branchId)->first();
        if (! $cashier) {
            $cashier = User::first();
        }
        if (! $cashier) {
            $this->error('No user found to use as cashier.');
            return 1;
        }

        $product = Product::first();
        if (! $product) {
            $this->error('No product found. Create at least one product first.');
            return 1;
        }

        $orNumber = (string) ($branch->current_or_number + 1);
        $branch->increment('current_or_number');

        $unitPrice = 10.00;
        $quantity = 2;
        $subtotal = $unitPrice * $quantity;
        $discountAmount = 0;
        $total = $subtotal - $discountAmount;

        $transaction = Transaction::create([
            'branch_id' => $branchId,
            'terminal_id' => $terminalId,
            'or_number' => $orNumber,
            'cashier_id' => $cashier->id,
            'total' => $total,
            'vat_amount' => 0,
            'discount_amount' => $discountAmount,
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction->id,
            'product_id' => $product->id,
            'product_batch_id' => null,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'subtotal' => $subtotal,
        ]);

        if ($enqueue && env('SYNC_MODE') === 'direct_db' && config('database.connections.mysql_cloud.host')) {
            app(SyncService::class)->enqueueTransaction($transaction->fresh());
            $this->info('Transaction enqueued for sync to cloud.');
        }

        $this->info("Dummy transaction created: id={$transaction->id}, or_number={$orNumber}, branch_id={$branchId}, terminal_id={$terminalId}.");
        return 0;
    }
}
