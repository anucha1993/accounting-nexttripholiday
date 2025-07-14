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
        if (isset($taxinvoiceIdsArray[0])) {
            $taxinvoiceIdsArray[0] = str_replace('[', '', $taxinvoiceIdsArray[0]);
        }
        return Excel::download(new salesExport(
            $taxinvoiceIdsArray,
            $request->commission_mode,
            $request->sale_id,
            $request->wholsale_id,
            $request->country_id,
            $request->status,
            $request->column_name,
            $request->keyword,
            $request->date_start,
            $request->date_end,
            $request->campaign_source_id
        ), 'sales.xlsx');
    }
}
