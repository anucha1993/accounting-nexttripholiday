<?php

use Carbon\Carbon;

if (!function_exists('getQuoteStatusPayment')) {
    function getQuoteStatusPayment($quotationModel)
    {
        $now = Carbon::now();
        $status = '';
        // ตรวจสอบ payment_status ผ่านความสัมพันธ์ quotePayment
        if ($quotationModel->quotePayment && $quotationModel->quotePayment->payment_status === 'refund') {

            $status = '<span class="badge rounded-pill bg-warning text-dark">รอคืนเงิน </span>';
        } elseif ($quotationModel->quote_status === 'cancel') {
            $status = '<span class="badge rounded-pill bg-danger">ยกเลิกการสั่งซื้อ</span>';
    
        } elseif ($quotationModel->quote_status === 'success') {
            $status = '<span class="badge rounded-pill bg-success">ชำระเงินครบแล้ว</span>';
        } elseif ($quotationModel->payment > 0) {
            $status = '<span class="badge rounded-pill bg-info">รอชำระเงินเต็มจำนวน</span>';
        } elseif ($quotationModel->quote_payment_type === 'deposit') {
            if ($now->gt(Carbon::parse($quotationModel->quote_payment_date))) {
                $status = '<span class="badge rounded-pill bg-danger">เกินกำหนดชำระเงิน</span>';
            } else {
                $status = '<span class="badge rounded-pill bg-warning text-dark">รอชำระเงินมัดจำ</span>';
            }
        } elseif ($quotationModel->quote_payment_type === 'full') {
            if ($now->gt(Carbon::parse($quotationModel->quote_payment_date_full))) {
                $status = '<span class="badge rounded-pill bg-danger">เกินกำหนดชำระเงิน</span>';
            } else {
                $status = '<span class="badge rounded-pill bg-info">รอชำระเงินเต็มจำนวน</span>';
            }
        } else {
            $status = '<span class="badge rounded-pill bg-secondary">รอชำระเงิน</span>';
        }
        return $status;
    }
}

