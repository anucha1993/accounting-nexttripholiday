<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class CheckNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ตรวจสอบเงื่อนไขต่างๆ เพื่อสร้างการแจ้งเตือนอัตโนมัติ';

    /**
     * @var NotificationService
     */
    protected $notificationService;

    /**
     * Create a new command instance.
     *
     * @param NotificationService $notificationService
     */
    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('เริ่มต้นตรวจสอบการแจ้งเตือนอัตโนมัติ...');

        try {
            // ตรวจสอบการแจ้งเตือนที่เกี่ยวกับการเดินทางที่ใกล้จะถึง
            $this->notificationService->checkUpcomingTravelNotifications();
            
            $this->info('ตรวจสอบการแจ้งเตือนเสร็จสิ้น');
        } catch (\Exception $e) {
            $this->error('เกิดข้อผิดพลาด: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
