<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        if (env('NODE_TYPE') === 'local') {
            $schedule->call(fn () => app(\App\Services\SyncService::class)->pushToCloud())
                ->everyFiveMinutes()
                ->name('sync-push')
                ->withoutOverlapping();

            $schedule->call(fn () => app(\App\Services\SyncService::class)->pullFromCloud())
                ->everyFifteenMinutes()
                ->name('sync-pull')
                ->withoutOverlapping();

            $schedule->call(fn () => app(\App\Services\SyncService::class)->heartbeat())
                ->everyMinute()
                ->name('sync-heartbeat');
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
