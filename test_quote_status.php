<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ระบุรหัสโควตที่ต้องการตรวจสอบ
$quoteId = 'QT25090717';
if ($argc > 1) {
    $quoteId = $argv[1];
}

echo "===== Testing Quote Status for {$quoteId} =====\n\n";

// ตรวจสอบข้อมูลในฐานข้อมูล
$records = \Illuminate\Support\Facades\DB::table('input_tax')
    ->where('input_tax_quote_number', $quoteId)
    ->where('input_tax_type', 4) // ประเภทโฮลเซล
    ->where('input_tax_status', 'success')
    ->get();

if ($records->isEmpty()) {
    echo "No input tax records (type 4) found for $quoteId\n";
} else {
    echo "Found " . $records->count() . " input tax records (type 4):\n";
    foreach ($records as $record) {
        echo "ID: {$record->input_tax_id}, File: {$record->input_tax_file}, Type: {$record->input_tax_type}\n";
        
        if (!empty($record->input_tax_file)) {
            $filePath = public_path($record->input_tax_file);
            echo "  - File exists: " . (file_exists($filePath) ? "YES" : "NO") . "\n";
            echo "  - Full path: $filePath\n";
        }
    }
}

// ทดสอบฟังก์ชัน getStatusWhosaleInputTax
require_once __DIR__ . '/app/Helpers/statusWhosaleInputTaxHelper.php';
$status = getStatusWhosaleInputTax($quoteId);
echo "\nStatus display: " . ($status ?: "EMPTY") . "\n";
echo "Status interpreted as: " . (strpos($status, 'รอใบกำกับภาษีโฮลเซลล์') !== false ? "รอใบกำกับภาษีโฮลเซลล์" : "ได้รับใบกำกับโฮลเซลแล้ว") . "\n";

// ทดสอบฟังก์ชัน isWaitingForTaxDocuments
require_once __DIR__ . '/app/Helpers/statusCheckListHelper.php';
$quote = \App\Models\quotations\quotationModel::where('quote_number', $quoteId)->first();
if ($quote) {
    $isWaiting = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
    echo "Is waiting for tax documents: " . ($isWaiting ? "YES" : "NO") . "\n";
    
    // จำลองการกรองของ QuotationFilterService.php
    if ($isWaiting) {
        echo "\nResult: $quoteId will NOT appear in sales reports (FILTERED OUT) ✓\n";
    } else {
        echo "\nResult: $quoteId will appear in sales reports (INCLUDED) ✗\n";
    }
    
    // แสดงข้อมูลโควตเพิ่มเติม
    echo "\nAdditional Quote Details:\n";
    echo "  - Quote Status: {$quote->quote_status}\n";
    echo "  - Payment: {$quote->payment}\n";
    
    if (isset($quote->quoteCheckStatus)) {
        echo "  - Wholesale Tax Status: " . ($quote->quoteCheckStatus->wholesale_tax_status ?? 'N/A') . "\n";
    }
} else {
    echo "Quote not found: $quoteId\n";
}