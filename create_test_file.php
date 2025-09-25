<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ล้าง cache
\Illuminate\Support\Facades\Artisan::call('cache:clear');

// โค้ดทดสอบเฉพาะ QT25090717
$quoteId = 'QT25090717';

// ลองแก้ไขโดยเพิ่มไฟล์ทดสอบ
echo "Attempting to create a test file for $quoteId\n";

// สร้างโฟลเดอร์ถ้ายังไม่มี
$folderPath = public_path('704/inputtax/QT25090717');
if (!file_exists($folderPath)) {
    mkdir($folderPath, 0777, true);
    echo "Created directory: $folderPath\n";
}

// สร้างไฟล์ทดสอบ
$filePath = public_path('704/inputtax/QT25090717/QT25090717_inputtax_68ca32f3e76a0.pdf');
if (!file_exists($filePath)) {
    // สร้างไฟล์ PDF จำลอง
    $testContent = '%PDF-1.4
1 0 obj
<< /Type /Catalog /Pages 2 0 R >>
endobj
2 0 obj
<< /Type /Pages /Kids [3 0 R] /Count 1 >>
endobj
3 0 obj
<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R >>
endobj
4 0 obj
<< /Length 44 >>
stream
BT /F1 12 Tf 100 700 Td (Test PDF file) Tj ET
endstream
endobj
trailer
<< /Root 1 0 R /Size 4 >>
%%EOF';

    file_put_contents($filePath, $testContent);
    echo "Created test file: $filePath\n";
} else {
    echo "File already exists: $filePath\n";
}

// ทดสอบอีกครั้งหลังสร้างไฟล์
echo "\nTesting again after file creation\n";
echo "==============================\n\n";

// ดึงข้อมูล quote
$quote = \App\Models\quotations\quotationModel::where('quote_number', $quoteId)->first();
if (!$quote) {
    echo "Quote not found\n";
    exit;
}

// ตรวจสอบไฟล์ใน InputTaxVat อีกครั้ง
echo "Checking InputTaxVat file status again\n";
$hasRealFile = false;

if ($quote->InputTaxVat && $quote->InputTaxVat->count() > 0) {
    foreach ($quote->InputTaxVat as $taxRecord) {
        echo "- Record ID: {$taxRecord->input_tax_id}, ";
        echo "Type: {$taxRecord->input_tax_type}, ";
        echo "Status: {$taxRecord->input_tax_status}, ";
        echo "File: " . (empty($taxRecord->input_tax_file) ? "EMPTY" : $taxRecord->input_tax_file) . "\n";
        
        if (!empty($taxRecord->input_tax_file) 
            && $taxRecord->input_tax_status === 'success'
            && $taxRecord->input_tax_type == 4) {
            
            // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
            $filePath = public_path($taxRecord->input_tax_file);
            $fileExists = file_exists($filePath);
            echo "  File exists: " . ($fileExists ? "YES" : "NO") . "\n";
            
            if ($fileExists) {
                $hasRealFile = true;
            }
        }
    }
}

echo "\nHas real file with type 4: " . ($hasRealFile ? "YES" : "NO") . "\n";

// ทดสอบฟังก์ชัน isWaitingForTaxDocuments โดยตรงอีกครั้ง
echo "\nTesting isWaitingForTaxDocuments function again\n";
$isWaiting = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
echo "isWaitingForTaxDocuments result: " . ($isWaiting ? "YES (filtered out)" : "NO (shown in report)") . "\n";

echo "\nExpected filtering result: " . (!$hasRealFile ? "FILTERED OUT (hidden)" : "INCLUDED (shown)") . "\n";