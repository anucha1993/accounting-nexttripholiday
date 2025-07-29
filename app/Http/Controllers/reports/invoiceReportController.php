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
        $column_name = $request->input('column_name');
        $keyword = $request->input('keyword');

        $invoices = invoiceModel::with('invoiceCustomer')
            ->when($searchDateStart && $searchDateEnd, function ($query) use ($searchDateStart, $searchDateEnd) {
                return $query->whereDate('invoice_date', '>=', $searchDateStart)->whereDate('invoice_date', '<=', $searchDateEnd);
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('invoice_status', $status);
            })

            ->when($column_name === 'invoice_number', function ($query) use ($keyword) {
                return $query->where('invoice_number', 'LIKE', '%' . $keyword . '%');
            })
            ->when($column_name === 'invoice_booking', function ($query) use ($keyword) {
                return $query->where('invoice_booking', 'LIKE', '%' . $keyword . '%');
            })

            ->when($column_name === 'customer_name', function ($query) use ($keyword) {
                return $query->whereHas('invoiceCustomer', function ($q1) use ($keyword) {
                    $q1->where('customer_name', 'LIKE', '%' . $keyword . '%');
                });
            })
            

            ->when($column_name === 'customer_texid', function ($query) use ($keyword) {
                return $query->whereHas('invoiceCustomer', function ($q1) use ($keyword) {
                    $q1->where('customer_texid', 'LIKE', '%' . $keyword . '%');
                });
            })

            ->when($column_name === 'all', function ($query) use ($keyword) {
                return $query->where(function ($q) use ($keyword) {
                    $q->where('invoice_number', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('invoice_booking', 'LIKE', '%' . $keyword . '%')
                      ->orWhereHas('quote', function ($q2) use ($keyword) {
              $q2->where('quote_number', 'LIKE', '%' . $keyword . '%');
          })
                      ->orWhereHas('invoiceCustomer', function ($q1) use ($keyword) {
                          $q1->where('customer_name', 'LIKE', '%' . $keyword . '%')
                             ->orWhere('customer_texid', 'LIKE', '%' . $keyword . '%');
                      });
                });
            })

            ->get();

        return view('reports.invoice-form', compact('invoices', 'request'));
    }
}
