<?php
//namespace App\Helpers;

// app/Helpers/StatusHelper.php

// app/Helpers/StatusHelper.php

if (!function_exists('getStatusText')) {
    function getStatusText($status)
    {
        return match($status) {
            'some' => 'รอดำเนินการ',
            'full' => 'อนุมัติแล้ว',
            default => '',
        };
    }
}
