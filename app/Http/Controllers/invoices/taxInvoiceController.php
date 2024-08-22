<?php

namespace App\Http\Controllers\invoices;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\invoices\invoiceModel;
use App\Models\products\productModel;
use App\Models\customers\customerModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\invoices\taxinvoiceModel;
use App\Models\quotations\quotationModel;

use App\Models\invoices\invoicePorductsModel;
use App\Models\taxinvoices\taxinvoiceProductModel;
use App\Models\invoices\taxinvoiceModel as InvoicestaxinvoiceModel;

class taxInvoiceController extends Controller
{
    //
     // function Runnumber invoice
     public function generateRunningCodeRV()
     {
         $taxtinvoice = InvoicestaxinvoiceModel::select('taxinvoice_number')->latest()->first();
         if (!empty($invoice)) {
             $taxinvoiceNumber = $taxtinvoice->taxinvoice_number;
         } else {
             $taxinvoiceNumber = 'RV' . date('Y') . date('m') .'-'. '0000';
         }
         $prefix = 'RV';
         $year = date('Y');
         $month = date('m');
         $lastFourDigits = substr($taxinvoiceNumber, -4);
         $incrementedNumber = intval($lastFourDigits) + 1;
         $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
         $runningCode = $prefix . $year . $month .'-'. $newNumber;
         return $runningCode;
     }

     public function store(invoiceModel $invoiceModel, Request $request)
     {
         $runningCode = $this->generateRunningCodeRV();
          taxinvoiceModel::create([
            'taxinvoice_number' => $runningCode,
            'taxinvoice_date' => date('Y-m-d') ,
            'invoice_number' => $invoiceModel->invoice_number,
            'created_by' => Auth::user()->name, 
          ]);
          $invoiceModel->update(['taxinvoice_number' => $runningCode, 'invoice_status' => 'taxinvoice']);
 
         return redirect()->back();
 
     }

     public function edit(invoiceModel $invoiceModel, Request $request)
    {
        $quotationModel = quotationModel::where('quote_number',$invoiceModel->quote_number)->first();
        $customer = customerModel::where('customer_id',$invoiceModel->customer_id)->first();
        $sale = saleModel::where('id',$invoiceModel->invoice_sale)->first();
        $tour = DB::connection('mysql2')->table('tb_tour')->select('code','airline_id')->where('id',$invoiceModel->tour_id)->first();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->select('travel_name')->where('id',$tour->airline_id)->first();
        $products = productModel::get();
        $invoiceProducts = invoicePorductsModel::select('products.product_name','products.id','invoice_product.product_qty',
        'invoice_product.product_price','invoice_product.product_id','invoice_product.expense_type','invoice_product.vat')
        ->where('invoice_id',$invoiceModel->invoice_id)->leftjoin('products','products.id','invoice_product.product_id')->get();
        //dd($quoteProducts);
        $taxinvoice = taxinvoiceModel::where('invoice_number',$invoiceModel->invoice_number)->first();
        return view('taxinvoices.form-edit',compact('quotationModel','invoiceModel','customer','sale','tour','airline','products','invoiceProducts','taxinvoice'));
    }


    public function update(invoiceModel $invoiceModel, Request $request)
    {
        //dd($invoiceModel->invoice_id);
        $invoiceModel->update($request->all());
        taxinvoiceModel::where('invoice_number',$invoiceModel->invoice_number)->update(
            [
                'taxinvoice_date' => $request->taxinvoice_date ,
                'taxinvoice_note' => $request->taxinvoice_note ,
                'updated_by' => Auth::user()->name, 
              ]
        );
        
         // delete product lits Old
         invoicePorductsModel::where('invoice_id',$invoiceModel->invoice_id)->delete();
         foreach($request->product_id as $key => $value)
         {
           if($request->product_id[$key]){
             $product = productModel::where('id',$request->product_id[$key])->first();
             
             invoicePorductsModel::create([
                 'invoice_id' => $invoiceModel->invoice_id,
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
