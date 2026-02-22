<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Terminal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TerminalController extends Controller
{
    /**
     * List terminals for a branch. One branch can have multiple terminals (POS counters).
     */
    public function index(Request $request, Branch $branch): JsonResponse
    {
        $this->authorizeBranch($request, $branch);
        $terminals = Terminal::where('branch_id', $branch->id)
            ->orderBy('code')
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $terminals,
        ]);
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
            'is_active' => 'nullable|boolean',
        ]);
        $validated['branch_id'] = $branch->id;
        $validated['is_active'] = $validated['is_active'] ?? true;
        $terminal = Terminal::create($validated);
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
        return response()->json(['status' => 'success', 'data' => $terminal]);
    }

    public function update(Request $request, Branch $branch, Terminal $terminal): JsonResponse
    {
        if ($request->user()->role !== 'super_admin') {
            return response()->json([
                'status' => false,
                'message' => 'Only Super Admin can modify POS terminals. Please contact your administrator.',
            ], 403);
        }
        $this->authorizeBranch($request, $branch);
        if ($terminal->branch_id !== $branch->id) {
            abort(404);
        }
        $validated = $request->validate([
            'code' => 'sometimes|string|max:50',
            'name' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        $terminal->update($validated);
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
        $terminal->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Terminal deleted.',
            'data' => null,
        ]);
    }

    private function authorizeBranch(Request $request, Branch $branch): void
    {
        $user = $request->user();
        if ($user->branch_id && $user->branch_id !== $branch->id && ! in_array($user->role, ['super_admin', 'admin'], true)) {
            abort(403, 'You can only manage terminals for your branch.');
        }
    }
}
