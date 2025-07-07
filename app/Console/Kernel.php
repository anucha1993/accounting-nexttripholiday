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
        // $schedule->command('inspire')->hourly();
        
        // ตรวจสอบการแจ้งเตือนทุกวันตอน 8:00 น.
        $schedule->command('notifications:check')
                ->dailyAt('08:00')
                ->appendOutputTo(storage_path('logs/notifications.log'));

        // รันแจ้งเตือน passport checklist ทุกวัน 07:00 และ 16:00 (เวลาไทย)
        $schedule->command('notify:passport-checklist')
            ->timezone('Asia/Bangkok')
            ->dailyAt('07:00');
        $schedule->command('notify:passport-checklist')
            ->timezone('Asia/Bangkok')
            ->dailyAt('16:00');
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
