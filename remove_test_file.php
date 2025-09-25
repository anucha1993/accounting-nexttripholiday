<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ทดสอบกับ QT25090717
$quoteId = 'QT25090717';

echo "Force 'รอใบกำกับภาษีโฮลเซลล์' status for $quoteId\n\n";

// ลบไฟล์ทดสอบเพื่อให้กลับไปที่สถานะเดิม
$filePath = public_path('704/inputtax/QT25090717/QT25090717_inputtax_68ca32f3e76a0.pdf');
if (file_exists($filePath)) {
    unlink($filePath);
    echo "Removed test file: $filePath\n";
}

echo "\nTest status again after file removal:\n";

// ทดสอบฟังก์ชัน getStatusWhosaleInputTax
require_once __DIR__ . '/app/Helpers/statusWhosaleInputTaxHelper.php';
$status = getStatusWhosaleInputTax($quoteId);
echo "Status: " . ($status ?: "EMPTY") . "\n";

// ทดสอบฟังก์ชัน isWaitingForTaxDocuments
require_once __DIR__ . '/app/Helpers/statusCheckListHelper.php';
$quote = \App\Models\quotations\quotationModel::where('quote_number', $quoteId)->first();
$isWaiting = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
echo "Is waiting for tax documents: " . ($isWaiting ? "YES" : "NO") . "\n";