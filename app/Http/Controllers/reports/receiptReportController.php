<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\payments\paymentModel;
use Illuminate\Http\Request;

class receiptReportController extends Controller
{
    //
    public function index(Request $request)
    {
        $searchDateStart = $request->input('date_start');
        $searchDateEnd = $request->input('date_end');
        $status = $request->input('status');
        $column_name = $request->input('column_name');
        $keyword = $request->input('keyword');
        //dd($searchDateStart,$searchDateEnd);
    

        $receipts = paymentModel::with('quote')
        ->when($searchDateStart && $searchDateEnd, function ($query) use ($searchDateStart, $searchDateEnd) {
            return $query->whereBetween('payment_in_date', [$searchDateStart . ' 00:00:00', $searchDateEnd . ' 23:59:59']);
        })
        
        ->when($status ,function ($query) use ($status) {
            return $query->where('payment_status', $status);
        })
    
        ->when($column_name === 'payment_number' ,function ($query) use ($keyword) {
            return $query->where('payment_number','LIKE','%'.$keyword.'%');
        })

          
        ->when($column_name === 'quote_number', function ($query) use ($keyword) {
            return $query->whereHas('quote', function ($q1) use ($keyword) {
                $q1->where('quote_number', 'LIKE', '%' . $keyword . '%');
            });
        })
        
        ->when($column_name === 'customer_name', function ($query) use ($keyword) {
            return $query->whereHas('paymentCustomer', function ($q1) use ($keyword) {
                $q1->where('customer_name', 'LIKE', '%' . $keyword . '%');
            });
        })

        ->when($column_name === 'customer_texid', function ($query) use ($keyword) {
            return $query->whereHas('paymentCustomer', function ($q1) use ($keyword) {
                $q1->where('customer_texid', 'LIKE', '%' . $keyword . '%');
            });
        })

        ->when($column_name === 'all', function ($query) use ($keyword) {
            return $query->where(function ($query) use ($keyword) {
                $query->where('payment_number', 'LIKE', '%' . $keyword . '%')
                    ->orWhereHas('paymentCustomer', function ($q1) use ($keyword) {
                        $q1->where('customer_name', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('customer_texid', 'LIKE', '%' . $keyword . '%');
                    })
                    ->orWhereHas('quote', function ($q1) use ($keyword) {
                        $q1->where('quote_number', 'LIKE', '%' . $keyword . '%');
                    });
            });
        })
        
        ->get();
        
        
     
        return view('reports.receipt-form',compact('receipts','request'));
    }
}
