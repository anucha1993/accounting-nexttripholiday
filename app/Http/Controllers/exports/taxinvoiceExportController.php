<?php

namespace App\Http\Controllers\exports;

use Illuminate\Http\Request;
use App\Exports\taxinvoiceExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class taxinvoiceExportController extends Controller
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
    
      // dd($invoiceIdsArray);
    
        return Excel::download(new taxinvoiceExport($taxinvoiceIdsArray), 'tax_invoice.xlsx');
    }
}
