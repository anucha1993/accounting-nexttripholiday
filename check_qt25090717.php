<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\quotations\quotationModel;
use App\Models\inputTaxModel;
use Illuminate\Support\Facades\Log;

// กำหนด quote number ที่ต้องการตรวจสอบ
$quoteNumber = 'QT25090717';

// ดึงข้อมูล quotation พร้อม relationships ที่เกี่ยวข้อง
$quote = quotationModel::where('quote_number', $quoteNumber)
    ->with([
        'quoteCheckStatus',
        'quoteLogStatus',
        'InputTaxVat',
        'checkfileInputtax',
        'quotePayments',
        'paymentWholesale',
        'quoteInvoice',
        'customer'
    ])
    ->first();

if (!$quote) {
    echo "ไม่พบโควตหมายเลข $quoteNumber!\n";
    exit;
}

echo "=== รายละเอียดโควต ===\n";
echo "ID: {$quote->quote_id}\n";
echo "หมายเลข: {$quote->quote_number}\n";
echo "สถานะ: {$quote->quote_status}\n";
echo "ยอดรวม: {$quote->quote_grand_total}\n\n";

// ตรวจสอบการชำระเงินจากลูกค้า
echo "=== การชำระเงินจากลูกค้า ===\n";
$totalPaid = 0;
$totalRefund = 0;
if ($quote->quotePayments && $quote->quotePayments->count() > 0) {
    foreach ($quote->quotePayments as $index => $payment) {
        $status = $payment->payment_status;
        $type = $payment->payment_type;
        $amount = $payment->payment_total;
        
        echo "รายการที่ #{$index}: สถานะ={$status}, ประเภท={$type}, จำนวน={$amount}\n";
        
        if ($status != 'cancel') {
            if ($type == 'refund' && !empty($payment->payment_file_path)) {
                $totalRefund += $amount;
            } elseif ($type != 'refund') {
                $totalPaid += $amount;
            }
        }
    }
}
$customerPaidNet = $totalPaid - $totalRefund;
echo "รวมการชำระ: {$totalPaid}\n";
echo "รวมการคืนเงิน: {$totalRefund}\n";
echo "ยอดสุทธิที่ลูกค้าชำระ: {$customerPaidNet}\n";
echo "ลูกค้าชำระครบหรือไม่: " . ($customerPaidNet >= $quote->quote_grand_total ? "ใช่" : "ไม่") . "\n\n";

// ตรวจสอบต้นทุนโฮลเซลล์และการชำระโฮลเซลล์
echo "=== ต้นทุนและการชำระโฮลเซลล์ ===\n";
$wholesaleCostTotal = 0;
if ($quote->InputTaxVat && $quote->InputTaxVat->count() > 0) {
    foreach ($quote->InputTaxVat as $index => $tax) {
        echo "รายการภาษี #{$index}:\n";
        echo "  - ID: {$tax->input_tax_id}\n";
        echo "  - ประเภท: {$tax->input_tax_type}\n";
        echo "  - สถานะ: " . ($tax->input_tax_status ?? 'NULL') . "\n";
        echo "  - ไฟล์: " . ($tax->input_tax_file ?: 'ไม่มีไฟล์') . "\n";
        echo "  - ยอดรวม: {$tax->input_tax_grand_total}\n";
        
        if (in_array($tax->input_tax_type, [2, 4, 5, 6, 7])) {
            $wholesaleCostTotal += $tax->input_tax_grand_total;
        }
    }
}
echo "รวมต้นทุนโฮลเซลล์: {$wholesaleCostTotal}\n";

// ตรวจสอบการชำระโฮลเซลล์
$wholesalePaid = 0;
$wholesaleRefund = 0;
if ($quote->paymentWholesale && $quote->paymentWholesale->count() > 0) {
    foreach ($quote->paymentWholesale as $index => $payment) {
        $fileName = $payment->payment_wholesale_file_name ?? '';
        $amount = $payment->payment_wholesale_total;
        $refundAmount = $payment->payment_wholesale_refund_total;
        $refundStatus = $payment->payment_wholesale_refund_status;
        
        echo "การชำระโฮลเซลล์ #{$index}: มีไฟล์=" . ($fileName ? "ใช่" : "ไม่") . ", จำนวน={$amount}\n";
        
        if (!empty($fileName)) {
            $wholesalePaid += $amount;
        }
        
        if ($refundStatus == 'success') {
            $wholesaleRefund += $refundAmount;
            echo "  การคืนเงิน: สถานะ={$refundStatus}, จำนวน={$refundAmount}\n";
        }
    }
}
$wholesalePaidNet = $wholesalePaid - $wholesaleRefund;
echo "รวมชำระโฮลเซลล์: {$wholesalePaid}\n";
echo "รวมคืนเงินโฮลเซลล์: {$wholesaleRefund}\n";
echo "ยอดสุทธิที่ชำระโฮลเซลล์: {$wholesalePaidNet}\n";
echo "ชำระโฮลเซลล์ครบหรือไม่: " . (abs($wholesalePaidNet - $wholesaleCostTotal) < 0.01 ? "ใช่" : "ไม่") . "\n\n";

