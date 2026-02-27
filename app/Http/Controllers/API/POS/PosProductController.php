<?php

namespace App\Http\Controllers\API\POS;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PosProductController extends Controller
{
    /**
     * Get branch ID for the logged-in cashier. POS products and inventory are always scoped to this branch.
     */
    private function branchIdForUser(Request $request): ?int
    {
        $branchId = $request->user()?->branch_id;
        return $branchId ? (int) $branchId : null;
    }

    /**
     * List all products and inventory for the cashier's branch. Only that branch's products are returned.
     */
    public function index(Request $request): JsonResponse
    {
        $branchId = $this->branchIdForUser($request);
        if (! $branchId) {
            return response()->json([
                'status' => false,
                'message' => 'Your account must be assigned to a branch. POS shows only products and inventory for your branch.',
            ], 403);
        }
        $products = Product::query()
            ->where('branch_id', $branchId)
            ->where('is_active', true)
            ->with(['category:id,name,type', 'batches' => fn ($b) => $b->where('quantity', '>', 0)->orderBy('expiry_date')])
            ->orderBy('name')
            ->paginate($request->get('per_page', 100));
        return response()->json(['status' => true, 'data' => $products]);
    }

    /**
     * Search products by barcode or name. Only returns products from the cashier's branch.
     */
    public function search(Request $request): JsonResponse
    {
        $branchId = $this->branchIdForUser($request);
        if (! $branchId) {
            return response()->json([
                'status' => false,
                'message' => 'Your account must be assigned to a branch. Product search is scoped to your branch.',
            ], 403);
        }
        $q = $request->get('q', '');
        $products = Product::query()
            ->where('branch_id', $branchId)
            ->where('is_active', true)
            ->when($q !== '', fn ($query) => $query->where(fn ($qry) => $qry
                ->where('barcode', 'like', '%' . $q . '%')
                ->orWhere('name', 'like', '%' . $q . '%')
                ->orWhere('generic_name', 'like', '%' . $q . '%')))
            ->with(['category:id,name,type', 'batches' => fn ($b) => $b->where('quantity', '>', 0)->orderBy('expiry_date')])
            ->limit(20)
            ->get();
        return response()->json(['status' => true, 'data' => $products]);
    }
}
