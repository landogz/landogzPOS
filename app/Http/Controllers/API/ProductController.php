<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * List products. super_admin: any branch; admin: company branches; others: own branch.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()->where('is_active', true);
        $user = $request->user();
        $branchId = $request->get('branch_id');
        $userBranchId = $user?->branch_id;
        $canChooseBranch = $userBranchId === null || in_array($user?->role, ['super_admin', 'admin'], true);
        if ($branchId !== null && $branchId !== '' && $canChooseBranch) {
            $query->where('branch_id', (int) $branchId);
        } elseif ($userBranchId !== null) {
            $query->where('branch_id', $userBranchId);
        } elseif ($user && $user->role === 'admin' && $user->company_id) {
            $companyBranchIds = Branch::where('company_id', $user->company_id)->pluck('id')->all();
            if (! empty($companyBranchIds)) {
                $query->whereIn('branch_id', $companyBranchIds);
            } else {
                $query->whereRaw('1 = 0');
            }
        }
        $products = $query->with('category')->paginate($request->get('per_page', 15));
        return response()->json(['status' => true, 'data' => $products]);
    }

    /**
     * Show one product. Admin can view products in their company's branches.
     */
    public function show(Request $request, Product $product): JsonResponse
    {
        $user = $request->user();
        $userBranchId = $user?->branch_id;
        if ($user && $user->role === 'admin' && $user->company_id) {
            $product->load('branch');
            if (! $product->branch || (int) $product->branch->company_id !== (int) $user->company_id) {
                abort(404);
            }
        } elseif ($userBranchId !== null && (int) $product->branch_id !== (int) $userBranchId) {
            abort(404);
        }
        $product->load(['category', 'batches']);
        return response()->json(['status' => true, 'data' => $product]);
    }
}
