<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryProductController extends Controller
{
    use ScopesInventoryByBranch;
    /**
     * List inventory products. Super admin and admin see all branches; manager and inventory_manager see only their branch.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()->with(['category', 'branch']);
        if ($this->shouldScopeByBranch($request)) {
            $query->where('branch_id', $this->branchIdForInventory($request));
        }
        $products = $query->paginate($request->get('per_page', 15));
        return response()->json(['status' => true, 'data' => $products]);
    }

    public function store(Request $request): JsonResponse
    {
        $branchId = $this->branchIdForInventory($request);
        $validated = $request->validate([
            'barcode' => 'nullable|string|max:100',
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'unit' => 'nullable|string|max:20',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|integer|min:0',
        ]);
        $validated['branch_id'] = $branchId;
        $product = Product::create($validated);
        return response()->json(['status' => true, 'message' => 'Product created.', 'data' => $product], 201);
    }

    public function show(Request $request, Product $product): JsonResponse
    {
        $this->ensureProductBelongsToUserBranch($request, $product);
        $product->load(['category', 'batches']);
        return response()->json(['status' => true, 'data' => $product]);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $this->ensureProductBelongsToUserBranch($request, $product);
        $validated = $request->validate([
            'barcode' => 'nullable|string|max:100',
            'name' => 'sometimes|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'unit' => 'nullable|string|max:20',
            'price' => 'sometimes|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        $product->update($validated);
        return response()->json(['status' => true, 'message' => 'Product updated.', 'data' => $product->fresh()]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->ensureProductBelongsToUserBranch(request(), $product);
        $product->update(['is_active' => false]);
        return response()->json(['status' => true, 'message' => 'Product deactivated.']);
    }

    private function ensureProductBelongsToUserBranch(Request $request, Product $product): void
    {
        if (! $this->shouldScopeByBranch($request)) {
            return;
        }
        $branchId = $this->branchIdForInventory($request);
        if ((int) $product->branch_id !== (int) $branchId) {
            abort(404, 'Product not found in your branch inventory.');
        }
    }
}