// ตรวจสอบสถานะ checklist
echo "=== สถานะ checklist ===\n";
if ($quote->quoteCheckStatus) {
    echo "booking_email_status: " . ($quote->quoteCheckStatus->booking_email_status ?? 'NULL') . "\n";
    echo "quote_status: " . ($quote->quoteCheckStatus->quote_status ?? 'NULL') . "\n";
    echo "inv_status: " . ($quote->quoteCheckStatus->inv_status ?? 'NULL') . "\n";
    echo "depositslip_status: " . ($quote->quoteCheckStatus->depositslip_status ?? 'NULL') . "\n";
    echo "fullslip_status: " . ($quote->quoteCheckStatus->fullslip_status ?? 'NULL') . "\n";
    echo "passport_status: " . ($quote->quoteCheckStatus->passport_status ?? 'NULL') . "\n";
    echo "appointment_status: " . ($quote->quoteCheckStatus->appointment_status ?? 'NULL') . "\n";
    echo "wholesale_skip_status: " . ($quote->quoteCheckStatus->wholesale_skip_status ?? 'NULL') . "\n";
    echo "withholding_tax_status: " . ($quote->quoteCheckStatus->withholding_tax_status ?? 'NULL') . "\n";
    echo "wholesale_tax_status: " . ($quote->quoteCheckStatus->wholesale_tax_status ?? 'NULL') . "\n";
} else {
    echo "ไม่พบข้อมูล quoteCheckStatus\n";
}

// ตรวจสอบเอกสารภาษี
echo "\n=== เอกสารภาษี ===\n";
if ($quote->quoteLogStatus) {
    echo "input_tax_status: " . ($quote->quoteLogStatus->input_tax_status ?? 'NULL') . "\n";
    echo "input_tax_withholding_status: " . ($quote->quoteLogStatus->input_tax_withholding_status ?? 'NULL') . "\n";
} else {
    echo "ไม่พบข้อมูล quoteLogStatus\n";
}

// ตรวจสอบไฟล์ใบกำกับภาษี
echo "\ncheckfileInputtax: ";
if ($quote->checkfileInputtax) {
    echo "มี\n";
    echo "input_tax_file: " . ($quote->checkfileInputtax->input_tax_file ?: 'NULL หรือว่างเปล่า') . "\n";
} else {
    echo "ไม่มี\n";
}

// ตรวจสอบว่ามีไฟล์ใน InputTaxVat หรือไม่ (ตามโค้ดแก้ไขใหม่)
$hasInputTaxFile = false;
if ($quote->InputTaxVat) {
    foreach ($quote->InputTaxVat as $tax) {
        if (!empty($tax->input_tax_file) && $tax->input_tax_status === 'success') {
            $hasInputTaxFile = true;
            break;
        }
    }
}
echo "\nมีไฟล์ InputTaxVat ที่สมบูรณ์: " . ($hasInputTaxFile ? "ใช่" : "ไม่") . "\n";

// ทดสอบเงื่อนไขการกรองแบบใหม่
echo "\n=== ทดสอบเงื่อนไขการกรอง ===\n";

// 1. ตรวจสอบ status badge
if (function_exists('getStatusBadgeCount') && $quote->quoteCheckStatus) {
    $badgeCount = getStatusBadgeCount($quote->quoteCheckStatus, $quote);
    echo "1. จำนวน Status Badge: {$badgeCount} (ควรเป็น 0 เพื่อให้ผ่าน)\n";
    if ($badgeCount > 0) {
        echo "   ไม่ผ่าน: โควตมีรายการสถานะที่ยังไม่สมบูรณ์\n";
    }
}

// 2. ตรวจสอบว่ารอเอกสารภาษีหรือไม่
if (function_exists('isWaitingForTaxDocuments')) {
    $waitingForTax = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
    echo "2. รอเอกสารภาษี: " . ($waitingForTax ? "ใช่" : "ไม่") . " (ควรเป็น 'ไม่' เพื่อให้ผ่าน)\n";
    if ($waitingForTax) {
        echo "   ไม่ผ่าน: โควตยังรอเอกสารภาษี\n";
    }
}

// 3. ตรวจสอบเงื่อนไขตามโค้ดที่แก้ไขใหม่
echo "\n3. เงื่อนไขที่แก้ไขใหม่:\n";
// เช็คสถานะ wholesale_tax_status
$wholesaleTaxStatus = isset($quote->quoteCheckStatus) ? $quote->quoteCheckStatus->wholesale_tax_status : null;
$isWaitingByStatus = is_null($wholesaleTaxStatus) || trim($wholesaleTaxStatus ?? '') !== 'ได้รับแล้ว';
echo "  - สถานะ wholesale_tax_status: {$wholesaleTaxStatus}\n";
echo "  - รอตามสถานะ: " . ($isWaitingByStatus ? "ใช่" : "ไม่") . "\n";
echo "  - มีไฟล์ที่สมบูรณ์: " . ($hasInputTaxFile ? "ใช่" : "ไม่") . "\n";

// เงื่อนไขรวม (แก้ไขใหม่)
$isWaitingByUpdatedCondition = $isWaitingByStatus && !$hasInputTaxFile;
echo "  - ผลเงื่อนไขรวม (แก้ไขใหม่): " . ($isWaitingByUpdatedCondition ? "รอเอกสารภาษี" : "ไม่รอเอกสารภาษี") . "\n";

// เงื่อนไขเดิมก่อนแก้ไข
$isWaitingByOldCondition = $isWaitingByStatus || !$hasInputTaxFile;
echo "  - ผลเงื่อนไขรวม (เดิมก่อนแก้ไข): " . ($isWaitingByOldCondition ? "รอเอกสารภาษี" : "ไม่รอเอกสารภาษี") . "\n";

echo "\n4. สรุป: โควตนี้ " . ($isWaitingByUpdatedCondition ? "ไม่ควร" : "ควร") . " แสดงในรายงานยอดขาย ตามเงื่อนไขที่แก้ไขแล้ว\n";