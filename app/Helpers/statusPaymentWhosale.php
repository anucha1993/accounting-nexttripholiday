<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('getStatusPaymentWhosale')) {
    function getStatusPaymentWhosale($quotationModel)
    {
        // 1. ยอดที่เราโอนไปยังโฮลเซลล์
        $depositTotal = $quotationModel->GetDepositWholesale();

        // 2. ยอดที่โฮลเซลล์คืนกลับมาแล้ว (refund สำเร็จ)
        $refundSuccessTotal = $quotationModel
            ->paymentWholesale()
            ->where('payment_wholesale_refund_status', 'success')
            ->get()
            ->sum(function ($row) {
                return abs($row->payment_wholesale_refund_total);
            });

        // 3. ยอดที่ยังรอคืน (refund ยังไม่ success)
        $refundPendingTotal = $quotationModel
            ->paymentWholesale()
            ->where('payment_wholesale_refund_status', '!=', 'success')
            ->get()
            ->sum(function ($row) {
                return abs($row->payment_wholesale_refund_total);
            });

        // 4. ต้นทุนโฮลเซลล์ (ใช้ inputtaxTotalWholesale)
        $wholesaleCost = $quotationModel->inputtaxTotalWholesale() ?? 0;

        // 5. ยอดที่ลูกค้าชำระมาแล้ว (ควรใช้ GetDeposit() แทน customer_paid)
        $customerPaid = $quotationModel->GetDeposit() ?? 0;


        // 📌 แสดงสถานะเฉพาะเมื่อมีการ "โอนเกิน" (refund)
        if ($depositTotal > 0 && $refundSuccessTotal + $refundPendingTotal > 0) {
            if ($refundPendingTotal <= 0 && $refundSuccessTotal > 0) {
                // return '<span class="badge rounded-pill bg-success">โฮลเซลล์คืนเงินแล้ว</span>';
                return '<span class="text-success">โฮลเซลล์คืนเงินแล้ว</span>';
            }
            if ($refundPendingTotal > 0) {
                // return '<span class="badge rounded-pill bg-warning text-dark">รอโฮลเซลล์คืนเงิน</span>';
                return '<span class="text-warning">รอโฮลเซลล์คืนเงิน</span>';
            }
        }

        // 6. ชำระเงินเกิน
        if ($depositTotal > $wholesaleCost) {
            // return '<span class="badge rounded-pill bg-danger">โอนเงินให้โฮลเซลล์เกิน</span>';
            return '<span class="text-danger">โอนเงินให้โฮลเซลล์เกิน</span>';
        }

        // 1. รอชำระเงินมัดจำ (ยอดโอนโฮลเซลล์ = 0 และลูกค้าชำระมาแล้ว)
        if ($depositTotal == 0 && $customerPaid > 0) {
            //return '<span class="badge rounded-pill bg-warning text-dark">รอชำระเงินมัดจำ</span>';
            return '<span class="text-warning">รอชำระเงินมัดจำ</span>';
        }

        // 2. รอชำระเงินส่วนที่เหลือ (ยอดโอนโฮลเซลล์ > 0 แต่น้อยกว่าต้นทุน)
        if ($depositTotal > 0 && $depositTotal < $wholesaleCost) {
            // return '<span class="badge rounded-pill bg-info text-dark">รอชำระเงินส่วนที่เหลือ</span>';
            return '<span class="text-warning">รอชำระเงินส่วนที่เหลือ</span>';
        }

        // 3. ชำระเงินครบแล้ว (ยอดโอนโฮลเซลล์ = ต้นทุน)
        if ($depositTotal == $wholesaleCost && $wholesaleCost > 0) {
            // return '<span class="badge rounded-pill bg-success">ชำระเงินครบแล้ว</span>';
            return '<span class="text-success">ชำระเงินครบแล้ว</span>';
        }

        return ''; // ไม่แสดงอะไรหากไม่มีสถานะ
    }
}
