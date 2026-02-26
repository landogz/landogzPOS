<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - PharmaPOS / Landogz POS (Multi-Node)
|--------------------------------------------------------------------------
| Prefix: /api/v1 (see RouteServiceProvider)
| Node middleware: 'node:local' | 'node:cloud' restricts by NODE_TYPE.
*/

// ─── Public (no auth) ──────────────────────────────────────────────────
Route::prefix('v1')->group(function () {
    Route::post('otp/send', [\App\Http\Controllers\API\OtpController::class, 'send'])->middleware('throttle:5,1');
    Route::post('otp/verify', [\App\Http\Controllers\API\OtpController::class, 'verify'])->middleware('throttle:10,1');
    Route::post('auth/login', [\App\Http\Controllers\API\AuthController::class, 'login']);
    Route::post('auth/verify-login-otp', [\App\Http\Controllers\API\AuthController::class, 'verifyLoginOtp'])->middleware('throttle:10,1');
    Route::post('auth/resend-login-otp', [\App\Http\Controllers\API\AuthController::class, 'resendLoginOtp'])->middleware('throttle:3,1');
    Route::post('auth/login-pin', [\App\Http\Controllers\API\AuthController::class, 'loginPin']);
    Route::post('auth/logout', [\App\Http\Controllers\API\AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('auth/me', [\App\Http\Controllers\API\AuthController::class, 'me'])->middleware('auth:sanctum');
    Route::post('quote-request', [\App\Http\Controllers\API\QuoteRequestController::class, 'store']);
});

// ─── Shared: available on BOTH cloud and local ─────────────────────────
Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    Route::get('user', fn (Request $request) => response()->json([
        'status' => 'success',
        'data' => $request->user()->load('branch.company'),
    ]));
    Route::apiResource('users', \App\Http\Controllers\API\UserController::class);
    Route::apiResource('products', \App\Http\Controllers\API\ProductController::class)->only(['index', 'show']);
    Route::apiResource('transactions', \App\Http\Controllers\API\TransactionController::class)->only(['index', 'show']);
    // Dashboard (1st prompt)
    Route::get('dashboard/summary', [\App\Http\Controllers\API\DashboardController::class, 'summary']);
    Route::get('dashboard/low-stock-alerts', [\App\Http\Controllers\API\DashboardController::class, 'lowStockAlerts']);
    Route::get('dashboard/expiring-soon', [\App\Http\Controllers\API\DashboardController::class, 'expiringSoon']);
    Route::get('dashboard/sales-today', [\App\Http\Controllers\API\DashboardController::class, 'salesToday']);
    Route::get('dashboard/branch-overview', [\App\Http\Controllers\API\DashboardController::class, 'branchOverview']);
    Route::get('geocode/reverse', [\App\Http\Controllers\API\GeocodeController::class, 'reverse'])->middleware('throttle:30,1');
    Route::get('geocode', [\App\Http\Controllers\API\GeocodeController::class, '__invoke'])->middleware('throttle:30,1');
    // BIR & Receipts (1st prompt)
    Route::get('bir/settings', [\App\Http\Controllers\API\BirSettingsController::class, 'index']);
    Route::put('bir/settings', [\App\Http\Controllers\API\BirSettingsController::class, 'update']);
    Route::get('receipts/{transaction_id}', [\App\Http\Controllers\API\ReceiptController::class, 'show']);
    Route::post('receipts/reprint/{id}', [\App\Http\Controllers\API\ReceiptController::class, 'reprint']);
    // Reports (1st prompt)
    Route::get('reports/sales', [\App\Http\Controllers\API\ReportController::class, 'sales']);
    Route::get('reports/inventory', [\App\Http\Controllers\API\ReportController::class, 'inventory']);
    Route::get('reports/profit-margin', [\App\Http\Controllers\API\ReportController::class, 'profitMargin']);
    Route::get('reports/expiring-products', [\App\Http\Controllers\API\ReportController::class, 'expiringProducts']);
    Route::get('reports/top-selling', [\App\Http\Controllers\API\ReportController::class, 'topSelling']);
    Route::get('reports/cashier-summary', [\App\Http\Controllers\API\ReportController::class, 'cashierSummary']);
    Route::get('reports/vat-summary', [\App\Http\Controllers\API\ReportController::class, 'vatSummary']);
    Route::get('reports/audit-log', [\App\Http\Controllers\API\ReportController::class, 'auditLog']);
    // Companies (super_admin only)
    Route::get('companies', [\App\Http\Controllers\API\CompanyController::class, 'index']);
    Route::post('companies', [\App\Http\Controllers\API\CompanyController::class, 'store']);
    Route::patch('companies/{company}/toggle-status', [\App\Http\Controllers\API\CompanyController::class, 'toggleStatus']);
    Route::get('companies/{company}/summary', [\App\Http\Controllers\API\CompanyController::class, 'summary']);
    Route::get('companies/{company}', [\App\Http\Controllers\API\CompanyController::class, 'show']);
    Route::put('companies/{company}', [\App\Http\Controllers\API\CompanyController::class, 'update']);
    Route::delete('companies/{company}', [\App\Http\Controllers\API\CompanyController::class, 'destroy']);
    // Branches (1st prompt)
    Route::get('branches', [\App\Http\Controllers\API\BranchController::class, 'index']);
    Route::post('branches', [\App\Http\Controllers\API\BranchController::class, 'store']);
    Route::get('branches/{branch}', [\App\Http\Controllers\API\BranchController::class, 'show']);
    Route::put('branches/{branch}', [\App\Http\Controllers\API\BranchController::class, 'update']);
    Route::patch('branches/{branch}/toggle-status', [\App\Http\Controllers\API\BranchController::class, 'toggleStatus']);
    Route::delete('branches/{branch}', [\App\Http\Controllers\API\BranchController::class, 'destroy']);
    Route::get('branches/{branch}/dashboard', [\App\Http\Controllers\API\BranchController::class, 'dashboard']);
    Route::get('branches/{branch}/stock', [\App\Http\Controllers\API\BranchController::class, 'stock']);
    Route::post('branches/{branch}/replenishment-request', [\App\Http\Controllers\API\BranchController::class, 'replenishmentRequest']);
    // Inventory (read: stock-levels + products list — available on all nodes; write/stock-in/out stay in api-local)
    Route::get('inventory/stock-levels', [\App\Http\Controllers\API\Inventory\StockLevelController::class, 'index']);
    Route::get('inventory/products', [\App\Http\Controllers\API\Inventory\InventoryProductController::class, 'index']);
    // Terminals: one branch can have multiple POS terminals
    Route::get('terminals', [\App\Http\Controllers\API\TerminalController::class, 'indexAll']);
    Route::get('branches/{branch}/terminals', [\App\Http\Controllers\API\TerminalController::class, 'index']);
    Route::post('branches/{branch}/terminals', [\App\Http\Controllers\API\TerminalController::class, 'store']);
    Route::get('branches/{branch}/terminals/{terminal}', [\App\Http\Controllers\API\TerminalController::class, 'show']);
    Route::put('branches/{branch}/terminals/{terminal}', [\App\Http\Controllers\API\TerminalController::class, 'update']);
    Route::delete('branches/{branch}/terminals/{terminal}', [\App\Http\Controllers\API\TerminalController::class, 'destroy']);
    Route::post('branches/{branch}/terminals/{terminal}/generate-key', [\App\Http\Controllers\API\TerminalController::class, 'generateKey']);
    Route::post('branches/{branch}/terminals/{terminal}/revoke-key', [\App\Http\Controllers\API\TerminalController::class, 'revokeKey']);
});

// ─── Local node only: inventory, POS, sync push/pull, receipts ──────────
Route::prefix('v1')->middleware(['auth:sanctum', 'node:local'])->group(base_path('routes/api-local.php'));

// ─── Cloud node only: chain management, consolidated reports, sync receive ───
Route::prefix('v1')->middleware(['auth:sanctum', 'node:cloud'])->group(base_path('routes/api-cloud.php'));
