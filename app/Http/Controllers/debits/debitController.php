<?php

namespace App\Http\Controllers\debits;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\invoices\invoiceModel;
use App\Models\products\productModel;
use App\Models\customers\customerModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\quotations\quotationModel;

class debitController extends Controller
{
    //

    public function create(invoiceModel $invoiceModel, Request $request)
    {
      
        $customer = customerModel::where('customer_id',$invoiceModel->customer_id)->first();
        $sale = saleModel::where('id',$invoiceModel->invoice_sale)->first();
        $tour = DB::connection('mysql2')->table('tb_tour')->select('code','airline_id')->where('id',$invoiceModel->tour_id)->first();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->select('travel_name')->where('id',$tour->airline_id)->first();
        $products = productModel::get();
        $quotationModel = quotationModel::where('quote_number',$invoiceModel->quote_number)->first();
      
        //dd($quoteProducts);
        return view('debits.form-create',compact('invoiceModel','customer','sale','tour','airline','products','quotationModel'));
    }
    
}
