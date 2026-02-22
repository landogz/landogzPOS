<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockIn;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockInController extends Controller
{
    use ScopesInventoryByBranch;

    public function store(Request $request): JsonResponse
    {
        $branchId = $this->branchIdForInventory($request);
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_batch_id' => 'nullable|exists:product_batches,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'quantity' => 'required|numeric|min:0.001',
            'cost' => 'nullable|numeric|min:0',
            'batch_number' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
        ]);
        $product = Product::findOrFail($validated['product_id']);
        if ((int) $product->branch_id !== $branchId) {
            abort(403, 'Product does not belong to your branch.');
        }
        $validated['branch_id'] = $branchId;
        $validated['received_by'] = $request->user()->id;
        $validated['received_at'] = now();

        if (!empty($validated['batch_number']) && empty($validated['product_batch_id'])) {
            $batch = ProductBatch::create([
                'product_id' => $validated['product_id'],
                'batch_number' => $validated['batch_number'],
                'expiry_date' => $validated['expiry_date'] ?? null,
                'quantity' => $validated['quantity'],
                'cost_price' => $validated['cost'] ?? 0,
                'supplier_id' => $validated['supplier_id'] ?? null,
            ]);
            $validated['product_batch_id'] = $batch->id;
        }
        unset($validated['batch_number'], $validated['expiry_date']);

        $stockIn = StockIn::create($validated);
        if ($stockIn->product_batch_id) {
            ProductBatch::where('id', $stockIn->product_batch_id)->increment('quantity', $validated['quantity']);
        }
        return response()->json(['status' => true, 'message' => 'Stock-in recorded.', 'data' => $stockIn], 201);
    }
}
