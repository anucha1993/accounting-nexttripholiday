<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// โหลด helpers ที่จำเป็น
require_once __DIR__ . '/app/Helpers/statusWhosaleInputTaxHelper.php';
require_once __DIR__ . '/app/Helpers/statusCheckListHelper.php';

// ทดสอบกับ QT25080002
$quoteId = 'QT25080002';

// เรียกใช้ฟังก์ชัน getStatusWhosaleInputTax โดยตรง
echo "Testing getStatusWhosaleInputTax for $quoteId\n";
$status = getStatusWhosaleInputTax($quoteId);
echo "Status: " . json_encode($status) . "\n\n";

// ทดสอบคิวรี่ DB โดยตรงด้วย
echo "Testing DB query directly\n";
$hasFile = Illuminate\Support\Facades\DB::table('input_tax')
    ->where('input_tax_quote_id', $quoteId)
    ->where('input_tax_status', 'success')
    ->whereNotNull('input_tax_file')
    ->where('input_tax_file', '!=', '')
    ->get();

echo "Number of records with files: " . count($hasFile) . "\n";
if (count($hasFile) > 0) {
    echo "File records found:\n";
    foreach ($hasFile as $record) {
        echo "Record ID: {$record->input_tax_id}, File: {$record->input_tax_file}\n";
    }
} else {
    echo "No file records found.\n";
}

// ตรวจสอบว่ามีบันทึก input_tax สำหรับ QT25090717 ทั้งหมด
echo "\nAll input_tax records for $quoteId:\n";
$allRecords = Illuminate\Support\Facades\DB::table('input_tax')
    ->where('input_tax_quote_id', $quoteId)
    ->get();

echo "Total records: " . count($allRecords) . "\n";
if (count($allRecords) > 0) {
    foreach ($allRecords as $record) {
        echo "Record ID: {$record->input_tax_id}, ";
        echo "Type: {$record->input_tax_type}, ";
        echo "Status: {$record->input_tax_status}, ";
        echo "File: " . (empty($record->input_tax_file) ? "EMPTY" : $record->input_tax_file) . "\n";
    }
} else {
    echo "No records found.\n";
}

// ตรวจสอบสถานะรายการด้วย DB โดยตรง
echo "\nChecking directly from database if this quotation has status 'waiting for tax documents'\n";
$quoteModel = \App\Models\quotations\quotationModel::where('quote_id', $quoteId)->first();
if ($quoteModel) {
    // ตรวจสอบรีเลชั่นที่จำเป็น
    $hasInputTaxFile = false;
    if ($quoteModel->InputTaxVat && $quoteModel->InputTaxVat->count() > 0) {
        foreach ($quoteModel->InputTaxVat as $tax) {
            if (!empty($tax->input_tax_file)) {
                $hasInputTaxFile = true;
                break;
            }
        }
    }
    echo "Has input tax file: " . ($hasInputTaxFile ? "YES" : "NO") . "\n";
}