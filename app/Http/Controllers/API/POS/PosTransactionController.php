<?php

namespace App\Http\Controllers\API\POS;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\TransactionItem;
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
            'payment_method' => 'nullable|string|in:cash,card,other',
            'discount_amount' => 'nullable|numeric|min:0',
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
        $orNumber = $branch ? (string) ($branch->current_or_number + 1) : '1';
        if ($branch) {
            $branch->increment('current_or_number');
        }

        $total = 0;
        $vatAmount = 0;
        $discountAmount = (float) ($validated['discount_amount'] ?? 0);
        $transaction = Transaction::create([
            'branch_id' => $branchId,
            'terminal_id' => $terminalId,
            'or_number' => $orNumber,
            'cashier_id' => $user->id,
            'total' => 0,
            'vat_amount' => 0,
            'discount_amount' => $discountAmount,
            'payment_method' => $validated['payment_method'] ?? 'cash',
            'status' => 'completed',
        ]);

        foreach ($validated['items'] as $row) {
            $subtotal = $row['quantity'] * $row['unit_price'];
            $total += $subtotal;
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $row['product_id'],
                'product_batch_id' => $row['product_batch_id'] ?? null,
                'quantity' => $row['quantity'],
                'unit_price' => $row['unit_price'],
                'subtotal' => $subtotal,
            ]);
            if (!empty($row['product_batch_id'])) {
                \App\Models\ProductBatch::where('id', $row['product_batch_id'])
                    ->decrement('quantity', $row['quantity']);
            }
        }
        $transaction->update([
            'total' => $total - $discountAmount,
            'vat_amount' => $vatAmount,
        ]);

        $transaction->load('items.product');
        return response()->json([
            'status' => true,
            'message' => 'Transaction completed.',
            'data' => $transaction,
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
