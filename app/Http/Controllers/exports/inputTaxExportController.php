<?php

namespace App\Http\Controllers\exports;

use App\Exports\inputTaxExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class inputTaxExportController extends Controller
{
    //
    public function export(Request $request)
    {
        $inputTaxIdsString = $request->input_tax_ids;
        
        $inputTaxdsArray = explode(',', trim($inputTaxIdsString, ']'));
    
        // ลบ '[' ออกจาก index แรก
        if (isset($inputTaxdsArray[0])) {
            $inputTaxdsArray[0] = str_replace('[', '', $inputTaxdsArray[0]);
        }
    
      // dd($invoiceIdsArray);
    
        return Excel::download(new inputTaxExport($inputTaxdsArray), 'รายงานภาษีซื้อ.xlsx');
    }
}
