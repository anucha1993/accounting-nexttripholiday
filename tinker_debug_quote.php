<?php

use App\Models\quotations\quotationModel;
use App\Models\inputTax\inputTaxModel;

$quote_number = 'QT25080187';

echo "=== Debugging Quote Filter for {$quote_number} ===" . PHP_EOL;

// 1. ตรวจสอบข้อมูลใบเสนอราคา
$quote = quotationModel::where('quote_number', $quote_number)->first();

if (!$quote) {
    echo "ไม่พบใบเสนอราคา {$quote_number}" . PHP_EOL;
    return;
}

echo "Quote ID: {$quote->quote_id}" . PHP_EOL;
echo "Status: {$quote->quote_status}" . PHP_EOL;
echo "Customer: {$quote->customer_name}" . PHP_EOL;
echo "Issue Date: {$quote->issue_date}" . PHP_EOL;

// 2. ตรวจสอบความสัมพันธ์กับ InputTaxVat
$inputTaxVat = inputTaxModel::where('input_tax_quote_id', $quote->quote_id)->first();

if ($inputTaxVat) {
    echo "InputTaxVat found - ID: {$inputTaxVat->id}, Type: {$inputTaxVat->input_tax_type}" . PHP_EOL;
} else {
    echo "InputTaxVat not found" . PHP_EOL;
}

// 3. ตรวจสอบ Helper function
$statusBadge = getStatusWhosaleInputTax($quote->quote_id);
echo "Status Badge from Helper: " . strip_tags($statusBadge) . PHP_EOL;

// 4. ตรวจสอบไฟล์ PDF ใน withholding folder
$filename = 'withholding_' . $quote->quote_id . '.pdf';
$filePath = public_path('withholding/' . $filename);

echo "Checking file: {$filePath}" . PHP_EOL;
echo "File exists: " . (file_exists($filePath) ? 'YES' : 'NO') . PHP_EOL;

if (file_exists($filePath)) {
    $fileSize = filesize($filePath);
    echo "File size: {$fileSize} bytes" . PHP_EOL;
}

// 5. ตรวจสอบตรรกะของ Filter Service
echo PHP_EOL . "=== Filter Logic Check ===" . PHP_EOL;

// จำลองตรรกะจาก QuotationFilterService
$hasInputTaxVat = $inputTaxVat && $inputTaxVat->input_tax_type == 4;
echo "Has InputTaxVat type 4: " . ($hasInputTaxVat ? 'YES' : 'NO') . PHP_EOL;

$hasWholeSaleFile = file_exists($filePath) && filesize($filePath) > 0;
echo "Has wholesale tax file: " . ($hasWholeSaleFile ? 'YES' : 'NO') . PHP_EOL;

$shouldBeFiltered = $hasInputTaxVat && !$hasWholeSaleFile;
echo "Should be FILTERED OUT: " . ($shouldBeFiltered ? 'YES' : 'NO') . PHP_EOL;
echo "Should be SHOWN in report: " . ($shouldBeFiltered ? 'NO' : 'YES') . PHP_EOL;

echo PHP_EOL . "=== Summary ===" . PHP_EOL;
if ($shouldBeFiltered) {
    echo "❌ This quote SHOULD BE filtered out from sales report" . PHP_EOL;
} else {
    echo "✅ This quote should appear in sales report" . PHP_EOL;
}