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
        
        // ตรวจสอบการแจ้งเตือนทุกวันตอน 8:00 น.ssss
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

             $schedule->command('notify:appointment-checklist')
            ->timezone('Asia/Bangkok')
            ->dailyAt('07:00');
        $schedule->command('notify:appointment-checklist')
            ->timezone('Asia/Bangkok')
            ->dailyAt('16:00');

    // กระจาย 7 table ไปใน 2 ช่วงเวลา โดยแต่ละ table ห่างกัน 10 นาที วันละ 2 ครั้ง
    $tables = ['tb_tour','tb_booking_form','tb_country','tb_travel_type','tb_wholesale','users','tb_tour_period'];
    $baseTimes = ['07:00', '13:00'];
    $interval = 10; // นาที
    foreach ($baseTimes as $baseTime) {
        foreach ($tables as $i => $table) {
            $time = \Carbon\Carbon::parse($baseTime)->addMinutes($i * $interval)->format('H:i');
            $schedule->command('webtour:sync-table ' . $table)->dailyAt($time);
        }
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
