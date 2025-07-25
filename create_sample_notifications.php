<?php
require_once __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

// ค้นหา Super Admin user ID
$superAdmin = \App\Models\User::whereHas("roles", function($q) {
    $q->where("name", "Super Admin");
})->first();

if (!$superAdmin) {
    echo "ไม่พบผู้ใช้ที่มีบทบาท Super Admin";
    exit;
}

echo "พบ Super Admin: " . $superAdmin->name . " (ID: " . $superAdmin->id . ")\n";

// สร้างการแจ้งเตือนทดสอบสำหรับ Super Admin
try {
    $notification = new \App\Models\Notification();
    $notification->user_id = $superAdmin->id;
    $notification->message = "การทดสอบการแจ้งเตือน - สร้างเมื่อ " . date("Y-m-d H:i:s");
    $notification->related_type = "test";
    $notification->related_id = 1;
    $notification->status = "unread";
    $notification->action_url = "/";
    $notification->save();
    
    echo "สร้างการแจ้งเตือนสำเร็จ ID: " . $notification->id;
} catch (\Exception $e) {
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}
