<?php

use App\Services\NotificationService;

if (!function_exists('notification')) {
    /**
     * ฟังก์ชัน helper สำหรับเข้าถึง NotificationService
     *
     * @return NotificationService
     */
    function notification(): NotificationService
    {
        return app(NotificationService::class);
    }
}
