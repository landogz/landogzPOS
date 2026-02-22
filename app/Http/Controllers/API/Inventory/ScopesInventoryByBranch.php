<?php

namespace App\Http\Controllers\API\Inventory;

use Illuminate\Http\Request;

trait ScopesInventoryByBranch
{
    /**
     * Whether to scope inventory by the user's branch. Super admin and admin see all branches.
     */
    protected function shouldScopeByBranch(Request $request): bool
    {
        $user = $request->user();
        if (! $user) {
            return true;
        }
        if (in_array($user->role, ['super_admin', 'admin'], true)) {
            return false;
        }
        return true;
    }

    /**
     * Branch ID for inventory: manager and inventory_manager must have a branch; only that branch's data is used.
     */
    protected function branchIdForInventory(Request $request): int
    {
        $user = $request->user();
        $branchId = $user?->branch_id;
        if ($branchId) {
            return (int) $branchId;
        }
        if ($user && in_array($user->role, ['manager', 'inventory_manager'], true)) {
            abort(403, 'Your account must be assigned to a branch. Inventory and products are scoped to your branch.');
        }
        $fallback = config('app.branch_id');
        if (! $fallback) {
            abort(403, 'No branch context. Assign your account to a branch or set BRANCH_ID.');
        }
        return (int) $fallback;
    }
}
