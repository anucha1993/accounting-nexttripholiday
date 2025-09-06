<?php
namespace App\Http\Controllers\reports;
use App\Http\Controllers\Controller;
use App\Models\payments\paymentModel;
use Illuminate\Http\Request;

class ReceiptReportController extends Controller
{
    public function index(Request $request)
    {
        $query = paymentModel::with(['quote', 'paymentCustomer']);

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

        $query = $query->orderBy('payment_in_date', 'desc');
        
        // คำนวณผลรวมทั้งหมด
        $totalAmount = (clone $query)->sum('payment_total');
        
        // ดึงค่า perPage จาก request หรือใช้ค่า default 10
        $perPage = (int) $request->input('perPage', 10);
        
        // ดึงข้อมูลแบบแบ่งหน้า
        $receipts = $query->paginate($perPage);

        return view('reports.receipt-form', compact('receipts', 'request', 'totalAmount', 'perPage'));
    }
}
