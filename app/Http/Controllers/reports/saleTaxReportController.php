<?php

namespace App\Http\Controllers\reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\invoices\taxinvoiceModel;

class saleTaxReportController extends Controller
{
    //
  public function __construct()
    {
        $this->middleware('auth');

    }
    public function index(Request $request)
    {
        $searchDateStart = $request->input('date_start');
        $searchDateEnd = $request->input('date_end');
        $status = $request->input('status');
        $seller_id = $request->input('seller_id');
        $document_number = $request->input('document_number');
        $reference_number = $request->input('reference_number');
        $customer_name = $request->input('customer_name');
        $grandTotalSum = 0;
        $vatTotal = 0;

        // ดึงรายชื่อเซลทั้งหมด
        $sellers = \App\Models\sales\saleModel::select('id', 'name')->get();

        $taxinvoices = taxinvoiceModel::with(['invoice','taxinvoiceCustomer'])
            ->when($searchDateStart && $searchDateEnd, function ($query) use ($searchDateStart, $searchDateEnd) {
                return $query->whereBetween('taxinvoice_date', [$searchDateStart, $searchDateEnd]);
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('taxinvoice_status', $status);
            })
            ->when($seller_id, function ($query) use ($seller_id) {
                return $query->whereHas('invoice', function ($q1) use ($seller_id) {
                    $q1->where('invoice_sale', $seller_id);
                });
            })
            ->when($document_number, function ($query) use ($document_number) {
                return $query->where('taxinvoice_number', 'LIKE', '%' . $document_number . '%');
            })
            ->when($reference_number, function ($query) use ($reference_number) {
                return $query->whereHas('invoice', function ($q1) use ($reference_number) {
                    $q1->where('invoice_number', 'LIKE', '%' . $reference_number . '%');
                });
            })
            ->when($customer_name, function ($query) use ($customer_name) {
                return $query->whereHas('taxinvoiceCustomer', function ($q1) use ($customer_name) {
                    $q1->where('customer_name', 'LIKE', '%' . $customer_name . '%');
                });
            });

        // Sort by ID in descending order
        $taxinvoices = $taxinvoices->orderBy('taxinvoice_id', 'desc');

        // If any filter is applied, show all records, otherwise paginate
        $hasFilters = $searchDateStart || $searchDateEnd || $status || $seller_id || $document_number || $reference_number || $customer_name;
        $taxinvoiceSearch = $hasFilters ? $taxinvoices->paginate(5000)->appends($request->query()) : $taxinvoices->paginate(10)->appends($request->query());

        $taxinvoiceSum = $taxinvoices->get();

        $grandTotalSum = $taxinvoiceSum->sum(function ($taxinvoice) {
            return $taxinvoice->invoice ? $taxinvoice->invoice->invoice_pre_vat_amount : 0;
        });
        $vatTotal = $taxinvoiceSum->sum(function ($taxinvoice) {
            return $taxinvoice->invoice ? $taxinvoice->invoice->invoice_vat : 0;
        });

        return view('reports.saletax-form', compact('taxinvoiceSearch','request','grandTotalSum','vatTotal','taxinvoiceSum','sellers'));
    }
}
