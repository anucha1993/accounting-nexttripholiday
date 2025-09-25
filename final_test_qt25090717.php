<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ล้าง cache
\Illuminate\Support\Facades\Artisan::call('cache:clear');

// โค้ดทดสอบเฉพาะ QT25090717
$quoteId = 'QT25090717';
echo "Final test for $quoteId\n";
echo "==================\n\n";

// ดึงข้อมูล quote
$quote = \App\Models\quotations\quotationModel::where('quote_number', $quoteId)->first();
if (!$quote) {
    echo "Quote not found\n";
    exit;
}

// ทดสอบฟังก์ชัน isWaitingForTaxDocuments โดยตรง
echo "Testing isWaitingForTaxDocuments function\n";

$isWaiting = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
echo "isWaitingForTaxDocuments result: " . ($isWaiting ? "YES (filtered out)" : "NO (shown in report)") . "\n\n";

// ตรวจสอบสถานะไฟล์ใน InputTaxVat
echo "Checking InputTaxVat file status\n";
$hasRealFile = false;

if ($quote->InputTaxVat && $quote->InputTaxVat->count() > 0) {
    foreach ($quote->InputTaxVat as $taxRecord) {
        echo "- Record ID: {$taxRecord->input_tax_id}, ";
        echo "Type: {$taxRecord->input_tax_type}, ";
        echo "Status: {$taxRecord->input_tax_status}, ";
        echo "File: " . (empty($taxRecord->input_tax_file) ? "EMPTY" : $taxRecord->input_tax_file) . "\n";
        
        if (!empty($taxRecord->input_tax_file) 
            && $taxRecord->input_tax_status === 'success'
            && $taxRecord->input_tax_type == 4) {
            
            // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
            $filePath = public_path($taxRecord->input_tax_file);
            $fileExists = file_exists($filePath);
            echo "  File exists: " . ($fileExists ? "YES" : "NO") . "\n";
            
            if ($fileExists) {
                $hasRealFile = true;
            }
        }
    }
}

echo "\nHas real file with type 4: " . ($hasRealFile ? "YES" : "NO") . "\n";
echo "Expected filtering result: " . (!$hasRealFile ? "FILTERED OUT (hidden)" : "INCLUDED (shown)") . "\n\n";

// จำลองการทำงานของ QuotationFilterService
echo "Simulating filter function from QuotationFilterService\n";

try {
    // จำลอง filter callback
    $shouldFilter = false;
    
    // ตรวจสอบสถานะภาษีใช้ isWaitingForTaxDocuments เท่านั้น
    $waitingForTax = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
    if ($waitingForTax) {
        echo "Quote filtered out: ยังรอใบกำกับภาษีโฮลเซลล์\n";
        $shouldFilter = true;
    } else {
        echo "Quote passed tax document check\n";
    }
    
    // เงื่อนไขการแสดงกำไร (จำลอง)
    echo "\nExpected report result: " . ($shouldFilter ? "FILTERED OUT (hidden)" : "INCLUDED (shown)") . "\n";
    
} catch (\Exception $e) {
    echo "Error in simulation: " . $e->getMessage() . "\n";
}