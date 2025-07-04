<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Notification;
use App\Models\User;

// บูตแอพ Laravel
$app = $app->make(Illuminate\Contracts\Console\Kernel::class);
$app->bootstrap();

$users = User::all();
$total = 0;

echo "สรุปข้อมูลการแจ้งเตือนในระบบ:\n";
echo "===========================\n";

foreach ($users as $user) {
    $unreadCount = Notification::where('user_id', $user->id)
        ->where('status', 'unread')
        ->count();
    
    $readCount = Notification::where('user_id', $user->id)
        ->where('status', 'read')
        ->count();
    
    $total += ($unreadCount + $readCount);
    
    echo "ผู้ใช้: {$user->name}\n";
    echo "- การแจ้งเตือนที่ยังไม่ได้อ่าน: {$unreadCount}\n";
    echo "- การแจ้งเตือนที่อ่านแล้ว: {$readCount}\n";
    echo "- รวม: " . ($unreadCount + $readCount) . "\n\n";
}

echo "รวมการแจ้งเตือนทั้งหมดในระบบ: {$total}\n";

// แสดงตัวอย่างการแจ้งเตือน 5 รายการล่าสุด
$latestNotifications = Notification::orderBy('created_at', 'desc')->take(5)->get();

echo "\nตัวอย่างการแจ้งเตือนล่าสุด 5 รายการ:\n";
echo "===========================\n";

foreach ($latestNotifications as $notification) {
    $user = User::find($notification->user_id);
    $status = $notification->status === 'read' ? 'อ่านแล้ว' : 'ยังไม่อ่าน';
    $createdAt = date('d/m/Y H:i', strtotime($notification->created_at));
    
    echo "ID: {$notification->id}\n";
    echo "ผู้ใช้: {$user->name}\n";
    echo "ข้อความ: {$notification->message}\n";
    echo "สถานะ: {$status}\n";
    echo "วันที่สร้าง: {$createdAt}\n";
    echo "URL: {$notification->action_url}\n";
    echo "---------------------------\n";
}
