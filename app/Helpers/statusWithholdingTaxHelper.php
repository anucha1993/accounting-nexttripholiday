<?php

if (!function_exists('getStatusWithholdingTax')) {
    function getStatusWithholdingTax($invoice)
    {
        // ✅ เช็คก่อนว่า object ไม่เป็น null
        if (!$invoice) {
            return ''; // หรือจะ return error ก็ได้ เช่น "ไม่พบข้อมูลใบแจ้งหนี้"
        }

        if ($invoice->invoice_withholding_tax_status === 'Y') {
            if (!empty($invoice->invoice_image)) {
                return '<span class="badge rounded-pill bg-success">ได้รับใบหักแล้ว</span>';
            } else {
                return '<span class="badge rounded-pill bg-warning text-dark">รอใบหัก ณ ที่จ่าย จากลูกค้า</span>';
            }
        }

        return ''; // กรณีไม่มีการหัก ณ ที่จ่าย
    }
}
