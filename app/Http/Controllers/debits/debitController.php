<?php

namespace App\Http\Controllers\debits;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use App\Models\debits\debitModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\invoices\invoiceModel;
use App\Models\products\productModel;
use App\Models\customers\customerModel;
use App\Models\debits\debitNoteProductModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\quotations\quotationModel;

class debitController extends Controller
{
    //

    // function Runnumber Debit
    public function generateRunningCodeDBN()
    {
        $debitModel = debitModel::select('debit_note_number')->latest()->first();
        if (!empty($invoice)) {
            $Number = $debitModel->invoice_number;
        } else {
            $Number = 'DBN' . date('Y') . date('m') .'-'. '0000';
        }
        $prefix = 'DBN';
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
        return view('debits.form-create',compact('invoiceModel','customer','sale','tour','airline','products','quotationModel'));
    }

    public function store(Request $request) 
    {
        $runningCode = $this->generateRunningCodeDBN();
        $request->merge(['created_by' => Auth::user()->name]); 
        $request->merge(['debit_note_number' => $runningCode]); 
        $debitModel = debitModel::create($request->all());

        foreach($request->product_id as $key => $value)
        {
          if($request->product_id[$key]){
            $product = productModel::where('id',$request->product_id[$key])->first();
            debitNoteProductModel::create([
                'debit_note_id' => $debitModel->debit_note_id,
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
        return redirect()->route('debits.form-edit',$debitModel->debit_note_id);
    }

    public function edit(debitModel $debitModel) 
    {
      $invoiceModel = invoiceModel::where('invoice_number',$debitModel->invoice_number)->first();
      $customer = customerModel::where('customer_id',$invoiceModel->customer_id)->first();
      $sale = saleModel::where('id',$invoiceModel->invoice_sale)->first();
      $tour = DB::connection('mysql2')->table('tb_tour')->select('code','airline_id')->where('id',$invoiceModel->tour_id)->first();
      $airline = DB::connection('mysql2')->table('tb_travel_type')->select('travel_name')->where('id',$tour->airline_id)->first();
      $products = productModel::get();
      $quotationModel = quotationModel::where('quote_number',$invoiceModel->quote_number)->first();

      $debitnoteProduct = debitNoteProductModel::select('products.product_name','products.id','debit_note_product.product_qty',
      'debit_note_product.product_price','debit_note_product.product_id','debit_note_product.expense_type','debit_note_product.vat')
      ->where('debit_note_id',$debitModel->debit_note_id)
      ->leftjoin('products','products.id','debit_note_product.product_id')->get();
      //dd($quoteProducts);
      return view('debits.form-edit',compact('invoiceModel','customer','sale','tour','airline','products','quotationModel','debitnoteProduct','debitModel'));
    }
    

    public function update(debitModel $debitModel, Request $request)
    {
      $request->merge(['updated_by' => Auth::user()->name]); 
      $debitModel->update($request->all());

      debitNoteProductModel::where('debit_note_id',$debitModel->debit_note_id)->delete();
      foreach($request->product_id as $key => $value)
      {
        if($request->product_id[$key]){
          $product = productModel::where('id',$request->product_id[$key])->first();
          debitNoteProductModel::create([
              'debit_note_id' => $debitModel->debit_note_id,
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
