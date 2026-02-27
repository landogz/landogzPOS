<?php

namespace App\Http\Controllers\API\POS;

use App\Http\Controllers\Controller;
use App\Models\PosHeldOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PosHeldOrderController extends Controller
{
    /**
     * List held orders for the current cashier's branch and terminal.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $branchId = $user->branch_id;
        $terminalId = $request->query('terminal_id');

        if (! $branchId) {
            return response()->json([
                'status' => false,
                'message' => 'Your account must be assigned to a branch.',
            ], 403);
        }

        $query = PosHeldOrder::where('branch_id', $branchId)
            ->where('cashier_id', $user->id)
            ->orderByDesc('created_at');

        if ($terminalId) {
            $query->where('terminal_id', $terminalId);
        }

        $held = $query->get()->map(function ($h) {
            return [
                'id' => $h->id,
                'payload' => $h->payload,
                'created_at' => $h->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'status' => true,
            'message' => null,
            'data' => $held->values()->all(),
        ]);
    }

    /**
     * Store a new held order (current cart snapshot).
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'terminal_id' => 'required|exists:terminals,id',
            'payload' => 'required|array',
            'payload.items' => 'required|array',
            'payload.discounts' => 'nullable|array',
            'payload.serviceCharge' => 'nullable|numeric|min:0',
        ]);

        $user = $request->user();
        $branchId = $user->branch_id;

        if (! $branchId) {
            return response()->json([
                'status' => false,
                'message' => 'Your account must be assigned to a branch.',
            ], 403);
        }

        $terminalId = $request->input('terminal_id');
        $terminal = \App\Models\Terminal::where('id', $terminalId)
            ->where('branch_id', $branchId)
            ->first();

        if (! $terminal) {
            return response()->json([
                'status' => false,
                'message' => 'Terminal not found or not assigned to your branch.',
            ], 403);
        }

        $payload = [
            'items' => $request->input('payload.items'),
            'discounts' => $request->input('payload.discounts', []),
            'serviceCharge' => (float) ($request->input('payload.serviceCharge', 0) ?: 0),
        ];

        $held = PosHeldOrder::create([
            'branch_id' => $branchId,
            'terminal_id' => $terminalId,
            'cashier_id' => $user->id,
            'payload' => $payload,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Order held.',
            'data' => [
                'id' => $held->id,
                'payload' => $held->payload,
                'created_at' => $held->created_at->toIso8601String(),
            ],
        ], 201);
    }

    /**
     * Delete a held order (e.g. when resumed or discarded).
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $held = PosHeldOrder::where('id', $id)
            ->where('cashier_id', $user->id)
            ->first();

        if (! $held) {
            return response()->json([
                'status' => false,
                'message' => 'Held order not found.',
            ], 404);
        }

        $held->delete();

        return response()->json([
            'status' => true,
            'message' => 'Held order removed.',
            'data' => null,
        ]);
    }
}
