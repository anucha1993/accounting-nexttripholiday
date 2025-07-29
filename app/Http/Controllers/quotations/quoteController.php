<?php

namespace App\Http\Controllers\quotations;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\User;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use App\Models\debits\debitModel;
use App\Models\mumday\numDayModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\booking\bookingModel;
use App\Models\booking\countryModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\invoices\invoiceModel;
use App\Models\payments\paymentModel;
use App\Models\products\productModel;
use App\Models\customers\customerModel;
use App\Models\invoices\taxinvoiceModel;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;
use App\Models\quotations\quoteProductModel;
use App\Models\booking\bookingQuotationModel;
use App\Models\withholding\WithholdingTaxDocument;
use Illuminate\Support\Facades\Log;
require_once app_path('Helpers/statusPaymentHelper.php');
require_once app_path('Helpers/statusPaymentWhosale.php');

class quoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function generateRunningBooking()
    {
        $quote = quotationModel::select('quote_booking')->latest()->first();
        if (!empty($quote)) {
            $quoteNumber = $quote->quote_booking;
        } else {
            $quoteNumber = 'BK' . date('y') . date('m') . '0000';
        }
        $prefix = 'BK';
        $year = date('y');
        $month = date('m');
        $lastFourDigits = substr($quoteNumber, -4);
        $incrementedNumber = intval($lastFourDigits) + 1;
        $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
        $runningCode = $prefix . $year . $month . $newNumber;
        return $runningCode;
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
            $CusNumber = $customer->customer_number;
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
        $wholesale = wholesaleModel::select('code')->where('id', $wholesale)->first();
        $prefix = $prefix;
        $code = $wholesale->code;

        if (!empty($quote)) {
            $quoteCode = $tourcodeOld;
        } else {
            $quoteCode = $code . $prefix . date('dmy', strtotime($dateStart)) . '0000';
        }

        $dateStart = date('dmy', strtotime($dateStart));
        $lastFourDigits = substr($quoteCode, -4);
        $incrementedNumber = intval($lastFourDigits);
        $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
        $runningCode = $code . $prefix . $dateStart . $newNumber;
        return $runningCode;
    }

    public function store(Request $request)
    {
        $runningBooking = $this->generateRunningBooking();

        if (empty($request->quote_bookin)) {
            $request->merge(['quote_booking' => $runningBooking]);
        }
        //dd($request);
        //dd($request->quote_booking);

        $country = DB::connection('mysql2')->table('tb_country')->select('iso2')->where('id', $request->quote_country)->first();
        $runningCodeTour = $this->generateRunningCodeTour($country->iso2, $request->quote_date_start, $request->quote_wholesale);
        //dd($runningCodeTour);

        $runningCode = $this->generateRunningCodeIV();

        if ($request->customer_type_new !== 'customerold') {
            // ตรวจสอบลูกค้าซ้ำตามเงื่อนไขที่กำหนด
            $customerName = trim($request->customer_name);
            $customerEmail = trim($request->customer_email);
            $customerByName = customerModel::where('customer_name', $customerName)->first();
            $customerByEmail = customerModel::where('customer_email', $customerEmail)->first();

            if ($customerByName && $customerByEmail && $customerByName->customer_id === $customerByEmail->customer_id) {
                // กรณีชื่อและอีเมลตรงกัน ให้ update
                $customerByName->update([
                    'customer_name' => $customerName,
                    'customer_email' => $customerEmail,
                    'customer_address' => $request->customer_address,
                    'customer_texid' => $request->customer_texid,
                    'customer_tel' => $request->customer_tel,
                    'customer_fax' => $request->customer_fax,
                    'customer_date' => $request->customer_date,
                    'customer_campaign_source' => $request->customer_campaign_source,
                    'customer_social_id' => $request->customer_social_id,
                ]);
                $customerModel = $customerByName;
            } else {
                // insert ใหม่ (กรณีชื่อซ้ำแต่ email ไม่ตรง หรือ email ซ้ำแต่ชื่อไม่ตรง หรือไม่ซ้ำทั้งคู่)
                $runningCodeCus = $this->generateRunningCodeCUS();
                $request->merge(['customer_number' => $runningCodeCus]);
                $customerModel = customerModel::create($request->all());
            }
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
                'customer_social_id' => $request->customer_social_id,
            ]);
            $customerModel = customerModel::where('customer_id', $request->customer_id)->first();
        }
        $request->merge([
            'quote_withholding_tax_status' => isset($request->quote_withholding_tax_status) ? 'Y' : 'N',
            'quote_tour_code' => $request->filled('quote_tour_code') ? $request->quote_tour_code : $runningCodeTour,
            'quote_number' => $runningCode,
            'quote_status' => 'wait',
            'quote_payment_status' => 'wait',
            'customer_id' => $customerModel->customer_id,
            'created_by' => Auth::user()->name,
        ]);

        $quote = quotationModel::create($request->all());

        // ลงข้อมูลรายการสินค้าและส่วนลด (unified row)
        if ($request->product_id && is_array($request->product_id)) {
            foreach ($request->product_id as $key => $productId) {
                if (!$productId) {
                    continue;
                }
                $productName = productModel::where('id', $productId)->first();
                quoteProductModel::create([
                    'quote_id' => $quote->quote_id,
                    'product_id' => $productId,
                    'product_name' => $productName ? $productName->product_name : '',
                    'product_qty' => $request->quantity[$key] ?? 1,
                    'product_price' => $request->price_per_unit[$key] ?? 0,
                    'product_sum' => $request->total_amount[$key] ?? 0,
                    'expense_type' => $request->expense_type[$key] ?? 'income',
                    'vat_status' => $request->vat_status[$key] ?? 'nonvat',
                    'withholding_tax' => $request->withholding_tax[$key] ?? 'N',
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
        $products = productModel::where('product_type', '!=', 'discount')->get();
        $productDiscount = productModel::where('product_type', 'discount')->get();
        $quoteProducts = quoteProductModel::where('quote_id', $quotationModel->quote_id)->where('expense_type', 'income')->get();
        $quoteProductsDiscount = quoteProductModel::where('quote_id', $quotationModel->quote_id)->where('expense_type', 'discount')->get();
        $campaignSource = DB::table('campaign_source')->get();

        return view('quotations.edit', compact('campaignSource', 'customer', 'quoteProducts', 'quotationModel', 'sales', 'country', 'airline', 'numDays', 'wholesale', 'products', 'productDiscount', 'quoteProductsDiscount'));
    }

    public function editNew(quotationModel $quotationModel, Request $request)
    {
        $sale = saleModel::select('name', 'id')
                ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
                ->where('id', $quotationModel->quote_sale)
                ->first();

        
        $customer = customerModel::where('customer_id', $quotationModel->customer_id)->leftjoin('campaign_source', 'campaign_source.campaign_source_id', 'customer.customer_campaign_source')->first();
        $tour = DB::connection('mysql2')->table('tb_tour')->where('id', $quotationModel->tour_id)->first();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->select('travel_name')->where('id', $quotationModel->quote_airline)->first();
        $wholesale = wholesaleModel::where('id', $quotationModel->quote_wholesale)->first();
        $quoteProducts = quoteProductModel::where('quote_id', $quotationModel->quote_id)->select('quote_product.*', 'products.product_pax')->leftjoin('products', 'products.id', 'quote_product.product_id')->get();

        $quotations = quotationModel::where('quotation.quote_id', $quotationModel->quote_id)
                      ->leftjoin('customer', 'customer.customer_id', 'quotation.customer_id')
                      ->get();

        $invoiceModel = invoiceModel::where('invoice_quote_id', $quotationModel->quote_id)->first();

        return view('quotations.form-edit-new', compact('quotationModel', 'customer', 'sale', 'airline', 'wholesale', 'quoteProducts', 'quotations', 'invoiceModel'));
    }

    public function editQuote(quotationModel $quotationModel, Request $request)
    {
        $quotations = quotationModel::where('quotation.quote_id', $quotationModel->quote_id)->leftjoin('customer', 'customer.customer_id', 'quotation.customer_id')->get();
        $invoices = invoiceModel::where('invoices.invoice_quote_id', $quotationModel->quote_id)->leftjoin('customer', 'customer.customer_id', 'invoices.customer_id')->get();
        $invoicesIds = $invoices->pluck('invoice_id');
        $taxinvoices = taxinvoiceModel::whereIn('taxinvoices.invoice_id', $invoicesIds)->leftjoin('invoices', 'invoices.invoice_number', 'taxinvoices.invoice_number')->leftjoin('customer', 'customer.customer_id', 'invoices.customer_id')->get();

        $invoiceModel = $invoices->first();

        $taxinvoiceIds = $taxinvoices->pluck('taxinvoice_number');
        // $debits = debitModel::whereIn('debit_taxinvoice_number', $taxinvoiceIds)->get();

        $quoteProducts = quoteProductModel::where('quote_id', $quotationModel->quote_id)->select('quote_product.*', 'products.product_pax')->leftJoin('products', 'products.id', '=', 'quote_product.product_id')->where('quote_product.expense_type', 'income')->get();
        $quoteProductsDiscount = quoteProductModel::where('quote_id', $quotationModel->quote_id)->select('quote_product.*', 'products.product_pax')->leftJoin('products', 'products.id', '=', 'quote_product.product_id')->where('expense_type', 'discount')->get();
        $document = WithholdingTaxDocument::where('quote_id', $quotationModel->quote_id)->first();
        return View::make('quotations.quote-table', compact('document', 'quoteProductsDiscount', 'quoteProducts', 'quotations', 'quotationModel', 'invoices', 'taxinvoices', 'invoiceModel'))->render();
    }

    public function modalEdit(quotationModel $quotationModel, Request $request)
    {
        $bookingModel = bookingModel::where('code', $quotationModel->quote_booking)->first();
        $customer = customerModel::where('customer_id', $quotationModel->customer_id)->first();
        if (Auth::user()->getRoleNames()->contains('sale')) {
            $sales = saleModel::select('name', 'id')
                ->where('id', Auth::user()->sale_id)
                ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
                ->get();
        } else {
            $sales = saleModel::select('name', 'id')
                ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
                ->get();
        }
        $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
        $numDays = numDayModel::orderBy('num_day_total')->get();
        $wholesale = wholesaleModel::where('status', 'on')->get();
        $products = productModel::where('product_type', '!=', 'discount')->get();
        $productDiscount = productModel::where('product_type', 'discount')->get();
        $quoteProducts = quoteProductModel::where('quote_id', $quotationModel->quote_id)->where('expense_type', 'income')->get();
        $quoteProductsDiscount = quoteProductModel::where('quote_id', $quotationModel->quote_id)->where('expense_type', 'discount')->get();
        $campaignSource = DB::table('campaign_source')->get();
        $mode = $request->get('mode', 'view');
        return view('quotations.modal-edit', compact('mode', 'campaignSource', 'customer', 'quoteProducts', 'quotationModel', 'sales', 'country', 'airline', 'numDays', 'wholesale', 'products', 'productDiscount', 'quoteProductsDiscount'));
    }

    public function modalView(quotationModel $quotationModel, Request $request)
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
        $products = productModel::where('product_type', '!=', 'discount')->get();
        $productDiscount = productModel::where('product_type', 'discount')->get();
        $quoteProducts = quoteProductModel::where('quote_id', $quotationModel->quote_id)->where('expense_type', 'income')->get();
        $quoteProductsDiscount = quoteProductModel::where('quote_id', $quotationModel->quote_id)->where('expense_type', 'discount')->get();

        $campaignSource = DB::table('campaign_source')->get();
        $mode = $request->get('mode', 'view');
        return view('quotations.modal-view', compact('mode', 'campaignSource', 'customer', 'quoteProducts', 'quotationModel', 'sales', 'country', 'airline', 'numDays', 'wholesale', 'products', 'productDiscount', 'quoteProductsDiscount'));
    }

    public function modalEditCopy(quotationModel $quotationModel)
    {
        $bookingModel = bookingModel::where('code', $quotationModel->quote_booking)->first();
        $customer = customerModel::where('customer_id', $quotationModel->customer_id)->first();
        if (Auth::user()->getRoleNames()->contains('sale')) {
            $sales = saleModel::select('name', 'id')
                ->where('id', Auth::user()->sale_id)
                ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
                ->get();
        } else {
            $sales = saleModel::select('name', 'id')
                ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
                ->get();
        }
        $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
        $numDays = numDayModel::orderBy('num_day_total')->get();
        $wholesale = wholesaleModel::where('status', 'on')->get();
        $products = productModel::where('product_type', '!=', 'discount')->get();
        $productDiscount = productModel::where('product_type', 'discount')->get();
        $quoteProducts = quoteProductModel::where('quote_id', $quotationModel->quote_id)->where('expense_type', 'income')->get();
        $quoteProductsDiscount = quoteProductModel::where('quote_id', $quotationModel->quote_id)->where('expense_type', 'discount')->get();
        $campaignSource = DB::table('campaign_source')->get();

        return view('quotations.modal-copy', compact('campaignSource', 'customer', 'quoteProducts', 'quotationModel', 'sales', 'country', 'airline', 'numDays', 'wholesale', 'products', 'productDiscount', 'quoteProductsDiscount'));
    }

    public function update(quotationModel $quotationModel, Request $request)
    {
        //dd($request);
        $country = DB::connection('mysql2')->table('tb_country')->select('iso2')->where('id', $request->quote_country)->first();

        $runningCodeTourUpdate = $this->generateRunningCodeTourUpdate($country->iso2, $request->quote_tour_code_old, $request->quote_date_start, $request->quote_wholesale);

        $request->merge([
            'quote_tour_code' => $request->filled('quote_tour_code') ? $request->quote_tour_code : $runningCodeTourUpdate,
            'updated_by' => Auth::user()->name,
        ]);

        //dd($runningCodeTourUpdate);

        $checkPaymentTotal = paymentModel::where('payment_quote_id', $quotationModel->quote_id)->where('payment_status', 'success')->sum('payment_total');
        $quotePaymentStatus = 'wait';
        if ($checkPaymentTotal >= $quotationModel->quote_grand_total) {
            $quotePaymentStatus = 'success';
            $paymentStatus = 'success';
        } elseif ($checkPaymentTotal <= 0) {
            $paymentStatus = 'wait';
        } else {
            $quotePaymentStatus = 'wait';
            $paymentStatus = 'payment';
        }

        $request->merge([
            'quote_withholding_tax_status' => isset($request->quote_withholding_tax_status) ? 'Y' : 'N',
            'quote_payment_status' => $paymentStatus,
            'quote_status' => $quotePaymentStatus,
            'payment' => $checkPaymentTotal,
        ]);

        customerModel::where('customer_id', $request->customer_id)->update([
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_address' => $request->customer_address,
            'customer_texid' => $request->customer_texid,
            'customer_tel' => $request->customer_tel,
            'customer_fax' => $request->customer_fax,
            'customer_date' => $request->customer_date,
            'customer_campaign_source' => $request->customer_campaign_source,
            'customer_social_id' => $request->customer_social_id,
        ]);

        $quotationModel->update($request->all());

        // Delete Product old
        quoteProductModel::where('quote_id', $quotationModel->quote_id)->delete();
        // Create product & discount rows (unified)
        if ($request->product_id && is_array($request->product_id)) {
            foreach ($request->product_id as $key => $productId) {
                if (!$productId) {
                    continue;
                }
                $productName = productModel::where('id', $productId)->first();
                quoteProductModel::create([
                    'quote_id' => $quotationModel->quote_id,
                    'product_id' => $productId,
                    'product_name' => $productName ? $productName->product_name : '',
                    'product_qty' => $request->quantity[$key] ?? 1,
                    'product_price' => $request->price_per_unit[$key] ?? 0,
                    'product_sum' => $request->total_amount[$key] ?? 0,
                    'expense_type' => $request->expense_type[$key] ?? 'income',
                    'vat_status' => $request->vat_status[$key] ?? 'nonvat',
                    'withholding_tax' => $request->withholding_tax[$key] ?? 'N',
                ]);
            }
        }

        return redirect()->route('quote.editNew', $quotationModel->quote_id)->with('success', 'Update Quotation Successfully.');
    }

    public function cancel(Request $request, quotationModel $quotationModel)
    {
        $quotationModel->update(['quote_cancel_note' => $request->quote_cancel_note, 'quote_status' => 'cancel']);
        $invoice = invoiceModel::where('invoice_quote_id', $quotationModel->quote_id)->update(['invoice_status' => 'cancel']);
        $checkInvioce = invoiceModel::select('invoice_id')->where('invoice_quote_id', $quotationModel->quote_id)->first();
        if ($invoice) {
            taxinvoiceModel::where('invoice_id', $checkInvioce->invoice_id)->update(['taxinvoice_status' => 'cancel']);
        }

        return redirect()->back();
    }

   public function Recancel(Request $request, quotationModel $quotationModel)
{
    // ตรวจสอบสถานะการชำระเงินก่อน คืนสถานะ
    $deposit = $quotationModel->GetDeposit();
    $quotePaymentStatus = $deposit <= 0 ? 'wait' : 'payment';
    $quoteStatus = $deposit >= $quotationModel->quote_grand_total ? 'success' : 'wait';
    // อัปเดตข้อมูล quotationModel
    $quotationModel->update([
        'payment' => $deposit,
        'quote_status' => $quoteStatus,
        'quote_payment_status' => $quotePaymentStatus,
    ]);
    // ตรวจสอบว่ามีการเปิดใบแจ้งหนี้หรือยัง
    $checkInvoice = invoiceModel::select('invoice_id')->where('invoice_quote_id', $quotationModel->quote_id)->first();
    $checkTaxinvoice = null; // กำหนดค่าเริ่มต้น

    if ($checkInvoice) {
        // Update สถานะ ใบแจ้งหนี้
        $checkTaxinvoice = taxinvoiceModel::select('invoice_id')->where('invoice_id', $checkInvoice->invoice_id)->first();
        // Update สถานะ กรณีมีการออกกำกับแล้ว
        if ($checkTaxinvoice) {
            taxinvoiceModel::where('invoice_id', $checkInvoice->invoice_id)->update(['taxinvoice_status' => 'success']);
            $checkInvoice->update(['invoice_status' => 'success']);
        } else {
            $checkInvoice->update(['invoice_status' => 'wait']);
        }
    }

    return redirect()->back();
}

    public function modalCancel(quotationModel $quotationModel)
    {
        return view('quotations.modal-cancel', compact('quotationModel'));
    }

    public function createModern()
    {
        $products = productModel::where('product_type', '!=', 'discount')->get();
        $customers = DB::table('customer')->get();

        if (Auth::user()->getRoleNames()->contains('sale')) {
            $sales = saleModel::select('name', 'id')
                ->where('id', Auth::user()->sale_id)
                ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
                ->get();
        } else {
            $sales = saleModel::select('name', 'id')
                ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
                ->get();
        }

        $tours = DB::connection('mysql2')->table('tb_tour')->where('status', 'on')->get();
        $numDays = numDayModel::orderBy('num_day_total')->get();
        $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
        $wholesale = wholesaleModel::where('status', 'on')->get();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
        $campaignSource = DB::table('campaign_source')->get();
        $productDiscount = productModel::where('product_type', 'discount')->get();
        return view('quotations.create-modern-full', compact('productDiscount', 'campaignSource', 'airline', 'wholesale', 'country', 'numDays', 'products', 'customers', 'sales', 'tours'));
    }

    public function createNew()
    {
        $products = productModel::where('product_type', '!=', 'discount')->get();
        $customers = DB::table('customer')->get();
        if (Auth::user()->getRoleNames()->contains('sale')) {
            $sales = saleModel::select('name', 'id')
                ->where('id', Auth::user()->sale_id)
                ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
                ->get();
        } else {
            $sales = saleModel::select('name', 'id')
                ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
                ->get();
        }
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
        $country = DB::connection('mysql2')->table('tb_country')->select('iso2')->where('id', $request->quote_country)->first();
        $runningCodeTourUpdate = $this->generateRunningCodeTourUpdate($country->iso2, $request->quote_tour_code_old, $request->quote_date_start, $request->quote_wholesale);
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
