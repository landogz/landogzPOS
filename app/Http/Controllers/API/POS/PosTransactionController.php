<?php

namespace App\Http\Controllers\API\POS;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Discount;
use App\Models\OfficialReceipt;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Services\AuditLogService;
use App\Services\SyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PosTransactionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_batch_id' => 'nullable|exists:product_batches,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.prescription_number' => 'nullable|string|max:100',
            'payment_method' => 'nullable|string|in:cash,card,other,ewallet',
            'payment_reference' => 'nullable|string|max:100',
            'payment_provider' => 'nullable|string|max:100',
            'customer_name' => 'nullable|string|max:255',
            'customer_address' => 'nullable|string|max:500',
            'discount_amount' => 'nullable|numeric|min:0',
            'discounts' => 'nullable|array',
            'discounts.*.type' => 'required|string|in:sc_pwd,senior_citizen,pwd,employee,promo,manual',
            'discounts.*.amount' => 'required|numeric|min:0',
            'discounts.*.reference_id' => 'nullable|string|max:100',
            'discounts.*.customer_name' => 'nullable|string|max:255',
            'terminal_id' => 'required|exists:terminals,id',
        ]);
        $user = $request->user();
        $branchId = $user->branch_id;
        if (! $branchId) {
            return response()->json([
                'status' => false,
                'message' => 'Your account must be assigned to a branch. Transactions are saved to the logged-in cashier\'s branch.',
            ], 403);
        }
        $terminal = \App\Models\Terminal::where('id', $validated['terminal_id'])
            ->where('branch_id', $branchId)
            ->first();
        if (! $terminal) {
            return response()->json([
                'status' => false,
                'message' => 'POS is not registered. Please register this terminal in Settings.',
            ], 403);
        }
        if (! $terminal->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'POS is not registered. This terminal is inactive. Please contact your administrator.',
            ], 403);
        }
        $terminalId = $terminal->id;
        $branch = Branch::find($branchId);
        if ($branch) {
            $nextOr = (int) $branch->current_or_number + 1;
            $orNumber = str_pad((string) $nextOr, 10, '0', STR_PAD_LEFT);
            $branch->increment('current_or_number');
        } else {
            $orNumber = '0000000001';
        }

        $itemsSubtotal = 0;
        $transaction = Transaction::create([
            'branch_id' => $branchId,
            'terminal_id' => $terminalId,
            'or_number' => $orNumber,
            'cashier_id' => $user->id,
            'total' => 0,
            'vat_amount' => 0,
            'discount_amount' => 0,
            'payment_method' => $validated['payment_method'] ?? 'cash',
            'payment_reference' => $validated['payment_reference'] ?? null,
            'payment_provider' => $validated['payment_provider'] ?? null,
            'customer_name' => $validated['customer_name'] ?? null,
            'customer_address' => $validated['customer_address'] ?? null,
            'status' => 'completed',
        ]);

        foreach ($validated['items'] as $row) {
            $subtotal = $row['quantity'] * $row['unit_price'];
            $itemsSubtotal += $subtotal;
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $row['product_id'],
                'product_batch_id' => $row['product_batch_id'] ?? null,
                'quantity' => $row['quantity'],
                'unit_price' => $row['unit_price'],
                'subtotal' => $subtotal,
                'prescription_number' => $row['prescription_number'] ?? null,
            ]);
            if (! empty($row['product_batch_id'])) {
                \App\Models\ProductBatch::where('id', $row['product_batch_id'])
                    ->decrement('quantity', $row['quantity']);
            }
        }

        $discountFromPayload = (float) ($validated['discount_amount'] ?? 0);
        $discountRecords = $validated['discounts'] ?? [];
        $totalDiscount = $discountFromPayload;
        foreach ($discountRecords as $d) {
            $amt = (float) ($d['amount'] ?? 0);
            $totalDiscount += $amt;
            Discount::create([
                'transaction_id' => $transaction->id,
                'type' => $d['type'],
                'amount' => $amt,
                'reference_id' => $d['reference_id'] ?? null,
                'customer_name' => $d['customer_name'] ?? null,
            ]);
        }

        $vatableSales = round($itemsSubtotal / 1.12, 2);
        $vatAmount = round($itemsSubtotal - $vatableSales, 2);
        $vatExempt = 0;
        $bir = \App\Models\BirSetting::where('branch_id', $branchId)->first();

        $transaction->update([
            'total' => round($itemsSubtotal - $totalDiscount, 2),
            'vat_amount' => $vatAmount,
            'discount_amount' => $totalDiscount,
        ]);

        OfficialReceipt::create([
            'transaction_id' => $transaction->id,
            'or_number' => $orNumber,
            'tin' => $bir?->tin,
            'bir_accreditation' => $bir?->accreditation_number,
            'vatable_sales' => $vatableSales,
            'vat_amount' => $vatAmount,
            'vat_exempt' => $vatExempt,
            'issued_at' => now(),
        ]);

        if (env('SYNC_MODE') === 'direct_db' && config('database.connections.mysql_cloud.host')) {
            app(SyncService::class)->enqueueTransaction($transaction->fresh());
        }

        $transaction->load('items.product', 'discounts');
        AuditLogService::log('transaction_completed', 'transactions', (int) $transaction->id, null, ['or_number' => $orNumber, 'total' => $transaction->total, 'payment_method' => $transaction->payment_method], $request, $branchId, $user->id);

        return response()->json([
            'status' => true,
            'message' => 'Transaction completed.',
            'data' => array_merge($transaction->toArray(), ['or_number' => $orNumber]),
        ], 201);
    }

    public function receipt(Request $request, Transaction $transaction): JsonResponse
    {
        $this->ensureTransactionBranchAccess($request->user(), $transaction);
        $transaction->load(['items.product', 'branch', 'cashier']);
        return response()->json(['status' => true, 'data' => $transaction]);
    }

    public function void(Request $request, Transaction $transaction): JsonResponse
    {
        $this->ensureTransactionBranchAccess($request->user(), $transaction);
        if ($transaction->status === 'voided') {
            return response()->json(['status' => false, 'message' => 'Already voided.'], 422);
        }
        $transaction->update(['status' => 'voided']);
        foreach ($transaction->items as $item) {
            if ($item->product_batch_id) {
                \App\Models\ProductBatch::where('id', $item->product_batch_id)
                    ->increment('quantity', $item->quantity);
            }
        }
        if (env('SYNC_MODE') === 'direct_db' && config('database.connections.mysql_cloud.host')) {
            app(SyncService::class)->enqueueTransaction($transaction->fresh());
        }
        AuditLogService::log('transaction_voided', 'transactions', (int) $transaction->id, ['status' => 'completed'], ['status' => 'voided'], $request, $transaction->branch_id);
        return response()->json(['status' => true, 'message' => 'Transaction voided.', 'data' => $transaction->fresh()]);
    }

    private function ensureTransactionBranchAccess($user, Transaction $transaction): void
    {
        if (! $user) {
            return;
        }
        if (in_array($user->role, ['super_admin', 'admin'], true)) {
            return;
        }
        if ($user->branch_id && (int) $transaction->branch_id !== (int) $user->branch_id) {
            abort(404, 'Transaction not found.');
        }
    }
}
