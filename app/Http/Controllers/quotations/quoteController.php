<?php

namespace App\Http\Controllers\quotations;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use App\Models\mumday\numDayModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\booking\bookingModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\invoices\invoiceModel;
use App\Models\products\productModel;
use App\Models\customers\customerModel;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;
use App\Models\quotations\quoteProductModel;
use App\Models\booking\bookingQuotationModel;

class quoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-quote|edit-booking|delete-quote|view-quote', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-quote', ['only' => ['create', 'store', 'createNew']]);
        $this->middleware('permission:edit-quote', ['only' => ['edit', 'update', 'cancel']]);
        $this->middleware('permission:delete-quote', ['only' => ['destroy']]);
    }

    public function index()
    {
        $sales = saleModel::select('name', 'id')
            ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
            ->get();

        $quotations = quotationModel::with('Salename', 'quoteCustomer', 'quoteWholesale')->orderBy('quotation.created_at', 'desc')->paginate(10);

        return view('quotations.index', compact('sales', 'quotations'));
    }

    public static function generateRunningBooking()
    {
        $prefix = 'BK';
        $year = date('y'); // ปีสองหลัก เช่น 24
        $month = date('m'); // เดือนสองหลัก เช่น 07

        $latestCode = quotationModel::select('quote_booking')->latest()->first();

        if ($latestCode) {
            $lastNumber = (int) substr($latestCode, 5); // ตัด prefix, ปี และเดือนออก
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }
        return $prefix . $year . $month . $newNumber;
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

    // function Runnumber ใบเสนอราคา
    public function generateRunningCodeCUS()
    {
        $customer = customerModel::select('customer_number')->latest()->first();
        if (!empty($customer)) {
            $CusNumber = $customer->quote_number;
        } else {
            $CusNumber = 'CUS-' . date('y') . date('m') . '0000';
        }
        $prefix = 'CUS-';
        $year = date('y');
        $month = date('m');
        $lastFourDigits = substr($CusNumber, -4);
        $incrementedNumber = intval($lastFourDigits) + 1;
        $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
        $runningCode = $prefix . $year . $month . $newNumber;
        return $runningCode;
    }

    public function generateRunningCodeTour($prefix, $dateStart, $wholesale)
    {
        $quote = quotationModel::select('quote_tour_code')->latest()->first();
        $prefix = $prefix;

        if (!empty($quote)) {
            $quoteCode = $quote->quote_tour_code;
        } else {
            $quoteCode = $prefix . date('dmy', strtotime($dateStart)) . '0000';
        }

        $dateStart = date('dmy', strtotime($dateStart));
        $lastFourDigits = substr($quoteCode, -4);
        $incrementedNumber = intval($lastFourDigits) + 1;
        $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
        $runningCode = $prefix . $dateStart . $newNumber;
        return $runningCode;
    }

    public function generateRunningCodeTourUpdate($prefix, $tourcodeOld, $dateStart, $wholesale)
    {
        $quote = quotationModel::select('quote_tour_code')->latest()->first();
        $wholesale = wholesaleModel::select('code')->where('id',$wholesale)->first();
        $prefix = $prefix;
        $code = $wholesale->code;

        if (!empty($quote)) {
            $quoteCode = $tourcodeOld;
        } else {
            $quoteCode =  $code . $prefix . date('dmy', strtotime($dateStart)) . '0000';
        }

        $dateStart = date('dmy', strtotime($dateStart));
        $lastFourDigits = substr($quoteCode, -4);
        $incrementedNumber = intval($lastFourDigits);
        $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
        $runningCode =  $code . $prefix . $dateStart . $newNumber;
        return $runningCode;
    }

    public function store(Request $request)
    {
        //dd($request);
        $runningBooking = $this->generateRunningBooking();

        if (empty($request->quote_bookin)) {
            $request->merge(['quote_booking' => $runningBooking]);
        }
        //dd($request->quote_booking);

        $country = DB::connection('mysql2')
            ->table('tb_country')
            ->select('iso2')
            ->where('id', $request->quote_country)
            ->first();
        $runningCodeTour = $this->generateRunningCodeTour($country->iso2, $request->quote_date_start, $request->quote_wholesale);
        //dd($runningCodeTour);

        $runningCode = $this->generateRunningCodeIV();

        if ($request->customer_type_new !== 'customerold') {
            $runningCodeCus = $this->generateRunningCodeCUS();
            $request->merge(['customer_number' => $runningCodeCus]);
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
                'customer_campaign_source' => $request->customer_campaign_source,
            ]);
            $customerModel = customerModel::where('customer_id', $request->customer_id)->first();
        }
        $request->merge([
            'quote_withholding_tax_status' => isset($request->quote_withholding_tax_status) ? 'Y' : 'N',
            'quote_tour_code' => $runningCodeTour,
            'quote_number' => $runningCode,
            'quote_status' => 'wait',
            'quote_payment_status' => 'wait',
            'customer_id' => $customerModel->customer_id,
            'created_by' => Auth::user()->name,
        ]);

        $quote = quotationModel::create($request->all());

        //ลงข้อมูลรายการสินค้า
        $sum = 0;
        foreach ($request->product_id as $key => $product) {
            $productName = productModel::where('id', $request->product_id[$key])->first();
            if ($request->product_id) {
                quoteProductModel::create([
                    'quote_id' => $quote->quote_id,
                    'product_id' => $request->product_id[$key],
                    'product_name' => $productName->product_name,
                    'product_qty' => $request->quantity[$key],
                    'product_price' => $request->price_per_unit[$key],
                    'product_sum' => $request->total_amount[$key],
                    'expense_type' => $request->expense_type[$key],
                    'vat_status' => $request->vat_status[$key],
                    'withholding_tax' => $request->withholding_tax[$key],
                ]);
            }
        }

        //Update status ใบจองทัวเป็น status = 'invoice'
        bookingModel::where('code', $quote->quote_booking)->update(['status' => 'quote']);
        $quoteID = $quote->quote_id;
        return redirect('quote/edit/new/' . $quoteID);
    }

    public function edit(quotationModel $quotationModel, Request $request)
    {
        $bookingModel = bookingModel::where('code', $quotationModel->quote_booking)->first();
        $customer = customerModel::where('customer_id', $quotationModel->customer_id)->first();
        $sales = saleModel::select('name', 'id')
            ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
            ->get();
        $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
        $numDays = numDayModel::orderBy('num_day_total')->get();
        $wholesale = wholesaleModel::where('status', 'on')->get();
        $products = productModel::where('product_type','!=', 'discount')->get();
        $productDiscount = productModel::where('product_type', 'discount')->get();
        $quoteProducts = quoteProductModel::where('quote_id', $quotationModel->quote_id)
            ->where('expense_type', 'income')
            ->get();
        $quoteProductsDiscount = quoteProductModel::where('quote_id', $quotationModel->quote_id)
            ->where('expense_type', 'discount')
            ->get();
        $campaignSource = DB::table('campaign_source')->get();

        return view('quotations.edit', compact('campaignSource', 'customer', 'quoteProducts', 'quotationModel', 'sales', 'country', 'airline', 'numDays', 'wholesale', 'products', 'productDiscount', 'quoteProductsDiscount'));
    }

    public function editNew(quotationModel $quotationModel, Request $request)
    {
        $sale = saleModel::where('id',$quotationModel->quote_sale)->first();
        $customer = customerModel::where('customer_id',$quotationModel->customer_id)->leftjoin('campaign_source','campaign_source.campaign_source_id','customer.customer_campaign_source')->first();
        $tour = DB::connection('mysql2')->table('tb_tour')->where('id', $quotationModel->tour_id)->first();
        $airline = DB::connection('mysql2')->table('tb_travel_type') ->select('travel_name')->where('id', $quotationModel->quote_airline)->first();
        $wholesale = wholesaleModel::where('id', $quotationModel->quote_wholesale)->first();
        $quoteProducts = quoteProductModel::where('quote_id', $quotationModel->quote_id)
        ->select('quote_product.*', 'products.product_pax')
        ->leftjoin('products','products.id','quote_product.product_id')->get();

        $quotations = quotationModel::where('quotation.quote_id',$quotationModel->quote_id)->leftjoin('customer', 'customer.customer_id', 'quotation.customer_id')->get();

        return view('quotations.form-edit-new',compact('quotationModel','customer','sale','airline','wholesale','quoteProducts','quotations'));
    }

    public function editQuote(quotationModel $quotationModel, Request $request)
    {
        $quotations = quotationModel::where('quotation.quote_id',$quotationModel->quote_id)->leftjoin('customer', 'customer.customer_id', 'quotation.customer_id')->get();
        $invoices = invoiceModel::where('invoices.invoice_quote_id',$quotationModel->quote_id)->leftjoin('customer', 'customer.customer_id', 'invoices.customer_id')->get();

        return View::make('quotations.quote-table', compact('quotations','quotationModel','invoices'))->render();
    }

    public function modalEdit(quotationModel $quotationModel)
    {
        $bookingModel = bookingModel::where('code', $quotationModel->quote_booking)->first();
        $customer = customerModel::where('customer_id', $quotationModel->customer_id)->first();
        $sales = saleModel::select('name', 'id')
            ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
            ->get();
        $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
        $numDays = numDayModel::orderBy('num_day_total')->get();
        $wholesale = wholesaleModel::where('status', 'on')->get();
        $products = productModel::where('product_type','!=', 'discount')->get();
        $productDiscount = productModel::where('product_type', 'discount')->get();
        $quoteProducts = quoteProductModel::where('quote_id', $quotationModel->quote_id)
            ->where('expense_type', 'income')
            ->get();
        $quoteProductsDiscount = quoteProductModel::where('quote_id', $quotationModel->quote_id)
            ->where('expense_type', 'discount')
            ->get();
        $campaignSource = DB::table('campaign_source')->get();

        return view('quotations.modal-edit', compact('campaignSource', 'customer', 'quoteProducts', 'quotationModel', 'sales', 'country', 'airline', 'numDays', 'wholesale', 'products', 'productDiscount', 'quoteProductsDiscount'));
    }

    







    public function update(quotationModel $quotationModel, Request $request)
    {
       // dd($request);
        $country = DB::connection('mysql2')
            ->table('tb_country')
            ->select('iso2')
            ->where('id', $request->quote_country)
            ->first();

        $runningCodeTourUpdate = $this->generateRunningCodeTourUpdate($country->iso2, $request->quote_tour_code_old, $request->quote_date_start,$request->quote_wholesale);

        $request->merge(['quote_tour_code' => $runningCodeTourUpdate,
        'updated_by' => Auth::user()->name,
       ]);

        //dd($runningCodeTourUpdate);

        $request->merge(['quote_withholding_tax_status' => isset($request->quote_withholding_tax_status) ? 'Y' : 'N']);

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

        $quotationModel->update($request->all());

        // Delete Product old
        quoteProductModel::where('quote_id', $quotationModel->quote_id)->delete();
        // Create product lits
        foreach ($request->product_id as $key => $value) {
            if ($request->product_id[$key]) {
                $productName = productModel::where('id', $request->product_id[$key])->first();
                quoteProductModel::create([
                    'quote_id' => $quotationModel->quote_id,
                    'product_id' => $request->product_id[$key],
                    'product_name' => $productName->product_name,
                    'product_qty' => $request->quantity[$key],
                    'product_price' => $request->price_per_unit[$key],
                    'product_sum' => $request->total_amount[$key],
                    'expense_type' => $request->expense_type[$key],
                    'vat_status' => $request->vat_status[$key],
                    'withholding_tax' => $request->withholding_tax[$key],
                ]);
            }
        }

        return redirect()->route('quote.editNew',$quotationModel->quote_id)->with('success', 'Update Quotation Successfully.');
    }

    public function cancel(quotationModel $quotationModel)
    {
        $quotationModel->update(['quote_status' => 'cancel']);
        return redirect()->back();
    }

    public function createNew()
    {
        $products = productModel::where('product_type','!=', 'discount')->get();
        $customers = DB::table('customer')->get();
        $sales = saleModel::select('name', 'id')
            ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
            ->get();
        $tours = DB::connection('mysql2')->table('tb_tour')->where('status', 'on')->get();
        $numDays = numDayModel::orderBy('num_day_total')->get();
        $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
        $wholesale = wholesaleModel::where('status', 'on')->get();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
        $campaignSource = DB::table('campaign_source')->get();
        $productDiscount = productModel::where('product_type', 'discount')->get();
        return view('quotations.create', compact('productDiscount', 'campaignSource', 'airline', 'wholesale', 'country', 'numDays', 'products', 'customers', 'sales', 'tours'));
    }

    public function AjaxUpdate(quotationModel $quotationModel, Request $request)
    {
        $country = DB::connection('mysql2')
            ->table('tb_country')
            ->select('iso2')
            ->where('id', $request->quote_country)
            ->first();
        $runningCodeTourUpdate = $this->generateRunningCodeTourUpdate($country->iso2, $request->quote_tour_code_old, $request->quote_date_start,$request->quote_wholesale);
        $request->merge(['quote_tour_code' => $runningCodeTourUpdate]);
        $request->merge(['quote_withholding_tax_status' => isset($request->quote_withholding_tax_status) ? 'Y' : 'N']);

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

        $quotationModel->update($request->all());

        return redirect()->back();
    }

   
    
}
