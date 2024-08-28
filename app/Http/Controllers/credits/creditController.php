<?php

namespace App\Http\Controllers\credits;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Models\credits\creditModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\invoices\invoiceModel;
use App\Models\products\productModel;
use App\Models\customers\customerModel;
use App\Models\quotations\quotationModel;
use App\Models\credits\creditNoteProductModel;

class creditController extends Controller
{
    //
     //

    // function Runnumber Debit
    public function generateRunningCodeDBN()
    {
        $creditModel = creditModel::select('credit_note_number')->latest()->first();
        if (!empty($creditModel)) {
            $Number = $creditModel->invoice_number;
        } else {
            $Number = 'CBN' . date('Y') . date('m') .'-'. '0000';
        }
        $prefix = 'CBN';
        $year = date('Y');
        $month = date('m');
        $lastFourDigits = substr($Number, -4);
        $incrementedNumber = intval($lastFourDigits) + 1;
        $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
        $runningCode = $prefix . $year . $month .'-'. $newNumber;
        return $runningCode;
    }


    public function create(invoiceModel $invoiceModel, Request $request)
    {
      
        $customer = customerModel::where('customer_id',$invoiceModel->customer_id)->first();
        $sale = saleModel::where('id',$invoiceModel->invoice_sale)->first();
        $tour = DB::connection('mysql2')->table('tb_tour')->select('code','airline_id')->where('id',$invoiceModel->tour_id)->first();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->select('travel_name')->where('id',$tour->airline_id)->first();
        $products = productModel::get();
        $quotationModel = quotationModel::where('quote_number',$invoiceModel->quote_number)->first();
      
        //dd($quoteProducts);
        return view('credits.form-create',compact('invoiceModel','customer','sale','tour','airline','products','quotationModel'));
    }

    public function store(Request $request) 
    {
        $runningCode = $this->generateRunningCodeDBN();
        $request->merge(['created_by' => Auth::user()->name]); 
        $request->merge(['credit_note_number' => $runningCode]); 
        $creditModel = creditModel::create($request->all());

        foreach($request->product_id as $key => $value)
        {
          if($request->product_id[$key]){
            $product = productModel::where('id',$request->product_id[$key])->first();
            creditNoteProductModel::create([
                'credit_note_id' => $creditModel->credit_note_id,
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'product_qty' => $request->quantity[$key],
                'product_price' => $request->price_per_unit[$key],
                'product_sum' => $request->total_amount[$key],
                'expense_type' => $request->expense_type[$key],
                'vat' => isset($request->non_vat[$key]) ? 'Y' : 'N',
            ]);
          }
          

        }
        return redirect()->route('credit.edit',$creditModel->credit_note_id);
    }

    public function edit(creditModel $creditModel) 
    {
     // dd($creditModel->invoice_number);

      $invoiceModel = invoiceModel::where('invoice_number',$creditModel->invoice_number)->first();
    
      $customer = customerModel::where('customer_id',$invoiceModel->customer_id)->first();
      $sale = saleModel::where('id',$invoiceModel->invoice_sale)->first();
      $tour = DB::connection('mysql2')->table('tb_tour')->select('code','airline_id')->where('id',$invoiceModel->tour_id)->first();
      $airline = DB::connection('mysql2')->table('tb_travel_type')->select('travel_name')->where('id',$tour->airline_id)->first();
      $products = productModel::get();
      $quotationModel = quotationModel::where('quote_number',$invoiceModel->quote_number)->first();

      $creditnoteProduct  = creditNoteProductModel::select('products.product_name','products.id','credit_note_product.product_qty',
      'credit_note_product.product_price','credit_note_product.product_id','credit_note_product.expense_type','credit_note_product.vat')
      ->where('credit_note_id',$creditModel->credit_note_id)
      ->leftjoin('products','products.id','credit_note_product.product_id')->get();
      //dd($quoteProducts);
      return view('credits.form-edit',compact('invoiceModel','customer','sale','tour','airline','products','quotationModel','creditnoteProduct','creditModel'));
    }
    

    public function update(creditModel $creditModel, Request $request)
    {
      $request->merge(['updated_by' => Auth::user()->name]); 
      $creditModel->update($request->all());

      creditNoteProductModel::where('credit_note_id',$creditModel->credit_note_id)->delete();
      foreach($request->product_id as $key => $value)
      {
        if($request->product_id[$key]){
          $product = productModel::where('id',$request->product_id[$key])->first();
          creditNoteProductModel::create([
              'credit_note_id' => $creditModel->credit_note_id,
              'product_id' => $product->id,
              'product_name' => $product->product_name,
              'product_qty' => $request->quantity[$key],
              'product_price' => $request->price_per_unit[$key],
              'product_sum' => $request->total_amount[$key],
              'expense_type' => $request->expense_type[$key],
              'vat' => isset($request->non_vat[$key]) ? 'Y' : 'N',
          ]);
        }
        
      }

      return redirect()->back();

    }


}
