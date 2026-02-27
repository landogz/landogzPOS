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
            'terminal',
            'cashier',
            'officialReceipt',
            'discounts',
        ])->findOrFail($transaction_id);

        $this->ensureReceiptAccess($request->user(), $transaction);

        $branch = $transaction->branch;
        $company = $branch?->company;
        $terminal = $transaction->terminal;
        $birBranch = BirSetting::where('branch_id', $transaction->branch_id)->first();

        // Top of receipt: company (name, TIN) + branch (address) + terminal (MIN, SN) — header TIN is company TIN
        $receiptHeader = [
            'company_name' => $company?->name ?? $branch?->name ?? config('app.name'),
            'branch_name' => $branch?->name ?? '',
            'address' => $branch?->address ?? '',
            'vat_registered_tin' => $company?->tin ?? $branch?->tin ?? '',
            'terminal_code' => $terminal?->code ?? '',
            'terminal_name' => $terminal?->name ?? '',
            'min' => $terminal?->min ?? '',
            'sn' => $terminal?->sn ?? $terminal?->code ?? '',
            'tin' => $company?->tin ?? $branch?->tin ?? '',
            'logo_url' => $branch?->logo_url ?? $company?->logo_url ?? null,
        ];

        // System provider footer (BIR required): ONE config for ALL receipts — from first BIR record (dashboard/bir-settings)
        $birProvider = BirSetting::orderBy('id')->first();
        $receiptFooter = [
            'tin' => $birProvider?->tin ?? $birBranch?->tin ?? $branch?->tin ?? '',
            'footer_text' => $birProvider?->footer_text ?? 'This document is not valid for claim of input tax.',
        ];

        $receipt = [
            'transaction_id' => $transaction->id,
            'or_number' => $transaction->or_number,
            'receipt_header' => $receiptHeader,
            'receipt_footer' => $receiptFooter,
            'pharmacy_name' => $receiptHeader['company_name'],
            'address' => $receiptHeader['address'],
            // System provider footer (BIR required) — same for all receipts, from /dashboard/bir-settings
            'pos_system_provider' => $birProvider?->provider_name,
            'provider_address' => $birProvider?->provider_address,
            'tin' => $receiptFooter['tin'],
            'bir_accreditation_number' => $birProvider?->accreditation_number ?? $transaction->officialReceipt?->bir_accreditation,
            'ptu_number' => $birProvider?->ptu_number,
            'validity_statement' => $birProvider?->validity_statement,
            'validity' => $birProvider ? [$birProvider->valid_from?->format('Y-m-d'), $birProvider->valid_until?->format('Y-m-d')] : null,
            'items' => $transaction->items->map(function ($i) use ($transaction) {
                $vatExempt = (float) ($transaction->officialReceipt?->vat_exempt ?? 0);
                return [
                    'product_name' => $i->product?->name,
                    'quantity' => (float) $i->quantity,
                    'unit_price' => (float) $i->unit_price,
                    'subtotal' => (float) $i->subtotal,
                    'prescription_number' => $i->prescription_number,
                    'is_vat_exempt' => $vatExempt > 0,
                ];
            })->values()->all(),
            'vatable_sales' => (float) ($transaction->officialReceipt?->vatable_sales ?? $transaction->total - $transaction->vat_amount),
            'vat_amount' => (float) ($transaction->officialReceipt?->vat_amount ?? $transaction->vat_amount),
            'vat_exempt' => (float) ($transaction->officialReceipt?->vat_exempt ?? 0),
            'zero_rated_sales' => 0,
            'discounts' => $transaction->discounts->map(fn ($d) => [
                'type' => $d->type,
                'amount' => (float) $d->amount,
                'reference_id' => $d->reference_id,
                'customer_name' => $d->customer_name,
            ])->values()->all(),
            'discount_amount' => (float) $transaction->discount_amount,
            'total' => (float) $transaction->total,
            'payment_method' => $transaction->payment_method,
            'payment_reference' => $transaction->payment_reference,
            'payment_provider' => $transaction->payment_provider,
            'cashier_name' => $transaction->cashier?->name,
            'issued_at' => $transaction->created_at->format('Y-m-d H:i:s'),
            'footer_text' => $receiptFooter['footer_text'],
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
