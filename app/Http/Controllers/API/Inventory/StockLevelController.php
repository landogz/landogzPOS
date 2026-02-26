<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockLevelController extends Controller
{
    use ScopesInventoryByBranch;

    public function index(Request $request): JsonResponse
    {
        $query = Product::query()
            ->with(['branch.company', 'category'])
            ->withSum('batches', 'quantity');
        if ($this->shouldScopeByBranch($request)) {
            $query->where('branch_id', $this->branchIdForInventory($request));
        }
        $products = $query->get();
        $levels = $products->map(fn ($p) => [
            'product_id' => $p->id,
            'branch_id' => $p->branch_id,
            'branch' => $p->relationLoaded('branch') ? $p->branch : null,
            'company' => $p->branch && $p->branch->relationLoaded('company') ? $p->branch->company : null,
            'name' => $p->name,
            'barcode' => $p->barcode,
            'generic_name' => $p->generic_name,
            'brand' => $p->brand,
            'unit' => $p->unit,
            'price' => $p->price !== null ? (float) $p->price : null,
            'cost' => $p->cost !== null ? (float) $p->cost : null,
            'image_path' => $p->image_path,
            'is_active' => (bool) $p->is_active,
            'category' => $p->relationLoaded('category') && $p->category ? ['id' => $p->category->id, 'name' => $p->category->name] : null,
            'total_quantity' => (float) ($p->batches_sum_quantity ?? 0),
            'reorder_level' => $p->reorder_level,
        ]);

        $valuationQuery = ProductBatch::query()
            ->join('products', 'product_batches.product_id', '=', 'products.id')
            ->selectRaw('SUM(product_batches.quantity * product_batches.cost_price) as total');
        if ($this->shouldScopeByBranch($request)) {
            $valuationQuery->where('products.branch_id', $this->branchIdForInventory($request));
        }
        $totalValuation = (float) ($valuationQuery->value('total') ?? 0);

        return response()->json([
            'status' => true,
            'data' => $levels,
            'total_valuation' => round($totalValuation, 2),
        ]);
    }
}
