<?php

namespace App\Http\Controllers\reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\invoices\taxinvoiceModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class taxinvoiceReportController extends Controller
{
    protected $perPage = 10;

    public function index(Request $request)
    {
        try {
            $query = taxinvoiceModel::with(['invoice.customer', 'invoice.quote'])
                ->select([
                    'taxinvoices.*',
                    DB::raw('SUM(invoices.invoice_grand_total) OVER() as total_grand_total'),
                    DB::raw('SUM(invoices.invoice_withholding_tax) OVER() as total_withholding_tax')
                ])
                ->join('invoices', 'taxinvoices.invoice_id', '=', 'invoices.invoice_id');

            $query = $this->applyFilters($query, $request);
            $query->orderBy('taxinvoice_date', 'desc');
            
            $taxinvoices = $query->paginate($this->perPage)
                ->appends($request->query());

            $grandTotalSum = $taxinvoices->sum('invoice.invoice_grand_total');
            $withholdingTaxSum = $taxinvoices->sum('invoice.invoice_withholding_tax');

            return view('reports.taxinvoice-form', compact(
                'taxinvoices',
                'request',
                'grandTotalSum',
                'withholdingTaxSum'
            ));

        } catch (\Exception $e) {
            Log::error('Tax Invoice Report Error: ' . $e->getMessage());
            return back()->with('error', 'เกิดข้อผิดพลาดในการดึงข้อมูล กรุณาลองใหม่อีกครั้ง')
                ->withInput();
        }
    }

    public function getExportData(Request $request)
    {
        try {
            $query = taxinvoiceModel::with(['invoice.customer', 'invoice.quote'])
                ->select('taxinvoices.*')
                ->join('invoices', 'taxinvoices.invoice_id', '=', 'invoices.invoice_id');

            $query = $this->applyFilters($query, $request);
            $data = $query->orderBy('taxinvoice_date', 'desc')->get();

            return response()->json($data);

        } catch (\Exception $e) {
            Log::error('Tax Invoice Export Error: ' . $e->getMessage());
            return response()->json(['error' => 'Export failed'], 500);
        }
    }

    protected function applyFilters($query, Request $request)
    {
        if ($request->date_start && $request->date_end) {
            $query->whereBetween('taxinvoice_date', [
                $request->date_start,
                $request->date_end
            ]);
        }

        if ($request->status) {
            $query->where('taxinvoice_status', $request->status);
        }

        $keyword = $request->input('keyword');
        $column_name = $request->input('column_name');

        if ($keyword) {
            switch ($column_name) {
                case 'taxinvoice_number':
                    $query->where('taxinvoice_number', 'LIKE', "%{$keyword}%");
                    break;

                case 'invoice_number':
                    $query->whereHas('invoice', function ($q) use ($keyword) {
                        $q->where('invoice_number', 'LIKE', "%{$keyword}%");
                    });
                    break;

                case 'invoice_booking':
                    $query->whereHas('invoice', function ($q) use ($keyword) {
                        $q->where('invoice_booking', 'LIKE', "%{$keyword}%");
                    });
                    break;

                case 'customer_name':
                    $query->whereHas('invoice.customer', function ($q) use ($keyword) {
                        $q->where('customer_name', 'LIKE', "%{$keyword}%");
                    });
                    break;

                case 'customer_texid':
                    $query->whereHas('invoice.customer', function ($q) use ($keyword) {
                        $q->where('customer_texid', 'LIKE', "%{$keyword}%");
                    });
                    break;

                default:
                    $query->where(function ($q) use ($keyword) {
                        $q->where('taxinvoice_number', 'LIKE', "%{$keyword}%")
                          ->orWhereHas('invoice', function ($q1) use ($keyword) {
                              $q1->where('invoice_number', 'LIKE', "%{$keyword}%")
                                 ->orWhere('invoice_booking', 'LIKE', "%{$keyword}%");
                          })
                          ->orWhereHas('invoice.customer', function ($q2) use ($keyword) {
                              $q2->where('customer_name', 'LIKE', "%{$keyword}%")
                                 ->orWhere('customer_texid', 'LIKE', "%{$keyword}%");
                          });
                    });
            }
        }

        return $query;
    }
}
