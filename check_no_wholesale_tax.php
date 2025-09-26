<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ตรวจสอบว่ามีโควตที่ไม่มีข้อมูล input_tax ประเภทโฮลเซล (type 4) หรือไม่
echo "===== Finding Quotations Without Wholesale Tax (type 4) =====\n\n";

// ดึงข้อมูลโควตทั้งหมดที่สถานะเป็น success
$quotations = \App\Models\quotations\quotationModel::where('quote_status', 'success')
    ->with('InputTaxVat') // load relationship
    ->limit(20) // จำกัดจำนวน
    ->get();

if ($quotations->isEmpty()) {
    echo "No success quotations found\n";
} else {
    echo "Found " . $quotations->count() . " success quotations to check\n\n";
    $noWholesaleTaxCount = 0;
    
    foreach ($quotations as $quote) {
        $hasWholesaleTax = false;
        
        // ตรวจสอบว่ามี InputTaxVat ประเภทโฮลเซล (type 4) หรือไม่
        if (!empty($quote->InputTaxVat) && $quote->InputTaxVat->count() > 0) {
            foreach ($quote->InputTaxVat as $taxRecord) {
                if ($taxRecord->input_tax_type == 4) { // ประเภทโฮลเซล
                    $hasWholesaleTax = true;
                    break;
                }
            }
        }
        
        if (!$hasWholesaleTax) {
            $noWholesaleTaxCount++;
            echo "Quote without wholesale tax: ID: {$quote->quote_id}, Number: {$quote->quote_number}\n";
            
            // ตรวจสอบข้อมูลอื่นๆ
            echo "  - Quote Status: {$quote->quote_status}\n";
            echo "  - Payment: {$quote->payment}\n";
            echo "  - Grand Total: {$quote->quote_grand_total}\n";
            echo "  - Would be shown in report after fix: YES\n";
            echo "-----------------------------------\n";
        }
    }
    
    echo "\nFound $noWholesaleTaxCount quotations without wholesale tax (type 4)\n";
}