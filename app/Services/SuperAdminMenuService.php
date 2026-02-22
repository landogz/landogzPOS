<?php

namespace App\Services;

/**
 * Menu access for super-admin sidebar by role/privilege.
 * Aligned with AuthController::permissionsForRole() and API restrictions.
 *
 * Roles:
 * - super_admin: all; can add multiple companies and admins (per company).
 * - admin: owner (company); can set branches and create manager accounts for branches.
 * - manager: branch-scoped; dashboard, users, products, transactions, inventory, reports, receipts (no branches/terminals/BIR).
 * - pharmacist: inventory only.
 * - cashier: POS (separate layout later).
 * Terminals: super_admin only.
 */
class SuperAdminMenuService
{
    /** Permission keys that gate each menu item (reports = whole Reports submenu). */
    public const MENU_DASHBOARD = 'dashboard';
    public const MENU_USERS = 'users';
    public const MENU_SUPPLIERS = 'suppliers';
    public const MENU_PRODUCTS = 'products';
    public const MENU_CATEGORIES = 'categories';
    public const MENU_TRANSACTIONS = 'transactions';
    public const MENU_INVENTORY = 'inventory';
    public const MENU_REPORTS = 'reports';
    public const MENU_BRANCHES = 'branches';
    public const MENU_COMPANIES = 'companies'; // super_admin only
    public const MENU_TERMINALS = 'terminals'; // super_admin only
    public const MENU_BIR = 'bir';
    public const MENU_RECEIPTS = 'receipts';

    /** Permissions per role (same as AuthController). * = all. */
    private static function permissionsForRole(string $role): array
    {
        $map = [
            'super_admin' => ['*'],
            'admin' => ['dashboard', 'users', 'suppliers', 'products', 'categories', 'transactions', 'inventory', 'reports', 'branches', 'bir', 'receipts'],
            'manager' => ['dashboard', 'users', 'suppliers', 'products', 'categories', 'transactions', 'inventory', 'reports', 'receipts'],
            'inventory_manager' => ['products', 'inventory', 'reports', 'receipts', 'stock-levels', 'batches'],
            'pharmacist' => ['inventory'],
            'cashier' => ['transactions', 'receipts', 'products.lookup'],
        ];
        return $map[$role] ?? ['transactions', 'receipts', 'products.lookup'];
    }

    /**
     * Whether the given role can access the menu item (permission key).
     * Terminals: only super_admin.
     */
    public static function canAccess(?string $role, string $permission): bool
    {
        if ($role === null || $role === '') {
            return true; // no role = show all (e.g. before auth)
        }

        if ($permission === self::MENU_TERMINALS || $permission === self::MENU_COMPANIES) {
            return $role === 'super_admin';
        }

        $permissions = self::permissionsForRole($role);
        if (in_array('*', $permissions, true)) {
            return true;
        }
        return in_array($permission, $permissions, true);
    }

    /**
     * Current user role for menu (from auth or config when no user).
     * When no user: use config('super-admin.menu_default_role'); default 'super_admin' shows all.
     * Set SUPER_ADMIN_MENU_DEFAULT_ROLE=pharmacist (e.g. in .env) to test filtered menu without auth.
     */
    public static function currentRole(): ?string
    {
        $user = auth()->user();
        if ($user && isset($user->role)) {
            return $user->role;
        }
        return config('super-admin.menu_default_role') ?? 'super_admin';
    }
}
