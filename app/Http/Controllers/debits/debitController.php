<?php

namespace App\Http\Controllers\debits;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use App\Models\debits\debitModel;
use App\Models\mumday\numDayModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\booking\bookingModel;
use Illuminate\Support\Facades\Auth;
use App\Models\invoices\invoiceModel;
use App\Models\products\productModel;
use App\Models\customers\customerModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\invoices\taxinvoiceModel;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;
use App\Models\debits\debitNoteProductModel;
use App\Models\invoices\invoicePorductsModel;

class debitController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');

    }

    // function Runnumber Debit
    public function generateRunningCodeDBN()
    {
        $debitModel = debitModel::select('debit_number')->latest()->first();
        if (!empty($debitModel)) {
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
      
       $taxinvoiceModel = taxinvoiceModel::where('invoice_id',$invoiceModel->invoice_id)->first();
       $customer = customerModel::where('customer_id',$invoiceModel->customer_id)->first();
       $campaignSource = DB::table('campaign_source')->get();
       $products = productModel::where('product_type','!=', 'discount')->get();
       $productDiscount = productModel::where('product_type','discount')->get();
       $debitCause = DB::table('list_debit')->get();
        return view('debits.modal-create', compact('taxinvoiceModel','invoiceModel','customer','campaignSource','products','productDiscount','debitCause'));
        
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

        $runningCode = $this->generateRunningCodeDBN();
        $request->merge(['created_by' => Auth::user()->name,
        'debit_withholding_tax_status'=> isset($request->debit_withholding_tax_status) ? 'Y' : 'N',
        'debit_status' => 'wait',
      ]); 
        $request->merge(['debit_number' => $runningCode]); 
        $debitModel = debitModel::create($request->all());

        foreach ($request->product_id as $key => $product) {
            
          if ($request->product_id[$key]) {
              $productName = productModel::where('id',$request->product_id[$key])->first();
              debitNoteProductModel::create([
                  'debit_id' => $debitModel->debit_id,
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

    public function edit(debitModel $debitModel) 
    {

      $invoiceModel = invoiceModel::where('invoice_number',$debitModel->debit_invoice_number)->first();
      $customer = customerModel::where('customer_id',$invoiceModel->customer_id)->first();
      $sales = saleModel::where('id',$invoiceModel->invoice_sale)->first();
      $tour = DB::connection('mysql2')->table('tb_tour')->select('code','airline_id')->where('id',$invoiceModel->tour_id)->first();
      $airline = DB::connection('mysql2')->table('tb_travel_type')->select('travel_name')->where('id',$invoiceModel->invoice_airline)->first();
      $products = productModel::where('product_type','income')->get();
      $productDiscount = productModel::where('product_type','discount')->get();

      $quotationModel = quotationModel::where('quote_number',$invoiceModel->invoice_quote)->first();
      $campaignSource = DB::table('campaign_source')->get();
      $debitProducts = debitNoteProductModel::where('debit_id',$debitModel->debit_id)->where('expense_type','income')->get();
      $debitProductDiscount = debitNoteProductModel::where('debit_id',$debitModel->debit_id)->where('expense_type','discount')->get();
      $debitCause  = DB::table('list_debit')->get();
      //dd($quoteProducts);

      return view('debits.modal-edit',compact('debitCause','debitProductDiscount','debitProducts','debitModel','invoiceModel','customer','sales','tour','airline','products','quotationModel','campaignSource','productDiscount'));
    }
    

    public function update(debitModel $debitModel, Request $request)
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

       $request->merge(['updated_by' => Auth::user()->name,
        'debit_withholding_tax_status'=> isset($request->debit_withholding_tax_status) ? 'Y' : 'N',
      ]); 

      $debitModel->update($request->all());
      debitNoteProductModel::where('debit_id',$debitModel->debit_id)->delete();

      foreach ($request->product_id as $key => $product) {
            
        if ($request->product_id[$key]) {
            $productName = productModel::where('id',$request->product_id[$key])->first();
            debitNoteProductModel::create([
              'debit_id' => $debitModel->debit_id,
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


}
