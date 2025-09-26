<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// เคลียร์ cache ก่อน
\Illuminate\Support\Facades\Artisan::call('cache:clear');
echo "Cache cleared\n\n";

// ตรวจสอบว่ามีโควตที่มีข้อมูล input_tax ประเภทโฮลเซล (type 4) ที่ระบุ path หรือไม่
echo "===== Testing Updated Logic for Wholesale Tax Files =====\n\n";

// ดึงข้อมูลโควตทั้งหมดที่สถานะเป็น success
$quotations = \App\Models\quotations\quotationModel::where('quote_status', 'success')
    ->with('InputTaxVat') // load relationship
    ->limit(10) // จำกัดจำนวน
    ->get();

if ($quotations->isEmpty()) {
    echo "No success quotations found\n";
} else {
    echo "Found " . $quotations->count() . " success quotations to check\n\n";
    
    // Load helper functions
    require_once __DIR__ . '/app/Helpers/statusWhosaleInputTaxHelper.php';
    
    foreach ($quotations as $quote) {
        echo "Quote ID: {$quote->quote_id}, Number: {$quote->quote_number}\n";
        
        // ตรวจสอบว่ามี InputTaxVat ประเภทโฮลเซล (type 4) หรือไม่
        $hasWholesaleTax = false;
        $hasPathInDB = false;
        
        if (!empty($quote->InputTaxVat) && $quote->InputTaxVat->count() > 0) {
            foreach ($quote->InputTaxVat as $taxRecord) {
                if ($taxRecord->input_tax_type == 4) { // ประเภทโฮลเซล
                    $hasWholesaleTax = true;
                    
                    // เช็คว่ามีการระบุ path ไฟล์หรือไม่
                    if (!empty($taxRecord->input_tax_file)) {
                        $hasPathInDB = true;
                        echo "  - Has path in DB: YES - {$taxRecord->input_tax_file}\n";
                        break;
                    }
                }
            }
        }
        
        if (!$hasWholesaleTax) {
            echo "  - Has wholesale tax record: NO\n";
        } else if (!$hasPathInDB) {
            echo "  - Has wholesale tax record: YES, but no path in DB\n";
        }
        
        // เรียกใช้ getStatusWhosaleInputTax เพื่อตรวจสอบสถานะ
        $status = getStatusWhosaleInputTax($quote->quote_number);
        $statusText = (strpos($status, 'รอใบกำกับภาษีโฮลเซลล์') !== false) ? 
            "รอใบกำกับภาษีโฮลเซลล์" : "ได้รับใบกำกับโฮลเซลแล้ว";
        
        echo "  - Status from getStatusWhosaleInputTax: $statusText\n";
        echo "  - Would be shown in report: " . ($statusText == "ได้รับใบกำกับโฮลเซลแล้ว" ? "YES" : "NO") . "\n";
        echo "-----------------------------------\n";
    }
}