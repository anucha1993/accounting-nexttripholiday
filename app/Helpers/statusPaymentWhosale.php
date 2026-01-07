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

        // ตั้งค่า tolerance สำหรับป้องกัน floating point precision issue
        $tolerance = 0.01; // ความผิดพลาด 1 สตางค์

        // Debug: Log or dump key values for investigation
        // Log::debug('[DEBUG] getStatusPaymentWhosale Quote ID: ' . $quotationModel->quote_id, [
        //     'depositTotal' => $depositTotal,
        //     'refundSuccessTotal' => $refundSuccessTotal,
        //     'refundPendingTotal' => $refundPendingTotal,
        //     'wholesaleCost' => $wholesaleCost,
        //     'customerPaid' => $customerPaid,
        //     'comparison_depositTotal_vs_wholesaleCost' => $depositTotal . ' vs ' . $wholesaleCost,
        //     'is_depositTotal_less_than_wholesaleCost' => ($depositTotal < $wholesaleCost) ? 'TRUE' : 'FALSE'
        // ]);

        // ตรวจสอบสถานะการชำระเงินก่อน แล้วค่อยดู refund status

        // 1. รอชำระเงินมัดจำ (ยอดโอนโฮลเซลล์ = 0 และลูกค้าชำระมาแล้ว)
        if ($depositTotal == 0 && $customerPaid > 0) {
            return '<span class="text-warning">รอชำระมัดเงินจำโฮลเซลล์</span>';
        }

        // 2. รอชำระเงินส่วนที่เหลือ (ยอดโอนโฮลเซลล์ > 0 แต่น้อยกว่าต้นทุน)
        if ($depositTotal > 0 && ($depositTotal + $tolerance) < $wholesaleCost) {
            return '<span class="text-warning">รอชำระเงินส่วนที่เหลือ</span>';
        }

        // 3. ชำระเงินเกิน - แสดงสถานะ refund
        if ($depositTotal > ($wholesaleCost + $tolerance)) {
            // ถ้ามี refund แสดงสถานะ refund
            if ($refundSuccessTotal + $refundPendingTotal > 0) {
                if ($refundPendingTotal <= 0 && $refundSuccessTotal > 0) {
                    return '<span class="text-success">โฮลเซลล์คืนเงินแล้ว</span>';
                }
                if ($refundPendingTotal > 0) {
                    return '<span class="text-warning">รอโฮลเซลล์คืนเงิน</span>';
                }
            }
            // ถ้ายังไม่มี refund แสดงว่าโอนเกิน
            return '<span class="text-danger">โอนเงินให้โฮลเซลล์เกิน</span>';
        }

        // 4. ชำระเงินครบแล้ว (ยอดโอนโฮลเซลล์ = ต้นทุน หรือใกล้เคียงกัน)
        if ($wholesaleCost > 0 && abs($depositTotal - $wholesaleCost) <= $tolerance) {
            return '<span class="text-success">ชำระเงินครบแล้ว</span>';
        }

        return ''; // ไม่แสดงอะไรหากไม่มีสถานะ
    }
}
