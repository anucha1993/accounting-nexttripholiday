<?php

namespace App\Http\Controllers\invoices;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\products\productModel;
use App\Models\customers\customerModel;
use App\Models\invoices\invoiceModel;
use App\Models\quotations\quotationModel;
use App\Models\quotations\quoteProductModel;
use Illuminate\Support\Facades\Auth;

class invoiceController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }


    // function Runnumber ใบเสนอราคา
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
      
        $customer = customerModel::where('customer_id',$quotationModel->customer_id)->first();
        $sale = saleModel::where('id',$quotationModel->quote_sale)->first();
        $tour = DB::connection('mysql2')->table('tb_tour')->select('code','airline_id')->where('id',$quotationModel->tour_id)->first();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->select('travel_name')->where('id',$tour->airline_id)->first();
        $products = productModel::get();
        $quoteProducts = quoteProductModel::select('products.product_name','products.id','quote_product.product_qty',
        'quote_product.product_price','quote_product.product_id','quote_product.expense_type','quote_product.vat')
        ->where('quote_id',$quotationModel->quote_id)->leftjoin('products','products.id','quote_product.product_id')->get();
        //dd($quoteProducts);
        return view('invoices.form-create',compact('quotationModel','customer','sale','tour','airline','products','quoteProducts','quoteProducts'));
    }

    public function store(Request $request)
    {
        $runningCode = $this->generateRunningCodeIVS();
        $request->merge(['invoice_number' => $runningCode]); 
        $request->merge(['created_by' => Auth::user()->name]); 
        invoiceModel::create($request->all());
        quotationModel::where('quote_number',$request->quote_number)->update(['quote_status'=> 'invoice']);
        return redirect('quote/sales/info/'.$request->quote_id);

    }

}
