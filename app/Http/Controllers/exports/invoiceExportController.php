<?php

namespace App\Http\Controllers\exports;

use Illuminate\Http\Request;
use App\Exports\invoiceExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class invoiceExportController extends Controller
{
    //
    public function export(Request $request)
    {
        $invoiceIdsString = $request->invoice_ids;
        
        $invoiceIdsArray = explode(',', trim($invoiceIdsString, ']'));
    
        // ลบ '[' ออกจาก index แรก
        if (isset($invoiceIdsArray[0])) {
            $invoiceIdsArray[0] = str_replace('[', '', $invoiceIdsArray[0]);
        }
    
      // dd($invoiceIdsArray);
    
        return Excel::download(new invoiceExport($invoiceIdsArray), 'invoice.xlsx');
    }
}
