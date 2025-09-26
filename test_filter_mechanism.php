<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ทดสอบกับโควตที่ปรากฎในรายงานยอดขาย
$quoteIds = ['QT25080148']; // เพิ่มโควตอื่นที่ต้องการทดสอบได้ตามต้องการ

echo "===== Testing Sales Report Filter =====\n\n";

// โหลด Helper functions ที่จำเป็น
require_once __DIR__ . '/app/Helpers/statusWhosaleInputTaxHelper.php';
require_once __DIR__ . '/app/Helpers/statusCheckListHelper.php';

foreach ($quoteIds as $quoteId) {
    echo "Testing quote: $quoteId\n";
    
    $quote = \App\Models\quotations\quotationModel::where('quote_number', $quoteId)
        ->with(['quoteCheckStatus', 'quoteLogStatus', 'InputTaxVat', 'checkfileInputtax'])
        ->first();
    
    if (!$quote) {
        echo "Quote not found\n\n";
        continue;
    }
    
    // ตรวจสอบสถานะจาก getStatusWhosaleInputTax(quote_number)
    $status1 = getStatusWhosaleInputTax($quoteId);
    echo "Status from quote_number: " . $status1 . "\n";
    echo "Has 'รอใบกำกับภาษีโฮลเซลล์': " . (strpos($status1, 'รอใบกำกับภาษีโฮลเซลล์') !== false ? "YES" : "NO") . "\n";
    
    // ตรวจสอบสถานะจาก getStatusWhosaleInputTax(checkfileInputtax)
    $status2 = getStatusWhosaleInputTax($quote->checkfileInputtax);
    echo "Status from checkfileInputtax: " . $status2 . "\n";
    echo "Has 'รอใบกำกับภาษีโฮลเซลล์': " . (strpos($status2, 'รอใบกำกับภาษีโฮลเซลล์') !== false ? "YES" : "NO") . "\n";
    
    // ตรวจสอบผลลัพธ์จาก isWaitingForTaxDocuments
    $isWaiting = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
    echo "isWaitingForTaxDocuments result: " . ($isWaiting ? "YES" : "NO") . "\n";
    
    // จำลองการกรองจาก QuotationFilterService
    $shouldFilter = false;
    
    // เงื่อนไขกรอง 1: isWaitingForTaxDocuments
    if ($isWaiting) {
        $shouldFilter = true;
    }
    
    // เงื่อนไขกรอง 2: สถานะจาก getStatusWhosaleInputTax
    if (strpos($status1, 'รอใบกำกับภาษีโฮลเซลล์') !== false || 
        strpos($status2, 'รอใบกำกับภาษีโฮลเซลล์') !== false) {
        $shouldFilter = true;
    }
    
    echo "Should be filtered from sales report: " . ($shouldFilter ? "YES" : "NO") . "\n";
    
    // ตรวจสอบข้อมูลเพิ่มเติม
    echo "\nAdditional information:\n";
    
    // ตรวจสอบข้อมูล InputTaxVat
    if ($quote->InputTaxVat && $quote->InputTaxVat->count() > 0) {
        echo "- InputTaxVat records: " . $quote->InputTaxVat->count() . "\n";
        foreach ($quote->InputTaxVat as $taxRecord) {
            echo "  * ID: {$taxRecord->input_tax_id}, Type: {$taxRecord->input_tax_type}, Status: {$taxRecord->input_tax_status}\n";
            echo "    File: " . ($taxRecord->input_tax_file ?: 'None') . "\n";
            if (!empty($taxRecord->input_tax_file)) {
                $filePath = public_path($taxRecord->input_tax_file);
                echo "    File exists: " . (file_exists($filePath) ? "YES" : "NO") . "\n";
            }
        }
    } else {
        echo "- No InputTaxVat records\n";
    }
    
    // ตรวจสอบข้อมูล checkfileInputtax
    if ($quote->checkfileInputtax) {
        echo "- Has checkfileInputtax: YES\n";
    } else {
        echo "- Has checkfileInputtax: NO\n";
    }
    
    echo "\n---------------------------------------------\n\n";
}