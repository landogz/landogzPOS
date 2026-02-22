<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockOut;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockOutController extends Controller
{
    use ScopesInventoryByBranch;

    public function store(Request $request): JsonResponse
    {
        $branchId = $this->branchIdForInventory($request);
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_batch_id' => 'nullable|exists:product_batches,id',
            'quantity' => 'required|numeric|min:0.001',
            'reason' => 'nullable|string|max:255',
        ]);
        $product = Product::findOrFail($validated['product_id']);
        if ((int) $product->branch_id !== $branchId) {
            abort(403, 'Product does not belong to your branch.');
        }
        $validated['branch_id'] = $branchId;
        $validated['recorded_by'] = $request->user()->id;
        $validated['recorded_at'] = now();
        $stockOut = StockOut::create($validated);
        return response()->json(['status' => true, 'message' => 'Stock-out recorded.', 'data' => $stockOut], 201);
    }
}
