<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Models\ProductBatch;
use App\Models\StockIn;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryReportController extends Controller
{
    use ScopesInventoryByBranch;

    public function expiring(Request $request): JsonResponse
    {
        $days = (int) $request->get('days', 90);
        $query = ProductBatch::query()
            ->where('quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->with('product.branch');
        if ($this->shouldScopeByBranch($request)) {
            $query->whereHas('product', fn ($q) => $q->where('branch_id', $this->branchIdForInventory($request)));
        }
        $items = $query->orderBy('expiry_date')->get();
        return response()->json(['status' => true, 'data' => $items]);
    }

    public function lowStock(Request $request): JsonResponse
    {
        $query = \App\Models\Product::query()
            ->with('branch')
            ->where('is_active', true)
            ->where('reorder_level', '>', 0)
            ->withSum('batches', 'quantity');
        if ($this->shouldScopeByBranch($request)) {
            $query->where('branch_id', $this->branchIdForInventory($request));
        }
        $products = $query->get()
            ->filter(fn ($p) => (float) ($p->batches_sum_quantity ?? 0) <= $p->reorder_level)
            ->values();
        return response()->json(['status' => true, 'data' => $products]);
    }

    public function valuation(Request $request): JsonResponse
    {
        $query = ProductBatch::query()->selectRaw('SUM(quantity * cost_price) as total');
        if ($this->shouldScopeByBranch($request)) {
            $query->whereHas('product', fn ($q) => $q->where('branch_id', $this->branchIdForInventory($request)));
        }
        $value = $query->value('total');
        return response()->json(['status' => true, 'data' => ['total_valuation' => (float) $value]]);
    }

    public function purchaseHistory(Request $request): JsonResponse
    {
        $query = StockIn::query()
            ->with(['product', 'product.branch', 'supplier', 'receivedBy']);
        if ($this->shouldScopeByBranch($request)) {
            $query->where('branch_id', $this->branchIdForInventory($request));
        }
        $list = $query->latest('received_at')->paginate($request->get('per_page', 15));
        return response()->json(['status' => true, 'data' => $list]);
    }
}
