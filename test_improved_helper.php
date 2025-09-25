<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// โหลด helper ที่จำเป็น
require_once __DIR__ . '/app/Helpers/statusWhosaleInputTaxHelper.php';

// ล้าง cache
\Illuminate\Support\Facades\Artisan::call('cache:clear');
\Illuminate\Support\Facades\Artisan::call('config:clear');

echo "Testing getStatusWhosaleInputTax function\n";

// ทดสอบด้วย quote_number โดยตรง
$quoteIds = ['QT25090717', 'QT25080002', 'QT25080076'];

foreach ($quoteIds as $quoteId) {
    echo "Testing Quote ID: $quoteId\n";
    
    // ทดสอบฟังก์ชันโดยส่งค่า quote_number โดยตรง
    echo "Direct call with quote_number:\n";
    $status = getStatusWhosaleInputTax($quoteId);
    echo "  Status: " . ($status ?: "EMPTY") . "\n";
    
    // ค้นหา quote object
    $quote = \App\Models\quotations\quotationModel::where('quote_number', $quoteId)->first();
    
    if ($quote) {
        echo "Testing with quote object:\n";
        $status = getStatusWhosaleInputTax($quote);
        echo "  Status: " . ($status ?: "EMPTY") . "\n";
        
        // ตรวจสอบ InputTaxVat ของ quote
        $inputTaxRecords = $quote->InputTaxVat;
        
        if (count($inputTaxRecords) > 0) {
            echo "Testing with input tax record:\n";
            foreach ($inputTaxRecords as $index => $record) {
                $status = getStatusWhosaleInputTax($record);
                echo "  Record #{$index} - Status: " . ($status ?: "EMPTY") . "\n";
            }
        }
    }
    
    echo "\n";
}

// ตรวจสอบข้อมูลในตาราง input_tax สำหรับ QT25090717 และ QT25080002
echo "Verifying data in input_tax table:\n";
foreach ($quoteIds as $quoteId) {
    $inputTaxRecords = \Illuminate\Support\Facades\DB::table('input_tax')
        ->where('input_tax_quote_number', $quoteId)
        ->get();
    
    echo "Quote ID: $quoteId - " . count($inputTaxRecords) . " records\n";
    
    foreach ($inputTaxRecords as $record) {
        echo "  ID: {$record->input_tax_id}, ";
        echo "Type: {$record->input_tax_type}, ";
        echo "Status: {$record->input_tax_status}, ";
        echo "File: " . (empty($record->input_tax_file) ? "EMPTY" : $record->input_tax_file) . "\n";
    }
    
    echo "\n";
}