<?php

namespace App\Http\Controllers\reports;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\invoices\taxinvoiceModel;
use App\Models\wholesale\wholesaleModel;
use App\Models\commissions\commissionListModel;
use App\Models\commissions\commissionGroupModel;

class saleReportController extends Controller
{
    //

    public function index(Request $request)
    {
        $searchDateStart = $request->input('date_start');
        $searchDateEnd = $request->input('date_end');
        $column_name = $request->input('column_name');
        $keyword = $request->input('keyword');
        $wholesaleId = $request->input('wholsale_id');
        $countryId = $request->input('country_id');
        $saleId = $request->input('sale_id');
        $mode = $request->commission_mode ?? 'qt';

        $taxinvoices = taxinvoiceModel::with('invoice', 'taxinvoiceCustomer')
            ->when($searchDateStart && $searchDateEnd, fn($q) => $q->whereBetween('taxinvoice_date', [$searchDateStart, $searchDateEnd]))
            ->where('taxinvoice_status', 'success')

            ->when($column_name === 'taxinvoice_number', fn($q) => $q->where('taxinvoice_number', 'LIKE', "%{$keyword}%"))
            ->when($column_name === 'invoice_number', fn($q) => $q->whereHas('invoice', fn($q1) => $q1->where('invoice_number', 'LIKE', "%{$keyword}%")))
            ->when($column_name === 'invoice_booking', fn($q) => $q->whereHas('invoice', fn($q1) => $q1->where('invoice_booking', 'LIKE', "%{$keyword}%")))
            ->when($column_name === 'customer_name', fn($q) => $q->whereHas('taxinvoiceCustomer', fn($q1) => $q1->where('customer_name', 'LIKE', "%{$keyword}%")))
            ->when($column_name === 'quote_number', fn($q) => $q->whereHas('invoice.quotation', fn($q1) => $q1->where('quote_number', 'LIKE', "%{$keyword}%")))
            ->when($column_name === 'customer_texid', fn($q) => $q->whereHas('taxinvoiceCustomer', fn($q1) => $q1->where('customer_texid', 'LIKE', "%{$keyword}%")))
            ->when($column_name === 'all', function ($q) use ($keyword) {
                return $q->where(function ($sub) use ($keyword) {
                    $sub->where('taxinvoice_number', 'LIKE', "%{$keyword}%")
                        ->orWhereHas('taxinvoiceCustomer', fn($q1) => $q1->where('customer_name', 'LIKE', "%{$keyword}%")->orWhere('customer_texid', 'LIKE', "%{$keyword}%"))
                        ->orWhereHas('invoice', fn($q1) => $q1->where('invoice_number', 'LIKE', "%{$keyword}%"))
                        ->orWhereHas('invoice.quotation', fn($q1) => $q1->where('quote_number', 'LIKE', "%{$keyword}%"));
                });
            })
            ->when($wholesaleId, function ($q) use ($wholesaleId) {
                return $q->whereHas('invoice.quotation', fn($q1) => $q1->where('quote_wholesale', $wholesaleId));
            })
            ->when($countryId, function ($q) use ($countryId) {
                return $q->whereHas('invoice.quotation', fn($q1) => $q1->where('quote_country', $countryId));
            })
            ->when($saleId, function ($q) use ($saleId) {
                return $q->whereHas('invoice.quotation', fn($q1) => $q1->where('quote_sale', $saleId));
            });

        if ($mode === 'total') {
            // ดึงข้อมูลทั้งหมดก่อน Group
            $allInvoices = $taxinvoices->get();
            // Group by Sale ID (หากไม่มี sale ให้ใช้ 'unknown')
            $taxinvoices = $allInvoices->groupBy(function ($item) {
                return $item->invoice->quote->Salename->id ?? 'unknown';
            });
            // สรุปรวม Grand Total และ VAT สำหรับทุกกลุ่ม
            $grandTotalSum = $allInvoices->sum(fn($tx) => $tx->invoice->invoice_grand_total);
            $vat = $allInvoices->sum(fn($tx) => $tx->invoice->invoice_withholding_tax);
        } else {
            // โหมด QT ปกติ
            $taxinvoices = $taxinvoices->get();
            $grandTotalSum = $taxinvoices->sum(fn($tx) => $tx->invoice->invoice_grand_total);
            $vat = $taxinvoices->sum(fn($tx) => $tx->invoice->invoice_withholding_tax);
        }

        // list wholesale ส่งไปให้ View
        $wholesales = WholesaleModel::where('status', 'on')->get();
        $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
        $sales = saleModel::select('name', 'id')
            ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
            ->get();

        return view('reports.sales-form', compact('taxinvoices', 'request', 'grandTotalSum', 'vat', 'wholesales', 'country', 'sales'));
    }
}
