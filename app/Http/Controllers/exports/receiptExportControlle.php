<?php

namespace App\Http\Controllers\exports;

use Illuminate\Http\Request;

use App\Exports\receiptExport;
use App\Http\Controllers\Controller;
use App\Models\payments\paymentModel;
use Maatwebsite\Excel\Facades\Excel;

class receiptExportControlle  extends Controller
{
    //
    public function export(Request $request)
    {
        // ถ้ามี parameter export_all ให้ดึงข้อมูลทั้งหมด
        if ($request->has('export_all')) {
            // ใช้เงื่อนไขเดียวกับที่ใช้แสดงในหน้ารายงาน
            $query = paymentModel::query();
            
            // Apply date range filter
            if ($request->filled(['date_start', 'date_end'])) {
                $query->whereBetween('payment_in_date', [
                    $request->date_start . ' 00:00:00',
                    $request->date_end . ' 23:59:59'
                ]);
            }

            // Apply status filter
            if ($request->filled('status')) {
                $query->where('payment_status', $request->status);
            }

            // Apply search filters
            if ($request->filled(['column_name', 'keyword'])) {
                $column = $request->column_name;
                $keyword = $request->keyword;

                switch ($column) {
                    case 'payment_number':
                        $query->where('payment_number', 'LIKE', '%' . $keyword . '%');
                        break;

                    case 'quote_number':
                        $query->whereHas('quote', function ($q) use ($keyword) {
                            $q->where('quote_number', 'LIKE', '%' . $keyword . '%');
                        });
                        break;

                    case 'customer_name':
                        $query->whereHas('paymentCustomer', function ($q) use ($keyword) {
                            $q->where('customer_name', 'LIKE', '%' . $keyword . '%');
                        });
                        break;

                    case 'customer_texid':
                        $query->whereHas('paymentCustomer', function ($q) use ($keyword) {
                            $q->where('customer_texid', 'LIKE', '%' . $keyword . '%');
                        });
                        break;

                    case 'all':
                        $query->where(function ($q) use ($keyword) {
                            $q->where('payment_number', 'LIKE', '%' . $keyword . '%')
                                ->orWhereHas('paymentCustomer', function ($q1) use ($keyword) {
                                    $q1->where('customer_name', 'LIKE', '%' . $keyword . '%')
                                        ->orWhere('customer_texid', 'LIKE', '%' . $keyword . '%');
                                })
                                ->orWhereHas('quote', function ($q1) use ($keyword) {
                                    $q1->where('quote_number', 'LIKE', '%' . $keyword . '%');
                                });
                        });
                        break;
                }
            }

            // ดึง ID ทั้งหมดที่ตรงตามเงื่อนไข
            $paymentIdsArray = $query->pluck('payment_id')->toArray();
        } else {
            // ใช้ ID ที่ส่งมาตามปกติ
            $paymentIdsString = $request->payment_ids;
            
            $paymentIdsArray = explode(',', trim($paymentIdsString, ']'));
              //dd($paymentIdsArray);
            // ลบ '[' ออกจาก index แรก
            if (isset($paymentIdsArray[0])) {
                $paymentIdsArray[0] = str_replace('[', '', $paymentIdsArray[0]);
            }
        }
    
        return Excel::download(new receiptExport($paymentIdsArray), 'receip.xlsx');
    }
}
