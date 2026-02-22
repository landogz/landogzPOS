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
        $query = Product::query()->with('branch')->withSum('batches', 'quantity');
        if ($this->shouldScopeByBranch($request)) {
            $query->where('branch_id', $this->branchIdForInventory($request));
        }
        $levels = $query->get()->map(fn ($p) => [
            'product_id' => $p->id,
            'branch_id' => $p->branch_id,
            'branch' => $p->relationLoaded('branch') ? $p->branch : null,
            'name' => $p->name,
            'barcode' => $p->barcode,
            'total_quantity' => (float) ($p->batches_sum_quantity ?? 0),
            'reorder_level' => $p->reorder_level,
        ]);
        return response()->json(['status' => true, 'data' => $levels]);
    }
}
