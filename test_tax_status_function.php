<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\quotations\quotationModel;

// กำหนด quote number ที่ต้องการตรวจสอบ
$quoteNumber = 'QT25090717';

// ดึงข้อมูล quotation พร้อม relationships ที่เกี่ยวข้อง
$quote = quotationModel::where('quote_number', $quoteNumber)
    ->with([
        'InputTaxVat',
        'checkfileInputtax'
    ])
    ->first();

if (!$quote) {
    echo "ไม่พบโควตหมายเลข $quoteNumber!\n";
    exit;
}

echo "=== ทดสอบฟังก์ชันแสดงสถานะใบกำกับภาษีโฮลเซลล์ ===\n";
echo "โควต: {$quote->quote_number}\n\n";

// ทดสอบฟังก์ชันเดิม
echo "ฟังก์ชันเดิม getStatusWhosaleInputTax แบบเก่า: \n";
if ($quote->checkfileInputtax) {
    if($quote->checkfileInputtax->input_tax_file) {
        echo "- สถานะที่ควรแสดง: ได้รับใบกำกับโฮลเซลแล้ว\n";
    } else {
        echo "- สถานะที่ควรแสดง: รอใบกำกับภาษีโฮลเซลล์\n";
    }
} else {
    echo "- ไม่มี checkfileInputtax\n";
}

// ทดสอบฟังก์ชันที่แก้ไขแล้ว
echo "\nฟังก์ชันใหม่ getStatusWhosaleInputTax แบบใหม่: \n";
echo "- " . strip_tags(getStatusWhosaleInputTax($quote->checkfileInputtax)) . "\n";

// แสดงข้อมูลไฟล์ใน InputTaxVat
echo "\nข้อมูลไฟล์ใน InputTaxVat:\n";
if ($quote->InputTaxVat && $quote->InputTaxVat->count() > 0) {
    foreach ($quote->InputTaxVat as $index => $tax) {
        echo "รายการ #{$index}: ประเภท={$tax->input_tax_type}, สถานะ={$tax->input_tax_status}, ";
        echo "ไฟล์=" . ($tax->input_tax_file ? $tax->input_tax_file : "ไม่มีไฟล์") . "\n";
    }
}

// ตรวจสอบว่าควรถูกกรองออกหรือไม่
$hasInputTaxFile = false;
if ($quote->InputTaxVat) {
    foreach ($quote->InputTaxVat as $tax) {
        if (!empty($tax->input_tax_file) && $tax->input_tax_status === 'success') {
            $hasInputTaxFile = true;
            break;
        }
    }
}

echo "\nสรุป:\n";
echo "- มีไฟล์ใบกำกับภาษีที่สมบูรณ์: " . ($hasInputTaxFile ? "ใช่" : "ไม่") . "\n";
echo "- ควรแสดงในรายงานยอดขาย: " . ($hasInputTaxFile ? "ใช่" : "ไม่") . "\n";
echo "- ควรแสดงสถานะในหน้า UI: " . ($hasInputTaxFile ? "ได้รับใบกำกับโฮลเซลแล้ว" : "รอใบกำกับภาษีโฮลเซลล์") . "\n";