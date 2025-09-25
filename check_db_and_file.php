<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ทดสอบกับ QT25090717
$quoteId = 'QT25090717';

echo "Checking database records for $quoteId\n\n";

// ตรวจสอบข้อมูลในฐานข้อมูล
$records = \Illuminate\Support\Facades\DB::table('input_tax')
    ->where('input_tax_quote_number', $quoteId)
    ->where('input_tax_status', 'success')
    ->get();

if ($records->isEmpty()) {
    echo "No records found for $quoteId\n";
} else {
    echo "Found " . $records->count() . " records:\n";
    foreach ($records as $record) {
        echo "ID: {$record->input_tax_id}, File: {$record->input_tax_file}, Type: {$record->input_tax_type}\n";
        
        if (!empty($record->input_tax_file)) {
            $filePath = public_path($record->input_tax_file);
            echo "  - File exists: " . (file_exists($filePath) ? "YES" : "NO") . "\n";
            echo "  - Full path: $filePath\n";
        }
    }
}

// ทดสอบสร้างไฟล์ใหม่
$filePath = public_path('704/inputtax/QT25090717/QT25090717_inputtax_test.pdf');
$dirPath = dirname($filePath);
if (!is_dir($dirPath)) {
    mkdir($dirPath, 0777, true);
}
file_put_contents($filePath, "TEST FILE");
echo "\nCreated test file: $filePath\n";

echo "\nAfter creating new test file:\n";
// ทดสอบฟังก์ชัน getStatusWhosaleInputTax
require_once __DIR__ . '/app/Helpers/statusWhosaleInputTaxHelper.php';
$status = getStatusWhosaleInputTax($quoteId);
echo "Status: " . ($status ?: "EMPTY") . "\n";

// ทดสอบฟังก์ชัน isWaitingForTaxDocuments
require_once __DIR__ . '/app/Helpers/statusCheckListHelper.php';
$quote = \App\Models\quotations\quotationModel::where('quote_number', $quoteId)->first();
$isWaiting = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
echo "Is waiting for tax documents: " . ($isWaiting ? "YES" : "NO") . "\n";