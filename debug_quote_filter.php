<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\quotations\quotationModel;
use Illuminate\Support\Facades\Log;

echo "=== DEBUG: QT25101101 ===\n\n";

// 1. ตรวจสอบว่า quote มีอยู่ในฐานข้อมูลหรือไม่
echo "1. ค้นหา Quote ในฐานข้อมูล:\n";
$quote = quotationModel::where('quote_number', 'QT25101101')->first();

if (!$quote) {
    echo "❌ ไม่พบ QT25101101 ในฐานข้อมูล!\n";
    exit;
}

echo "✅ พบ QT25101101\n";
echo "   - Quote ID: {$quote->quote_id}\n";
echo "   - Quote Status: {$quote->quote_status}\n";
echo "   - Quote Date Start: {$quote->quote_date_start}\n";
echo "   - Quote Sale: {$quote->quote_sale}\n";
echo "   - Quote Country: {$quote->quote_country}\n";
echo "   - Quote Wholesale: {$quote->quote_wholesale}\n\n";

// 2. ตรวจสอบว่า quote_status ตรงเงื่อนไขหรือไม่
echo "2. ตรวจสอบ quote_status:\n";
$validStatuses = ['success', 'invoice'];
if (in_array($quote->quote_status, $validStatuses)) {
    echo "✅ Status ถูกต้อง: {$quote->quote_status} (ต้องเป็น success หรือ invoice)\n\n";
} else {
    echo "❌ Status ไม่ถูกต้อง: {$quote->quote_status} (ต้องเป็น success หรือ invoice)\n";
    echo "   ** นี่คือสาเหตุที่ไม่เจอ! **\n\n";
}

// 3. โหลด relationships
echo "3. โหลด Relationships:\n";
$quote->load([
    'customer',
    'quotePayments',
    'paymentWholesale',
    'InputTaxVat',
    'quoteInvoice',
    'quoteCheckStatus',
    'quoteLogStatus',
    'checkfileInputtax'
]);

// 4. ตรวจสอบ wholesale_skip_status
echo "4. ตรวจสอบ wholesale_skip_status:\n";
if (isset($quote->quoteCheckStatus)) {
    echo "   - wholesale_skip_status: " . ($quote->quoteCheckStatus->wholesale_skip_status ?? 'NULL') . "\n";
    if ($quote->quoteCheckStatus->wholesale_skip_status === 'ไม่ต้องการออก') {
        echo "   ✅ มี wholesale_skip_status = 'ไม่ต้องการออก' (จะผ่าน filter)\n\n";
    } else {
        echo "   ⚠️  ไม่ใช่ 'ไม่ต้องการออก' (จะต้องผ่านเงื่อนไขอื่นๆ)\n\n";
    }
} else {
    echo "   ❌ ไม่มี quoteCheckStatus\n\n";
}

// 5. ตรวจสอบ getStatusBadgeCount
echo "5. ตรวจสอบ Status Badge Count:\n";
if (function_exists('getStatusBadgeCount') && isset($quote->quoteCheckStatus)) {
    $statusCount = getStatusBadgeCount($quote->quoteCheckStatus, $quote);
    echo "   - Badge Count: {$statusCount}\n";
    if ($statusCount > 0) {
        echo "   ❌ มีงานที่ยังไม่เสร็จ (count > 0) -> จะถูกกรองออก\n";
        echo "   ** นี่อาจเป็นสาเหตุที่ไม่เจอ! **\n\n";
    } else {
        echo "   ✅ ไม่มีงานค้าง (count = 0)\n\n";
    }
} else {
    echo "   ⚠️  ไม่สามารถเช็ค Badge Count ได้\n\n";
}

// 6. ตรวจสอบสถานะใบหัก ณ ที่จ่าย จากลูกค้า
echo "6. ตรวจสอบสถานะใบหัก ณ ที่จ่าย (จากลูกค้า):\n";
if ($quote->quoteInvoice && function_exists('getStatusWithholdingTax')) {
    $withholdingStatus = getStatusWithholdingTax($quote->quoteInvoice);
    echo "   - Withholding Status: {$withholdingStatus}\n";
    if (strpos($withholdingStatus, 'รอใบหัก จากลูกค้า') !== false) {
        echo "   ❌ พบสถานะ 'รอใบหัก จากลูกค้า' -> จะถูกกรองออก\n";
        echo "   ** นี่อาจเป็นสาเหตุที่ไม่เจอ! **\n\n";
    } else {
        echo "   ✅ ไม่ใช่สถานะ 'รอใบหัก จากลูกค้า'\n\n";
    }
} else {
    echo "   ⚠️  ไม่มี quoteInvoice หรือไม่มีฟังก์ชัน getStatusWithholdingTax\n\n";
}

