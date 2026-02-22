<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockIn;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReceiveDeliveryController extends Controller
{
    use ScopesInventoryByBranch;

    public function store(Request $request): JsonResponse
    {
        $branchId = $this->branchIdForInventory($request);
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'batch_number' => 'required|string|max:100',
            'expiry_date' => 'nullable|date',
            'quantity' => 'required|numeric|min:0.001',
            'cost_price' => 'nullable|numeric|min:0',
        ]);
        $product = Product::findOrFail($validated['product_id']);
        if ((int) $product->branch_id !== $branchId) {
            abort(403, 'Product does not belong to your branch.');
        }
        $batch = ProductBatch::create([
            'product_id' => $validated['product_id'],
            'batch_number' => $validated['batch_number'],
            'expiry_date' => $validated['expiry_date'] ?? null,
            'quantity' => $validated['quantity'],
            'cost_price' => $validated['cost_price'] ?? 0,
            'supplier_id' => $validated['supplier_id'] ?? null,
        ]);
        StockIn::create([
            'branch_id' => $branchId,
            'product_id' => $validated['product_id'],
            'product_batch_id' => $batch->id,
            'supplier_id' => $validated['supplier_id'] ?? null,
            'quantity' => $validated['quantity'],
            'cost' => $validated['cost_price'] ?? 0,
            'received_by' => $request->user()->id,
            'received_at' => now(),
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Delivery received and batch created.',
            'data' => $batch->load('product'),
        ], 201);
    }
}
