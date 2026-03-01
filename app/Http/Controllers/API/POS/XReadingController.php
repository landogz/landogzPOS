<?php

namespace App\Http\Controllers\API\POS;

use App\Http\Controllers\Controller;
use App\Models\XReading;
use App\Services\ManagerVerificationService;
use App\Services\XReadingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class XReadingController extends Controller
{
    public function __construct(
        protected XReadingService $service,
        protected ManagerVerificationService $managerVerification
    ) {}

    /**
     * Generate a new X-Reading (mid-day snapshot). Requires manager PIN or password.
     * Can be called multiple times per day.
     */
    public function generate(Request $request): JsonResponse
    {
        $user = $request->user();
        $branchId = $user->branch_id;
        if (! $branchId) {
            return response()->json([
                'status' => false,
                'message' => 'Your account must be assigned to a branch.',
            ], 403);
        }

        $request->validate([
            'terminal_id' => 'required',
            'pin_or_password' => 'required|string|min:1',
            'cash_count' => 'required|array',
        ]);

        $manager = $this->managerVerification->verifyForBranch($branchId, $request->input('pin_or_password'));

        $terminalId = $request->input('terminal_id');
        if (! $terminalId) {
            return response()->json([
                'status' => false,
                'message' => 'Terminal is required.',
            ], 422);
        }

        $terminalId = is_numeric($terminalId) ? (int) $terminalId : $terminalId;
        if (is_int($terminalId)) {
            $terminal = \App\Models\Terminal::where('id', $terminalId)->where('branch_id', $branchId)->first();
            if (! $terminal) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid terminal for this branch.',
                ], 422);
            }
        }

        $cashCountRaw = $request->input('cash_count', []);
        $denomKeys = ['1000', '500', '200', '100', '50', '20', '10', '5', '1', '0.25', '0.10', '0.05', '0.01'];
        $cashCount = [];
        foreach ($denomKeys as $key) {
            $cashCount[$key] = isset($cashCountRaw[$key]) ? max(0, (int) $cashCountRaw[$key]) : 0;
        }

        $options = [
            'administrator_name' => $manager->name ?? $manager->email,
            'cash_count' => $cashCount,
        ];
        if ($request->filled('change_fund')) {
            $options['change_fund'] = (float) $request->input('change_fund');
        }
        if ($request->filled('pull_outs')) {
            $options['pull_outs'] = (float) $request->input('pull_outs');
        }
        if ($request->filled('amount_submitted')) {
            $options['amount_submitted'] = (float) $request->input('amount_submitted');
        }
        $xReading = $this->service->generate($branchId, is_int($terminalId) ? $terminalId : null, $options);

        $xReading->load(['branch:id,name,address,tin', 'cashier:id,name,email', 'terminal:id,code,name']);

        return response()->json([
            'status' => true,
            'message' => 'X-Reading generated successfully. Counters have NOT been reset.',
            'data' => $xReading,
        ]);
    }

    /**
     * Get a single X-Reading by ID (for print).
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $xReading = XReading::where('id', $id)
            ->where('branch_id', $request->user()->branch_id)
            ->with(['branch:id,name,address,tin,company_id', 'branch.company:id,name,logo', 'cashier:id,name,email', 'terminal:id,code,name'])
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'data' => $xReading,
        ]);
    }

    /**
     * Get latest X-Reading for branch/terminal.
     */
    public function latest(Request $request): JsonResponse
    {
        $branchId = $request->user()->branch_id;
        if (! $branchId) {
            return response()->json(['status' => false, 'message' => 'Branch required.'], 403);
        }

        $terminalId = $request->input('terminal_id');
        $query = XReading::where('branch_id', $branchId)
            ->with(['branch:id,name,address,tin', 'cashier:id,name,email', 'terminal:id,code,name']);
        if ($terminalId !== null && $terminalId !== '') {
            $query->where('terminal_id', $terminalId);
        }
        $xReading = $query->latest()->first();

        if (! $xReading) {
            return response()->json([
                'status' => false,
                'message' => 'No X-Reading found. Generate one first.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $xReading,
        ]);
    }

    /**
     * Get X-Reading history for the day.
     */
    public function history(Request $request): JsonResponse
    {
        $branchId = $request->user()->branch_id;
        if (! $branchId) {
            return response()->json(['status' => false, 'message' => 'Branch required.'], 403);
        }

        $date = $request->input('date', now()->toDateString());
        $terminalId = $request->input('terminal_id');

        $query = XReading::where('branch_id', $branchId)
            ->whereDate('created_at', $date)
            ->with(['cashier:id,name,email', 'terminal:id,code,name'])
            ->orderByDesc('created_at');
        if ($terminalId !== null && $terminalId !== '') {
            $query->where('terminal_id', $terminalId);
        }
        $items = $query->paginate(20);

        return response()->json([
            'status' => true,
            'data' => $items->items(),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    /**
     * Mark X-Reading as printed.
     */
    public function markPrinted(Request $request, int $id): JsonResponse
    {
        $xReading = XReading::where('id', $id)
            ->where('branch_id', $request->user()->branch_id)
            ->firstOrFail();
        $xReading->update(['printed_at' => now()]);

        return response()->json(['status' => true, 'message' => 'Marked as printed.']);
    }
}
