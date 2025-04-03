<?php

namespace App\Http\Controllers\reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\invoices\taxinvoiceModel;

class saleTaxReportController extends Controller
{
    //

    public function index(Request $request)
    {
        $searchDateStart = $request->input('date_start');
        $searchDateEnd = $request->input('date_end');
        $status = $request->input('status');
        $column_name = $request->input('column_name');
        $keyword = $request->input('keyword');
        $grandTotalSum = 0;
        $withholdingTaxSum = 0;
    
        $taxinvoices = taxinvoiceModel::with('invoice','taxinvoiceCustomer')->when($searchDateStart && $searchDateEnd, function ($query) use ($searchDateStart, $searchDateEnd) {
            return $query->whereBetween('taxinvoice_date', [$searchDateStart, $searchDateEnd]);
        })
        ->when($status ,function ($query) use ($status) {
            return $query->where('taxinvoice_status', $status);
        })
    
        ->when($column_name === 'taxinvoice_number' ,function ($query) use ($keyword) {
            return $query->where('taxinvoice_number','LIKE','%'.$keyword.'%');
        })
        ->when($column_name === 'invoice_number', function ($query) use ($keyword) {
            return $query->whereHas('invoice', function ($q1) use ($keyword) {
                $q1->where('invoice_number', 'LIKE', '%' . $keyword . '%');
            });
        })

        ->when($column_name === 'invoice_booking', function ($query) use ($keyword) {
            return $query->whereHas('invoice', function ($q1) use ($keyword) {
                $q1->where('invoice_booking', 'LIKE', '%' . $keyword . '%');
            });
        })

        ->when($column_name === 'customer_name', function ($query) use ($keyword) {
            return $query->whereHas('taxinvoiceCustomer', function ($q1) use ($keyword) {
                $q1->where('customer_name', 'LIKE', '%' . $keyword . '%');
            });
        })

        ->when($column_name === 'customer_texid', function ($query) use ($keyword) {
            return $query->whereHas('taxinvoiceCustomer', function ($q1) use ($keyword) {
                $q1->where('customer_texid', 'LIKE', '%' . $keyword . '%');
            });
        })

        ->when($column_name === 'all', function ($query) use ($keyword) {
            return $query->where('taxinvoice_number', 'LIKE', '%' . $keyword . '%')
               
                ->orWhereHas('taxinvoiceCustomer', function ($q1) use ($keyword) {
                    $q1->where('customer_name', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('customer_texid', 'LIKE', '%' . $keyword . '%');
                })
                ->orWhereHas('invoice', function ($q1) use ($keyword) {
                    $q1->where('invoice_number', 'LIKE', '%' . $keyword . '%');
                     
                });
        })
        
        ->get();

        $grandTotalSum = $taxinvoices->sum(function ($taxinvoice) {
            return $taxinvoice->invoice->invoice_grand_total;
        });
        
        $vatTotal = $taxinvoices->sum(function ($taxinvoice) {
            return $taxinvoice->invoice->invoice_vat;
        });

        
        return view('reports.saletax-form', compact('taxinvoices','request','grandTotalSum','vatTotal'));
    }
}
