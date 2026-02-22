<?php

namespace App\Http\Controllers\API\Cloud;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CloudSyncController extends Controller
{
    /**
     * Receive pushed records from local nodes (API sync mode).
     */
    public function receive(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'branch_id' => 'required|integer',
            'model_type' => 'required|string|max:100',
            'action' => 'required|string|in:create,update,delete',
            'payload' => 'nullable|array',
        ]);
        $table = $validated['model_type'];
        $action = $validated['action'];
        $payload = $validated['payload'] ?? [];
        try {
            if ($action === 'delete' && isset($payload['id'])) {
                DB::table($table)->where('id', $payload['id'])->delete();
            } elseif (in_array($action, ['create', 'update']) && !empty($payload)) {
                DB::table($table)->upsert([$payload], ['id'], array_keys($payload));
            }
            return response()->json(['status' => true, 'message' => 'Received.']);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * POST /api/v1/sync/heartbeat — receive heartbeat from local nodes (cloud only).
     */
    public function heartbeat(Request $request): JsonResponse
    {
        $request->validate([
            'branch_id' => 'nullable|integer',
            'node_id' => 'nullable|integer',
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Heartbeat received.',
            'data' => ['at' => now()->toIso8601String()],
        ]);
    }
}
