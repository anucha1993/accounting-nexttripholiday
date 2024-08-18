<?php

namespace App\Http\Controllers\invoices;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\products\productModel;
use App\Models\customers\customerModel;
use App\Models\quotations\quotationModel;
use App\Models\quotations\quoteProductModel;

class invoiceController extends Controller
{
    //
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

}
