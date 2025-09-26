<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "===== Clearing Application Cache =====\n\n";

// ล้าง Cache ทั้งหมด
\Illuminate\Support\Facades\Artisan::call('cache:clear');
echo "Cache cleared: " . \Illuminate\Support\Facades\Artisan::output() . "\n";

\Illuminate\Support\Facades\Artisan::call('config:clear');
echo "Config cache cleared: " . \Illuminate\Support\Facades\Artisan::output() . "\n";

\Illuminate\Support\Facades\Artisan::call('route:clear');
echo "Route cache cleared: " . \Illuminate\Support\Facades\Artisan::output() . "\n";

\Illuminate\Support\Facades\Artisan::call('view:clear');
echo "View cache cleared: " . \Illuminate\Support\Facades\Artisan::output() . "\n";

// สำหรับ Laravel 5.5 ขึ้นไป
if (method_exists(\Illuminate\Support\Facades\Artisan::class, 'call') && 
    \Illuminate\Support\Facades\Artisan::hasCommand('optimize:clear')) {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    echo "Optimization cache cleared: " . \Illuminate\Support\Facades\Artisan::output() . "\n";
}