<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ตรวจสอบว่ามีโควตที่มีไฟล์ input_tax ประเภทโฮลเซล (type 4) อยู่จริงหรือไม่
echo "===== Finding Quotations With Valid Wholesale Tax Files (type 4) =====\n\n";

// ดึงข้อมูลโควตทั้งหมดที่สถานะเป็น success
$quotations = \App\Models\quotations\quotationModel::where('quote_status', 'success')
    ->with('InputTaxVat') // load relationship
    ->limit(50) // จำกัดจำนวนให้มากขึ้น
    ->get();

if ($quotations->isEmpty()) {
    echo "No success quotations found\n";
} else {
    echo "Found " . $quotations->count() . " success quotations to check\n\n";
    $validFileCount = 0;
    $noValidFileCount = 0;
    $noTaxRecordCount = 0;
    
    foreach ($quotations as $quote) {
        $hasWholesaleTax = false;
        $hasValidFile = false;
        
        // ตรวจสอบว่ามี InputTaxVat ประเภทโฮลเซล (type 4) หรือไม่
        if (!empty($quote->InputTaxVat) && $quote->InputTaxVat->count() > 0) {
            foreach ($quote->InputTaxVat as $taxRecord) {
                if ($taxRecord->input_tax_type == 4) { // ประเภทโฮลเซล
                    $hasWholesaleTax = true;
                    
                    // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
                    if (!empty($taxRecord->input_tax_file)) {
                        $filePath = public_path($taxRecord->input_tax_file);
                        if (file_exists($filePath)) {
                            $hasValidFile = true;
                            break;
                        }
                    }
                }
            }
        }
        
        if (!$hasWholesaleTax) {
            $noTaxRecordCount++;
            echo "Quote without wholesale tax record: ID: {$quote->quote_id}, Number: {$quote->quote_number}\n";
            echo "  - Would be shown in report after fix: YES (no tax record to check)\n";
            echo "-----------------------------------\n";
        } else if ($hasValidFile) {
            $validFileCount++;
            echo "Quote with valid wholesale tax file: ID: {$quote->quote_id}, Number: {$quote->quote_number}\n";
            echo "  - Would be shown in report after fix: YES (has valid tax file)\n";
            echo "-----------------------------------\n";
        } else {
            $noValidFileCount++;
            echo "Quote without valid wholesale tax file: ID: {$quote->quote_id}, Number: {$quote->quote_number}\n";
            echo "  - Would be shown in report after fix: NO (waiting for tax document)\n";
            echo "-----------------------------------\n";
        }
    }
    
    echo "\nSummary:\n";
    echo "- Quotes without wholesale tax records: $noTaxRecordCount (will be shown in report)\n";
    echo "- Quotes with valid wholesale tax files: $validFileCount (will be shown in report)\n";
    echo "- Quotes without valid wholesale tax files: $noValidFileCount (will not be shown in report)\n";
    echo "- Total quotes that should be shown: " . ($noTaxRecordCount + $validFileCount) . " out of " . $quotations->count() . "\n";
}