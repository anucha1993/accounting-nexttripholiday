<?php

use Carbon\Carbon;

if (!function_exists('getQuoteStatusQuotePayment')) {
    function getQuoteStatusQuotePayment($quotationModel)
    {
        $total = $quotationModel->total;                 // ยอดที่ลูกค้าชำระเข้ามา
        $refund = $quotationModel->refund_total;         // ยอดที่คืนลูกค้าแล้ว
        $refundPayments = $quotationModel->quotePayments
            ->where('payment_type', 'refund');

        $hasPendingRefund = $refundPayments
            ->where('payment_status', '!=', 'success')
            ->count() > 0;

        $hasSuccessRefund = $refundPayments
            ->where('payment_status', 'success')
            ->count() > 0;

        // กรณีใบเสนอราคาโดนยกเลิก
        if ($quotationModel->quote_status === 'cancel') {
            // ไม่มีการชำระเงินเลย
            if ($total <= 0) {
                return '';
            }

            // มีการชำระเงิน แต่ยังไม่ได้คืน
            if ($total > 0 && $refund < $total) {
                return '<span class="badge rounded-pill bg-warning text-dark">รอคืนเงินลูกค้า</span>';
            }

            // คืนเงินครบแล้ว
            if ($total > 0 && $refund >= $total) {
                return '<span class="badge rounded-pill bg-success">คืนเงินลูกค้าแล้ว</span>';
            }
        }

        // กรณีใบเสนอราคายังไม่ถูกยกเลิก
        if ($quotationModel->quote_status !== 'cancel') {
            if ($hasPendingRefund) {
                return '<span class="badge rounded-pill bg-warning text-dark">รอคืนเงินบางส่วน</span>';
            }

            if ($hasSuccessRefund) {
                return '<span class="badge rounded-pill bg-success">คืนเงินบางส่วนแล้ว</span>';
            }
        }

        // ไม่เข้าเงื่อนไขใดเลย
        return '';
    }
}


