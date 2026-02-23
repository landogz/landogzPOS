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
        $query = Branch::query()
            ->with('company:id,name')
            ->withCount('terminals')
            ->withSum('transactions', 'total');
        $user = $request->user();
        if ($user && $user->role !== 'super_admin') {
            if ($user->role === 'admin' && $user->company_id) {
                $query->where('company_id', $user->company_id);
            } elseif ($user->branch_id) {
                $query->where('id', $user->branch_id);
            }
        }
        $companyId = $request->query('company_id');
        if ($companyId !== null && $companyId !== '') {
            $query->where('company_id', (int) $companyId);
        }
        $status = $request->query('status');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
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
            'is_active' => 'nullable|boolean',
        ]);
        if (! isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }
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

    public function update(Request $request, Branch $branch): JsonResponse
    {
        if (! $this->canManageBranches($request->user())) {
            return response()->json(['status' => false, 'message' => 'Only super admin or admin can update branches.'], 403);
        }
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => 'nullable|string',
            'tin' => 'nullable|string|max:50',
            'bir_series_start' => 'nullable|string|max:50',
            'bir_series_end' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);
        $branch->update($validated);
        $branch->load('company');
        return response()->json(['status' => 'success', 'message' => 'Branch updated.', 'data' => $branch]);
    }

    public function toggleStatus(Request $request, Branch $branch): JsonResponse
    {
        if (! $this->canManageBranches($request->user())) {
            return response()->json(['status' => false, 'message' => 'Only super admin or admin can manage branches.'], 403);
        }
        $branch->update(['is_active' => ! $branch->is_active]);
        $branch->load('company');
        return response()->json([
            'status' => 'success',
            'message' => $branch->is_active ? 'Branch activated.' : 'Branch deactivated.',
            'data' => $branch,
        ]);
    }

    public function destroy(Request $request, Branch $branch): JsonResponse
    {
        if (! $this->canManageBranches($request->user())) {
            return response()->json(['status' => false, 'message' => 'Only super admin or admin can delete branches.'], 403);
        }
        $branch->delete();
        return response()->json(['status' => 'success', 'message' => 'Branch deleted.', 'data' => null]);
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
        if ($user->role === 'super_admin') {
            return;
        }
        if ($user->role === 'admin') {
            if ($user->company_id && (int) $branch->company_id !== (int) $user->company_id) {
                abort(403, 'You can only access branches of your company.');
            }
            return;
        }
        if ($user->branch_id && (int) $user->branch_id !== (int) $branch->id) {
            abort(403, 'You can only access your own branch.');
        }
    }
}
