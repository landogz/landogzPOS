<?php

namespace App\Http\Controllers\API\POS;

use App\Http\Controllers\Controller;
use App\Models\DaySession;
use App\Models\ZReading;
use App\Services\DaySessionService;
use App\Services\ManagerVerificationService;
use App\Services\ZReadingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ZReadingController extends Controller
{
    public function __construct(
        protected ZReadingService $zReadingService,
        protected DaySessionService $daySessionService,
        protected ManagerVerificationService $managerVerification
    ) {}

    /**
     * Get current day session status for the terminal (open/closed, or block reason).
     */
    public function dayStatus(Request $request): JsonResponse
    {
        $user = $request->user();
        $branchId = $user->branch_id;
        if (! $branchId) {
            return response()->json(['status' => false, 'message' => 'Branch required.'], 403);
        }
        $terminalId = $request->input('terminal_id');
        if (! $terminalId) {
            return response()->json(['status' => false, 'message' => 'Terminal is required.'], 422);
        }
        $terminalId = (int) $terminalId;
        $terminal = \App\Models\Terminal::where('id', $terminalId)->where('branch_id', $branchId)->first();
        if (! $terminal) {
            return response()->json(['status' => false, 'message' => 'Invalid terminal.'], 422);
        }

        $check = $this->daySessionService->canOpenToday($branchId, $terminalId);
        $openSession = $this->daySessionService->getOpenForTerminalDate($branchId, $terminalId, now());

        return response()->json([
            'status' => true,
            'data' => [
                'can_open' => $check['ok'],
                'message' => $check['message'],
                'day_session' => $openSession ? $openSession->load('openedByUser:id,name') : null,
                'day_closed' => (bool) $check['session'] === false && $check['message'] && str_contains($check['message'], 'already closed'),
            ],
        ]);
    }

    /**
     * Generate Z-Reading (end-of-day). Manager only; requires PIN and cash count.
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

        $terminalId = (int) $request->input('terminal_id');
        $terminal = \App\Models\Terminal::where('id', $terminalId)->where('branch_id', $branchId)->first();
        if (! $terminal) {
            return response()->json(['status' => false, 'message' => 'Invalid terminal.'], 422);
        }

        $daySession = $this->daySessionService->getOpenForTerminalDate($branchId, $terminalId, now());
        if (! $daySession) {
            $closedToday = DaySession::where('branch_id', $branchId)
                ->where('terminal_id', $terminalId)
                ->where('session_date', now()->toDateString())
                ->where('status', 'closed')
                ->exists();
            if ($closedToday) {
                return response()->json([
                    'status' => false,
                    'message' => 'Day already closed. Z-Reading was performed for today. You can run Z-Reading again after opening the next day.',
                ], 422);
            }
            // No open session: try to open the day now (e.g. POS was used without going through Open shift)
            $canOpen = $this->daySessionService->canOpenToday($branchId, $terminalId);
            if ($canOpen['ok']) {
                $daySession = $this->daySessionService->getOrCreateForToday($branchId, $terminalId, 0);
            }
            if (! $daySession) {
                return response()->json([
                    'status' => false,
                    'message' => $canOpen['message'] ?? 'No day session open. Open the day from POS first (start your shift with opening cash), then run Z-Reading at end of day.',
                ], 422);
            }
        }

        $denomKeys = ['1000', '500', '200', '100', '50', '20', '10', '5', '1', '0.25', '0.10', '0.05', '0.01'];
        $cashCount = [];
        $raw = $request->input('cash_count', []);
        foreach ($denomKeys as $key) {
            $cashCount[$key] = isset($raw[$key]) ? max(0, (int) $raw[$key]) : 0;
        }

        $options = [
            'cash_count' => $cashCount,
            'amount_submitted' => $request->filled('amount_submitted') ? (float) $request->input('amount_submitted') : null,
            'pull_outs' => (float) ($request->input('pull_outs') ?? 0),
            'store_manager_name' => $manager->name ?? $manager->email,
        ];

        $zReading = $this->zReadingService->generate($daySession, $options);
        $zReading->load(['branch:id,name,address,tin,company_id', 'branch.company:id,name,logo', 'terminal:id,code,name,min,sn']);

        return response()->json([
            'status' => true,
            'message' => 'Z-Reading generated. Day session closed.',
            'data' => $zReading,
        ]);
    }

    /**
     * Get a single Z-Reading by ID (for print).
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $zReading = ZReading::where('id', $id)
            ->where('branch_id', $request->user()->branch_id)
            ->with(['branch:id,name,address,tin,company_id', 'branch.company:id,name,logo', 'terminal:id,code,name,min,sn'])
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'data' => $zReading,
        ]);
    }

    /**
     * Mark Z-Reading as printed.
     */
    public function markPrinted(Request $request, int $id): JsonResponse
    {
        $zReading = ZReading::where('id', $id)
            ->where('branch_id', $request->user()->branch_id)
            ->firstOrFail();
        $zReading->update(['printed_at' => now()]);

        return response()->json(['status' => true, 'message' => 'Marked as printed.']);
    }
}
