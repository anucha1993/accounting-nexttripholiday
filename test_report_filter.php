<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ล้าง cache
\Illuminate\Support\Facades\Artisan::call('cache:clear');
\Illuminate\Support\Facades\Artisan::call('config:clear');
\Illuminate\Support\Facades\Artisan::call('view:clear');

// ทดสอบการกรองข้อมูลใน QuotationFilterService
$quoteIds = ['QT25090717', 'QT25080002', 'QT25080076'];

echo "Testing QuotationFilterService for filtering quotes\n";
echo "================================================\n\n";

foreach ($quoteIds as $quoteId) {
    echo "Testing Quote ID: $quoteId\n";
    
    // ดึงข้อมูล quote
    $quote = \App\Models\quotations\quotationModel::where('quote_number', $quoteId)->first();
    
    if (!$quote) {
        echo "Quote not found\n\n";
        continue;
    }
    
    // ทดสอบเงื่อนไขการกรอง
    echo "Basic Info: ID={$quote->quote_id}, Number={$quote->quote_number}\n";
    
    // ตรวจสอบสถานะภาษีโฮลเซลล์
    $wholesaleTaxStatus = isset($quote->quoteCheckStatus) ? 
        $quote->quoteCheckStatus->wholesale_tax_status : null;
    echo "Wholesale Tax Status: " . ($wholesaleTaxStatus ?: "NULL") . "\n";
    
    // ตรวจสอบไฟล์ด้วย type 4 และต้องมีไฟล์จริง
    $hasInputTaxFile = false;
    if ($quote->InputTaxVat) {
        foreach ($quote->InputTaxVat as $taxRecord) {
            if (!empty($taxRecord->input_tax_file) 
                && $taxRecord->input_tax_status === 'success'
                && $taxRecord->input_tax_type == 4) {
                
                // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
                $filePath = public_path($taxRecord->input_tax_file);
                if (file_exists($filePath)) {
                    $hasInputTaxFile = true;
                    break;
                }
            }
        }
    }
    echo "Has Input Tax File (Type 4, exists): " . ($hasInputTaxFile ? "YES" : "NO") . "\n";
    
    // คำนวณเงื่อนไขตามที่ใช้ใน QuotationFilterService
    $isWaiting = (is_null($wholesaleTaxStatus) || 
                 trim($wholesaleTaxStatus) !== 'ได้รับแล้ว') && 
                 !$hasInputTaxFile;
    
    echo "Is Waiting for Tax Documents: " . ($isWaiting ? "YES (filtered out)" : "NO (shown)") . "\n";
    
    // ตรวจสอบด้วย isWaitingForTaxDocuments
    if (function_exists('isWaitingForTaxDocuments')) {
        $waitingForTax = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
        echo "isWaitingForTaxDocuments result: " . ($waitingForTax ? "YES (filtered out)" : "NO (shown)") . "\n";
    }
    
    // สรุปผลการกรอง
    echo "EXPECTED RESULT: " . ($isWaiting ? "FILTERED OUT (hide)" : "INCLUDED (show)") . "\n\n";
}

// ทดสอบจำลองรายงาน
echo "Simulating report results\n";
echo "========================\n\n";

// สร้าง request จำลอง
$request = new \Illuminate\Http\Request();

// เรียกใช้ QuotationFilterService::filter
try {
    $results = \App\Services\QuotationFilterService::filter($request);
    
    // ตรวจสอบว่า QT25090717, QT25080002 และ QT25080076 อยู่ในผลลัพธ์หรือไม่
    $quotesInReport = [];
    foreach ($results as $item) {
        $quotesInReport[] = $item->quote_number;
    }
    
    echo "Quotes found in report: " . count($quotesInReport) . "\n";
    
    foreach ($quoteIds as $quoteId) {
        $found = in_array($quoteId, $quotesInReport);
        echo "Quote $quoteId: " . ($found ? "FOUND in report" : "NOT FOUND in report") . "\n";
    }
    
    echo "\nAll quotes in report:\n";
    foreach ($quotesInReport as $qn) {
        echo "- $qn\n";
    }
    
} catch (\Exception $e) {
    echo "Error simulating report: " . $e->getMessage() . "\n";
}