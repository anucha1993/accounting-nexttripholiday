<?php

use Carbon\Carbon;

if (!function_exists('getQuoteStatusPayment')) {
    function getQuoteStatusPayment($quotationModel)
    {
        $now = Carbon::now();
        $status = '';
        
        // ใช้ relationship แทน accessor methods เพื่อหลีกเลี่ยงปัญหา object conversion
        $payments = $quotationModel->quotePayments;
        
        $depositTotal = $payments->where('payment_status', '!=', 'cancel')
                                ->where('payment_type', '!=', 'refund')
                                ->sum('payment_total');
                                
        $refundTotal = $payments->where('payment_status', '!=', 'cancel')
                               ->where('payment_type', '=', 'refund')
                               ->whereNotNull('payment_file_path')
                               ->sum('payment_total');
        
        $paymentTotal = $quotationModel->quote_grand_total - $depositTotal + $refundTotal;
        $payment = $depositTotal - $refundTotal;

        switch (true) {
            // คืนเงิน
            case $quotationModel->quotePayment && $quotationModel->quotePayment->payment_status === 'refund':
                // $status = '<span class="badge rounded-pill bg-warning text-dark">รอคืนเงิน </span>';
                 $status = '<span class="text-warning">รอคืนเงิน </span>';
                break;

            // ยกเลิก
            case $quotationModel->quote_status === 'cancel':
                // $status = '<span class="badge rounded-pill bg-danger">ยกเลิกการสั่งซื้อ</span>';
                $status = '<span class="text-danger">ยกเลิกการสั่งซื้อ</span>';
                break;

            // ชำระเงินครบแล้ว
            case $paymentTotal == 0:
                // $status = '<span class="badge rounded-pill bg-success">ชำระเงินครบแล้ว</span>';
                $status = '<span class="text-success">ชำระเงินครบแล้ว</span>';
                break;

            // ชำระเงินเกิน
            case $payment > $quotationModel->quote_grand_total:
                // $status = '<span class="badge rounded-pill bg-info">ชำระเงินเกิน</span>';
                $status = '<span class="text-info">ชำระเงินเกิน</span>';
                break;

            // ชำระเงินบางส่วน (จ่ายมัดจำแล้ว รอจ่ายเต็มจำนวน)
            case $payment > 0 && $payment < $quotationModel->quote_grand_total:
                // เช็คเฉพาะวันครบกำหนดจ่าย "เต็มจำนวน" เท่านั้น
                $paymentDate = $quotationModel->quote_payment_date_full;
                if ($paymentDate && $now->gt(Carbon::parse($paymentDate))) {
                    // $status = '<span class="badge rounded-pill bg-danger">เกินกำหนดชำระเงิน</span>';
                    $status = '<span class="text-danger">เกินกำหนดชำระเงิน</span>';
                } else {
                    // $status = '<span class="badge rounded-pill bg-info">รอชำระเงินเต็มจำนวน</span>';
                    $status = '<span class="text-info">รอชำระเงินเต็มจำนวน</span>';
                }
                break;

            // ยังไม่ชำระเงิน (deposit)
            case $quotationModel->quote_payment_type === 'deposit' && $payment == 0:
                if ($now->gt(Carbon::parse($quotationModel->quote_payment_date))) {
                    //$status = '<span class="badge rounded-pill bg-danger">เกินกำหนดชำระเงิน</span>';
                    $status = '<span class="text-danger">เกินกำหนดชำระเงิน</span>';
                } else {
                    //$status = '<span class="badge rounded-pill bg-warning text-dark">รอชำระเงินมัดจำ</span>';
                    $status = '<span class="text-warning">รอชำระเงินมัดจำ</span>';
                }
                break;

            // ยังไม่ชำระเงิน (full)
            case $quotationModel->quote_payment_type === 'full' && $payment == 0:
                if ($now->gt(Carbon::parse($quotationModel->quote_payment_date_full))) {

                    // $status = '<span class="badge rounded-pill bg-danger">เกินกำหนดชำระเงิน</span>';
                    $status = '<span class="text-danger">เกินกำหนดชำระเงิน</span>';
                } else {
                    //$status = '<span class="badge rounded-pill bg-info">รอชำระเงินเต็มจำนวน</span>';
                    $status = '<span class="text-info">รอชำระเงินเต็มจำนวน</span>';
                }
                break;

            // กรณีอื่น ๆ
            default:
                // $status = '<span class="badge rounded-pill bg-secondary">รอชำระเงิน</span>';
                $status = '<span class="text-secondary">รอชำระเงิน</span>';
                break;
        }
        return $status;

        // // ตรวจสอบ payment_status ผ่านความสัมพันธ์ quotePayment
        // if ($quotationModel->quotePayment && $quotationModel->quotePayment->payment_status === 'refund') {

        //     $status = '<span class="badge rounded-pill bg-warning text-dark">รอคืนเงิน </span>';
        // } elseif ($quotationModel->quote_status === 'cancel') {
        //     $status = '<span class="badge rounded-pill bg-danger">ยกเลิกการสั่งซื้อ</span>';

        // } elseif ($quotationModel->quote_status === 'success' || $quotationModel->quote_status === 'invoice') {

        //     if ($paymentTotal == 0) {
        //         $status = '<span class="badge rounded-pill bg-success">ชำระเงินครบแล้ว</span>';
        //     } elseif ($quotationModel->payment > $quotationModel->quote_grand_total) {
        //         $status = '<span class="badge rounded-pill bg-info">ชำระเงินเกิน</span>';
        //     }else {

        //     }
        //     // $status = '<span class="badge rounded-pill bg-success">ชำระเงินครบแล้ว</span>'.$quotationModel->payment;
        // } elseif ($quotationModel->payment > 0 && $quotationModel->payment < $quotationModel->quote_grand_total) {
        //     // กรณีชำระเงินบางส่วน
        //     $paymentDate = null;
        //     if ($quotationModel->quote_payment_type === 'deposit') {
        //         $paymentDate = $quotationModel->quote_payment_date;
        //     } elseif ($quotationModel->quote_payment_type === 'full') {
        //         $paymentDate = $quotationModel->quote_payment_date_full;
        //     }
        //     if ($paymentDate && $now->gt(Carbon::parse($paymentDate))) {
        //         $status = '<span class="badge rounded-pill bg-danger">เกินกำหนดชำระเงิน</span>';
        //     } else {
        //         $status = '<span class="badge rounded-pill bg-info">รอชำระเงินเต็มจำนวน</span>';
        //     }
        // } elseif ($quotationModel->quote_payment_type === 'deposit') {
        //     if ($now->gt(Carbon::parse($quotationModel->quote_payment_date))) {
        //         $status = '<span class="badge rounded-pill bg-danger">เกินกำหนดชำระเงิน</span>';
        //     } else {
        //         $status = '<span class="badge rounded-pill bg-warning text-dark">รอชำระเงินมัดจำ</span>';
        //     }
        // } elseif ($quotationModel->quote_payment_type === 'full') {
        //     if ($now->gt(Carbon::parse($quotationModel->quote_payment_date_full))) {
        //         $status = '<span class="badge rounded-pill bg-danger">เกินกำหนดชำระเงิน</span>';
        //     } else {
        //         $status = '<span class="badge rounded-pill bg-info">รอชำระเงินเต็มจำนวน</span>';
        //     }
        // } else {
        //     $status = '<span class="badge rounded-pill bg-secondary">รอชำระเงิน</span>';
        // }
        // return $status;
    }
}
