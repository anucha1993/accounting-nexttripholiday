<?php

if (!function_exists('getQuoteStatusWithholdingTax')) {
    function getQuoteStatusWithholdingTax($quoteLogStatus)
    {
        // ✅ เช็คก่อนว่า object ไม่เป็น null
        if (!$quoteLogStatus) {
            return ''; // หรือจะ return error ก็ได้ เช่น "ไม่พบข้อมูลใบแจ้งหนี้"
        }

        if ($quoteLogStatus && $quoteLogStatus->wholesale_skip_status === 'ต้องการออก') {
            if($quoteLogStatus->wholesale_tax_status === 'ได้รับแล้ว'){
             return '<span class="badge rounded-pill bg-success">ออกใบหักแล้ว </span>';
            }else{
                 return '<span class="badge rounded-pill bg-warning">รอออกใบหัก ณ ที่จ่ายโฮลเซลล์'.$quoteLogStatus->wholesale_skip_status.' </span>';
            }
        }

        return ''; // กรณีไม่มีการหัก ณ ที่จ่าย 
    }
}
