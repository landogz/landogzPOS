<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /** Roles a manager is allowed to create/assign for their branch. */
    private const MANAGER_ALLOWED_ROLES = ['cashier', 'inventory_manager'];

    public function index(Request $request): JsonResponse
    {
        $query = User::query()->with('branch:id,name')->with('company:id,name');
        $currentUser = $request->user();
        if ($currentUser && $currentUser->role === 'manager' && $currentUser->branch_id) {
            $query->where('branch_id', $currentUser->branch_id);
        } elseif ($currentUser && $currentUser->role === 'admin' && $currentUser->company_id) {
            $query->where('company_id', $currentUser->company_id);
        } elseif ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->filled('company_id')) {
            if ($currentUser && $currentUser->role === 'admin' && $currentUser->company_id && (int) $request->company_id !== (int) $currentUser->company_id) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('company_id', $request->company_id);
            }
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn ($qry) => $qry->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"));
        }
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);
        $users = $query->paginate($request->get('per_page', 15));
        return response()->json([
            'status' => 'success',
            'data' => $users,
            'meta' => ['current_page' => $users->currentPage(), 'total' => $users->total(), 'per_page' => $users->perPage()],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'branch_id' => 'nullable|exists:branches,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:super_admin,admin,manager,pharmacist,cashier,inventory_manager',
            'pin' => 'nullable|string|size:4',
            'is_active' => 'nullable|boolean',
        ]);

        $currentUser = $request->user();
        if ($currentUser && $currentUser->role === 'manager') {
            if (!$currentUser->branch_id) {
                return response()->json(['status' => false, 'message' => 'Manager must be assigned to a branch to create users.'], 403);
            }
            if (!in_array($validated['role'], self::MANAGER_ALLOWED_ROLES, true)) {
                return response()->json(['status' => false, 'message' => 'Manager can only create cashier or inventory_manager for their branch.'], 403);
            }
            $validated['branch_id'] = $currentUser->branch_id;
        }
        if ($currentUser && $currentUser->role === 'admin' && $currentUser->company_id) {
            if ($validated['role'] === 'super_admin') {
                return response()->json(['status' => false, 'message' => 'You cannot create a Super Admin.'], 403);
            }
            $validated['company_id'] = $currentUser->company_id;
            if (!empty($validated['branch_id'])) {
                $branch = \App\Models\Branch::find($validated['branch_id']);
                if (!$branch || (int) $branch->company_id !== (int) $currentUser->company_id) {
                    return response()->json(['status' => false, 'message' => 'Branch must belong to your company.'], 403);
                }
            }
        }

        $validated['password'] = Hash::make($validated['password']);
        if (!empty($validated['pin'])) {
            $validated['pin_hash'] = Hash::make($validated['pin']);
        }
        unset($validated['pin']);
        $validated['is_active'] = $validated['is_active'] ?? true;
        $user = User::create($validated);
        $this->assignRoleIfExists($user, $validated['role']);
        $user->load(['branch', 'company']);
        return response()->json([
            'status' => 'success',
            'message' => 'User created.',
            'data' => $user,
        ], 201);
    }

    public function show(Request $request, User $user): JsonResponse
    {
        $currentUser = $request->user();
        if ($currentUser && $currentUser->role === 'admin' && $currentUser->company_id && (int) $user->company_id !== (int) $currentUser->company_id) {
            abort(404);
        }
        if ($currentUser && $currentUser->role === 'manager' && $currentUser->branch_id && (int) $user->branch_id !== (int) $currentUser->branch_id) {
            abort(404);
        }
        $user->load(['branch', 'company']);
        return response()->json(['status' => 'success', 'data' => $user]);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'branch_id' => 'nullable|exists:branches,id',
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'sometimes|string|in:super_admin,admin,manager,pharmacist,cashier,inventory_manager',
            'pin' => 'nullable|string|size:4',
            'is_active' => 'nullable|boolean',
        ]);

        $currentUser = $request->user();
        if ($currentUser && $currentUser->role === 'manager') {
            if (!$currentUser->branch_id || (int) $user->branch_id !== (int) $currentUser->branch_id) {
                return response()->json(['status' => false, 'message' => 'You can only update users in your branch.'], 403);
            }
            if (array_key_exists('role', $validated) && !in_array($validated['role'], self::MANAGER_ALLOWED_ROLES, true)) {
                return response()->json(['status' => false, 'message' => 'Manager can only assign role cashier or inventory_manager.'], 403);
            }
            $validated['branch_id'] = $currentUser->branch_id;
        }
        if ($currentUser && $currentUser->role === 'admin' && $currentUser->company_id) {
            if ((int) $user->company_id !== (int) $currentUser->company_id) {
                return response()->json(['status' => false, 'message' => 'You can only update users in your company.'], 403);
            }
            if (array_key_exists('role', $validated) && $validated['role'] === 'super_admin') {
                return response()->json(['status' => false, 'message' => 'You cannot assign Super Admin role.'], 403);
            }
            if (array_key_exists('company_id', $validated)) {
                $validated['company_id'] = $currentUser->company_id;
            }
            if (array_key_exists('branch_id', $validated) && $validated['branch_id']) {
                $branch = \App\Models\Branch::find($validated['branch_id']);
                if (!$branch || (int) $branch->company_id !== (int) $currentUser->company_id) {
                    return response()->json(['status' => false, 'message' => 'Branch must belong to your company.'], 403);
                }
            }
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }
        if (array_key_exists('pin', $validated)) {
            $validated['pin_hash'] = $validated['pin'] ? Hash::make($validated['pin']) : null;
        }
        unset($validated['pin'], $validated['password_confirmation']);
        $user->update($validated);
        if (array_key_exists('role', $validated)) {
            $this->assignRoleIfExists($user, $validated['role']);
        }
        app(SyncService::class)->enqueueUser($user->fresh());
        $user->load(['branch', 'company']);
        return response()->json([
            'status' => 'success',
            'message' => 'User updated.',
            'data' => $user->fresh(),
        ]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        $currentUser = $request->user();
        if ($currentUser && $currentUser->role === 'admin' && $currentUser->company_id && (int) $user->company_id !== (int) $currentUser->company_id) {
            return response()->json(['status' => false, 'message' => 'You can only deactivate users in your company.'], 403);
        }
        if ($currentUser && $currentUser->role === 'manager') {
            if (!$currentUser->branch_id || (int) $user->branch_id !== (int) $currentUser->branch_id) {
                return response()->json(['status' => false, 'message' => 'You can only deactivate users in your branch.'], 403);
            }
        }
        $user->tokens()->delete();
        $user->update(['is_active' => false]);
        return response()->json([
            'status' => 'success',
            'message' => 'User deactivated.',
            'data' => null,
        ]);
    }

    private function assignRoleIfExists(User $user, string $role): void
    {
        $roleModel = \Spatie\Permission\Models\Role::where('name', $role)->where('guard_name', 'web')->first();
        if ($roleModel && !$user->hasRole($role)) {
            $user->assignRole($roleModel);
        }
    }
}
