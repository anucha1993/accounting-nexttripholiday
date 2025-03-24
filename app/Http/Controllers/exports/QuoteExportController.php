<?php

namespace App\Http\Controllers\exports;

use App\Exports\QuoteExport;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class QuoteExportController extends Controller
{
    public function export(Request $request)
    {
        $quoteIdsString = $request->quote_ids;
        
        $quoteIdsArray = explode(',', trim($quoteIdsString, ']'));
    
        // ลบ '[' ออกจาก index แรก
        if (isset($quoteIdsArray[0])) {
            $quoteIdsArray[0] = str_replace('[', '', $quoteIdsArray[0]);
        }
    
       //dd($quoteIdsArray);
    
        return Excel::download(new QuoteExport($quoteIdsArray), 'quote.xlsx');
    }
}
