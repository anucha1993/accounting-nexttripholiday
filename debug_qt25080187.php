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
echo "Customer: " . ($quote->customer_name ?? 'N/A') . PHP_EOL;
echo "Issue Date: " . ($quote->issue_date ?? 'N/A') . PHP_EOL;

// 2. ตรวจสอบความสัมพันธ์กับ inputTax
$inputTaxVat = inputTaxModel::where('input_tax_quote_id', $quote->quote_id)->first();

if ($inputTaxVat) {
    echo "InputTaxVat found - ID: {$inputTaxVat->id}, Type: {$inputTaxVat->input_tax_type}" . PHP_EOL;
} else {
    echo "InputTaxVat not found" . PHP_EOL;
}

// 3. ตรวจสอบ Helper function แบบ manual
// จำลองตรรกะจาก getStatusWhosaleInputTax
$records = \Illuminate\Support\Facades\DB::table('input_tax')
    ->where('input_tax_quote_id', $quote->quote_id)
    ->where('input_tax_status', 'success')
    ->where('input_tax_type', 4) // ต้องเป็นประเภทโฮลเซล
    ->whereNotNull('input_tax_file')
    ->where('input_tax_file', '!=', '')
    ->get();

echo "Records found for type 4: " . $records->count() . PHP_EOL;

$hasFile = false;
foreach ($records as $record) {
    $filePath = public_path($record->input_tax_file);
    echo "Checking file: {$filePath}" . PHP_EOL;
    if (file_exists($filePath)) {
        $hasFile = true;
        echo "File exists: YES" . PHP_EOL;
        break;
    } else {
        echo "File exists: NO" . PHP_EOL;
    }
}

if ($hasFile) {
    $statusFromHelper = 'ได้รับใบกำกับโฮลเซลแล้ว';
} else {
    $statusFromHelper = 'รอใบกำกับภาษีโฮลเซลล์';
}

echo "Status from Helper logic: {$statusFromHelper}" . PHP_EOL;

// 4. ตรวจสอบไฟล์ PDF ใน withholding folder (ตามที่ QuotationFilterService ใช้)
$filename = 'withholding_' . $quote->quote_id . '.pdf';
$withholdingFilePath = public_path('withholding/' . $filename);

echo PHP_EOL . "=== Withholding File Check ===" . PHP_EOL;
echo "Checking withholding file: {$withholdingFilePath}" . PHP_EOL;
echo "File exists: " . (file_exists($withholdingFilePath) ? 'YES' : 'NO') . PHP_EOL;

if (file_exists($withholdingFilePath)) {
    $fileSize = filesize($withholdingFilePath);
    echo "File size: {$fileSize} bytes" . PHP_EOL;
}

// 5. ตรวจสอบตรรกะของ Filter Service
echo PHP_EOL . "=== Filter Logic Check ===" . PHP_EOL;

$hasInputTaxVat = $inputTaxVat && $inputTaxVat->input_tax_type == 4;
echo "Has InputTaxVat type 4: " . ($hasInputTaxVat ? 'YES' : 'NO') . PHP_EOL;

$hasWholeSaleFile = file_exists($withholdingFilePath) && filesize($withholdingFilePath) > 0;
echo "Has wholesale tax file: " . ($hasWholeSaleFile ? 'YES' : 'NO') . PHP_EOL;

$shouldBeFiltered = $hasInputTaxVat && !$hasWholeSaleFile;
echo "Should be FILTERED OUT: " . ($shouldBeFiltered ? 'YES' : 'NO') . PHP_EOL;
echo "Should be SHOWN in report: " . ($shouldBeFiltered ? 'NO' : 'YES') . PHP_EOL;

echo PHP_EOL . "=== Summary ===" . PHP_EOL;
if ($shouldBeFiltered) {
    echo "❌ This quote SHOULD BE filtered out from sales report" . PHP_EOL;
    echo "❌ Reason: Has InputTaxVat type 4 BUT no withholding file" . PHP_EOL;
} else {
    echo "✅ This quote should appear in sales report" . PHP_EOL;
    if (!$hasInputTaxVat) {
        echo "✅ Reason: No InputTaxVat type 4" . PHP_EOL;
    } else if ($hasWholeSaleFile) {
        echo "✅ Reason: Has withholding file" . PHP_EOL;
    }
}