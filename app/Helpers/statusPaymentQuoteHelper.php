<?php
use Carbon\Carbon;

if (!function_exists('getQuoteStatusQuotePayment')) {
    function getQuoteStatusQuotePayment($quotationModel)
    {
        $totalPaid = $quotationModel->total;

        $refundPayments = $quotationModel->quotePayments->where('payment_type', 'refund');
        $Total_trade =  $quotationModel->GetDeposit() - $quotationModel->Refund();

        // รวมยอด refund success เป็นบวก
        $refundSuccessTotal = $refundPayments
            ->whereNotNull('payment_file_path')
            ->sum(fn($p) => abs($p->payment_total));

        // ตรวจสอบว่ามี refund ที่ยังรออยู่หรือไม่
        $hasPendingRefund = $refundPayments
            ->whereNull('payment_file_path')
            ->count() > 0;

        // รวมยอด refund ที่ wait เป็นบวก
        $refundWaitTotal = $refundPayments
            ->whereNull('payment_file_path')
            ->sum(fn($p) => abs($p->payment_total));

        if ($quotationModel->quote_status === 'cancel') {
            if ($totalPaid <= 0) {
                return '';
            }

            if ($Total_trade <= 0) {
                return '<span class="badge rounded-pill bg-success">คืนเงินให้ลูกค้าแล้ว</span>';
            }

            if ($hasPendingRefund) {
                return '<span class="badge rounded-pill bg-warning text-dark">รอคืนเงินลูกค้า</span>';
            }

            return '<span class="badge rounded-pill bg-danger">ยังไม่ได้คืนเงินลูกค้า</span>';
        } 
        else {
            // ถ้ามี pending refund อยู่ → แสดง "รอคืนเงินบางส่วน" เสมอ
            if ($hasPendingRefund) {
                return '<span class="badge rounded-pill bg-warning text-dark">รอคืนเงินลูกค้า</span>';
            }

            // ไม่มี pending แล้วแต่เคยคืนบางส่วน → "คืนเงินบางส่วนแล้ว"
            if ($refundSuccessTotal > 0) {
                return '<span class="badge rounded-pill bg-success">คืนเงินให้ลูกค้าแล้ว</span>';
            }
        }

        return '';
    }
}