// 7. ตรวจสอบสถานะใบหัก ณ ที่จ่าย
echo "7. ตรวจสอบ withholding_tax_status:\n";
if (isset($quote->quoteCheckStatus)) {
    $withholdingTaxStatus = $quote->quoteCheckStatus->withholding_tax_status;
    echo "   - withholding_tax_status: " . ($withholdingTaxStatus ?? 'NULL') . "\n";
    if (is_null($withholdingTaxStatus) || trim($withholdingTaxStatus) === '') {
        echo "   ❌ สถานะเป็น NULL หรือว่าง -> จะถูกกรองออก\n";
        echo "   ** นี่อาจเป็นสาเหตุที่ไม่เจอ! **\n\n";
    } else {
        echo "   ✅ มีสถานะใบหัก ณ ที่จ่าย\n\n";
    }
} else {
    echo "   ❌ ไม่มี quoteCheckStatus\n\n";
}

// 8. ตรวจสอบสถานะใบกำกับภาษีโฮลเซลล์
echo "8. ตรวจสอบสถานะใบกำกับภาษีโฮลเซลล์:\n";
if (function_exists('getStatusWhosaleInputTax')) {
    $wholesaleStatus = getStatusWhosaleInputTax($quote->checkfileInputtax);
    echo "   - Wholesale Tax Status: {$wholesaleStatus}\n";
    if (strpos($wholesaleStatus, 'รอใบกำกับภาษีโฮลเซลล์') !== false) {
        echo "   ❌ พบสถานะ 'รอใบกำกับภาษีโฮลเซลล์' -> จะถูกกรองออก\n";
        echo "   ** นี่อาจเป็นสาเหตุที่ไม่เจอ! **\n\n";
    } else {
        echo "   ✅ ไม่ใช่สถานะ 'รอใบกำกับภาษีโฮลเซลล์'\n\n";
    }
} else {
    echo "   ⚠️  ไม่มีฟังก์ชัน getStatusWhosaleInputTax\n\n";
}

// 9. ตรวจสอบ InputTaxVat
echo "9. ตรวจสอบ InputTaxVat:\n";
if ($quote->InputTaxVat && $quote->InputTaxVat->count() > 0) {
    echo "   - จำนวน InputTaxVat: {$quote->InputTaxVat->count()}\n";
    $hasValidFile = false;
    foreach ($quote->InputTaxVat as $record) {
        echo "   - Record: input_tax_status={$record->input_tax_status}, ";
        echo "input_tax_type={$record->input_tax_type}, ";
        echo "input_tax_file=" . ($record->input_tax_file ?? 'NULL') . "\n";
        
        if ($record->input_tax_status === 'success' && 
            $record->input_tax_type == 4 && 
            !empty($record->input_tax_file)) {
            echo "     ✅ มีข้อมูล input_tax_file ใน database (ไม่เช็คว่าไฟล์มีอยู่จริง)\n";
            $hasValidFile = true;
        }
    }
    if (!$hasValidFile) {
        echo "   ❌ ไม่มีข้อมูลใบกำกับภาษีโฮลเซลล์ที่ถูกต้อง -> จะถูกกรองออก\n";
        echo "   ** นี่อาจเป็นสาเหตุที่ไม่เจอ! **\n\n";
    } else {
        echo "   ✅ มีข้อมูลใบกำกับภาษีโฮลเซลล์ใน database\n\n";
    }
} else {
    echo "   ⚠️  ไม่มี InputTaxVat (ควรผ่าน filter)\n\n";
}

// 10. สรุป
echo "=== สรุป ===\n";
echo "หาก Quote ถูกกรองออก จะเป็นเพราะเหตุผลใดเหตุผลหนึ่งข้างต้นที่มีเครื่องหมาย ❌\n";
echo "โปรดตรวจสอบส่วนที่มีข้อความ '** นี่อาจเป็นสาเหตุที่ไม่เจอ! **'\n";
