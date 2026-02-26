<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Branch scope for queries: null = all (super_admin), array = branch ids (admin = company branches, others = own branch).
     */
    private function branchScope($user): ?array
    {
        if (!$user) {
            return null;
        }
        if ($user->role === 'super_admin') {
            return null;
        }
        if ($user->role === 'admin' && $user->company_id) {
            return Branch::where('company_id', $user->company_id)->pluck('id')->all();
        }
        if ($user->branch_id) {
            return [$user->branch_id];
        }
        return null;
    }

    /**
     * GET /api/v1/dashboard/summary — sales, transaction count, low-stock, expiring.
     * Query: period=today|week|month (default today). Returns current + previous period for trend.
     */
    public function summary(Request $request): JsonResponse
    {
        $period = $request->query('period', 'today');
        $scope = $this->branchScope($request->user());
        [$start, $end, $prevStart, $prevEnd] = $this->periodRanges($period);

        $salesCurrent = Transaction::query()
            ->where('status', 'completed')
            ->when($scope !== null, fn ($q) => $q->whereIn('branch_id', $scope))
            ->whereBetween('created_at', [$start, $end])
            ->sum('total');
        $salesPrev = Transaction::query()
            ->where('status', 'completed')
            ->when($scope !== null, fn ($q) => $q->whereIn('branch_id', $scope))
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->sum('total');
        $transactionCount = Transaction::query()
            ->where('status', 'completed')
            ->when($scope !== null, fn ($q) => $q->whereIn('branch_id', $scope))
            ->whereBetween('created_at', [$start, $end])
            ->count();
        $transactionCountPrev = Transaction::query()
            ->where('status', 'completed')
            ->when($scope !== null, fn ($q) => $q->whereIn('branch_id', $scope))
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->count();

        $lowStockCount = $this->lowStockItems($scope)->count();
        $expiringCount = ProductBatch::query()
            ->where('quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(90))
            ->when($scope !== null, fn ($q) => $q->whereHas('product', fn ($p) => $p->whereIn('branch_id', $scope)))
            ->count();

        $salesTrend = $salesPrev > 0 ? round((($salesCurrent - $salesPrev) / $salesPrev) * 100, 1) : ($salesCurrent > 0 ? 100 : 0);
        $transactionTrend = $transactionCountPrev > 0 ? round((($transactionCount - $transactionCountPrev) / $transactionCountPrev) * 100, 1) : ($transactionCount > 0 ? 100 : 0);

        $user = $request->user();
        return response()->json([
            'status' => 'success',
            'data' => [
                'role' => $user?->role ?? null,
                'period' => $period,
                'sales' => (float) $salesCurrent,
                'sales_today' => (float) $salesCurrent,
                'sales_trend_pct' => $salesTrend,
                'transaction_count' => $transactionCount,
                'transaction_trend_pct' => $transactionTrend,
                'low_stock_count' => $lowStockCount,
                'expiring_soon_count' => $expiringCount,
                'last_updated' => now()->toIso8601String(),
            ],
        ]);
    }

    /**
     * @return array{0: \Carbon\Carbon, 1: \Carbon\Carbon, 2: \Carbon\Carbon, 3: \Carbon\Carbon} [start, end, prevStart, prevEnd]
     */
    private function periodRanges(string $period): array
    {
        $now = now();
        if ($period === 'week') {
            $end = $now->copy()->endOfDay();
            $start = $now->copy()->startOfWeek();
            $prevEnd = $start->copy()->subSecond();
            $prevStart = $prevEnd->copy()->startOfWeek();
            return [$start, $end, $prevStart, $prevEnd];
        }
        if ($period === 'month') {
            $end = $now->copy()->endOfDay();
            $start = $now->copy()->startOfMonth();
            $prevEnd = $start->copy()->subSecond();
            $prevStart = $prevEnd->copy()->startOfMonth();
            return [$start, $end, $prevStart, $prevEnd];
        }
        $start = $now->copy()->startOfDay();
        $end = $now->copy()->endOfDay();
        $prevStart = $start->copy()->subDay()->startOfDay();
        $prevEnd = $start->copy()->subSecond();
        return [$start, $end, $prevStart, $prevEnd];
    }

    /**
     * GET /api/v1/dashboard/low-stock-alerts
     */
    public function lowStockAlerts(Request $request): JsonResponse
    {
        $scope = $this->branchScope($request->user());
        $items = $this->lowStockItems($scope);
        return response()->json(['status' => 'success', 'data' => $items->values()]);
    }

    /**
     * GET /api/v1/dashboard/expiring-soon — items expiring within 30/60/90 days (query param: days=30).
     */
    public function expiringSoon(Request $request): JsonResponse
    {
        $days = (int) $request->get('days', 30);
        $scope = $this->branchScope($request->user());
        $items = ProductBatch::query()
            ->where('quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->when($scope !== null, fn ($q) => $q->whereHas('product', fn ($p) => $p->whereIn('branch_id', $scope)))
            ->with('product:id,name,barcode,unit')
            ->orderBy('expiry_date')
            ->get();
        return response()->json(['status' => 'success', 'data' => $items]);
    }

    /**
     * GET /api/v1/dashboard/sales-today
     */
    public function salesToday(Request $request): JsonResponse
    {
        $scope = $this->branchScope($request->user());
        $total = Transaction::query()
            ->where('status', 'completed')
            ->when($scope !== null, fn ($q) => $q->whereIn('branch_id', $scope))
            ->whereDate('created_at', today())
            ->sum('total');
        $count = Transaction::query()
            ->where('status', 'completed')
            ->when($scope !== null, fn ($q) => $q->whereIn('branch_id', $scope))
            ->whereDate('created_at', today())
            ->count();
        return response()->json([
            'status' => 'success',
            'data' => ['total' => (float) $total, 'transaction_count' => $count],
        ]);
    }

    /**
     * GET /api/v1/dashboard/branch-overview — for chain owners: all branches with sales in period.
     * Query: period=today|week|month. super_admin: all branches; admin: only their company's branches.
     */
    public function branchOverview(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user || ! in_array($user->role, ['super_admin', 'admin'], true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have permission to view branch overview.',
            ], 403);
        }
        $period = $request->query('period', 'today');
        [$start, $end] = $this->periodRanges($period);
        $query = Branch::with('company:id,name');
        if ($user->role === 'admin' && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }
        $branches = $query->get()->map(function (Branch $branch) use ($start, $end) {
            $sales = Transaction::where('branch_id', $branch->id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$start, $end])
                ->sum('total');
            $count = Transaction::where('branch_id', $branch->id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$start, $end])
                ->count();
            return [
                'id' => $branch->id,
                'name' => $branch->name,
                'address' => $branch->address,
                'company' => $branch->company,
                'sales_today' => (float) $sales,
                'transaction_count_today' => $count,
            ];
        });
        return response()->json([
            'status' => 'success',
            'data' => $branches,
            'last_updated' => now()->toIso8601String(),
        ]);
    }

    /**
     * @param  array<int>|null  $branchScope  Branch IDs to scope to, or null for all.
     */
    private function lowStockItems(?array $branchScope): \Illuminate\Support\Collection
    {
        return Product::query()
            ->when($branchScope !== null, fn ($q) => $q->whereIn('branch_id', $branchScope))
            ->where('is_active', true)
            ->where('reorder_level', '>', 0)
            ->withSum('batches', 'quantity')
            ->with('branch:id,name,company_id', 'branch.company:id,name')
            ->get()
            ->filter(fn ($p) => (float) ($p->batches_sum_quantity ?? 0) <= $p->reorder_level);
    }
}
