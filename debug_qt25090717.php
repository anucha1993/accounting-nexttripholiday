<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ดูข้อมูลเฉพาะ QT25090717
$quoteId = 'QT25090717';
echo "Detailed analysis for $quoteId\n";
echo "================================\n\n";

// ตรวจสอบข้อมูลในตาราง input_tax
echo "1. INPUT_TAX RECORDS:\n";
$records = \Illuminate\Support\Facades\DB::table('input_tax')
    ->where('input_tax_quote_number', $quoteId)
    ->get();

foreach ($records as $record) {
    echo "ID: {$record->input_tax_id}\n";
    echo "Type: {$record->input_tax_type}\n";
    echo "Status: {$record->input_tax_status}\n";
    echo "File: " . (empty($record->input_tax_file) ? "EMPTY" : $record->input_tax_file) . "\n";
    echo "Withholding: {$record->input_tax_withholding}\n";
    echo "VAT: {$record->input_tax_vat}\n";
    echo "Grand Total: {$record->input_tax_grand_total}\n";
    echo "Wholesale: {$record->input_tax_wholesale}\n";
    echo "----------------------------\n";
}

// ดูข้อมูลในตาราง quotation
echo "\n2. QUOTATION RECORD:\n";
$quote = \App\Models\quotations\quotationModel::where('quote_number', $quoteId)->first();
if ($quote) {
    echo "Quote ID: {$quote->quote_id}\n";
    echo "Quote Number: {$quote->quote_number}\n";
    echo "Quote Status: {$quote->quote_status}\n";
    echo "Quote Grand Total: {$quote->quote_grand_total}\n";
    echo "Quote Wholesale: {$quote->quote_wholesale}\n";
    
    // ตรวจสอบสถานะใบกำกับภาษี
    echo "\n3. TAX STATUS CHECK:\n";
    $wholesaleTaxStatus = isset($quote->quoteCheckStatus) ? 
        $quote->quoteCheckStatus->wholesale_tax_status : null;
    echo "Wholesale Tax Status: " . ($wholesaleTaxStatus ?: "NULL") . "\n";
    
    // ตรวจสอบการมีไฟล์
    $hasInputTaxFile = false;
    if ($quote->InputTaxVat) {
        foreach ($quote->InputTaxVat as $taxRecord) {
            if (!empty($taxRecord->input_tax_file) && $taxRecord->input_tax_status === 'success') {
                $hasInputTaxFile = true;
                break;
            }
        }
    }
    echo "Has Input Tax File: " . ($hasInputTaxFile ? "YES" : "NO") . "\n";
    
    // ตรวจสอบเงื่อนไขการกรอง
    $isWaiting = (is_null($wholesaleTaxStatus) || 
                 trim($wholesaleTaxStatus) !== 'ได้รับแล้ว') && 
                 !$hasInputTaxFile;
    echo "Is Waiting for Tax Documents: " . ($isWaiting ? "YES (should be filtered)" : "NO (should be shown)") . "\n";
    
    // ทดสอบฟังก์ชันที่เราแก้ไข
    echo "\n4. TESTING HELPER FUNCTIONS:\n";
    require_once __DIR__ . '/app/Helpers/statusWhosaleInputTaxHelper.php';
    require_once __DIR__ . '/app/Helpers/statusCheckListHelper.php';
    
    echo "getStatusWhosaleInputTax result: " . getStatusWhosaleInputTax($quoteId) . "\n";
    
    // ตรวจสอบเพิ่มเติมว่ามีการใช้ isWaitingForTaxDocuments
    echo "\n5. ADDITIONAL CHECKS:\n";
    try {
        if (function_exists('isWaitingForTaxDocuments')) {
            if (isset($quote->quoteLogStatus)) {
                $isWaitingCheck = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
                echo "isWaitingForTaxDocuments result: " . ($isWaitingCheck ? "YES (waiting)" : "NO (not waiting)") . "\n";
            } else {
                echo "quoteLogStatus is not available for isWaitingForTaxDocuments check\n";
            }
        } else {
            echo "isWaitingForTaxDocuments function not found\n";
        }
    } catch (\Exception $e) {
        echo "Error checking isWaitingForTaxDocuments: " . $e->getMessage() . "\n";
    }
    
    // ตรวจสอบ LogStatus ของ Quote
    echo "\n6. QUOTE LOG STATUS:\n";
    if (isset($quote->quoteLogStatus)) {
        $logStatus = $quote->quoteLogStatus;
        echo "Log Status ID: {$logStatus->id}\n";
        echo "Status Fields:\n";
        $statusFields = [
            'wholesale_tax_status', 'deposit_status', 'payment_status', 'ticket_status',
            'invoice_status', 'booking_status', 'booking_email_status', 'supplier_payment_status'
        ];
        foreach ($statusFields as $field) {
            echo "  {$field}: " . ($logStatus->$field ?? "NULL") . "\n";
        }
    } else {
        echo "No quoteLogStatus found\n";
    }
    
} else {
    echo "Quote not found\n";
}

