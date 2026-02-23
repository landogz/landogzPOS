<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\ProductBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request): JsonResponse
    {
        $query = $this->baseReportQuery($request, Transaction::query()->where('status', 'completed'));
        $from = $request->get('date_from', now()->startOfMonth()->toDateString());
        $to = $request->get('date_to', now()->toDateString());
        $query->whereBetween('created_at', [$from, $to]);
        $groupBy = $request->get('group_by', 'day'); // day, month, branch
        if ($groupBy === 'day') {
            $rows = $query->selectRaw('date(created_at) as period, sum(total) as total, count(*) as count')
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        } elseif ($groupBy === 'month') {
            $rows = $query->selectRaw('date_format(created_at, "%Y-%m") as period, sum(total) as total, count(*) as count')
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        } else {
            $rows = $query->selectRaw('branch_id as period, sum(total) as total, count(*) as count')
                ->groupBy('branch_id')
                ->get();
            $branchIds = $rows->pluck('period')->filter()->unique();
            $branches = \App\Models\Branch::whereIn('id', $branchIds)->get()->keyBy('id');
            $rows = $rows->map(fn ($r) => (object) [
                'period' => $r->period,
                'period_name' => $branches->get($r->period)?->name,
                'total' => (float) $r->total,
                'count' => (int) $r->count,
            ]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $rows,
            'meta' => $this->meta($request),
        ]);
    }

    public function inventory(Request $request): JsonResponse
    {
        $user = $request->user();
        $branchId = $user?->branch_id ?? $request->get('branch_id');
        $query = Product::query();
        if ($branchId) {
            $query->where('branch_id', $branchId);
        } elseif ($user && $user->role === 'admin' && $user->company_id) {
            $companyBranchIds = \App\Models\Branch::where('company_id', $user->company_id)->pluck('id')->all();
            if (! empty($companyBranchIds)) {
                $query->whereIn('branch_id', $companyBranchIds);
            } else {
                $query->whereRaw('1 = 0');
            }
        }
        $query->withSum('batches', 'quantity');
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        $products = $query->get()->map(fn ($p) => [
            'product_id' => $p->id,
            'name' => $p->name,
            'barcode' => $p->barcode,
            'quantity' => (float) ($p->batches_sum_quantity ?? 0),
            'reorder_level' => $p->reorder_level,
            'cost' => (float) $p->cost,
        ]);
        return response()->json(['status' => 'success', 'data' => $products]);
    }

    public function profitMargin(Request $request): JsonResponse
    {
        $from = $request->get('date_from', now()->startOfMonth()->toDateString());
        $to = $request->get('date_to', now()->toDateString());
        $user = $request->user();
        $branchId = $user?->branch_id ?? $request->get('branch_id');
        $companyBranchIds = ($user && $user->role === 'admin' && $user->company_id)
            ? \App\Models\Branch::where('company_id', $user->company_id)->pluck('id')->all()
            : null;
        $items = TransactionItem::query()
            ->whereHas('transaction', function ($t) use ($from, $to, $branchId, $companyBranchIds) {
                $t->where('status', 'completed')->whereBetween('created_at', [$from, $to]);
                if ($branchId) {
                    $t->where('branch_id', $branchId);
                } elseif (! empty($companyBranchIds)) {
                    $t->whereIn('branch_id', $companyBranchIds);
                }
            })
            ->with(['product', 'transaction'])
            ->get();
        $totalRevenue = $items->sum('subtotal');
        $totalCost = 0;
        foreach ($items as $item) {
            $totalCost += $item->product_batch_id
                ? (float) \App\Models\ProductBatch::find($item->product_batch_id)?->cost_price * (float) $item->quantity
                : (float) $item->product?->cost * (float) $item->quantity;
        }
        return response()->json([
            'status' => 'success',
            'data' => [
                'revenue' => $totalRevenue,
                'cost' => $totalCost,
                'profit' => $totalRevenue - $totalCost,
                'margin_percent' => $totalRevenue > 0 ? round((($totalRevenue - $totalCost) / $totalRevenue) * 100, 2) : 0,
            ],
        ]);
    }

    public function topSelling(Request $request): JsonResponse
    {
        $from = $request->get('date_from', now()->startOfMonth()->toDateString());
        $to = $request->get('date_to', now()->toDateString());
        $branchId = $request->user()?->branch_id ?? $request->get('branch_id');
        $limit = (int) $request->get('per_page', 20);
        $rows = TransactionItem::query()
            ->whereHas('transaction', fn ($t) => $t->where('status', 'completed')->whereBetween('created_at', [$from, $to])->when($branchId, fn ($q) => $q->where('branch_id', $branchId)))
            ->selectRaw('product_id, sum(quantity) as total_qty, sum(subtotal) as total_sales')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->with('product:id,name,barcode')
            ->get();
        return response()->json(['status' => 'success', 'data' => $rows]);
    }

    public function vatSummary(Request $request): JsonResponse
    {
        $from = $request->get('date_from', now()->startOfMonth()->toDateString());
        $to = $request->get('date_to', now()->toDateString());
        $branchId = $request->user()?->branch_id ?? $request->get('branch_id');
        $q = Transaction::query()
            ->where('status', 'completed')
            ->whereBetween('created_at', [$from, $to])
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId));
        $vatable = (clone $q)->sum(DB::raw('total - vat_amount'));
        $vatAmount = (clone $q)->sum('vat_amount');
        $vatExempt = \App\Models\OfficialReceipt::query()
            ->whereHas('transaction', fn ($t) => $t->where('status', 'completed')->whereBetween('created_at', [$from, $to])->when($branchId, fn ($qb) => $qb->where('branch_id', $branchId)))
            ->sum('vat_exempt');
        return response()->json([
            'status' => 'success',
            'data' => [
                'period' => [$from, $to],
                'vatable_sales' => (float) $vatable,
                'vat_amount' => (float) $vatAmount,
                'vat_exempt' => (float) $vatExempt,
            ],
        ]);
    }

    public function auditLog(Request $request): JsonResponse
    {
        $query = DB::table('audit_logs')->orderByDesc('created_at');
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        $perPage = (int) $request->get('per_page', 15);
        $logs = $query->paginate($perPage);
        return response()->json([
            'status' => 'success',
            'data' => $logs->items(),
            'meta' => ['current_page' => $logs->currentPage(), 'total' => $logs->total(), 'per_page' => $logs->perPage()],
        ]);
    }

    public function expiringProducts(Request $request): JsonResponse
    {
        $days = (int) $request->get('days', 90);
        $branchId = $request->user()?->branch_id ?? $request->get('branch_id');
        $items = ProductBatch::query()
            ->where('quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->when($branchId, fn ($q) => $q->whereHas('product', fn ($p) => $p->where('branch_id', $branchId)))
            ->with('product:id,name,barcode,unit')
            ->orderBy('expiry_date')
            ->get();
        return response()->json(['status' => 'success', 'data' => $items]);
    }

    public function cashierSummary(Request $request): JsonResponse
    {
        $from = $request->get('date_from', now()->toDateString());
        $to = $request->get('date_to', now()->toDateString());
        $branchId = $request->user()?->branch_id ?? $request->get('branch_id');
        $rows = Transaction::query()
            ->where('status', 'completed')
            ->whereBetween('created_at', [$from, $to])
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->selectRaw('cashier_id, sum(total) as total_sales, count(*) as transaction_count')
            ->groupBy('cashier_id')
            ->with('cashier:id,name,email')
            ->get();
        return response()->json(['status' => 'success', 'data' => $rows]);
    }

    private function baseReportQuery(Request $request, $query)
    {
        $user = $request->user();
        if ($request->filled('branch_id')) {
            $query->where('branch_id', (int) $request->branch_id);
            return $query;
        }
        if ($user?->branch_id) {
            $query->where('branch_id', $user->branch_id);
            return $query;
        }
        if ($user && $user->role === 'admin' && $user->company_id) {
            $companyBranchIds = \App\Models\Branch::where('company_id', $user->company_id)->pluck('id')->all();
            if (! empty($companyBranchIds)) {
                $query->whereIn('branch_id', $companyBranchIds);
            } else {
                $query->whereRaw('1 = 0');
            }
        }
        return $query;
    }

    private function meta(Request $request): array
    {
        return [
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];
    }
}
