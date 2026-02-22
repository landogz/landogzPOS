<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\ReplenishmentRequest;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Branch::query()->with('company:id,name');
        if ($request->user()?->branch_id && $request->user()?->role !== 'super_admin' && $request->user()?->role !== 'admin') {
            $query->where('id', $request->user()->branch_id);
        }
        $branches = $query->orderBy('name')->get();
        return response()->json([
            'status' => 'success',
            'data' => $branches,
        ]);
    }

    /**
     * Create a branch. Only super_admin and admin can create branches (e.g. to set up manager branches).
     */
    public function store(Request $request): JsonResponse
    {
        if (!$this->canManageBranches($request->user())) {
            return response()->json([
                'status' => false,
                'message' => 'Only super admin or admin can create branches.',
            ], 403);
        }
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'tin' => 'nullable|string|max:50',
            'bir_series_start' => 'nullable|string|max:50',
            'bir_series_end' => 'nullable|string|max:50',
        ]);
        $branch = Branch::create($validated);
        $branch->load('company');
        return response()->json([
            'status' => 'success',
            'message' => 'Branch created.',
            'data' => $branch,
        ], 201);
    }

    public function show(Request $request, Branch $branch): JsonResponse
    {
        $this->ensureBranchAccess($request->user(), $branch);
        $branch->load('company');
        return response()->json(['status' => 'success', 'data' => $branch]);
    }

    /**
     * GET /api/v1/branches/{id}/dashboard
     */
    public function dashboard(Request $request, Branch $branch): JsonResponse
    {
        $this->ensureBranchAccess($request->user(), $branch);
        $salesToday = Transaction::where('branch_id', $branch->id)
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total');
        $transactionCount = Transaction::where('branch_id', $branch->id)
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->count();
        return response()->json([
            'status' => 'success',
            'data' => [
                'branch' => $branch->load('company'),
                'sales_today' => (float) $salesToday,
                'transaction_count_today' => $transactionCount,
            ],
        ]);
    }

    /**
     * GET /api/v1/branches/{id}/stock
     */
    public function stock(Request $request, Branch $branch): JsonResponse
    {
        $this->ensureBranchAccess($request->user(), $branch);
        $products = Product::where('branch_id', $branch->id)
            ->withSum('batches', 'quantity')
            ->get()
            ->map(fn ($p) => [
                'product_id' => $p->id,
                'name' => $p->name,
                'barcode' => $p->barcode,
                'quantity' => (float) ($p->batches_sum_quantity ?? 0),
                'reorder_level' => $p->reorder_level,
            ]);
        return response()->json([
            'status' => 'success',
            'data' => ['branch_id' => $branch->id, 'items' => $products],
        ]);
    }

    /**
     * POST /api/v1/branches/{id}/replenishment-request
     */
    public function replenishmentRequest(Request $request, Branch $branch): JsonResponse
    {
        $this->ensureBranchAccess($request->user(), $branch);
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_requested' => 'required|numeric|min:0.001',
        ]);
        $validated['requesting_branch_id'] = $branch->id;
        $validated['status'] = 'pending';
        $req = ReplenishmentRequest::create($validated);
        $req->load('product:id,name,barcode');
        return response()->json([
            'status' => 'success',
            'message' => 'Replenishment request submitted.',
            'data' => $req,
        ], 201);
    }

    private function canManageBranches($user): bool
    {
        return $user && in_array($user->role, ['super_admin', 'admin'], true);
    }

    private function ensureBranchAccess($user, Branch $branch): void
    {
        if (!$user) {
            return;
        }
        if (in_array($user->role, ['super_admin', 'admin'], true)) {
            return;
        }
        if ($user->branch_id && (int) $user->branch_id !== (int) $branch->id) {
            abort(403, 'You can only access your own branch.');
        }
    }
}
