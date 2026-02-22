<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - CLOUD NODE ONLY (Chain, Reports, Sync Receive)
|--------------------------------------------------------------------------
*/

// Cloud-specific: chain dashboard and sync receive (branches moved to shared API)
Route::get('dashboard/chain', [\App\Http\Controllers\API\Cloud\ChainDashboardController::class, 'index']);
Route::get('reports/consolidated', [\App\Http\Controllers\API\Cloud\ConsolidatedReportController::class, 'index']);
Route::post('sync/receive', [\App\Http\Controllers\API\Cloud\CloudSyncController::class, 'receive']);
Route::post('sync/heartbeat', [\App\Http\Controllers\API\Cloud\CloudSyncController::class, 'heartbeat']);
