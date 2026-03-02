<?php

namespace App\Http\Controllers\API\POS;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Discount;
use App\Models\OfficialReceipt;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\TransactionPayment;
use App\Services\AuditLogService;
use App\Services\DaySessionService;
use App\Services\SyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosTransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $branchId = $user->branch_id;
        if (! $branchId) {
            return response()->json([
                'status' => false,
                'message' => 'Your account must be assigned to a branch.',
            ], 403);
        }
        $query = Transaction::where('branch_id', $branchId)
            ->with('cashier:id,name,email')
            ->select('id', 'or_number', 'total', 'status', 'payment_method', 'payment_provider', 'cashier_id', 'created_at');
        if ($request->filled('terminal_id')) {
            $query->where('terminal_id', $request->terminal_id);
        }
        if ($request->filled('status') && $request->get('status') !== 'all') {
            $query->where('status', $request->get('status'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }
        if ($request->filled('search')) {
            $term = $request->get('search');
            $query->where(function ($q) use ($term) {
                $q->where('or_number', 'like', '%' . $term . '%')
                    ->orWhereRaw('CAST(total AS CHAR) LIKE ?', ['%' . $term . '%']);
            });
        }
        $sortCol = $request->get('sort', 'created_at');
        $sortDir = strtolower($request->get('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $allowedSort = ['created_at', 'or_number', 'total', 'status'];
        if (in_array($sortCol, $allowedSort, true)) {
            $query->orderBy($sortCol, $sortDir);
        } else {
            $query->orderByDesc('created_at');
        }
        $perPage = (int) $request->get('per_page', 20);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 20;
        $transactions = $query->paginate($perPage);
        $items = $transactions->items();
        foreach ($items as $t) {
            $t->cashier_name = $t->cashier ? ($t->cashier->name ?: $t->cashier->email) : null;
        }
        return response()->json([
            'status' => true,
            'data' => $items,
            'meta' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'from' => $transactions->firstItem(),
                'to' => $transactions->lastItem(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_batch_id' => 'nullable|exists:product_batches,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.prescription_number' => 'nullable|string|max:100',
            'items.*.notes' => 'nullable|string|max:500',
            'payment_method' => 'nullable|string|in:cash,card,other,ewallet,split',
            'payment_reference' => 'nullable|string|max:100',
            'payment_provider' => 'nullable|string|max:100',
            'payments' => 'nullable|array',
            'payments.*.payment_method' => 'required|string|in:cash,card,ewallet,other',
            'payments.*.amount' => 'required|numeric|min:0',
            'payments.*.payment_reference' => 'nullable|string|max:100',
            'payments.*.payment_provider' => 'nullable|string|max:100',
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

        $daySessionService = app(DaySessionService::class);
        $daySession = $daySessionService->getOpenForTerminalDate($branchId, $terminalId, now());
        if (! $daySession) {
            $canOpen = $daySessionService->canOpenToday($branchId, $terminalId);
            if ($canOpen['ok']) {
                $daySession = $daySessionService->getOrCreateForToday($branchId, $terminalId, 0);
            }
            if (! $daySession) {
                return response()->json([
                    'status' => false,
                    'message' => $canOpen['message'] ?? 'No open day session. Open the day from POS first, or perform Z-Reading if the day was already closed.',
                ], 422);
            }
        }

        try {
            $result = DB::transaction(function () use ($validated, $branchId, $terminalId, $user, $daySession) {
            $branch = Branch::find($branchId);
            if ($branch) {
                $nextOr = (int) $branch->current_or_number + 1;
                $orNumber = str_pad((string) $nextOr, 10, '0', STR_PAD_LEFT);
                $branch->increment('current_or_number');
            } else {
                $orNumber = '0000000001';
            }

            $transaction = Transaction::create([
                'branch_id' => $branchId,
                'terminal_id' => $terminalId,
                'day_session_id' => $daySession->id,
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

            $itemsSubtotal = 0;
            foreach ($validated['items'] as $row) {
                $needQty = (float) $row['quantity'];
                $unitPrice = (float) $row['unit_price'];
                $productId = (int) $row['product_id'];
                $batchId = ! empty($row['product_batch_id']) ? (int) $row['product_batch_id'] : null;

                if ($batchId) {
                    $batch = ProductBatch::where('id', $batchId)->where('product_id', $productId)->lockForUpdate()->first();
                    if (! $batch) {
                        throw new \RuntimeException('Invalid batch for product.');
                    }
                    $available = (float) $batch->quantity;
                    if ($available < $needQty) {
                        $product = Product::find($productId);
                        $name = $product ? $product->name : 'Product #'.$productId;
                        throw new \RuntimeException('Insufficient stock for "'.$name.'". Available: '.$available.', requested: '.$needQty.'.');
                    }
                    $subtotal = $needQty * $unitPrice;
                    $itemsSubtotal += $subtotal;
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $productId,
                        'product_batch_id' => $batchId,
                        'quantity' => $needQty,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                        'prescription_number' => $row['prescription_number'] ?? null,
                        'notes' => isset($row['notes']) && trim((string) $row['notes']) !== '' ? trim((string) $row['notes']) : null,
                    ]);
                    $batch->decrement('quantity', $needQty);
                } else {
                    $product = Product::where('id', $productId)->where('branch_id', $branchId)->first();
                    if (! $product) {
                        throw new \RuntimeException('Product not found or does not belong to this branch.');
                    }
                    $batches = ProductBatch::where('product_id', $productId)
                        ->where('quantity', '>', 0)
                        ->orderBy('expiry_date')
                        ->lockForUpdate()
                        ->get();
                    $totalAvailable = $batches->sum(fn ($b) => (float) $b->quantity);
                    if ($totalAvailable < $needQty) {
                        throw new \RuntimeException('Insufficient stock for "'.$product->name.'". Available: '.$totalAvailable.', requested: '.$needQty.'.');
                    }
                    $remaining = $needQty;
                    foreach ($batches as $batch) {
                        if ($remaining <= 0) {
                            break;
                        }
                        $take = min((float) $batch->quantity, $remaining);
                        if ($take <= 0) {
                            continue;
                        }
                        $subtotal = $take * $unitPrice;
                        $itemsSubtotal += $subtotal;
                        TransactionItem::create([
                            'transaction_id' => $transaction->id,
                            'product_id' => $productId,
                            'product_batch_id' => $batch->id,
                            'quantity' => $take,
                            'unit_price' => $unitPrice,
                            'subtotal' => $subtotal,
                            'prescription_number' => $row['prescription_number'] ?? null,
                            'notes' => isset($row['notes']) && trim((string) $row['notes']) !== '' ? trim((string) $row['notes']) : null,
                        ]);
                        $batch->decrement('quantity', $take);
                        $remaining -= $take;
                    }
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

            $totalDue = round($itemsSubtotal - $totalDiscount, 2);
            $payments = $validated['payments'] ?? [];
            $isSplit = ! empty($payments);

            if ($isSplit) {
                $paymentsSum = round(array_sum(array_column($payments, 'amount')), 2);
                if (abs($paymentsSum - $totalDue) > 0.01) {
                    throw new \RuntimeException('Split payment total (₱' . number_format($paymentsSum, 2) . ') must equal total due (₱' . number_format($totalDue, 2) . ').');
                }
                foreach ($payments as $p) {
                    TransactionPayment::create([
                        'transaction_id' => $transaction->id,
                        'payment_method' => $p['payment_method'],
                        'amount' => (float) $p['amount'],
                        'payment_reference' => $p['payment_reference'] ?? null,
                        'payment_provider' => $p['payment_provider'] ?? null,
                    ]);
                }
            }

            $transaction->update([
                'total' => $totalDue,
                'vat_amount' => $vatAmount,
                'discount_amount' => $totalDiscount,
                'payment_method' => $isSplit ? 'split' : $transaction->payment_method,
                'payment_reference' => $isSplit ? null : $transaction->payment_reference,
                'payment_provider' => $isSplit ? null : $transaction->payment_provider,
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

            return ['transaction' => $transaction, 'or_number' => $orNumber];
            });
        } catch (\Throwable $e) {
            $message = $e instanceof \RuntimeException ? $e->getMessage() : 'Transaction failed. Please try again.';
            return response()->json(['status' => false, 'message' => $message], 422);
        }

        if (env('SYNC_MODE') === 'direct_db' && config('database.connections.mysql_cloud.host')) {
            app(SyncService::class)->enqueueTransaction($result['transaction']->fresh());
        }

        $result['transaction']->load('items.product', 'discounts');
        AuditLogService::log('transaction_completed', 'transactions', (int) $result['transaction']->id, null, ['or_number' => $result['or_number'], 'total' => $result['transaction']->total, 'payment_method' => $result['transaction']->payment_method], $request, $branchId, $user->id);

        return response()->json([
            'status' => true,
            'message' => 'Transaction completed.',
            'data' => array_merge($result['transaction']->toArray(), ['or_number' => $result['or_number']]),
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
        $reason = $request->input('reason');
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
        $newValues = ['status' => 'voided'];
        if ($reason !== null && $reason !== '') {
            $newValues['void_reason'] = $reason;
        }
        AuditLogService::log('transaction_voided', 'transactions', (int) $transaction->id, ['status' => 'completed'], $newValues, $request, $transaction->branch_id);
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
