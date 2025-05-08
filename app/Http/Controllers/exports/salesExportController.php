<?php

namespace App\Http\Controllers\exports;

use App\Exports\salesExport;
use Illuminate\Http\Request;
use App\Exports\saleTaxExport;
use App\Exports\taxinvoiceExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class salesExportController extends Controller
{
    //
    public function export(Request $request)
    {
        $taxinvoiceIdsString = $request->taxinvoice_ids;
        
        $taxinvoiceIdsArray = explode(',', trim($taxinvoiceIdsString, ']'));
    
        // ลบ '[' ออกจาก index แรก
        if (isset($taxinvoiceIdsArray[0])) {
            $taxinvoiceIdsArray[0] = str_replace('[', '', $taxinvoiceIdsArray[0]);
        }
    
      //dd($taxinvoiceIdsArray);
    
        return Excel::download(new salesExport($taxinvoiceIdsArray), 'sales.xlsx');
    }
}
