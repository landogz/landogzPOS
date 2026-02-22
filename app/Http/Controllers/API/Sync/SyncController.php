<?php

namespace App\Http\Controllers\API\Sync;

use App\Http\Controllers\Controller;
use App\Services\SyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    public function push(Request $request): JsonResponse
    {
        app(SyncService::class)->pushToCloud();
        return response()->json(['status' => true, 'message' => 'Sync push completed.']);
    }

    public function pull(Request $request): JsonResponse
    {
        app(SyncService::class)->pullFromCloud();
        return response()->json(['status' => true, 'message' => 'Sync pull completed.']);
    }

    /**
     * POST /api/v1/sync/heartbeat — terminal/local reports online status.
     */
    public function heartbeat(Request $request): JsonResponse
    {
        if (config('app.node_type') === 'local') {
            app(SyncService::class)->heartbeat();
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Heartbeat received.',
            'data' => ['at' => now()->toIso8601String()],
        ]);
    }
}
