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
use App\Models\invoices\taxinvoiceModel;
use App\Models\quotations\quotationModel;
use App\Models\credits\creditNoteProductModel;

class creditController extends Controller
{
    //
     //

    // function Runnumber Creadit
    public function generateRunningCodeCBN()
    {
        $creditModel = creditModel::select('credit_number')->latest()->first();
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
        $taxinvoiceModel = taxinvoiceModel::where('invoice_number',$invoiceModel->invoice_number)->first();
        $customer = customerModel::where('customer_id',$invoiceModel->customer_id)->first();
        $sales = saleModel::where('id',$invoiceModel->invoice_sale)->first();
        $tour = DB::connection('mysql2')->table('tb_tour')->select('code','airline_id')->where('id',$invoiceModel->tour_id)->first();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->select('travel_name')->where('id',$invoiceModel->invoice_airline)->first();
        $products = productModel::where('product_type','income')->get();
        $productDiscount = productModel::where('product_type','discount')->get();
        $quotationModel = quotationModel::where('quote_number',$invoiceModel->invoice_quote)->first();
        $campaignSource = DB::table('campaign_source')->get();
        $causes = DB::table('list_credit')->get();
        //dd($quoteProducts);
        return view('credits.form-create',compact('causes','taxinvoiceModel','invoiceModel','customer','sales','tour','airline','products','quotationModel','campaignSource','productDiscount'));
    }

    public function store(Request $request) 
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

        $runningCode = $this->generateRunningCodeCBN();
        $request->merge(['created_by' => Auth::user()->name,
        'credit_withholding_tax_status'=> isset($request->credit_withholding_tax_status) ? 'Y' : 'N',
        'credit_status' => 'wait',
      ]); 
        $request->merge(['credit_number' => $runningCode]); 
        $creditModel = creditModel::create($request->all());

        foreach ($request->product_id as $key => $product) {
            
          if ($request->product_id[$key]) {
              $productName = productModel::where('id',$request->product_id[$key])->first();
              creditNoteProductModel::create([
                  'credit_id' => $creditModel->credit_id,
                  'product_id' => $request->product_id[$key],
                  'product_name' => $productName->product_name,
                  'product_qty' => $request->quantity[$key],
                  'product_price' => $request->price_per_unit[$key],
                  'product_sum' => $request->total_amount[$key],
                  'expense_type' => $request->expense_type[$key],
                  'vat_status' => $request->vat_status[$key],
                  'withholding_tax' => isset($request->withholding_tax[$key]) ? 'Y' : 'N',
              ]);
          }
         
      }

        return redirect()->route('credit.edit',$creditModel->credit_id);
    }


    public function edit(creditModel $creditModel) 
    {

      $invoiceModel = invoiceModel::where('invoice_number',$creditModel->credit_invoice)->first();
      $customer = customerModel::where('customer_id',$invoiceModel->customer_id)->first();
      $sales = saleModel::where('id',$invoiceModel->invoice_sale)->first();
      $tour = DB::connection('mysql2')->table('tb_tour')->select('code','airline_id')->where('id',$invoiceModel->tour_id)->first();
      $airline = DB::connection('mysql2')->table('tb_travel_type')->select('travel_name')->where('id',$invoiceModel->invoice_airline)->first();
      $products = productModel::where('product_type','income')->get();
      $productDiscount = productModel::where('product_type','discount')->get();

      $quotationModel = quotationModel::where('quote_number',$invoiceModel->invoice_quote)->first();
      $campaignSource = DB::table('campaign_source')->get();
      $creditProducts = creditNoteProductModel::where('credit_id',$creditModel->credit_id)->where('expense_type','income')->get();
      $creditProductDiscount = creditNoteProductModel::where('credit_id',$creditModel->credit_id)->where('expense_type','discount')->get();
      $causes = DB::table('list_credit')->get();
      //dd($quoteProducts);
      return view('credits.form-edit',compact('causes','creditProductDiscount','creditProducts','creditModel','invoiceModel','customer','sales','tour','airline','products','quotationModel','campaignSource','productDiscount'));
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
