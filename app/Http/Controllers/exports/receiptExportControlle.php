<?php

namespace App\Http\Controllers\exports;

use Illuminate\Http\Request;

use App\Exports\receiptExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class receiptExportControlle  extends Controller
{
    //
    public function export(Request $request)
    {
        $paymentIdsString = $request->payment_ids;
        
        $paymentIdsArray = explode(',', trim($paymentIdsString, ']'));
          //dd($paymentIdsArray);
        // ลบ '[' ออกจาก index แรก
        if (isset($paymentIdsArray[0])) {
            $paymentIdsArray[0] = str_replace('[', '', $paymentIdsArray[0]);
        }
    
      // dd($paymentIdsArray);
    
        return Excel::download(new receiptExport($paymentIdsArray), 'receip.xlsx');
    }
}
