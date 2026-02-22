<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\CompanyRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class CompanyService
{
    public const LOGO_DISK = 'public';
    public const LOGO_DIR = 'companies';

    public function __construct(
        private CompanyRepository $repository
    ) {}

    public function list(?string $search = null): Collection
    {
        return $this->repository->getAll($search);
    }

    public function get(int $id): ?Company
    {
        return $this->repository->find($id);
    }

    public function create(array $data, ?UploadedFile $logo = null): Company
    {
        if ($logo && $logo->isValid()) {
            $data['logo'] = $logo->store(self::LOGO_DIR, self::LOGO_DISK);
        }
        return $this->repository->create($data);
    }

    public function update(Company $company, array $data, ?UploadedFile $logo = null): Company
    {
        if ($logo && $logo->isValid()) {
            if ($company->logo) {
                Storage::disk(self::LOGO_DISK)->delete($company->logo);
            }
            $data['logo'] = $logo->store(self::LOGO_DIR, self::LOGO_DISK);
        }
        return $this->repository->update($company, $data);
    }

    public function delete(Company $company): bool
    {
        if ($company->logo) {
            Storage::disk(self::LOGO_DISK)->delete($company->logo);
        }
        return $this->repository->delete($company);
    }

    public function toggleStatus(Company $company): Company
    {
        return $this->repository->toggleActive($company);
    }

    /**
     * Summary for company: branches with sales/transaction stats and overall totals.
     * Includes KPIs with prior-period comparison, charts data, and widgets.
     * When $branchId is provided (and belongs to this company), data is scoped to that branch only.
     */
    public function getSummary(Company $company, ?string $dateFrom = null, ?string $dateTo = null, ?int $branchId = null): array
    {
        $dateFrom = $dateFrom ?? now()->startOfMonth()->toDateString();
        $dateTo = $dateTo ?? now()->toDateString();

        $branches = $company->branches()->get();
        $branchIds = $branches->pluck('id')->toArray();

        if ($branchId !== null && in_array($branchId, $branchIds, true)) {
            $branches = $branches->where('id', $branchId)->values();
            $branchIds = [$branchId];
        }

        $fromTs = $dateFrom . ' 00:00:00';
        $toTs = $dateTo . ' 23:59:59';

        $transactionsQuery = \App\Models\Transaction::query()
            ->whereIn('branch_id', $branchIds)
            ->whereBetween('created_at', [$fromTs, $toTs]);

        $completedQuery = (clone $transactionsQuery)->where('status', 'completed');

        $overall = (clone $completedQuery)->selectRaw('COUNT(*) as transaction_count, COALESCE(SUM(total), 0) as total_sales, COALESCE(SUM(vat_amount), 0) as total_vat, COALESCE(SUM(discount_amount), 0) as total_discount')->first();

        $totalSales = (float) ($overall->total_sales ?? 0);
        $txCount = (int) ($overall->transaction_count ?? 0);
        $avgTransactionValue = $txCount > 0 ? $totalSales / $txCount : 0;
        $totalVat = (float) ($overall->total_vat ?? 0);
        $totalDiscount = (float) ($overall->total_discount ?? 0);
        $netSales = $totalSales - $totalDiscount;
        $grossSales = $totalSales;

        $daysDiff = max(1, (new \DateTime($dateTo))->diff(new \DateTime($dateFrom))->days + 1);
        $prevEnd = (new \DateTime($dateFrom))->modify('-1 day')->format('Y-m-d');
        $prevStart = (new \DateTime($prevEnd))->modify('-'.$daysDiff.' days')->format('Y-m-d');
        $prevFromTs = $prevStart . ' 00:00:00';
        $prevToTs = $prevEnd . ' 23:59:59';
        $prevSales = (float) (clone $transactionsQuery)->where('status', 'completed')->whereBetween('created_at', [$prevFromTs, $prevToTs])->sum('total');
        $revenueChangePct = $prevSales > 0 ? (($totalSales - $prevSales) / $prevSales) * 100 : ($totalSales > 0 ? 100 : 0);

        $totalProductsSold = (int) \App\Models\TransactionItem::query()
            ->whereHas('transaction', fn ($q) => $q->whereIn('branch_id', $branchIds)->where('status', 'completed')->whereBetween('created_at', [$fromTs, $toTs]))
            ->sum('quantity');

        $byBranch = \App\Models\Transaction::query()
            ->where('status', 'completed')
            ->whereIn('branch_id', $branchIds)
            ->whereBetween('created_at', [$fromTs, $toTs])
            ->selectRaw('branch_id, COUNT(*) as transaction_count, COALESCE(SUM(total), 0) as total_sales')
            ->groupBy('branch_id')
            ->get()
            ->keyBy('branch_id');

        $branchCashierCounts = \App\Models\User::query()
            ->whereIn('branch_id', $branchIds)
            ->selectRaw('branch_id, COUNT(*) as cnt')
            ->groupBy('branch_id')
            ->pluck('cnt', 'branch_id');

        $topProductPerBranch = \App\Models\TransactionItem::query()
            ->whereHas('transaction', fn ($q) => $q->whereIn('branch_id', $branchIds)->where('status', 'completed')->whereBetween('created_at', [$fromTs, $toTs]))
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->selectRaw('transactions.branch_id, products.name as product_name, SUM(transaction_items.quantity) as qty')
            ->groupBy('transactions.branch_id', 'products.id', 'products.name')
            ->get()
            ->groupBy('branch_id')
            ->map(fn ($rows) => $rows->sortByDesc('qty')->first());

        $branchesData = $branches->map(function ($branch) use ($byBranch, $branchCashierCounts, $topProductPerBranch) {
            $stats = $byBranch->get($branch->id);
            $txCount = $stats ? (int) $stats->transaction_count : 0;
            $totalSales = $stats ? (float) $stats->total_sales : 0;
            $avgTx = $txCount > 0 ? $totalSales / $txCount : 0;
            $top = $topProductPerBranch->get($branch->id);
            return [
                'id' => $branch->id,
                'name' => $branch->name,
                'address' => $branch->address,
                'transaction_count' => $txCount,
                'total_sales' => $totalSales,
                'avg_transaction_value' => round($avgTx, 2),
                'top_product_name' => $top ? $top->product_name : null,
                'cashiers_count' => (int) ($branchCashierCounts[$branch->id] ?? 0),
            ];
        });

        $allTxQuery = (clone $transactionsQuery)->with(['cashier:id,name', 'branch:id,name']);
        $recentTransactions = $allTxQuery->latest()->limit(50)->get()->map(fn ($t) => [
            'id' => $t->id,
            'or_number' => $t->or_number,
            'total' => (float) $t->total,
            'vat_amount' => (float) ($t->vat_amount ?? 0),
            'discount_amount' => (float) ($t->discount_amount ?? 0),
            'payment_method' => $t->payment_method ?? 'cash',
            'status' => $t->status ?? 'completed',
            'created_at' => $t->created_at?->toIso8601String(),
            'branch_name' => $t->branch?->name,
            'cashier_name' => $t->cashier?->name,
        ]);

        $salesByDay = (clone $completedQuery)
            ->selectRaw('date(created_at) as period, COALESCE(SUM(total), 0) as total, COUNT(*) as count')
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(fn ($r) => ['date' => $r->period, 'total' => (float) $r->total, 'count' => (int) $r->count, 'avg_value' => $r->count > 0 ? round((float) $r->total / $r->count, 2) : 0]);

        $lowStockAlerts = \App\Models\Product::query()
            ->whereIn('branch_id', $branchIds)
            ->where('reorder_level', '>', 0)
            ->withSum('batches', 'quantity')
            ->get()
            ->filter(fn ($p) => (float) ($p->batches_sum_quantity ?? 0) <= $p->reorder_level)
            ->count();

        $expiringSoon = \App\Models\ProductBatch::query()
            ->where('quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(90))
            ->whereHas('product', fn ($q) => $q->whereIn('branch_id', $branchIds))
            ->count();

        $outOfStock = \App\Models\Product::query()
            ->whereIn('branch_id', $branchIds)
            ->withSum('batches', 'quantity')
            ->get()
            ->filter(fn ($p) => (float) ($p->batches_sum_quantity ?? 0) <= 0)
            ->count();

        $top5Products = \App\Models\TransactionItem::query()
            ->whereHas('transaction', fn ($q) => $q->whereIn('branch_id', $branchIds)->where('status', 'completed')->whereBetween('created_at', [$fromTs, $toTs]))
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->selectRaw('products.name as product_name, SUM(transaction_items.quantity) as quantity_sold, SUM(transaction_items.subtotal) as revenue')
            ->groupBy('transaction_items.product_id', 'products.name')
            ->orderByRaw('SUM(transaction_items.quantity) DESC')
            ->limit(5)
            ->get()
            ->map(fn ($r) => ['product_name' => $r->product_name, 'quantity_sold' => (int) $r->quantity_sold, 'revenue' => (float) $r->revenue]);

        $salesByPayment = (clone $completedQuery)
            ->selectRaw('COALESCE(payment_method, \'cash\') as method, COALESCE(SUM(total), 0) as total')
            ->groupBy('payment_method')
            ->get()
            ->map(fn ($r) => ['method' => $r->method ?: 'cash', 'total' => (float) $r->total]);

        $topCashiers = \App\Models\Transaction::query()
            ->where('status', 'completed')
            ->whereIn('branch_id', $branchIds)
            ->whereBetween('created_at', [$fromTs, $toTs])
            ->selectRaw('cashier_id, COUNT(*) as tx_count, COALESCE(SUM(total), 0) as total_sales')
            ->groupBy('cashier_id')
            ->orderByRaw('COALESCE(SUM(total), 0) DESC')
            ->limit(5)
            ->with('cashier:id,name')
            ->get()
            ->map(fn ($r) => ['cashier_name' => $r->cashier?->name ?? 'Unknown', 'transaction_count' => (int) $r->tx_count, 'total_sales' => (float) $r->total_sales]);

        $thisYear = (int) (new \DateTime($dateFrom))->format('Y');
        $lastYear = $thisYear - 1;
        $monthlyThisYear = \App\Models\Transaction::query()
            ->where('status', 'completed')
            ->whereIn('branch_id', $branchIds)
            ->whereYear('created_at', $thisYear)
            ->selectRaw('MONTH(created_at) as month, COALESCE(SUM(total), 0) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');
        $monthlyLastYear = \App\Models\Transaction::query()
            ->where('status', 'completed')
            ->whereIn('branch_id', $branchIds)
            ->whereYear('created_at', $lastYear)
            ->selectRaw('MONTH(created_at) as month, COALESCE(SUM(total), 0) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');
        $monthlyComparison = collect(range(1, 12))->map(fn ($m) => [
            'month' => $m,
            'this_year' => (float) ($monthlyThisYear->get($m)?->total ?? 0),
            'last_year' => (float) ($monthlyLastYear->get($m)?->total ?? 0),
        ])->values()->all();

        $vatThisMonth = (float) \App\Models\Transaction::query()
            ->where('status', 'completed')
            ->whereIn('branch_id', $branchIds)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('vat_amount');

        $orRanges = $branches->map(fn ($b) => [
            'branch_name' => $b->name,
            'or_from' => $b->bir_series_start,
            'or_to' => $b->bir_series_end,
            'current_or' => $b->current_or_number,
        ])->values()->all();

        $activeCashiersToday = (int) \App\Models\Transaction::query()
            ->whereIn('branch_id', $branchIds)
            ->whereDate('created_at', now()->toDateString())
            ->whereNotNull('cashier_id')
            ->selectRaw('COUNT(DISTINCT cashier_id) as c')
            ->value('c');

        $mostActiveToday = \App\Models\Transaction::query()
            ->whereIn('branch_id', $branchIds)
            ->whereDate('created_at', now()->toDateString())
            ->selectRaw('cashier_id, COUNT(*) as cnt')
            ->groupBy('cashier_id')
            ->orderByRaw('COUNT(*) DESC')
            ->with('cashier:id,name')
            ->first();

        return [
            'company' => $company->only(['id', 'name', 'tin', 'address', 'contact', 'logo_url', 'is_active']),
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'summary' => [
                'branches_count' => $branches->count(),
                'transaction_count' => $txCount,
                'total_sales' => $totalSales,
                'total_vat' => $totalVat,
                'total_discount' => $totalDiscount,
                'revenue_change_pct' => round($revenueChangePct, 1),
                'prev_period_sales' => $prevSales,
                'avg_transaction_value' => round($avgTransactionValue, 2),
                'total_products_sold' => $totalProductsSold,
                'net_sales' => round($netSales, 2),
                'gross_sales' => round($grossSales, 2),
                'low_stock_alerts' => $lowStockAlerts,
                'expiring_soon_count' => $expiringSoon,
                'out_of_stock_count' => $outOfStock,
            ],
            'sales_by_day' => $salesByDay->values()->all(),
            'monthly_comparison' => $monthlyComparison,
            'sales_by_payment_method' => $salesByPayment->values()->all(),
            'top_5_products' => $top5Products->values()->all(),
            'top_cashiers' => $topCashiers->values()->all(),
            'branches' => $branchesData,
            'recent_transactions' => $recentTransactions,
            'inventory_summary' => [
                'low_stock_count' => $lowStockAlerts,
                'expiring_soon_count' => $expiringSoon,
                'out_of_stock_count' => $outOfStock,
            ],
            'bir_summary' => [
                'vat_this_month' => round($vatThisMonth, 2),
                'or_ranges' => $orRanges,
                'z_reading_status' => null,
                'last_z_reading_date' => null,
            ],
            'user_activity' => [
                'active_cashiers_today' => $activeCashiersToday,
                'most_active_cashier_today' => $mostActiveToday ? $mostActiveToday->cashier?->name : null,
            ],
        ];
    }
}
