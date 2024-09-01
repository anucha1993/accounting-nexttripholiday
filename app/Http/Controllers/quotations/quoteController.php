<?php

namespace App\Http\Controllers\quotations;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\booking\bookingModel;
use Illuminate\Support\Facades\Auth;
use App\Models\invoices\invoiceModel;
use App\Models\customers\customerModel;
use App\Models\products\productModel;
use App\Models\quotations\quotationModel;
use App\Models\quotations\quoteProductModel;

class quoteController extends Controller
{
    public function index()
    {
        $sales = saleModel::select('name', 'id')->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])->get();

        $quotations = quotationModel::with('quoteBooking.bookingSale', 'quoteCustomer', 'quoteWholesale')->orderBy('quotation.created_at', 'desc')->paginate(10);

        return view('quotations.index', compact('sales', 'quotations'));
    }

    // function Runnumber ใบเสนอราคา
    public function generateRunningCodeIV()
    {
        $quote = quotationModel::select('quote_number')->latest()->first();
        if (!empty($quote)) {
            $quoteNumber = $quote->quote_number;
        } else {
            $quoteNumber = 'QT' . date('y') . date('m') . '0000';
        }
        $prefix = 'QT';
        $year = date('y');
        $month = date('m');
        $lastFourDigits = substr($quoteNumber, -4);
        $incrementedNumber = intval($lastFourDigits) + 1;
        $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
        $runningCode = $prefix . $year . $month . $newNumber;
        return $runningCode;
    }

    public function store(Request $request)
    {
        $runningCode = $this->generateRunningCodeIV();
        if ($request->customer_type_new !== 'customerold') {
            //customerNew
            $customerModel = customerModel::create($request->all());
        } else {
            //customerOld
            customerModel::where('customer_id', $request->customer_id)->update([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_address' => $request->customer_address,
                'customer_texid' => $request->customer_texid,
                'customer_tel' => $request->customer_tel,
                'customer_fax' => $request->customer_fax,
                'customer_date' => $request->customer_date,
            ]);
            $customerModel = customerModel::where('customer_id', $request->customer_id)->first();
        }

        $request->merge(['quote_number' => $runningCode]); //เลขที่ใบแจ้งหนี้
        $request->merge(['quote_status' => 'wait']); //เลขที่ใบแจ้งหนี้
        $request->merge(['customer_id' => $customerModel->customer_id]); // id ลูกค้า
        $request->merge(['created_by' => Auth::user()->name]); // id ลูกค้า
        $quote = quotationModel::create($request->all());

        //ลงข้อมูลรายการสินค้า
        $sum = 0;
        foreach ($request->product_id as $key => $product) {
            if ($request->product_id) {
                quoteProductModel::create([
                    'quote_id' => $quote->quote_id,
                    'product_id' => $request->product_id[$key],
                    'product_name' => $request->product_name[$key],
                    'product_qty' => $request->product_qty[$key],
                    'product_price' => $request->product_price[$key],
                    'product_sum' => $request->product_sum[$key],
                    'expense_type' => $request->expense_type[$key],
                ]);
            }
            $sum += $request->product_sum[$key];
        }
        $quote->update(['quote_total' => $sum]);

        //Update status ใบจองทัวเป็น status = 'invoice'
        bookingModel::where('code', $quote->quote_booking)->update(['status' => 'quote']);
        $quoteID = $quote->quote_id;
        return redirect('quote/edit/' . $quoteID);
    }

    public function edit(quotationModel $quotationModel, Request $request)
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
        return view('quotations.edit',compact('quotationModel','customer','sale','tour','airline','products','quoteProducts','quoteProducts'));
    }

    public function update(quotationModel $quotationModel, Request $request)
    {
        // dd($request);
        $request->merge(['vat_3_status' => isset($request->vat3_status) ? 'Y' : 'N']); //เลขที่ใบแจ้งหนี้

        $quotationModel->update($request->all());
        
        // Delete Product old
        quoteProductModel::where('quote_id',$quotationModel->quote_id)->delete();
        // Create product lits
        foreach($request->product_id as $key => $value)
        {
          if($request->product_id[$key]){
            $product = productModel::where('id',$request->product_id[$key])->first();
            
            quoteProductModel::create([
                'quote_id' => $quotationModel->quote_id,
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

        return redirect()->back()->with('success','Update Quotation Successfully.');
    }
}
