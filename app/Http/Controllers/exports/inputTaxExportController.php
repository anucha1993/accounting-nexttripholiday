<?php

namespace App\Http\Controllers\exports;

use App\Exports\inputTaxExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\inputTax\inputTaxModel;
use Maatwebsite\Excel\Facades\Excel;

class inputTaxExportController extends Controller
{
    //
    public function export(Request $request)
    {
        // ถ้ามี parameter export_all ให้ดึงข้อมูลทั้งหมด
        if ($request->has('export_all')) {
            // ใช้เงื่อนไขเดียวกับที่ใช้แสดงในหน้ารายงาน
            $inputTaxQuery = inputTaxModel::where('input_tax_type', 0)
                ->with([
                    'quote.quoteWholesale',
                    'quote.quoteSale', 
                    'invoice.taxinvoice'
                ]);

            // Apply status filter
            if ($request->filled('status')) {
                if ($request->status === 'not_null') {
                    $inputTaxQuery->whereNotNull('input_tax_file');
                } elseif ($request->status === 'is_null') {
                    $inputTaxQuery->whereNull('input_tax_file');
                }
            }

            // Apply date range filter
            if ($request->filled(['date_start', 'date_end'])) {
                $inputTaxQuery->whereBetween('input_tax_date_tax', [
                    $request->date_start,
                    $request->date_end
                ]);
            }

            // Apply seller filter
            if ($request->filled('seller_id')) {
                $inputTaxQuery->whereHas('quote', function ($query) use ($request) {
                    $query->where('quote_sale', $request->seller_id);
                });
            }

            // Apply reference document number filter
            if ($request->filled('reference_number_doc')) {
                $inputTaxQuery->where('input_tax_ref', 'like', '%' . $request->reference_number_doc . '%');
            }

            // Apply document number filter
            if ($request->filled('document_number')) {
                $inputTaxQuery->where('input_tax_number_tax', 'like', '%' . $request->document_number . '%');
            }

            // Apply reference number filter
            if ($request->filled('reference_number')) {
                $inputTaxQuery->whereHas('invoice.taxinvoice', function ($query) use ($request) {
                    $query->where('taxinvoice_number', 'like', '%' . $request->reference_number . '%');
                });
            }

            // Apply wholesale filter
            if ($request->filled('wholesale_id')) {
                $inputTaxQuery->whereHas('quote', function ($query) use ($request) {
                    $query->where('quote_wholesale', $request->wholesale_id);
                });
            }

            // Get all matching IDs
            $inputTaxdsArray = $inputTaxQuery->pluck('input_tax_id')->toArray();
        } else {
            // ใช้ ID ที่ส่งมาตามปกติ
            $inputTaxIdsString = $request->input_tax_ids;
            
            $inputTaxdsArray = explode(',', trim($inputTaxIdsString, ']'));
        
            // ลบ '[' ออกจาก index แรก
            if (isset($inputTaxdsArray[0])) {
                $inputTaxdsArray[0] = str_replace('[', '', $inputTaxdsArray[0]);
            }
        }
    
        return Excel::download(new inputTaxExport($inputTaxdsArray), 'รายงานภาษีซื้อ.xlsx');
    }
}
