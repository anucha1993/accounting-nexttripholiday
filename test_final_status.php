<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ล้าง cache
\Illuminate\Support\Facades\Artisan::call('cache:clear');

// ทดสอบกับ QT25090717
$quoteId = 'QT25090717';

echo "===========================================\n";
echo "Testing status filter for quote: $quoteId\n";
echo "===========================================\n\n";

// ดึงข้อมูล quote
$quote = \App\Models\quotations\quotationModel::where('quote_number', $quoteId)->first();
if (!$quote) {
    echo "Quote not found\n";
    exit;
}

echo "Quote info: ID={$quote->quote_id}, Number={$quote->quote_number}\n\n";

// ทดสอบ 1: ตรวจสอบสถานะ "รอใบกำกับภาษีโฮลเซลล์"
echo "1. Testing getStatusWhosaleInputTax function:\n";
require_once __DIR__ . '/app/Helpers/statusWhosaleInputTaxHelper.php';
$status = getStatusWhosaleInputTax($quoteId);
echo "   Status: " . ($status ?: "EMPTY") . "\n";
echo "   Has 'รอใบกำกับภาษีโฮลเซลล์' status: " . (strpos($status, 'รอใบกำกับภาษีโฮลเซลล์') !== false ? "YES" : "NO") . "\n\n";

// ทดสอบ 2: ตรวจสอบ isWaitingForTaxDocuments
echo "2. Testing isWaitingForTaxDocuments function:\n";
require_once __DIR__ . '/app/Helpers/statusCheckListHelper.php';
$isWaiting = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
echo "   Is waiting for tax documents: " . ($isWaiting ? "YES (should be filtered out)" : "NO (should be shown)") . "\n\n";

// ทดสอบ 3: จำลองตัวกรองใน QuotationFilterService
echo "3. Simulating QuotationFilterService filter:\n";
$shouldFilter = false;

// a. ตรวจสอบสถานะการรอภาษีโดยตรงด้วย isWaitingForTaxDocuments
$waitingForTax = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
if ($waitingForTax) {
    echo "   Quote should be filtered out by isWaitingForTaxDocuments\n";
    $shouldFilter = true;
} else {
    echo "   Quote passed isWaitingForTaxDocuments check\n";
}

// b. ตรวจสอบสถานะจาก getStatusWhosaleInputTax
$status = getStatusWhosaleInputTax($quote->quote_number);
if (strpos($status, 'รอใบกำกับภาษีโฮลเซลล์') !== false) {
    echo "   Quote should be filtered out by 'รอใบกำกับภาษีโฮลเซลล์' status\n";
    $shouldFilter = true;
} else {
    echo "   Quote does not have 'รอใบกำกับภาษีโฮลเซลล์' status\n";
}

// สรุปผล
echo "\nFinal result: Quote should be " . ($shouldFilter ? "FILTERED OUT (hidden)" : "INCLUDED (shown)") . " in report\n";

// ตรวจสอบว่า QT25090717 มีข้อมูลไฟล์จริงหรือไม่
echo "\n4. Additional file verification:\n";
if ($quote->InputTaxVat && $quote->InputTaxVat->count() > 0) {
    foreach ($quote->InputTaxVat as $taxRecord) {
        if ($taxRecord->input_tax_type == 4) {
            echo "   Found type 4 record: ID={$taxRecord->input_tax_id}, File=" . ($taxRecord->input_tax_file ?: "EMPTY") . "\n";
            if (!empty($taxRecord->input_tax_file)) {
                $filePath = public_path($taxRecord->input_tax_file);
                $fileExists = file_exists($filePath);
                echo "   File exists: " . ($fileExists ? "YES" : "NO") . "\n";
                echo "   File path: $filePath\n";
                
                if ($fileExists) {
                    $fileSize = filesize($filePath);
                    echo "   File size: $fileSize bytes\n";
                }
            }
        }
    }
} else {
    echo "   No InputTaxVat records found\n";
}