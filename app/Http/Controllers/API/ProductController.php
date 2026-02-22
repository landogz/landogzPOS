<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * List products. Inventory is per branch: when user has a branch, only that branch's products are returned.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()->where('is_active', true);
        $branchId = $request->get('branch_id');
        $userBranchId = $request->user()?->branch_id;
        $canChooseBranch = $userBranchId === null || in_array($request->user()?->role, ['super_admin', 'admin'], true);
        if ($branchId !== null && $canChooseBranch) {
            $query->where('branch_id', $branchId);
        } elseif ($userBranchId !== null) {
            $query->where('branch_id', $userBranchId);
        }
        $products = $query->with('category')->paginate($request->get('per_page', 15));
        return response()->json(['status' => true, 'data' => $products]);
    }

    /**
     * Show one product. Users with a branch can only view products belonging to that branch.
     */
    public function show(Request $request, Product $product): JsonResponse
    {
        $userBranchId = $request->user()?->branch_id;
        if ($userBranchId !== null && (int) $product->branch_id !== (int) $userBranchId) {
            abort(404);
        }
        $product->load(['category', 'batches']);
        return response()->json(['status' => true, 'data' => $product]);
    }
}
