<?php

namespace App\Http\Controllers\reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;
use App\Models\payments\paymentWholesaleModel;
use App\Exports\paymentWholesaleExport;
use Maatwebsite\Excel\Facades\Excel;

class paymentWholesaleReportController extends Controller
{
    //


public function index(Request $request)
{
    $query = paymentWholesaleModel::query()->with(['quote.quoteWholesale']);

    // วันที่
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date . ' 23:59:59'
        ]);
    }

    // กรองจาก quote_wholesale
    if ($request->filled('wholesale_id')) {
        $quoteIds = quotationModel::where('quote_wholesale', $request->wholesale_id)->pluck('quote_id');
        $query->whereIn('payment_wholesale_quote_id', $quoteIds);
    }


    // Payment No.
    if ($request->filled('payment_wholesale_number')) {
        $query->where('payment_wholesale_number', 'like', '%' . $request->payment_wholesale_number . '%');
    }

    // เลขใบเสนอราคา
    if ($request->filled('quote_number')) {
        $query->whereHas('quote', function ($q) use ($request) {
            $q->where('quote_number', 'like', '%' . $request->quote_number . '%');
        });
    }

    // ประเภทการชำระ
    if ($request->filled('payment_type')) {
        $query->where('payment_wholesale_type', $request->payment_type);
    }

    // วันที่ชำระ
    if ($request->filled('payment_start_date') && $request->filled('payment_end_date')) {
        $query->whereBetween('payment_wholesale_date', [
            $request->payment_start_date . ' 00:00:00',
            $request->payment_end_date . ' 23:59:59'
        ]);
    }

    // 👉 แสดงผลลัพธ์แบบแบ่งหน้า
    $paymentWholesale = $query->latest()->paginate(10)->withQueryString(); // <-- pagination 10 records

    // 👉 คำนวณผลรวม
    $sum_total = $query->sum('payment_wholesale_total');
    $sum_refund = $query->sum('payment_wholesale_refund_total');

    // รายชื่อ wholesales สำหรับ dropdown
    $wholesales = wholesaleModel::all();

    return view('reports.payment-wholesale-form', compact(
        'paymentWholesale',
        'wholesales',
        'sum_total',
        'sum_refund'
    ));
}

public function exportExcel(Request $request)
{
    return Excel::download(new paymentWholesaleExport($request), 'payment_wholesale_report.xlsx');
}



}
