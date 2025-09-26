<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ตรวจสอบข้อมูลใน quotation และสถานะของแต่ละรายการ
echo "===== Checking Quotation Status for All Records =====\n\n";

// ดึงข้อมูลโควตทั้งหมดที่สถานะเป็น success
$quotations = \App\Models\quotations\quotationModel::where('quote_status', 'success')
    ->limit(10) // จำกัดจำนวนเพื่อไม่ให้มากเกินไป
    ->get();

if ($quotations->isEmpty()) {
    echo "No success quotations found\n";
} else {
    echo "Found " . $quotations->count() . " success quotations\n\n";
    
    // Load helper functions
    require_once __DIR__ . '/app/Helpers/statusWhosaleInputTaxHelper.php';
    
    foreach ($quotations as $quote) {
        echo "Quote ID: {$quote->quote_id}, Number: {$quote->quote_number}\n";
        
        // ตรวจสอบสถานะ
        $status = getStatusWhosaleInputTax($quote->quote_number);
        $statusText = (strpos($status, 'รอใบกำกับภาษีโฮลเซลล์') !== false) ? 
            "รอใบกำกับภาษีโฮลเซลล์" : "ได้รับใบกำกับโฮลเซลแล้ว";
            
        echo "Status: $statusText\n";
        
        // ตรวจสอบว่ามีข้อมูล input_tax หรือไม่
        $taxRecords = \Illuminate\Support\Facades\DB::table('input_tax')
            ->where('input_tax_quote_number', $quote->quote_number)
            ->where('input_tax_type', 4) // ประเภทโฮลเซล
            ->get();
            
        echo "Input tax records (type 4): " . $taxRecords->count() . "\n";
        
        // ตรวจสอบว่ามีไฟล์อยู่จริงหรือไม่
        $hasFile = false;
        foreach ($taxRecords as $record) {
            if (!empty($record->input_tax_file)) {
                $filePath = public_path($record->input_tax_file);
                $fileExists = file_exists($filePath);
                $hasFile = $hasFile || $fileExists;
                echo "  - File: {$record->input_tax_file}, Exists: " . ($fileExists ? "YES" : "NO") . "\n";
            }
        }
        
        echo "Has valid file: " . ($hasFile ? "YES" : "NO") . "\n";
        echo "Would be shown in report: " . (strpos($status, 'รอใบกำกับภาษีโฮลเซลล์') === false ? "YES" : "NO") . "\n";
        echo "-----------------------------------\n";
    }
}