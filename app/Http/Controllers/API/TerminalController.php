<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Terminal;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TerminalController extends Controller
{
    /**
     * List all terminals. super_admin: all; admin: only terminals of their company's branches.
     */
    public function indexAll(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user || ! in_array($user->role, ['super_admin', 'admin'], true)) {
            return response()->json(['status' => false, 'message' => 'Only Super Admin or Admin can list terminals.'], 403);
        }
        $query = Terminal::with('branch:id,name,company_id', 'branch.company:id,name')
            ->orderBy('branch_id')
            ->orderBy('code');
        if ($user->role === 'admin' && $user->company_id) {
            $query->whereHas('branch', fn ($q) => $q->where('company_id', $user->company_id));
        }
        $terminals = $query->get()->map(fn (Terminal $t) => [
                'id' => $t->id,
                'branch_id' => $t->branch_id,
                'branch_name' => $t->branch?->name,
                'company_id' => $t->branch?->company_id,
                'company_name' => $t->branch?->company?->name,
                'code' => $t->code,
                'name' => $t->name,
                'min' => $t->min,
                'tin' => $t->tin,
                'is_active' => $t->is_active,
                'is_registered' => $t->hasApiKey(),
                'api_key_last_used_at' => $t->api_key_last_used_at?->toIso8601String(),
            ]);
        return response()->json(['status' => true, 'data' => $terminals]);
    }

    /**
     * List terminals for a branch. One branch can have multiple terminals (POS counters).
     */
    public function index(Request $request, Branch $branch): JsonResponse
    {
        $this->authorizeBranch($request, $branch);
        $terminals = Terminal::where('branch_id', $branch->id)
            ->orderBy('code')
            ->get()
            ->map(fn (Terminal $t) => $this->terminalToArray($t));
        return response()->json([
            'status' => 'success',
            'data' => $terminals,
        ]);
    }

    private function terminalToArray(Terminal $t): array
    {
        return [
            'id' => $t->id,
            'branch_id' => $t->branch_id,
            'code' => $t->code,
            'name' => $t->name,
            'min' => $t->min,
            'tin' => $t->tin,
            'is_active' => $t->is_active,
            'is_registered' => $t->hasApiKey(),
            'api_key_last_used_at' => $t->api_key_last_used_at?->toIso8601String(),
        ];
    }

    public function store(Request $request, Branch $branch): JsonResponse
    {
        if ($request->user()->role !== 'super_admin') {
            return response()->json([
                'status' => false,
                'message' => 'Only Super Admin can add POS terminals. Please contact your administrator.',
            ], 403);
        }
        $this->authorizeBranch($request, $branch);
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'nullable|string|max:255',
            'min' => 'nullable|string|max:50',
            'tin' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['branch_id'] = $branch->id;
        $validated['is_active'] = $validated['is_active'] ?? true;
        $terminal = Terminal::create($validated);
        AuditLogService::log('created', 'terminals', (int) $terminal->id, null, $terminal->only(['branch_id', 'code', 'name', 'is_active']), $request, $branch->id);
        return response()->json([
            'status' => 'success',
            'message' => 'Terminal created.',
            'data' => $terminal,
        ], 201);
    }

    public function show(Request $request, Branch $branch, Terminal $terminal): JsonResponse
    {
        $this->authorizeBranch($request, $branch);
        if ($terminal->branch_id !== $branch->id) {
            abort(404);
        }
        return response()->json(['status' => 'success', 'data' => $this->terminalToArray($terminal)]);
    }

    /**
     * Generate a new API key for this terminal. Super Admin only.
     * Returns the plain key ONCE — put it in the POS device .env as TERMINAL_API_KEY=...
     */
    public function generateKey(Request $request, Branch $branch, Terminal $terminal): JsonResponse
    {
        if ($request->user()->role !== 'super_admin') {
            return response()->json(['status' => false, 'message' => 'Only Super Admin can generate terminal keys.'], 403);
        }
        $this->authorizeBranch($request, $branch);
        if ($terminal->branch_id !== $branch->id) {
            abort(404);
        }
        $key = $terminal->generateApiKey();
        AuditLogService::log('terminal_key_generated', 'terminals', (int) $terminal->id, null, ['generated_at' => now()->toIso8601String()], $request, $branch->id);
        return response()->json([
            'status' => true,
            'message' => 'Key generated. Copy it now — it will not be shown again. Add to your POS .env: TERMINAL_API_KEY=' . $key,
            'data' => [
                'terminal_id' => $terminal->id,
                'key' => $key,
                'env_line' => 'TERMINAL_API_KEY=' . $key,
            ],
        ]);
    }

    /**
     * Revoke the terminal's API key. Super Admin only. Key will no longer authenticate.
     */
    public function revokeKey(Request $request, Branch $branch, Terminal $terminal): JsonResponse
    {
        if ($request->user()->role !== 'super_admin') {
            return response()->json(['status' => false, 'message' => 'Only Super Admin can revoke terminal keys.'], 403);
        }
        $this->authorizeBranch($request, $branch);
        if ($terminal->branch_id !== $branch->id) {
            abort(404);
        }
        $terminal->revokeApiKey();
        AuditLogService::log('terminal_key_revoked', 'terminals', (int) $terminal->id, null, ['revoked_at' => now()->toIso8601String()], $request, $branch->id);
        return response()->json([
            'status' => true,
            'message' => 'Terminal key revoked.',
            'data' => $this->terminalToArray($terminal->fresh()),
        ]);
    }

    public function update(Request $request, Branch $branch, Terminal $terminal): JsonResponse
    {
        if (! in_array($request->user()->role, ['super_admin', 'admin'], true)) {
            return response()->json([
                'status' => false,
                'message' => 'Only Super Admin or Admin can modify POS terminals. Please contact your administrator.',
            ], 403);
        }
        $this->authorizeBranch($request, $branch);
        if ($terminal->branch_id !== $branch->id) {
            abort(404);
        }
        $validated = $request->validate([
            'code' => 'sometimes|string|max:50',
            'name' => 'nullable|string|max:255',
            'min' => 'nullable|string|max:50',
            'tin' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);
        $old = $terminal->only(['code', 'name', 'is_active']);
        $terminal->update($validated);
        AuditLogService::log('updated', 'terminals', (int) $terminal->id, $old, $terminal->fresh()->only(['code', 'name', 'is_active']), $request, $branch->id);
        return response()->json([
            'status' => 'success',
            'message' => 'Terminal updated.',
            'data' => $terminal->fresh(),
        ]);
    }

    public function destroy(Request $request, Branch $branch, Terminal $terminal): JsonResponse
    {
        if ($request->user()->role !== 'super_admin') {
            return response()->json([
                'status' => false,
                'message' => 'Only Super Admin can remove POS terminals. Please contact your administrator.',
            ], 403);
        }
        $this->authorizeBranch($request, $branch);
        if ($terminal->branch_id !== $branch->id) {
            abort(404);
        }
        $old = $terminal->only(['id', 'branch_id', 'code', 'name', 'is_active']);
        $terminal->delete();
        AuditLogService::log('deleted', 'terminals', (int) $old['id'], $old, null, $request, $branch->id);
        return response()->json([
            'status' => 'success',
            'message' => 'Terminal deleted.',
            'data' => null,
        ]);
    }

    private function authorizeBranch(Request $request, Branch $branch): void
    {
        $user = $request->user();
        if ($user->role === 'admin' && $user->company_id && (int) $branch->company_id !== (int) $user->company_id) {
            abort(403, 'You can only manage terminals for branches of your company.');
        }
        if ($user->branch_id && $user->branch_id !== $branch->id && ! in_array($user->role, ['super_admin', 'admin'], true)) {
            abort(403, 'You can only manage terminals for your branch.');
        }
    }
}
