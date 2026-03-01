<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - LOCAL NODE ONLY (Inventory, POS, Sync)
|--------------------------------------------------------------------------
*/

// Inventory (write/show/update/delete and stock-in/out — GET products & stock-levels are in main api.php)
Route::post('inventory/products', [\App\Http\Controllers\API\Inventory\InventoryProductController::class, 'store']);
Route::get('inventory/products/{product}', [\App\Http\Controllers\API\Inventory\InventoryProductController::class, 'show']);
Route::put('inventory/products/{product}', [\App\Http\Controllers\API\Inventory\InventoryProductController::class, 'update']);
Route::delete('inventory/products/{product}', [\App\Http\Controllers\API\Inventory\InventoryProductController::class, 'destroy']);
Route::post('inventory/stock-in', [\App\Http\Controllers\API\Inventory\StockInController::class, 'store']);
Route::post('inventory/stock-out', [\App\Http\Controllers\API\Inventory\StockOutController::class, 'store']);
Route::post('inventory/stock-transfer', [\App\Http\Controllers\API\Inventory\StockTransferController::class, 'store']);
Route::get('inventory/batches/{product}', [\App\Http\Controllers\API\Inventory\BatchController::class, 'index']);
Route::get('inventory/expiring', [\App\Http\Controllers\API\Inventory\InventoryReportController::class, 'expiring']);
Route::get('inventory/low-stock', [\App\Http\Controllers\API\Inventory\InventoryReportController::class, 'lowStock']);
Route::get('inventory/valuation', [\App\Http\Controllers\API\Inventory\InventoryReportController::class, 'valuation']);
Route::post('inventory/receive-delivery', [\App\Http\Controllers\API\Inventory\ReceiveDeliveryController::class, 'store']);
Route::get('inventory/purchase-history', [\App\Http\Controllers\API\Inventory\InventoryReportController::class, 'purchaseHistory']);

// POS (products and inventory shown are always for the logged-in cashier's branch; terminal must be registered)
Route::get('pos/terminal/check', [\App\Http\Controllers\API\POS\PosSessionController::class, 'checkTerminal']);
Route::get('pos/terminal/current', [\App\Http\Controllers\API\POS\PosSessionController::class, 'currentFromEnv']);
Route::post('pos/session/open', [\App\Http\Controllers\API\POS\PosSessionController::class, 'open']);
Route::post('pos/session/close', [\App\Http\Controllers\API\POS\PosSessionController::class, 'close']);
Route::post('pos/verify-manager', [\App\Http\Controllers\API\POS\PosSessionController::class, 'verifyManager']);
Route::post('pos/log-line-void', [\App\Http\Controllers\API\POS\PosSessionController::class, 'logLineVoid']);
Route::get('pos/products', [\App\Http\Controllers\API\POS\PosProductController::class, 'index']);
Route::get('pos/products/search', [\App\Http\Controllers\API\POS\PosProductController::class, 'search']);
Route::get('pos/transactions', [\App\Http\Controllers\API\POS\PosTransactionController::class, 'index']);
Route::post('pos/transactions', [\App\Http\Controllers\API\POS\PosTransactionController::class, 'store']);
Route::get('pos/transactions/{transaction}/receipt', [\App\Http\Controllers\API\POS\PosTransactionController::class, 'receipt']);
Route::post('pos/transactions/{transaction}/void', [\App\Http\Controllers\API\POS\PosTransactionController::class, 'void']);
Route::post('pos/x-reading/generate', [\App\Http\Controllers\API\POS\XReadingController::class, 'generate']);
Route::get('pos/x-reading/latest', [\App\Http\Controllers\API\POS\XReadingController::class, 'latest']);
Route::get('pos/x-reading/history', [\App\Http\Controllers\API\POS\XReadingController::class, 'history']);
Route::get('pos/x-reading/{id}', [\App\Http\Controllers\API\POS\XReadingController::class, 'show']);
Route::patch('pos/x-reading/{id}/printed', [\App\Http\Controllers\API\POS\XReadingController::class, 'markPrinted']);
Route::get('pos/cashier/summary', [\App\Http\Controllers\API\POS\PosCashierController::class, 'summary']);
Route::get('pos/held-orders', [\App\Http\Controllers\API\POS\PosHeldOrderController::class, 'index']);
Route::post('pos/held-orders', [\App\Http\Controllers\API\POS\PosHeldOrderController::class, 'store']);
Route::delete('pos/held-orders/{id}', [\App\Http\Controllers\API\POS\PosHeldOrderController::class, 'destroy']);

// Sync (local pushes to cloud; pull master data from cloud when SYNC_MODE=direct_db)
Route::post('sync/push', [\App\Http\Controllers\API\Sync\SyncController::class, 'push']);
Route::get('sync/pull', [\App\Http\Controllers\API\Sync\SyncController::class, 'pull']);
Route::post('sync/pull-master', [\App\Http\Controllers\API\Sync\SyncController::class, 'pullMaster']);
Route::post('sync/heartbeat', [\App\Http\Controllers\API\Sync\SyncController::class, 'heartbeat']);

// Receipts
Route::post('receipts/print', [\App\Http\Controllers\API\ReceiptController::class, 'print']);