// ตรวจสอบเงื่อนไขเฉพาะใน QuotationFilterService
echo "\n7. FILTER SERVICE SIMULATION:\n";
try {
    $filterService = new \App\Services\QuotationFilterService();
    
    // จำลองการตัดสินใจของ filter callback
    if (!$quote) {
        echo "Cannot simulate filter: Quote not found\n";
        exit;
    }
    
    // สร้าง request จำลอง
    $request = new \Illuminate\Http\Request();
    
    // ตรวจสอบสถานะการรอใบกำกับภาษีโดยตรง
    $wholesaleTaxStatus = isset($quote->quoteCheckStatus) ? 
        $quote->quoteCheckStatus->wholesale_tax_status : null;
        
    // ตรวจสอบไฟล์
    $hasInputTaxFile = false;
    if ($quote->InputTaxVat) {
        foreach ($quote->InputTaxVat as $taxRecord) {
            if (!empty($taxRecord->input_tax_file) && $taxRecord->input_tax_status === 'success') {
                $hasInputTaxFile = true;
                break;
            }
        }
    }
    
    // เงื่อนไขการกรองจาก QuotationFilterService
    $isWaiting = (is_null($wholesaleTaxStatus) || 
                 trim($wholesaleTaxStatus) !== 'ได้รับแล้ว') && 
                 !$hasInputTaxFile;
                 
    echo "Filter condition result: " . ($isWaiting ? 
        "FILTERED OUT (ยังรอใบกำกับภาษีโฮลเซลล์)" : 
        "INCLUDED (มีใบกำกับภาษีโฮลเซลล์แล้ว)") . "\n";
    
    // ทดสอบ isWaitingForTaxDocuments
    if (function_exists('isWaitingForTaxDocuments') && isset($quote->quoteLogStatus)) {
        $waitingForTax = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
        echo "isWaitingForTaxDocuments result: " . ($waitingForTax ? 
            "FILTERED OUT (ยังรอเอกสารภาษี)" : 
            "INCLUDED (มีเอกสารภาษีครบแล้ว)") . "\n";
    }
    
} catch (\Exception $e) {
    echo "Error simulating filter: " . $e->getMessage() . "\n";
}

// ตรวจสอบฟิลด์และคอลัมน์ของ input_tax_file และ input_tax_status ด้วยวิธีพิเศษ
echo "\n8. DETAILED FILE CHECK:\n";
try {
    $hasMissingFile = \Illuminate\Support\Facades\DB::table('input_tax')
        ->where('input_tax_quote_number', $quoteId)
        ->where(function($query) {
            $query->whereNull('input_tax_file')
                ->orWhere('input_tax_file', '');
        })
        ->where('input_tax_status', 'success')
        ->exists();
    
    echo "Has records with MISSING file but SUCCESS status: " . ($hasMissingFile ? "YES" : "NO") . "\n";
    
    $hasActualFile = \Illuminate\Support\Facades\DB::table('input_tax')
        ->where('input_tax_quote_number', $quoteId)
        ->whereNotNull('input_tax_file')
        ->where('input_tax_file', '!=', '')
        ->where('input_tax_status', 'success')
        ->count();
    
    echo "Number of records with actual files and SUCCESS status: {$hasActualFile}\n";
    
    // ตรวจสอบรายละเอียดเพิ่มเติม
    $fileRecords = \Illuminate\Support\Facades\DB::table('input_tax')
        ->where('input_tax_quote_number', $quoteId)
        ->whereNotNull('input_tax_file')
        ->where('input_tax_file', '!=', '')
        ->get();
    
    foreach ($fileRecords as $record) {
        echo "ID: {$record->input_tax_id}, File: {$record->input_tax_file}, Status: {$record->input_tax_status}\n";
        
        // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
        $filePath = public_path($record->input_tax_file);
        $fileExists = file_exists($filePath);
        echo "  Physical file exists: " . ($fileExists ? "YES" : "NO") . "\n";
    }
    
} catch (\Exception $e) {
    echo "Error in detailed file check: " . $e->getMessage() . "\n";
}

// ดูว่ามีปัญหาเกี่ยวกับ cache หรือไม่
echo "\n9. CACHE CHECK:\n";
try {
    // ลอง clear ค่า _cached หรือคำนวนใหม่
    if ($quote) {
        $customerPaid = isset($quote->_cached_deposit) ? 
            $quote->_cached_deposit - $quote->_cached_refund : 
            "Unknown (cache not available)";
        echo "Customer paid: {$customerPaid}\n";
        
        $inputtaxTotal = isset($quote->_cached_inputtax_total) ? 
            $quote->_cached_inputtax_total : 
            "Unknown (cache not available)";
        echo "Input tax total: {$inputtaxTotal}\n";
        
        $countPayment = isset($quote->_cached_wholesale_payment_count) ? 
            $quote->_cached_wholesale_payment_count : 
            "Unknown (cache not available)";
        echo "Wholesale payment count: {$countPayment}\n";
        
        $wholesalePaidNet = isset($quote->_cached_wholesale_paid) ? 
            $quote->_cached_wholesale_paid - $quote->_cached_wholesale_refund : 
            "Unknown (cache not available)";
        echo "Wholesale paid net: {$wholesalePaidNet}\n";
    }
} catch (\Exception $e) {
    echo "Error checking cache: " . $e->getMessage() . "\n";
}