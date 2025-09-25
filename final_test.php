<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// โหลดและล้าง cache
require_once __DIR__ . '/app/Helpers/statusWhosaleInputTaxHelper.php';
require_once __DIR__ . '/app/Helpers/statusCheckListHelper.php';

// ล้าง cache
\Illuminate\Support\Facades\Artisan::call('cache:clear');
\Illuminate\Support\Facades\Artisan::call('config:clear');

echo "Final testing of the fixed logic\n";

// ทดสอบกับ QT25090717 และ QT25080002
$quoteIds = ['QT25090717', 'QT25080002', 'QT25080076'];

foreach ($quoteIds as $quoteId) {
    echo "\n=====================================\n";
    echo "Testing Quote ID: $quoteId\n";
    echo "=====================================\n";
    
    // 1. ดึงข้อมูล quotation
    $quote = \App\Models\quotations\quotationModel::where('quote_number', $quoteId)->first();
    
    if (!$quote) {
        echo "Quote not found\n";
        continue;
    }
    
    echo "Basic Info: ID={$quote->quote_id}, Number={$quote->quote_number}\n\n";
    
    // 2. ตรวจสอบสถานะโดยใช้ getStatusWhosaleInputTax
    echo "Status from getStatusWhosaleInputTax: " . getStatusWhosaleInputTax($quoteId) . "\n\n";
    
    // 3. ตรวจสอบ InputTaxVat
    echo "InputTaxVat Records:\n";
    if ($quote->InputTaxVat && $quote->InputTaxVat->count() > 0) {
        foreach ($quote->InputTaxVat as $idx => $record) {
            echo "Record #{$idx}: ID={$record->input_tax_id}, ";
            echo "Type={$record->input_tax_type}, ";
            echo "Status={$record->input_tax_status}, ";
            echo "File=" . (empty($record->input_tax_file) ? "EMPTY" : "EXISTS") . "\n";
        }
    } else {
        echo "No InputTaxVat records\n";
    }
    
    echo "\n";
    
    // 4. ตรวจสอบ checkfileInputtax
    echo "checkfileInputtax:\n";
    $checkFile = $quote->checkfileInputtax;
    if ($checkFile) {
        echo "Found: ID={$checkFile->input_tax_id}, ";
        echo "Type={$checkFile->input_tax_type}, ";
        echo "Status={$checkFile->input_tax_status}, ";
        echo "File=" . (empty($checkFile->input_tax_file) ? "EMPTY" : "EXISTS") . "\n";
    } else {
        echo "No checkfileInputtax record found\n";
    }
    
    echo "\n";
    
    // 5. ตรวจสอบเงื่อนไขการกรองตามที่ใช้ใน QuotationFilterService
    echo "Filter Conditions Check:\n";
    
    // ดึงข้อมูลที่จำเป็น
    $wholesaleTaxStatus = isset($quote->quoteCheckStatus) ? 
        $quote->quoteCheckStatus->wholesale_tax_status : null;
        
    // ตรวจสอบไฟล์จาก InputTaxVat
    $hasInputTaxFile = false;
    if ($quote->InputTaxVat) {
        foreach ($quote->InputTaxVat as $taxRecord) {
            if (!empty($taxRecord->input_tax_file) && $taxRecord->input_tax_status === 'success') {
                $hasInputTaxFile = true;
                break;
            }
        }
    }
    
    echo "Wholesale Tax Status: " . ($wholesaleTaxStatus ?: "NULL") . "\n";
    echo "Has Input Tax File: " . ($hasInputTaxFile ? "YES" : "NO") . "\n";
    
    // คำนวณเงื่อนไขตามที่ใช้ใน QuotationFilterService
    $isWaiting = (is_null($wholesaleTaxStatus) || 
                 trim($wholesaleTaxStatus) !== 'ได้รับแล้ว') && 
                 !$hasInputTaxFile;
                 
    echo "Is Waiting for Tax Documents: " . ($isWaiting ? "YES (will be filtered out)" : "NO (will be shown)") . "\n";
    
    // ตรวจสอบโดยตรงจาก DB
    echo "\nDirect DB Check for input_tax records:\n";
    $records = \Illuminate\Support\Facades\DB::table('input_tax')
        ->where('input_tax_quote_number', $quoteId)
        ->get();
        
    if (count($records) > 0) {
        foreach ($records as $record) {
            echo "ID: {$record->input_tax_id}, ";
            echo "Type: {$record->input_tax_type}, ";
            echo "Status: {$record->input_tax_status}, ";
            echo "File: " . (empty($record->input_tax_file) ? "EMPTY" : "EXISTS") . "\n";
        }
    } else {
        echo "No records found\n";
    }
}