<?php

if (!function_exists('getStatusWhosaleInputTax')) {
    function getStatusWhosaleInputTax($inputTax)
    {

        if(!$inputTax)
        {
             return '';
        }

        if($inputTax->input_tax_file)
        {
             return '<span class="badge rounded-pill bg-success">ได้รับใบกำกับโฮลเซลแล้ว </span>';
           
        }else{
            return '<span class="badge rounded-pill bg-warning text-black">รอใบกำกับภาษีโฮลเซลล์ </span>';
        }

        return ''; // กรณีไม่มีการหัก ณ ที่จ่าย
    }
}
