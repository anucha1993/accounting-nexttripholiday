<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\quotations\quotationModel;

// กำหนด quote number ที่ต้องการตรวจสอบ
$quoteNumber = 'QT25090717';

// ดึงข้อมูล quotation
$quote = quotationModel::where('quote_number', $quoteNumber)->first();

if (!$quote) {
    echo "ไม่พบโควตหมายเลข $quoteNumber!\n";
    exit;
}

// ดึงข้อมูล inputTax ทั้งหมดสำหรับโควตนี้
$inputTaxRecords = \App\Models\inputTax\inputTaxModel::where('input_tax_quote_id', $quote->quote_id)->get();

echo "=== ทดสอบฟังก์ชัน getStatusWhosaleInputTax ที่แก้ไขใหม่ ===\n";
echo "โควต: {$quote->quote_number} (ID: {$quote->quote_id})\n\n";

echo "ข้อมูล InputTax ทั้งหมด:\n";
foreach ($inputTaxRecords as $index => $tax) {
    echo "รายการ #{$index}:\n";
    echo "  - ID: {$tax->input_tax_id}\n";
    echo "  - ประเภท: {$tax->input_tax_type}\n";
    echo "  - สถานะ: {$tax->input_tax_status}\n";
    echo "  - ไฟล์: " . (empty($tax->input_tax_file) ? "ไม่มีไฟล์" : $tax->input_tax_file) . "\n";
}

echo "\nผลการตรวจสอบด้วย DB Query โดยตรง:\n";
$hasFile = \Illuminate\Support\Facades\DB::table('input_taxes')
    ->where('input_tax_quote_id', $quote->quote_id)
    ->where('input_tax_status', 'success')
    ->whereNotNull('input_tax_file')
    ->where('input_tax_file', '!=', '')
    ->exists();
echo "มีไฟล์ input_tax_file ที่ไม่ว่างเปล่า: " . ($hasFile ? "ใช่" : "ไม่") . "\n";

// ดึง checkfileInputtax
$checkfileInputtax = $quote->checkfileInputtax;

echo "\nข้อมูล checkfileInputtax:\n";
if ($checkfileInputtax) {
    echo "  - ID: {$checkfileInputtax->input_tax_id}\n";
    echo "  - ประเภท: {$checkfileInputtax->input_tax_type}\n";
    echo "  - สถานะ: {$checkfileInputtax->input_tax_status}\n";
    echo "  - ไฟล์: " . (empty($checkfileInputtax->input_tax_file) ? "ไม่มีไฟล์" : $checkfileInputtax->input_tax_file) . "\n";
} else {
    echo "ไม่พบข้อมูล checkfileInputtax\n";
}

echo "\nผลลัพธ์จากฟังก์ชัน getStatusWhosaleInputTax:\n";
echo "- " . strip_tags(getStatusWhosaleInputTax($checkfileInputtax)) . "\n";

// สร้างฟังก์ชันจำลองเพื่อทดสอบโค้ดที่แก้ไขแล้ว
function testModifiedFunction($inputTax) {
    if (!$inputTax) {
        return 'ไม่มีข้อมูล inputTax';
    }
    
    if (is_object($inputTax)) {
        if (!empty($inputTax->input_tax_file)) {
            return 'ได้รับใบกำกับโฮลเซลแล้ว';
        } else {
            $quoteId = $inputTax->input_tax_quote_id;
            
            $hasFile = \Illuminate\Support\Facades\DB::table('input_taxes')
                ->where('input_tax_quote_id', $quoteId)
                ->where('input_tax_status', 'success')
                ->whereNotNull('input_tax_file')
                ->where('input_tax_file', '!=', '')
                ->exists();
            
            if ($hasFile) {
                return 'ได้รับใบกำกับโฮลเซลแล้ว';
            } else {
                return 'รอใบกำกับภาษีโฮลเซลล์';
            }
        }
    }
    
    return 'กรณีอื่นๆ';
}

echo "\nผลลัพธ์จากฟังก์ชันทดสอบ testModifiedFunction:\n";
echo "- " . testModifiedFunction($checkfileInputtax) . "\n";