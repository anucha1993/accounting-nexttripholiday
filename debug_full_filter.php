<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\QuotationFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

echo "=== DEBUG: Full Filter Test for QT25101101 ===\n\n";

// Login as first user (หรือเลือก user ที่ต้องการ)
$user = User::first();
if (!$user) {
    echo "❌ ไม่พบ User ในระบบ\n";
    exit;
}
Auth::login($user);
echo "Login as: {$user->name} (ID: {$user->id})\n\n";

// จำลอง Request ตามที่ส่งมาจาก URL
$request = new Request([
    'keyword' => 'QT25101101',
    'commission_mode' => 'all'
]);

echo "Request Parameters:\n";
echo "  - keyword: " . $request->input('keyword') . "\n";
echo "  - commission_mode: " . $request->input('commission_mode') . "\n\n";

echo "เริ่มต้นการ Filter...\n";

try {
    $quotations = QuotationFilterService::filter($request);
    
    echo "จำนวน Quotations หลังจาก Filter: " . $quotations->count() . "\n\n";
    
    // ค้นหา QT25101101
    $found = $quotations->first(function($q) {
        return $q->quote_number === 'QT25101101';
    });
    
    if ($found) {
        echo "✅ พบ QT25101101 หลังจาก Filter!\n";
        echo "   - Quote ID: {$found->quote_id}\n";
        echo "   - Quote Number: {$found->quote_number}\n";
        echo "   - Quote Status: {$found->quote_status}\n";
        echo "   - Quote Date Start: {$found->quote_date_start}\n";
        echo "   - Net Profit: " . (method_exists($found, 'getNetProfit') ? $found->getNetProfit() : 'N/A') . "\n";
    } else {
        echo "❌ ไม่พบ QT25101101 หลังจาก Filter!\n\n";
        
        // แสดงรายการ 10 อันแรกเพื่อดู
        echo "รายการ 10 อันแรกที่ผ่าน Filter:\n";
        foreach ($quotations->take(10) as $q) {
            echo "  - {$q->quote_number} (ID: {$q->quote_id}, Status: {$q->quote_status})\n";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ เกิดข้อผิดพลาด: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== เสร็จสิ้น ===\n";
