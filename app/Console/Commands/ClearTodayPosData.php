<?php

namespace App\Console\Commands;

use App\Models\DaySession;
use App\Models\PosSession;
use App\Models\Transaction;
use App\Models\ZReading;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClearTodayPosData extends Command
{
    protected $signature = 'pos:clear-today
                            {--force : Skip confirmation}';

    protected $description = 'Remove all POS sessions, day sessions, Z-readings, and transactions dated today (app timezone).';

    public function handle(): int
    {
        $today = Carbon::today();
        $todayStr = $today->toDateString();

        if (! $this->option('force')) {
            if (! $this->confirm("This will permanently delete all pos_sessions, day_sessions, z_readings, and transactions for {$todayStr}. Continue?")) {
                return 0;
            }
        }

        $transactionCount = Transaction::whereDate('created_at', $today)->count();
        $posSessionCount = PosSession::whereDate('opened_at', $today)->count();
        $daySessionCount = DaySession::where('session_date', $today)->count();
        $zReadingCount = ZReading::whereDate('reporting_date', $today)->count();

        $this->info("Today ({$todayStr}): {$transactionCount} transactions, {$posSessionCount} pos_sessions, {$daySessionCount} day_sessions, {$zReadingCount} z_readings.");

        // 1. Delete transactions (today) – cascade will remove items, official_receipts, discounts, transaction_payments
        $deletedTransactions = Transaction::whereDate('created_at', $today)->delete();
        $this->line("Deleted {$deletedTransactions} transactions.");

        // 2. Unlink Z-Reading from day_sessions so we can delete Z-readings and day_sessions
        DaySession::where('session_date', $today)->update(['z_reading_id' => null]);

        // 3. Delete Z-readings for today
        $deletedZReadings = ZReading::whereDate('reporting_date', $today)->delete();
        $this->line("Deleted {$deletedZReadings} z_readings.");

        // 4. Delete day_sessions for today
        $deletedDaySessions = DaySession::where('session_date', $today)->delete();
        $this->line("Deleted {$deletedDaySessions} day_sessions.");

        // 5. Delete pos_sessions opened today
        $deletedPosSessions = PosSession::whereDate('opened_at', $today)->delete();
        $this->line("Deleted {$deletedPosSessions} pos_sessions.");

        $this->info("Done. All session and transaction data for {$todayStr} has been removed.");

        return 0;
    }
}
