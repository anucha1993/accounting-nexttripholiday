<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "===== Final Test for QT25090717 =====\n\n";

// ตรวจสอบสถานะ getStatusWhosaleInputTax
require_once __DIR__ . '/app/Helpers/statusWhosaleInputTaxHelper.php';
$status = getStatusWhosaleInputTax('QT25090717');
echo "Status display: " . $status . "\n";

// ตรวจสอบฟังก์ชัน isWaitingForTaxDocuments
require_once __DIR__ . '/app/Helpers/statusCheckListHelper.php';
$quote = \App\Models\quotations\quotationModel::where('quote_number', 'QT25090717')->first();
$isWaiting = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
echo "Is waiting for tax documents: " . ($isWaiting ? "YES" : "NO") . "\n";

// จำลองการกรองของ QuotationFilterService.php
if ($isWaiting) {
    echo "\nResult: QT25090717 will NOT appear in sales reports (FILTERED OUT) ✓\n";
} else {
    echo "\nResult: QT25090717 will appear in sales reports (INCLUDED) ✗\n";
}

// ทดสอบกับรหัสโควตอื่น
echo "\n===== Testing with another quote number =====\n";
// ดึงโควตอื่นมาทดสอบ
$otherQuote = \App\Models\quotations\quotationModel::where('quote_number', '!=', 'QT25090717')
    ->where('quote_status', 'success')
    ->first();

if ($otherQuote) {
    echo "Testing with quote: {$otherQuote->quote_number}\n";
    
    $status = getStatusWhosaleInputTax($otherQuote->quote_number);
    echo "Status display: " . $status . "\n";
    
    $isWaiting = isWaitingForTaxDocuments($otherQuote->quoteLogStatus, $otherQuote);
    echo "Is waiting for tax documents: " . ($isWaiting ? "YES" : "NO") . "\n";
}