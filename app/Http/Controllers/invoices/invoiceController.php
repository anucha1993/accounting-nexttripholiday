<?php

namespace App\Http\Controllers\invoices;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\invoices\invoiceModel;
use App\Models\customers\customerModel;
use App\Models\invoices\invoicePorductsModel;

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

    public function index()
    {
        $sales = saleModel::select('name', 'id')
            ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
            ->get();

        $invoices = InvoiceModel::with('invoiceBooking.bookingSale', 'invoiceCustomer', 'invoiceWholesale')
            ->orderBy('invoices.created_at', 'desc')
            ->paginate(10);

        return view('invoices.index', compact('sales', 'invoices'));
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
       // dd($request);
        $invoiceNumber = invoiceModel::latest()->first();
        if($invoiceNumber)
        {
            $invoice_number = $invoiceNumber->invoice_number;
        }else{
            $invoice_number = 0;
           
        }
       
        $runningCodeIV = $this->generateRunningCodeIV($invoice_number);
       
        if($request->customer_type_new !== 'customerold') {
            //customerNew
           $customerModel =  customerModel::create($request->all());
        }else{
            //customerOld
             customerModel::where('customer_id',$request->customer_id)
             ->update([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_address' => $request->customer_address,
                'customer_texid' => $request->customer_texid,
                'customer_tel' =>  $request->customer_tel,
                'customer_fax' =>  $request->customer_fax,
                'customer_date' => $request->customer_date,
             ]);

            

            $customerModel = customerModel::where('customer_id',$request->customer_id)->first();
        }
        //dd($customerModel);
        $request->merge(['invoice_number'=> $runningCodeIV]); //เลขที่ใบแจ้งหนี้
        $request->merge(['invoice_status'=> 'wait']); //เลขที่ใบแจ้งหนี้
        $request->merge(['customer_id'=> $customerModel->customer_id]); // id ลูกค้า
        $request->merge(['created_by'=> Auth::user()->name]); // id ลูกค้า
        $invoice = invoiceModel::create($request->all());
        
        //ลงข้อมูลรายการสินค้า
        $sum = 0;
         foreach ($request->product_id as $key => $product)
         {
            if($request->product_id) {
                invoicePorductsModel::create([
                    'invoice_id' => $invoice->invoice_id,
                    'product_id' => $request->product_id[$key],
                    'product_name' => $request->product_name[$key],
                    'invoice_qty' => $request->invoice_qty[$key],
                    'invoice_price' => $request->invoice_price[$key],
                    'invoice_sum'  =>$request->invoice_sum[$key],
                    'expense_type' => $request->expense_type[$key],
                ]);
            }
            $sum += $request->invoice_sum[$key];
         }
         invoiceModel::where('invoice_id',$invoice->invoice_id)->update(['invoice_total'=>$sum]);

    }
    // 
    public function edit(Request $request, invoiceModel $invoiceModel)
    {
        return view('invoices.edit-invoice',compact('invoiceModel'));
    }

}
