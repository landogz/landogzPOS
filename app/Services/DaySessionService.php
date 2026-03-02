<?php

namespace App\Services;

use App\Models\DaySession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DaySessionService
{
    /**
     * Get or create the open day session for the given branch + terminal for today.
     * If yesterday's session is still open, returns null and caller should block (require Z-Reading first).
     */
    public function getOrCreateForToday(int $branchId, int $terminalId, float $openingCash = 0): ?DaySession
    {
        $today = Carbon::today();

        $openToday = DaySession::where('branch_id', $branchId)
            ->where('terminal_id', $terminalId)
            ->where('session_date', $today)
            ->where('status', 'open')
            ->first();

        if ($openToday) {
            return $openToday;
        }

        $closedToday = DaySession::where('branch_id', $branchId)
            ->where('terminal_id', $terminalId)
            ->where('session_date', $today)
            ->where('status', 'closed')
            ->first();

        if ($closedToday) {
            return null;
        }

        $yesterdayOpen = DaySession::where('branch_id', $branchId)
            ->where('terminal_id', $terminalId)
            ->where('session_date', $today->copy()->subDay())
            ->where('status', 'open')
            ->first();

        if ($yesterdayOpen) {
            return null;
        }

        return DaySession::create([
            'branch_id' => $branchId,
            'terminal_id' => $terminalId,
            'session_date' => $today,
            'opened_by' => Auth::id(),
            'opening_cash' => $openingCash,
            'status' => 'open',
            'opened_at' => now(),
        ]);
    }

    /**
     * Get the open day session for the given branch + terminal for the given date (usually today).
     */
    public function getOpenForTerminalDate(int $branchId, int $terminalId, $date): ?DaySession
    {
        $d = $date instanceof Carbon ? $date : Carbon::parse($date);

        return DaySession::where('branch_id', $branchId)
            ->where('terminal_id', $terminalId)
            ->where('session_date', $d)
            ->where('status', 'open')
            ->first();
    }

    /**
     * Check if yesterday's session was closed (so we can open today).
     */
    public function canOpenToday(int $branchId, int $terminalId): array
    {
        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();

        $openToday = DaySession::where('branch_id', $branchId)
            ->where('terminal_id', $terminalId)
            ->where('session_date', $today)
            ->where('status', 'open')
            ->first();

        if ($openToday) {
            return ['ok' => true, 'session' => $openToday, 'message' => null];
        }

        $closedToday = DaySession::where('branch_id', $branchId)
            ->where('terminal_id', $terminalId)
            ->where('session_date', $today)
            ->where('status', 'closed')
            ->first();

        if ($closedToday) {
            return ['ok' => false, 'session' => null, 'message' => 'Day already closed. Z-Reading done for today.'];
        }

        $yesterdayOpen = DaySession::where('branch_id', $branchId)
            ->where('terminal_id', $terminalId)
            ->where('session_date', $yesterday)
            ->where('status', 'open')
            ->first();

        if ($yesterdayOpen) {
            return ['ok' => false, 'session' => null, 'message' => "Yesterday's session was not closed. Please perform Z-Reading for " . $yesterday->format('Y-m-d') . ' before opening today.'];
        }

        return ['ok' => true, 'session' => null, 'message' => null];
    }

    /**
     * Close the day session and link the Z-Reading.
     */
    public function closeDaySession(DaySession $session, int $zReadingId, int $closedByUserId): void
    {
        $session->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closed_by' => $closedByUserId,
            'z_reading_id' => $zReadingId,
        ]);
    }
}
