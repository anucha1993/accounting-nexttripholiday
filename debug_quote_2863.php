<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\quotations\quotationModel;

$quoteId = 2863;
$quotation = quotationModel::find($quoteId);

if (!$quotation) {
    echo "ไม่พบ Quote ID: {$quoteId}\n";
    exit;
}

echo "=== DEBUG Quote ID: {$quoteId} ===\n\n";

// 1. ยอดที่โอนไปยังโฮลเซลล์
$depositTotal = $quotation->GetDepositWholesale();
echo "1. depositTotal (ยอดที่โอนให้โฮลเซลล์): {$depositTotal}\n";

// 2. ยอดที่โฮลเซลล์คืนกลับมาแล้ว (refund สำเร็จ)
$refundSuccessTotal = $quotation
    ->paymentWholesale()
    ->where('payment_wholesale_refund_status', 'success')
    ->get()
    ->sum(function ($row) {
        return abs($row->payment_wholesale_refund_total);
    });
echo "2. refundSuccessTotal (ยอดคืนสำเร็จ): {$refundSuccessTotal}\n";

// 3. ยอดที่ยังรอคืน (refund ยังไม่ success)
$refundPendingTotal = $quotation
    ->paymentWholesale()
    ->where('payment_wholesale_refund_status', '!=', 'success')
    ->get()
    ->sum(function ($row) {
        return abs($row->payment_wholesale_refund_total);
    });
echo "3. refundPendingTotal (ยอดรอคืน): {$refundPendingTotal}\n";

// 4. ต้นทุนโฮลเซลล์
$wholesaleCost = $quotation->inputtaxTotalWholesale() ?? 0;
echo "4. wholesaleCost (ต้นทุนโฮลเซลล์): {$wholesaleCost}\n";

// 5. ยอดที่ลูกค้าชำระมาแล้ว
$customerPaid = $quotation->GetDeposit() ?? 0;
echo "5. customerPaid (ยอดลูกค้าชำระ): {$customerPaid}\n\n";

echo "=== การตรวจสอบเงื่อนไข ===\n";
echo "depositTotal > 0: " . ($depositTotal > 0 ? 'YES' : 'NO') . "\n";
echo "refundSuccessTotal + refundPendingTotal > 0: " . (($refundSuccessTotal + $refundPendingTotal) > 0 ? 'YES' : 'NO') . "\n";
echo "refundPendingTotal > 0: " . ($refundPendingTotal > 0 ? 'YES' : 'NO') . "\n";
echo "depositTotal > wholesaleCost: " . ($depositTotal > $wholesaleCost ? 'YES' : 'NO') . "\n";
echo "depositTotal < wholesaleCost: " . ($depositTotal < $wholesaleCost ? 'YES' : 'NO') . "\n";
echo "abs(depositTotal - wholesaleCost): " . abs($depositTotal - $wholesaleCost) . "\n\n";

// แสดงรายการ payment wholesale
echo "=== รายการ Payment Wholesale ===\n";
$payments = $quotation->paymentWholesale()->get();
foreach ($payments as $payment) {
    echo "ID: {$payment->payment_wholesale_id}\n";
    echo "  Amount: {$payment->payment_wholesale_total}\n";
    echo "  Refund Status: {$payment->payment_wholesale_refund_status}\n";
    echo "  Refund Total: {$payment->payment_wholesale_refund_total}\n";
    echo "  Date: {$payment->payment_wholesale_date}\n\n";
}

echo "=== สถานะที่ควรแสดง ===\n";
$status = getStatusPaymentWhosale($quotation);
echo "Status: {$status}\n";
