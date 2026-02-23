<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BirSetting;
use App\Models\Transaction;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    /**
     * GET /api/v1/receipts/{transaction_id} — BIR-style receipt data for printing.
     * Branch-scoped: user can only view receipts for transactions in their branch (super_admin/admin can view any).
     */
    public function show(Request $request, int $transaction_id): JsonResponse
    {
        $transaction = Transaction::with([
            'items.product',
            'branch.company',
            'cashier',
            'officialReceipt',
            'discounts',
        ])->findOrFail($transaction_id);

        $this->ensureReceiptAccess($request->user(), $transaction);

        $bir = BirSetting::where('branch_id', $transaction->branch_id)->first();
        $receipt = [
            'transaction_id' => $transaction->id,
            'or_number' => $transaction->or_number,
            'pharmacy_name' => $transaction->branch?->company?->name ?? $transaction->branch?->name ?? config('app.name'),
            'address' => $transaction->branch?->address,
            'tin' => $bir?->tin ?? $transaction->officialReceipt?->tin,
            'bir_accreditation_number' => $bir?->accreditation_number ?? $transaction->officialReceipt?->bir_accreditation,
            'validity' => $bir ? [$bir->valid_from?->format('Y-m-d'), $bir->valid_until?->format('Y-m-d')] : null,
            'items' => $transaction->items->map(fn ($i) => [
                'product_name' => $i->product?->name,
                'quantity' => (float) $i->quantity,
                'unit_price' => (float) $i->unit_price,
                'subtotal' => (float) $i->subtotal,
            ])->values()->all(),
            'vatable_sales' => (float) ($transaction->officialReceipt?->vatable_sales ?? $transaction->total - $transaction->vat_amount),
            'vat_amount' => (float) ($transaction->officialReceipt?->vat_amount ?? $transaction->vat_amount),
            'vat_exempt' => (float) ($transaction->officialReceipt?->vat_exempt ?? 0),
            'discounts' => $transaction->discounts->map(fn ($d) => [
                'type' => $d->type,
                'amount' => (float) $d->amount,
                'customer_name' => $d->customer_name,
            ])->values()->all(),
            'discount_amount' => (float) $transaction->discount_amount,
            'total' => (float) $transaction->total,
            'payment_method' => $transaction->payment_method,
            'cashier_name' => $transaction->cashier?->name,
            'issued_at' => $transaction->created_at->format('Y-m-d H:i:s'),
            'footer_text' => $bir?->footer_text ?? 'This document is not valid for claim of input tax.',
        ];

        return response()->json([
            'status' => 'success',
            'data' => $receipt,
        ]);
    }

    /**
     * POST /api/v1/receipts/print — legacy: queue print (returns receipt data).
     */
    public function print(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
        ]);
        $transaction = Transaction::findOrFail($validated['transaction_id']);
        $this->ensureReceiptAccess($request->user(), $transaction);
        $response = $this->show($request, $validated['transaction_id']);
        $data = $response->getData(true);
        return response()->json([
            'status' => 'success',
            'message' => 'Print job queued.',
            'data' => $data['data'] ?? null,
        ]);
    }

    /**
     * POST /api/v1/receipts/reprint/{id} — log reprint event.
     */
    public function reprint(Request $request, int $id): JsonResponse
    {
        $transaction = Transaction::findOrFail($id);
        $this->ensureReceiptAccess($request->user(), $transaction);
        AuditLogService::log('receipt_reprint', 'transactions', (int) $transaction->id, null, ['reprinted_at' => now()->toIso8601String()], $request, $transaction->branch_id);
        $response = $this->show($request, $id);
        $data = $response->getData(true);
        return response()->json([
            'status' => 'success',
            'message' => 'Reprint logged.',
            'data' => $data['data'] ?? null,
        ]);
    }

    private function ensureReceiptAccess($user, Transaction $transaction): void
    {
        if (! $user) {
            return;
        }
        if ($user->role === 'super_admin') {
            return;
        }
        if ($user->role === 'admin' && $user->company_id) {
            $transaction->load('branch');
            if (! $transaction->branch || (int) $transaction->branch->company_id !== (int) $user->company_id) {
                abort(404, 'Receipt not found.');
            }
            return;
        }
        if ($user->branch_id && (int) $transaction->branch_id !== (int) $user->branch_id) {
            abort(404, 'Receipt not found.');
        }
    }
}
