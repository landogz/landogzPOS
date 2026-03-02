<?php

use App\Http\Controllers\SuperAdmin\SuperAdminViewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
});

Route::get('request-quote', [SuperAdminViewController::class, 'requestQuote'])->name('quote.request');

// Super-admin login (alias: route name used by landing/footer; same as dashboard/login)
Route::get('super-admin/login', [SuperAdminViewController::class, 'login'])->name('super-admin.login');

// Redirect old /super-admin URLs to /dashboard (backward compatibility)
Route::get('super-admin/login', [SuperAdminViewController::class, 'login'])->name('super-admin.login');
Route::get('super-admin', function () {
    return redirect()->route('dashboard.dashboard', [], 301);
});
Route::get('super-admin/{path}', function ($path) {
    $path = trim($path, '/');
    return redirect()->to(url('/dashboard' . ($path ? '/' . $path : '')), 301);
})->where('path', '.*');

// Dashboard panel (Rubick side-menu; login + dashboard + API-based menus)
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('login', [SuperAdminViewController::class, 'login'])->name('login');
    Route::get('/', [SuperAdminViewController::class, 'dashboard'])->name('dashboard');
    Route::get('users', [SuperAdminViewController::class, 'users'])->name('users');
    Route::get('suppliers', [SuperAdminViewController::class, 'suppliers'])->name('suppliers');
    Route::get('products', [SuperAdminViewController::class, 'products'])->name('products');
    Route::get('categories', [SuperAdminViewController::class, 'categories'])->name('categories');
    Route::get('transactions', [SuperAdminViewController::class, 'transactions'])->name('transactions');
    Route::get('pos', [SuperAdminViewController::class, 'pos'])->name('pos');
    Route::get('pos/lock', [SuperAdminViewController::class, 'posLock'])->name('pos.lock');
    Route::get('pos/receipt-print', [SuperAdminViewController::class, 'posReceiptPrint'])->name('pos.receipt-print');
    Route::get('pos/x-reading-print', [SuperAdminViewController::class, 'posXReadingPrint'])->name('pos.x-reading-print');
    Route::get('pos/z-reading-print', [SuperAdminViewController::class, 'posZReadingPrint'])->name('pos.z-reading-print');
    Route::get('inventory', [SuperAdminViewController::class, 'inventory'])->name('inventory');
    Route::get('chain', [SuperAdminViewController::class, 'chainDashboard'])->name('chain');
    Route::get('companies', [SuperAdminViewController::class, 'companies'])->name('companies');
    Route::get('companies/{company}/summary', [SuperAdminViewController::class, 'companySummary'])->name('companies.summary');
    Route::get('branches', [SuperAdminViewController::class, 'branches'])->name('branches');
    Route::get('terminals', [SuperAdminViewController::class, 'terminals'])->name('terminals');
    Route::get('bir-settings', [SuperAdminViewController::class, 'birSettings'])->name('bir-settings');
    Route::get('receipts', [SuperAdminViewController::class, 'receipts'])->name('receipts');
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('z-reading', [SuperAdminViewController::class, 'reportPage'])->name('z-reading');
        Route::get('x-reading', [SuperAdminViewController::class, 'reportPage'])->name('x-reading');
        Route::get('sales', [SuperAdminViewController::class, 'reportPage'])->name('sales');
        Route::get('vat-relief', [SuperAdminViewController::class, 'reportPage'])->name('vat-relief');
        Route::get('alphalist-payees', [SuperAdminViewController::class, 'reportPage'])->name('alphalist-payees');
        Route::get('audit-trail', [SuperAdminViewController::class, 'reportPage'])->name('audit-trail');
        Route::get('inventory', [SuperAdminViewController::class, 'reportPage'])->name('inventory');
        Route::get('profit-margin', [SuperAdminViewController::class, 'reportPage'])->name('profit-margin');
        Route::get('expiring-products', [SuperAdminViewController::class, 'reportPage'])->name('expiring-products');
        Route::get('top-selling', [SuperAdminViewController::class, 'reportPage'])->name('top-selling');
        Route::get('cashier-summary', [SuperAdminViewController::class, 'reportPage'])->name('cashier-summary');
        Route::get('vat-summary', [SuperAdminViewController::class, 'reportPage'])->name('vat-summary');
        Route::get('audit-log', [SuperAdminViewController::class, 'reportPage'])->name('audit-log');
        Route::get('consolidated', [SuperAdminViewController::class, 'reportPage'])->name('consolidated');
    });
});
