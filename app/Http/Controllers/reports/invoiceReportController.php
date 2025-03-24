<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\invoices\invoiceModel;
use Illuminate\Http\Request;

class invoiceReportController extends Controller
{
    //

    public function index(Request $request)
    {
        $searchDateStart = $request->input('date_start');
        $searchDateEnd = $request->input('date_end');
        $status = $request->input('status');
    
        $invoices = invoiceModel::when($searchDateStart && $searchDateEnd, function ($query) use ($searchDateStart, $searchDateEnd) {
            return $query->whereBetween('invoice_date', [$searchDateStart, $searchDateEnd]);
        })
        ->when($status ,function ($query) use ($status) {
            return $query->where('invoice_status', $status);
        })
        ->get();
        
        return view('reports.invoice-form', compact('invoices'));
    }
}
