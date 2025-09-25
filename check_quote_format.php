<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ตรวจสอบโค้ด QT25090717 และ QT25080002
$quoteIds = ['QT25090717', 'QT25080002'];

foreach ($quoteIds as $quoteId) {
    echo "Checking quote ID: $quoteId\n";
    
    // ตรวจสอบในตาราง input_tax โดยตรง
    $records = \Illuminate\Support\Facades\DB::table('input_tax')
        ->where('input_tax_quote_number', $quoteId)
        ->get();
    
    echo "Records found in input_tax table: " . count($records) . "\n";
    
    if (count($records) > 0) {
        foreach ($records as $record) {
            echo "ID: {$record->input_tax_id}, ";
            echo "Type: {$record->input_tax_type}, ";
            echo "Status: {$record->input_tax_status}, ";
            echo "File: " . (empty($record->input_tax_file) ? "EMPTY" : $record->input_tax_file) . "\n";
        }
    }
    
    // ตรวจสอบในตาราง quotation
    $quote = \Illuminate\Support\Facades\DB::table('quotation')
        ->where('quote_id', $quoteId)
        ->first();
    
    if ($quote) {
        echo "\nFound in quotation table:\n";
        echo "ID: {$quote->quote_id}, Number: {$quote->quote_number}\n\n";
    } else {
        echo "\nNot found in quotation table\n\n";
    }
}

// ตรวจสอบการใช้ quote_id vs quote_number
echo "Checking column format discrepancies:\n";
echo "Sample data from input_tax table:\n";
$sampleInputTax = \Illuminate\Support\Facades\DB::table('input_tax')
    ->limit(2)
    ->get();
    
if (count($sampleInputTax) > 0) {
    echo "Input tax sample:\n";
    foreach ($sampleInputTax as $record) {
        echo "input_tax_quote_id: {$record->input_tax_quote_id}, ";
        echo "input_tax_quote_number: {$record->input_tax_quote_number}\n";
    }
}

echo "\nSample data from quotation table:\n";
$sampleQuote = \Illuminate\Support\Facades\DB::table('quotation')
    ->limit(2)
    ->get();
    
if (count($sampleQuote) > 0) {
    echo "Quotation sample:\n";
    foreach ($sampleQuote as $record) {
        echo "quote_id: {$record->quote_id}, ";
        echo "quote_number: {$record->quote_number}\n";
    }
}