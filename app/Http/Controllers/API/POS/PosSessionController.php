<?php

namespace App\Http\Controllers\API\POS;

use App\Http\Controllers\Controller;
use App\Models\PosSession;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PosSessionController extends Controller
{
    /**
     * Check if a terminal is registered and active for the current user's branch.
     * Use this on POS startup when a specific terminal_id is supplied (e.g. external POS device).
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

    /**
     * Resolve the current POS terminal using the TERMINAL_API_KEY in .env.
     * Used by the built-in web POS UI so cashiers don't have to choose a terminal manually.
     */
    public function currentFromEnv(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user || ! $user->branch_id) {
            return response()->json([
                'status' => false,
                'message' => 'Your account must be assigned to a branch to use the POS.',
            ], 403);
        }

        $envKey = trim((string) env('TERMINAL_API_KEY', ''));
        if ($envKey === '') {
            return response()->json([
                'status' => false,
                'message' => 'TERMINAL_API_KEY is not configured for this POS device. Please add it to the .env file.',
            ], 403);
        }

        if (strlen($envKey) < 12 || ! str_starts_with($envKey, \App\Models\Terminal::API_KEY_PREFIX_LABEL)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid TERMINAL_API_KEY configuration. Please regenerate the terminal key and update .env.',
            ], 403);
        }

        $prefix = substr($envKey, 0, 12);

        $terminal = \App\Models\Terminal::where('branch_id', $user->branch_id)
            ->where('api_key_prefix', $prefix)
            ->first();

        if (! $terminal || ! $terminal->checkApiKey($envKey) || ! $terminal->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'POS is not registered or this terminal is inactive. Please check the terminal key in Settings.',
            ], 403);
        }

        $terminal->update(['api_key_last_used_at' => now()]);

        $terminal->load('branch.company');
        $bir = \App\Models\BirSetting::where('branch_id', $terminal->branch_id)->first();
        $birDisplay = $bir ? [
            'tin' => $bir->tin,
            'ptu_number' => $bir->ptu_number,
            'footer_text' => $bir->footer_text ?? 'This document is not valid for claim of input tax.',
        ] : ['tin' => null, 'ptu_number' => null, 'footer_text' => 'This document is not valid for claim of input tax.'];

        $data = $terminal->toArray();
        $data['bir_display'] = $birDisplay;

        return response()->json([
            'status' => true,
            'message' => 'Terminal resolved.',
            'data' => $data,
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

    /**
     * Verify manager PIN or password for the cashier's branch (e.g. before voiding a line).
     * Requires a user with role=manager on the same branch whose PIN or password matches.
     */
    public function verifyManager(Request $request): JsonResponse
    {
        $request->validate([
            'pin_or_password' => 'required|string|min:1',
        ]);

        $user = $request->user();
        $branchId = $user->branch_id;
        if (! $branchId) {
            return response()->json([
                'status' => false,
                'message' => 'Your account must be assigned to a branch.',
            ], 403);
        }

        $managers = User::where('branch_id', $branchId)
            ->where('role', 'manager')
            ->where('is_active', true)
            ->get();

        $value = $request->input('pin_or_password');

        foreach ($managers as $manager) {
            if ($manager->pin_hash && Hash::check($value, $manager->pin_hash)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Manager verified.',
                    'data' => ['verified' => true],
                ]);
            }
            if ($manager->password && Hash::check($value, $manager->getAuthPassword())) {
                return response()->json([
                    'status' => true,
                    'message' => 'Manager verified.',
                    'data' => ['verified' => true],
                ]);
            }
        }

        throw ValidationException::withMessages([
            'pin_or_password' => ['Invalid manager PIN or password for this branch.'],
        ]);
    }

    /**
     * Log a line void (item removed from order before completion) for audit trail.
     */
    public function logLineVoid(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
            'product_name' => 'nullable|string|max:255',
            'reason' => 'required|string|in:wrong_item,customer_changed_mind,damaged,other',
        ]);

        $user = $request->user();
        if (! $user || ! $user->branch_id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        AuditLogService::log(
            'pos_line_void',
            'products',
            (int) $request->product_id,
            null,
            [
                'product_name' => $request->product_name,
                'reason' => $request->reason,
            ],
            $request,
            $user->branch_id,
            $user->id
        );

        return response()->json([
            'status' => true,
            'message' => 'Line void logged.',
            'data' => null,
        ]);
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
