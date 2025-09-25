<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ทดสอบ relationship ที่แก้ไขแล้ว
$quoteIds = ['QT25090717', 'QT25080002', 'QT25080076'];

foreach ($quoteIds as $quoteId) {
    echo "Testing Quote ID: $quoteId\n";
    
    // ค้นหา quote จาก quote_number
    $quote = \App\Models\quotations\quotationModel::where('quote_number', $quoteId)->first();
    
    if ($quote) {
        echo "Found quote record: ID={$quote->quote_id}, Number={$quote->quote_number}\n";
        
        // ทดสอบ relationship InputTaxVat
        echo "Testing InputTaxVat relationship:\n";
        $inputTaxRecords = $quote->InputTaxVat;
        echo "  Found " . count($inputTaxRecords) . " input tax records\n";
        
        foreach ($inputTaxRecords as $record) {
            echo "  - ID: {$record->input_tax_id}, ";
            echo "Type: {$record->input_tax_type}, ";
            echo "Status: {$record->input_tax_status}, ";
            echo "File: " . (empty($record->input_tax_file) ? "EMPTY" : $record->input_tax_file) . "\n";
        }
        
        // ทดสอบ relationship checkfileInputtax
        echo "Testing checkfileInputtax relationship:\n";
        $checkFile = $quote->checkfileInputtax;
        if ($checkFile) {
            echo "  Found: ID={$checkFile->input_tax_id}, File=" . ($checkFile->input_tax_file ? $checkFile->input_tax_file : "EMPTY") . "\n";
        } else {
            echo "  No checkfileInputtax record found\n";
        }
        
        // ทดสอบฟังก์ชัน getStatusWhosaleInputTax
        echo "Testing getStatusWhosaleInputTax:\n";
        $status = getStatusWhosaleInputTax($quoteId);
        echo "  Status: " . ($status ? $status : "EMPTY") . "\n";
        
        // ทดสอบสถานะการรอใบกำกับภาษี
        echo "Testing isWaitingForTaxDocuments:\n";
        if (function_exists('isWaitingForTaxDocuments')) {
            // ต้องใช้พารามิเตอร์ 2 ตัว
            // $isWaiting = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
            // echo "  Is waiting: " . ($isWaiting ? "YES" : "NO") . "\n";
            echo "  Function requires 2 parameters - skipping test\n";
        } else {
            echo "  Function isWaitingForTaxDocuments not found or loaded\n";
        }
    } else {
        echo "No quote found with number: $quoteId\n";
        
        // แต่ยังสามารถทดสอบ input_tax table ได้โดยตรง
        echo "Testing input_tax table directly:\n";
        $records = \Illuminate\Support\Facades\DB::table('input_tax')
            ->where('input_tax_quote_number', $quoteId)
            ->get();
            
        echo "  Found " . count($records) . " records in input_tax table\n";
        
        foreach ($records as $record) {
            echo "  - ID: {$record->input_tax_id}, ";
            echo "Type: {$record->input_tax_type}, ";
            echo "Status: {$record->input_tax_status}, ";
            echo "File: " . (empty($record->input_tax_file) ? "EMPTY" : $record->input_tax_file) . "\n";
        }
    }
    
    echo "\n";
}