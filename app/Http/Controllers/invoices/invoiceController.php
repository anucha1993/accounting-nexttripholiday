<?php

namespace App\Http\Controllers\invoices;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use App\Models\mumday\numDayModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\quotations\quoteController;
use App\Models\booking\bookingModel;
use Illuminate\Support\Facades\Auth;
use App\Models\invoices\invoiceModel;
use App\Models\products\productModel;
use App\Models\customers\customerModel;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;
use App\Models\quotations\quoteProductModel;
use App\Models\invoices\invoicePorductsModel;

class invoiceController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-invoice|edit-invoice|delete-invoice|view-invoice', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-invoice', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-invoice', ['only' => ['edit', 'update','cancel']]);
        $this->middleware('permission:delete-invoice', ['only' => ['destroy','delete']]);
    }

    


    // function Runnumber invoice
    public function generateRunningCodeIVS()
    {
        $invoice = invoiceModel::select('invoice_number')->latest()->first();
        if (!empty($invoice)) {
            $invoiceNumber = $invoice->invoice_number;
        } else {
            $invoiceNumber = 'IVS' . date('y') . date('m') .'-'. '0000';
        }
        $prefix = 'IVS';
        $year = date('y');
        $month = date('m');
        $lastFourDigits = substr($invoiceNumber, -4);
        $incrementedNumber = intval($lastFourDigits) + 1;
        $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
        $runningCode = $prefix . $year . $month .'-'. $newNumber;
        return $runningCode;
    }

    

    public function create(quotationModel $quotationModel, Request $request)
    {
      
        $bookingModel = bookingModel::where('code',$quotationModel->quote_booking)->first();
        $customer = customerModel::where('customer_id', $quotationModel->customer_id)->first();
        $sales = saleModel::select('name', 'id')->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])->get();
        $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
        $numDays = numDayModel::orderBy('num_day_total')->get();
        $wholesale = wholesaleModel::where('status', 'on')->get();
        $products = productModel::where('product_type','!=', 'discount')->get();
        $productDiscount = productModel::where('product_type','discount')->get();
        $quoteProducts = quoteProductModel::where('quote_id',$quotationModel->quote_id)->where('expense_type','income')->get();
        $quoteProductsDiscount = quoteProductModel::where('quote_id',$quotationModel->quote_id)->where('expense_type','discount')->get();
        $campaignSource = DB::table('campaign_source')->get();

        
        return view('invoices.modal-create', compact('campaignSource','customer','quoteProducts','quotationModel','sales','country','airline','numDays','wholesale','products','productDiscount','quoteProductsDiscount'));
        
    }

    public function store(Request $request)
    {
        //dd($request);
        $runningCode = $this->generateRunningCodeIVS();
        $request->merge([
            'invoice_sale' => $request->quote_sale,
            'invoice_number' => $runningCode,
            'invoice_status' => 'wait',
            'invoice_withholding_tax_status'=> isset($request->invoice_withholding_tax_status) ? 'Y' : 'N',
         ]); 

         if($request->customer_id) {
            customerModel::where('customer_id', $request->customer_id)->update([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_address' => $request->customer_address,
                'customer_texid' => $request->customer_texid,
                'customer_tel' => $request->customer_tel,
                'customer_fax' => $request->customer_fax,
                'customer_date' => $request->customer_date,
                'customer_campaign_source' => $request->customer_campaign_source,
            ]);
         }

        $request->merge(['created_by' => Auth::user()->name]); 
       $invoice = invoiceModel::create($request->all());
       quotationModel::where('quote_id',$invoice->invoice_quote_id)->update(['quote_status'=> 'invoice']);

         // Create product lits
         foreach ($request->product_id as $key => $product) {
            
            if ($request->product_id[$key]) {
                $productName = productModel::where('id',$request->product_id[$key])->first();
                invoicePorductsModel::create([
                    'invoice_id' => $invoice->invoice_id,
                    'product_id' => $request->product_id[$key],
                    'product_name' => $productName->product_name,
                    'product_qty' => $request->quantity[$key],
                    'product_price' => $request->price_per_unit[$key],
                    'product_sum' => $request->total_amount[$key],
                    'expense_type' => $request->expense_type[$key],
                    'vat_status' => $request->vat_status[$key],
                    'withholding_tax' =>$request->withholding_tax[$key],
                ]);
            }
           
        }
        return redirect()->back();

    }

   
    public function edit(invoiceModel $invoiceModel, Request $request)
    {
      
        $quotationModel = quotationModel::where('quote_id',$invoiceModel->invoice_quote_id)->first();
        $bookingModel = bookingModel::where('code',$quotationModel->quote_booking)->first();
        $customer = customerModel::where('customer_id', $quotationModel->customer_id)->first();
        $sales = saleModel::select('name', 'id')->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])->get();
        $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
        $numDays = numDayModel::orderBy('num_day_total')->get();
        $wholesale = wholesaleModel::where('status', 'on')->get();
        $products = productModel::where('product_type','!=', 'discount')->get();
        $productDiscount = productModel::where('product_type','discount')->get();
        $invoiceProducts = invoicePorductsModel::where('invoice_id',$invoiceModel->invoice_id)->where('expense_type','income')->get();
        $invoiceProductsDiscount = invoicePorductsModel::where('invoice_id',$invoiceModel->invoice_id)->where('expense_type','discount')->get();
        $campaignSource = DB::table('campaign_source')->get();

        
        return view('invoices.modal-edit', compact('invoiceModel','campaignSource','customer','invoiceProducts','quotationModel','sales','country','airline','numDays','wholesale','products','productDiscount','invoiceProductsDiscount'));
        
    }

    public function update(invoiceModel $invoiceModel, Request $request)
    {
        //dd($request);
        
         if($request->customer_id) {
            customerModel::where('customer_id', $request->customer_id)->update([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_address' => $request->customer_address,
                'customer_texid' => $request->customer_texid,
                'customer_tel' => $request->customer_tel,
                'customer_fax' => $request->customer_fax,
                'customer_date' => $request->customer_date,
                'customer_campaign_source' => $request->customer_campaign_source,
            ]);
         }


        $request->merge([
            'invoice_withholding_tax_status'=> isset($request->invoice_withholding_tax_status) ? 'Y' : 'N',
            'updated_by' => Auth::user()->name
    ]); 
        $invoiceModel->update($request->all());
        
         // delete product lits Old
         invoicePorductsModel::where('invoice_id',$invoiceModel->invoice_id)->delete();
         foreach($request->product_id as $key => $value)
         {
           if($request->product_id[$key]){
             $product = productModel::where('id',$request->product_id[$key])->first();
             $request->merge(['vat' => isset($request->non_vat[$key]) ? 'Y' : 'N']); 
             invoicePorductsModel::create([
                'invoice_id' => $invoiceModel->invoice_id,
                'product_id' => $request->product_id[$key],
                'product_name' => $product->product_name,
                'product_qty' => $request->quantity[$key],
                'product_price' => $request->price_per_unit[$key],
                'product_sum' => $request->total_amount[$key],
                'expense_type' => $request->expense_type[$key],
                'vat_status' => $request->vat_status[$key],
                'withholding_tax' => $request->withholding_tax[$key],
            ]);
           }
 
         }

         return redirect()->back();
         
        
    }

    public function cancel(invoiceModel $invoiceModel)
    {
        $invoiceModel->update(['invoice_status' => 'cancel']);
        return redirect()->back();
    }

   


}
