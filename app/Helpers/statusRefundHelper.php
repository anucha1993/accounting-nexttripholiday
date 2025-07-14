<?php


if (!function_exists('getStatusCustomerRefund')) {
    //
    function getStatusCustomerRefund($quoteLog)
    {
        if (!$quoteLog) {
            return '';
        }
        
        if ($quoteLog->customer_refund_status === 'คืนเงินสำเร็จ') {
            return '<span class="badge bg-success badge-sm">คืนเงินลูกค้าแล้ว</span>';
        } elseif ($quoteLog->customer_refund_status === 'ยังไม่ได้คืนเงิน') {
            return '<span class="badge bg-warning text-dark badge-sm">ยังไม่คืนเงินลูกค้า</span>';
        }

        return '';
    }
}



if (!function_exists('getStatusWholesaleRefund')) {
    function getStatusWholesaleRefund($quoteLog)
    {
        if (!$quoteLog) {
            return '';
        }

        if ($quoteLog->wholesale_refund_status === 'คืนเงินสำเร็จ') {
            return '<span class="badge bg-success badge-sm">โฮลเซลล์คืนเงินแล้ว</span>';
        } elseif ($quoteLog->wholesale_refund_status === 'ยังไม่ได้คืนเงิน') {
            return '<span class="badge bg-warning text-dark badge-sm">ยังไม่ได้รับเงินคืน</span>';
        }

        return '';
    }
}
