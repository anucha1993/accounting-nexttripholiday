<?php

namespace App\Http\Controllers\invoices;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\customers\customerModel;

class invoiceController extends Controller
{
    // 
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-invoice|edit-invoice|delete-invoice|view-invoice', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-invoice', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-invoice', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-invoice', ['only' => ['destroy']]);
    }

    // function Runnumber  เลขที่อ้างอิง
    public function generateRunningCodeIV($bookingNumber)
    {
        $prefix = 'IV';
        $year = date('y'); // Last two digits of the current year
        $month = date('m'); // Current month
    
        // Extract the last 4 digits from the booking number
        $lastFourDigits = substr($bookingNumber, -4);
    
        // Convert the last four digits to an integer and increment
        $incrementedNumber = intval($lastFourDigits) + 1;
    
        // Format the incremented number to be 4 digits with leading zeros
        $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
    
        // Generate the new running code
        $runningCode = $prefix . $year . $month . $newNumber;
    
        return $runningCode;
    }
    
    // function Runnumber  เลขที่ใบแจ้งหนี้
    public function generateRunningCodeIVS()
    {
        $prefix = 'IVS';
        $year = date('Y');
        $month = date('m');

        $lastInvoiceNumber = DB::table('invoices')
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->orderBy('id', 'desc')
            ->value('invoice_code');
        $lastFourDigits = substr($lastInvoiceNumber, -4);
        $newNumber = str_pad($lastFourDigits + 1, 4, '0', STR_PAD_LEFT);

        $runningCode = $prefix . $year . $month . '-' . $newNumber;

        return $runningCode;
    }

    public function store(Request $request)
    {
        dd($request);
        $runningCodeIV = $this->generateRunningCodeIV($request->booking_number);

        if($request->customer_type_new === 'customerNew') {

            customerModel::create($request->all());
            return 'customerNew';
        }else{
            customerModel::where('customer_id',$request->customer_id)->update($request->all());
            return 'customerOld';
        }

        $request->merge(['invoice_number'=> $runningCodeIV]);

    }

    // 
    public function edit()
    {
        return view('invoices.edit-invoice');
    }


}
