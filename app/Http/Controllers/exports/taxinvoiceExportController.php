<?php

namespace App\Http\Controllers\exports;

use Illuminate\Http\Request;
use App\Exports\taxinvoiceExport;
use App\Http\Controllers\Controller;
use App\Models\invoices\taxinvoiceModel;
use Maatwebsite\Excel\Facades\Excel;

class taxinvoiceExportController extends Controller
{
    //
    public function export(Request $request)
    {
        // ถ้ามี parameter export_all ให้ดึงข้อมูลทั้งหมด
        if ($request->has('export_all')) {
            // ใช้เงื่อนไขเดียวกับที่ใช้แสดงในหน้ารายงาน
            $query = taxinvoiceModel::query();
            
            // Apply date range filter
            if ($request->filled(['date_start', 'date_end'])) {
                $query->whereBetween('taxinvoice_date', [
                    $request->date_start,
                    $request->date_end
                ]);
            }

            // Apply status filter
            if ($request->filled('status')) {
                $query->where('taxinvoice_status', $request->status);
            }

            // Apply search filters
            if ($request->filled(['column_name', 'keyword'])) {
                $column = $request->column_name;
                $keyword = $request->keyword;

                switch ($column) {
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
                                     ->orWhere('invoice_quote_number', 'LIKE', "%{$keyword}%");
                              })
                              ->orWhereHas('invoice.customer', function ($q2) use ($keyword) {
                                  $q2->where('customer_name', 'LIKE', "%{$keyword}%")
                                     ->orWhere('customer_texid', 'LIKE', "%{$keyword}%");
                              });
                        });
                }
            }

            // ดึง ID ทั้งหมดที่ตรงตามเงื่อนไข
            $taxinvoiceIdsArray = $query->pluck('taxinvoice_id')->toArray();
        } else {
            // ใช้ ID ที่ส่งมาตามปกติ
            $taxinvoiceIdsString = $request->taxinvoice_ids;
            
            $taxinvoiceIdsArray = explode(',', trim($taxinvoiceIdsString, ']'));
        
            // ลบ '[' ออกจาก index แรก
            if (isset($taxinvoiceIdsArray[0])) {
                $taxinvoiceIdsArray[0] = str_replace('[', '', $taxinvoiceIdsArray[0]);
            }
        }
    
        return Excel::download(new taxinvoiceExport($taxinvoiceIdsArray), 'tax_invoice.xlsx');
    }
}
