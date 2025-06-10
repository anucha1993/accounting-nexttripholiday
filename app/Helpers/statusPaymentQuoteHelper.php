<?php
use Carbon\Carbon;

if (!function_exists('getQuoteStatusQuotePayment')) {
    function getQuoteStatusQuotePayment($quotationModel)
    {
        $status = '';
        $totalPaid = $quotationModel->getTotalAttribute(); // ยอดที่ลูกค้าจ่ายทั้งหมด

        $refundPayments = $quotationModel->quotePayments->where('payment_type', 'refund');

        // ✅ รวมยอด refund ที่ success แล้ว โดยแปลงให้เป็นบวก
        $refundSuccessTotal = $refundPayments
            ->where('payment_status', 'success')
            ->sum(function ($payment) {
                return abs($payment->payment_total);
            });

        // ✅ รวมยอด refund ที่ยังรอคืน โดยแปลงให้เป็นบวก
        $refundWaitTotal = $refundPayments
            ->where('payment_status', 'wait')
            ->sum(function ($payment) {
                return abs($payment->payment_total);
            });

        // ✅ สถานะยกเลิก
        if ($quotationModel->quote_status === 'cancel') {
            if ($totalPaid <= 0) {
                return '';
            }

            if ($refundSuccessTotal >= $totalPaid) {
                return '<span class="badge rounded-pill bg-success">คืนเงินลูกค้าแล้ว</span>';
            }

            if ($refundWaitTotal > 0) {
                return '<span class="badge rounded-pill bg-warning text-dark">รอคืนเงินลูกค้า</span>';
            }

            return '<span class="badge rounded-pill bg-danger">ยังไม่ได้คืนเงินลูกค้า</span>';
        } else {
            // ✅ กรณียังไม่ยกเลิก
            if ($refundWaitTotal > 0) {
                return '<span class="badge rounded-pill bg-warning text-dark">รอคืนเงินบางส่วน</span>';
            }

            if ($refundSuccessTotal > $totalPaid && $totalPaid > 0) {
                //  return $refundSuccessTotal.'-'.$totalPaid;
                return '<span class="badge rounded-pill bg-success">คืนเงินบางส่วนแล้ว</span>';
            }
             if ($refundSuccessTotal > $totalPaid && $totalPaid <= 0) {
                //  return $refundSuccessTotal.'-'.$totalPaid;
                return '<span class="badge rounded-pill bg-warning">รอคืนเงินบางส่วน</span>';
            }
        }
        // return $refundSuccessTotal;

        return '<span class="badge rounded-pill bg-secondary">ไม่มียอดคืน</span>';
    }
}


