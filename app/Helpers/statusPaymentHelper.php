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
            
            if ($quotationModel->payment === $quotationModel->quote_grand_total) {
                $status = '<span class="badge rounded-pill bg-success">ชำระเงินครบแล้ว</span>';
            } elseif ($quotationModel->payment > $quotationModel->quote_grand_total) {
                $status = '<span class="badge rounded-pill bg-info">ชำระเงินเกิน</span>';
            }else {

            }
            // $status = '<span class="badge rounded-pill bg-success">ชำระเงินครบแล้ว</span>'.$quotationModel->payment;
        } elseif ($quotationModel->payment > 0 && $quotationModel->payment < $quotationModel->quote_grand_total) {
            // กรณีชำระเงินบางส่วน
            $paymentDate = null;
            if ($quotationModel->quote_payment_type === 'deposit') {
                $paymentDate = $quotationModel->quote_payment_date;
            } elseif ($quotationModel->quote_payment_type === 'full') {
                $paymentDate = $quotationModel->quote_payment_date_full;
            }
            if ($paymentDate && $now->gt(Carbon::parse($paymentDate))) {
                $status = '<span class="badge rounded-pill bg-danger">เกินกำหนดชำระเงิน</span>';
            } else {
                $status = '<span class="badge rounded-pill bg-info">รอชำระเงินเต็มจำนวน</span>';
            }
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




