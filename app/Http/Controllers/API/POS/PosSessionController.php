<?php

namespace App\Http\Controllers\API\POS;

use App\Http\Controllers\Controller;
use App\Models\PosSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PosSessionController extends Controller
{
    /**
     * Check if a terminal is registered and active for the current user's branch.
     * Use this on POS startup; if 403, show "POS is not registered".
     */
    public function checkTerminal(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user->branch_id) {
            return response()->json([
                'status' => false,
                'message' => 'Your account must be assigned to a branch.',
            ], 403);
        }
        $terminalId = $request->query('terminal_id');
        if (! $terminalId) {
            return response()->json([
                'status' => false,
                'message' => 'POS is not registered. Please register this terminal in Settings.',
            ], 403);
        }
        $terminal = \App\Models\Terminal::where('id', $terminalId)
            ->where('branch_id', $user->branch_id)
            ->first();
        if (! $terminal) {
            return response()->json([
                'status' => false,
                'message' => 'POS is not registered. Please register this terminal in Settings.',
            ], 403);
        }
        if (! $terminal->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'POS is not registered. This terminal is inactive. Please contact your administrator.',
            ], 403);
        }
        return response()->json([
            'status' => true,
            'message' => 'Terminal is registered.',
            'data' => $terminal->load('branch'),
        ]);
    }

    public function open(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user->branch_id) {
            return response()->json([
                'status' => false,
                'message' => 'Your account must be assigned to a branch to open a POS session.',
            ], 403);
        }
        $validated = $request->validate([
            'terminal_id' => 'required|exists:terminals,id',
            'opening_cash' => 'nullable|numeric|min:0',
        ]);
        $terminal = \App\Models\Terminal::where('id', $validated['terminal_id'])
            ->where('branch_id', $user->branch_id)
            ->first();
        if (! $terminal) {
            return response()->json([
                'status' => false,
                'message' => 'POS is not registered. Please register this terminal in Settings.',
            ], 403);
        }
        if (! $terminal->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'POS is not registered. This terminal is inactive. Please contact your administrator.',
            ], 403);
        }
        $terminalId = $terminal->id;
        $session = PosSession::create([
            'cashier_id' => $user->id,
            'terminal_id' => $terminalId,
            'opened_at' => now(),
            'opening_cash' => $validated['opening_cash'] ?? 0,
        ]);
        $session->load('terminal');
        return response()->json(['status' => true, 'message' => 'Session opened.', 'data' => $session], 201);
    }

    public function close(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_id' => 'required|exists:pos_sessions,id',
            'closing_cash' => 'nullable|numeric|min:0',
        ]);
        $session = PosSession::where('id', $validated['session_id'])
            ->where('cashier_id', $request->user()->id)
            ->whereNull('closed_at')
            ->firstOrFail();
        $session->update([
            'closed_at' => now(),
            'closing_cash' => $validated['closing_cash'] ?? $session->opening_cash,
        ]);
        return response()->json(['status' => true, 'message' => 'Session closed.', 'data' => $session->fresh()]);
    }
}
