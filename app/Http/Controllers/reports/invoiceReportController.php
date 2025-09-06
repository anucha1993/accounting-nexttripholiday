<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\invoices\invoiceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class invoiceReportController extends Controller
{
    protected $perPage = 10; // จำนวนรายการต่อหน้า

    public function index(Request $request)
    {
        try {
            $query = invoiceModel::query()
                ->with(['invoiceCustomer', 'quote']) // Eager load relationships
                ->select([
                    'invoices.*',
                    DB::raw('SUM(invoice_grand_total) OVER() as total_grand_total'),
                    DB::raw('SUM(invoice_withholding_tax) OVER() as total_withholding_tax')
                ]);

            // Apply filters
            $query = $this->applyDateFilter($query, $request);
            $query = $this->applyStatusFilter($query, $request);
            $query = $this->applySearchFilter($query, $request);

            // Order by invoice date descending
            $query->orderBy('invoice_date', 'desc');

            // Get paginated results
            $invoices = $query->paginate($this->perPage)
                ->appends($request->query());

            // Calculate totals for the current page
            $pageTotals = [
                'grand_total' => $invoices->sum('invoice_grand_total'),
                'withholding_tax' => $invoices->sum('invoice_withholding_tax')
            ];

            return view('reports.invoice-form', compact('invoices', 'request', 'pageTotals'));

        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Invoice Report Error: ' . $e->getMessage());
            
            return back()
                ->with('error', 'เกิดข้อผิดพลาดในการดึงข้อมูล กรุณาลองใหม่อีกครั้ง')
                ->withInput();
        }
    }

    protected function applyDateFilter($query, Request $request)
    {
        $searchDateStart = $request->input('date_start');
        $searchDateEnd = $request->input('date_end');

        if ($searchDateStart && $searchDateEnd) {
            $query->whereDate('invoice_date', '>=', $searchDateStart)
                  ->whereDate('invoice_date', '<=', $searchDateEnd);
        }

        return $query;
    }

    protected function applyStatusFilter($query, Request $request)
    {
        $status = $request->input('status');

        if ($status) {
            $query->where('invoice_status', $status);
        }

        return $query;
    }

    protected function applySearchFilter($query, Request $request)
    {
        $column_name = $request->input('column_name');
        $keyword = $request->input('keyword');

        if (!$keyword) {
            return $query;
        }

        switch ($column_name) {
            case 'invoice_number':
                $query->where('invoice_number', 'LIKE', "%{$keyword}%");
                break;

            case 'invoice_booking':
                $query->where('invoice_booking', 'LIKE', "%{$keyword}%");
                break;

            case 'customer_name':
                $query->whereHas('invoiceCustomer', function ($q) use ($keyword) {
                    $q->where('customer_name', 'LIKE', "%{$keyword}%");
                });
                break;

            case 'customer_texid':
                $query->whereHas('invoiceCustomer', function ($q) use ($keyword) {
                    $q->where('customer_texid', 'LIKE', "%{$keyword}%");
                });
                break;

            case 'all':
                $query->where(function ($q) use ($keyword) {
                    $q->where('invoice_number', 'LIKE', "%{$keyword}%")
                      ->orWhere('invoice_booking', 'LIKE', "%{$keyword}%")
                      ->orWhereHas('quote', function ($q2) use ($keyword) {
                          $q2->where('quote_number', 'LIKE', "%{$keyword}%");
                      })
                      ->orWhereHas('invoiceCustomer', function ($q1) use ($keyword) {
                          $q1->where('customer_name', 'LIKE', "%{$keyword}%")
                             ->orWhere('customer_texid', 'LIKE', "%{$keyword}%");
                      });
                });
                break;
        }

        return $query;
    }

    public function getExportData(Request $request)
    {
        try {
            $query = invoiceModel::query()
                ->with(['invoiceCustomer', 'quote'])
                ->select([
                    'invoices.*',
                    DB::raw('SUM(invoice_grand_total) OVER() as total_grand_total'),
                    DB::raw('SUM(invoice_withholding_tax) OVER() as total_withholding_tax')
                ]);

            // Apply the same filters as the index page
            $query = $this->applyDateFilter($query, $request);
            $query = $this->applyStatusFilter($query, $request);
            $query = $this->applySearchFilter($query, $request);

            // Order by invoice date descending
            return $query->orderBy('invoice_date', 'desc')->get();

        } catch (\Exception $e) {
            \Log::error('Invoice Export Error: ' . $e->getMessage());
            return null;
        }
    }

    public function getTotalAmount($invoices)
    {
        return [
            'grand_total' => $invoices->sum('invoice_grand_total'),
            'withholding_tax' => $invoices->sum('invoice_withholding_tax')
        ];
    }
}

