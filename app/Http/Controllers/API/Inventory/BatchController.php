<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    use ScopesInventoryByBranch;

    /**
     * Batches for a product. Super admin and admin can view any product; manager and inventory_manager only their branch.
     */
    public function index(Request $request, Product $product): JsonResponse
    {
        if ($this->shouldScopeByBranch($request) && (int) $product->branch_id !== (int) $this->branchIdForInventory($request)) {
            abort(404, 'Product not found in your branch inventory.');
        }
        $batches = ProductBatch::where('product_id', $product->id)
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date')
            ->get();
        return response()->json(['status' => true, 'data' => $batches]);
    }
}
