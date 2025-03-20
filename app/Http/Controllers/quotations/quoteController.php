<?php

namespace App\Http\Controllers\quotations;

use Carbon\Carbon;
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
require_once app_path('Helpers/statusPaymentHelper.php');

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

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 50);
        $searchKeyword = $request->input('search_keyword');
        $searchPeriodDateStart = $request->input('search_period_start');
        $searchPeriodDateEnd = $request->input('search_period_end');
        $searchQuoteDateStart = $request->input('search_booking_start');
        $searchQuoteDateEnd = $request->input('search_booking_end');

        $searchDateStartCreated = $request->input('search_tour_date_start_created');
        $searchDateEndCreated = $request->input('search_tour_date_end_created');
        $searchSale = $request->input('search_sale');
        $searchCountry = $request->input('search_country');
        $searchWholesale = $request->input('search_wholesale');
        $searchAirline = $request->input('search_airline');
        $searchPax = $request->input('search_pax');
        $searchLogStatus = $request->input('search_check_list');
        $searchNotLogStatus = $request->input('search_not_check_list');
        $searchPaymentWholesaleStatus = $request->input('search_wholesale_payment');
        $searchCustomerPayment = $request->input('search_customer_payment', 'all');

       // dd($searchNotLogStatus);
        $sales = saleModel::select('name', 'id')
            ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
            ->get();
        $airlines = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();

        $country = countryModel::get();
        $wholesales = wholesaleModel::get();

        $quotations = quotationModel::with('Salename', 'quoteCustomer', 'quoteWholesale', 'paymentWholesale', 'quoteInvoice', 'quoteLogStatus')
            // Search คียร์เวิร์ด
            ->when($searchKeyword, function ($query, $searchKeyword) {
                return $query->where(function ($q) use ($searchKeyword) {
                    $q->whereHas('quoteCustomer', function ($q1) use ($searchKeyword) {
                        $q1->where('customer_name', 'LIKE', '%' . $searchKeyword . '%');
                    })
                        ->orWhere('quote_number', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('quote_tour_name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('quote_tour_name1', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('quote_booking', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhereHas('quoteInvoice', function ($q2) use ($searchKeyword) {
                            $q2->where('invoice_number', 'LIKE', '%' . $searchKeyword . '%');
                        });
                });
            })
            //Search Quote Date
            ->when($searchPeriodDateStart && $searchPeriodDateEnd, function ($query) use ($searchPeriodDateStart, $searchPeriodDateEnd) {
                return $query->where(function ($q) use ($searchPeriodDateStart, $searchPeriodDateEnd) {
                    $q->whereBetween('quote_date_start', [$searchPeriodDateStart, $searchPeriodDateEnd])
                        ->orWhereBetween('quote_date_end', [$searchPeriodDateStart, $searchPeriodDateEnd])
                        ->orWhere(function ($q) use ($searchPeriodDateStart, $searchPeriodDateEnd) {
                            $q->where('quote_date_start', '<=', $searchPeriodDateStart)->where('quote_date_end', '>=', $searchPeriodDateEnd);
                        });
                });
            })

            // Searchs Quote Date
            ->when($searchQuoteDateStart && $searchQuoteDateEnd, function ($query) use ($searchQuoteDateStart, $searchQuoteDateEnd) {
                return $query->whereBetween('quote_date', [$searchQuoteDateStart, $searchQuoteDateEnd]);
            })

            // Search Airline
            ->when($searchAirline && $searchAirline != 'all', function ($query) use ($searchAirline) {
                return $query->where('quote_airline', $searchAirline);
            })
            // Search Pax
            ->when($searchPax && $searchPax != null, function ($query) use ($searchPax) {
                return $query->where('quote_pax_total', $searchPax);
            })

             // Search Quote Log Status ทำหมดแล้ว
             ->when($searchLogStatus && $searchLogStatus === 'allCheck', function ($query, $searchLogStatus) {
                return $query->whereHas('quoteLogStatus', function ($q1) use ($searchLogStatus) {
                       $q1->where('booking_email_status', 'ส่งแล้ว');
                       $q1->where('invoice_status', 'ได้แล้ว');
                       $q1->where('slip_status', 'ส่งแล้ว');
                       $q1->where('passport_status', 'ส่งแล้ว');
                       $q1->where('appointment_status', 'ส่งแล้ว');
                       $q1->where('withholding_tax_status', 'ออกแล้ว');
                       $q1->where('wholesale_tax_status', 'ได้รับแล้ว');
                });
            })

            // Search Quote Log Status
            ->when($searchLogStatus, function ($query, $searchLogStatus) {
                return $query->whereHas('quoteLogStatus', function ($q1) use ($searchLogStatus) {
                    switch ($searchLogStatus) {
                        case 'booking_email_status':
                            $q1->where('booking_email_status', 'ส่งแล้ว');
                            break;
                        case 'invoice_status':
                            $q1->where('invoice_status', 'ได้แล้ว');
                            break;
                        case 'slip_status':
                            $q1->where('slip_status', 'ส่งแล้ว');
                            break;
                        case 'passport_status':
                            $q1->where('passport_status', 'ส่งแล้ว');
                            break;
                        case 'appointment_status':
                            $q1->where('appointment_status', 'ส่งแล้ว');
                            break;
                        case 'withholding_tax_status':
                            $q1->where('withholding_tax_status', 'ออกแล้ว');
                            break;
                        case 'wholesale_tax_status':
                            $q1->where('wholesale_tax_status', 'ได้รับแล้ว');
                            break;
                    }
                });
            })

             // Search Quote Log Status Not
             ->when($searchNotLogStatus, function ($query, $searchNotLogStatus) {
                return $query->whereHas('quoteLogStatus', function ($q1) use ($searchNotLogStatus) {
                    switch ($searchNotLogStatus) {
                        case 'booking_email_status':
                            $q1->where('booking_email_status', 'ยังไม่ได้ส่ง')->orWhereNull('booking_email_status');
                            break;
                        case 'invoice_status':
                            $q1->where('invoice_status','ยังไม่ได้')->orWhereNull('invoice_status');
                            break;
                        case 'slip_status':
                            $q1->where('slip_status','ยังไม่ได้ส่ง')->orWhereNull('slip_status');
                            break;
                        case 'passport_status':
                            $q1->where('passport_status','ยังไม่ได้ส่ง')->orWhereNull('passport_status');
                            break;
                        case 'appointment_status':
                            $q1->where('appointment_status','ยังไม่ได้ส่ง')->orWhereNull('appointment_status');
                            break;
                        case 'withholding_tax_status':
                            $q1->where('withholding_tax_status','ยังไม่ได้ออก')->orWhereNull('withholding_tax_status');
                            break;
                        case 'wholesale_tax_status':
                            $q1->where('wholesale_tax_status','ยังไม่ได้รับ')->orWhereNull('wholesale_tax_status');
                            break;
                    }
                });
            })

            ->when($searchDateStartCreated && $searchDateEndCreated, function ($query) use ($searchDateStartCreated, $searchDateEndCreated) {
                return $query->whereBetween('quote_booking_create', [$searchDateStartCreated, $searchDateEndCreated]);
            })
            ->when($searchSale && $searchSale != 'all', function ($query) use ($searchSale) {
                return $query->where('quote_sale', $searchSale);
            })
            ->when($searchCountry && $searchCountry != 'all', function ($query) use ($searchCountry) {
                return $query->where('quote_country', $searchCountry);
            })
            ->when($searchWholesale && $searchWholesale != 'all', function ($query) use ($searchWholesale) {
                return $query->where('quote_wholesale', $searchWholesale);
            })

            ->when($searchPaymentWholesaleStatus === 'NULL', function ($query) {
                // กรณี "รอชำระเงิน" หมายถึงไม่มีข้อมูลใน paymentWholesale เลย
                $query->whereDoesntHave('paymentWholesale');
            })
            ->when($searchPaymentWholesaleStatus === 'deposit', function ($query) {
                // กรณี "รอชำระเงินเต็มจำนวน" หมายถึงแถวล่าสุดของ paymentWholesale เป็น deposit
                $query->whereHas('paymentWholesale', function ($q) {
                    $q->where('payment_wholesale_id', function ($subquery) {
                        $subquery->select('payment_wholesale_id')->from('payment_wholesale')->whereColumn('payment_wholesale.payment_wholesale_quote_id', 'quotation.quote_id')->orderBy('payment_wholesale_id', 'desc')->limit(1);
                    })->where('payment_wholesale_type', 'deposit');
                });
            })
            ->when($searchPaymentWholesaleStatus === 'full', function ($query) {
                // กรณี "ชำระเงินแล้ว" หมายถึงแถวล่าสุดของ paymentWholesale เป็น full
                $query->whereHas('paymentWholesale', function ($q) {
                    $q->where('payment_wholesale_id', function ($subquery) {
                        $subquery->select('payment_wholesale_id')->from('payment_wholesale')->whereColumn('payment_wholesale.payment_wholesale_quote_id', 'quotation.quote_id')->orderBy('payment_wholesale_id', 'desc')->limit(1);
                    })->where('payment_wholesale_type', 'full');
                });
            })

            ->orderBy('created_at', 'desc')
            ->orderBy('created_at', 'desc');
            if ($request->search === 'Y') {
                $quotations = $quotations->get();
            } else {
                $quotations = $quotations->paginate(10);
            }

        // กรองสถานะใน PHP
        // if ($searchCustomerPayment !== 'all') {
        //     $filtered = $quotations->getCollection()->filter(function ($quotation) use ($searchCustomerPayment) {
        //         return strip_tags(getQuoteStatusPayment($quotation)) === $searchCustomerPayment;
        //     });

        //     // กำหนด Collection ที่กรองแล้วกลับเข้าไปใน Paginator
        //     $quotations->setCollection($filtered);
        // }

        if ($searchCustomerPayment !== 'all') {
            $filtered = $quotations->filter(function ($quotation) use ($searchCustomerPayment) {
                return strip_tags(getQuoteStatusPayment($quotation)) === $searchCustomerPayment;
            });
    
            $quotations = $filtered; // แทนที่ $quotations ด้วย filtered collection
        }

        $statuses = $quotations
            ->map(function ($quotation) {
                return strip_tags(getQuoteStatusPayment($quotation));
            })
            ->unique();

        $SumPax = $quotations->sum('quote_pax_total');
        $SumTotal = $quotations->sum('quote_grand_total');

        return view('quotations.index', compact('SumTotal', 'SumPax', 'airlines', 'sales', 'wholesales', 'quotations', 'country', 'request', 'statuses'));
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
        $products = productModel::where('product_type', '!=', 'discount')->get();
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
        $sale = saleModel::where('id', $quotationModel->quote_sale)->first();
        $customer = customerModel::where('customer_id', $quotationModel->customer_id)
            ->leftjoin('campaign_source', 'campaign_source.campaign_source_id', 'customer.customer_campaign_source')
            ->first();
        $tour = DB::connection('mysql2')
            ->table('tb_tour')
            ->where('id', $quotationModel->tour_id)
            ->first();
        $airline = DB::connection('mysql2')
            ->table('tb_travel_type')
            ->select('travel_name')
            ->where('id', $quotationModel->quote_airline)
            ->first();
        $wholesale = wholesaleModel::where('id', $quotationModel->quote_wholesale)->first();
        $quoteProducts = quoteProductModel::where('quote_id', $quotationModel->quote_id)
            ->select('quote_product.*', 'products.product_pax')
            ->leftjoin('products', 'products.id', 'quote_product.product_id')
            ->get();

        $quotations = quotationModel::where('quotation.quote_id', $quotationModel->quote_id)
            ->leftjoin('customer', 'customer.customer_id', 'quotation.customer_id')
            ->get();

        $invoiceModel = invoiceModel::where('invoice_quote_id', $quotationModel->quote_id)->first();

        return view('quotations.form-edit-new', compact('quotationModel', 'customer', 'sale', 'airline', 'wholesale', 'quoteProducts', 'quotations', 'invoiceModel'));
    }

    public function editQuote(quotationModel $quotationModel, Request $request)
    {
        $quotations = quotationModel::where('quotation.quote_id', $quotationModel->quote_id)
            ->leftjoin('customer', 'customer.customer_id', 'quotation.customer_id')
            ->get();
        $invoices = invoiceModel::where('invoices.invoice_quote_id', $quotationModel->quote_id)
            ->leftjoin('customer', 'customer.customer_id', 'invoices.customer_id')
            ->get();
        $invoicesIds = $invoices->pluck('invoice_id');
        $taxinvoices = taxinvoiceModel::whereIn('taxinvoices.invoice_id', $invoicesIds)->leftjoin('invoices', 'invoices.invoice_number', 'taxinvoices.invoice_number')->leftjoin('customer', 'customer.customer_id', 'invoices.customer_id')->get();

        $invoiceModel = $invoices->first();

        $taxinvoiceIds = $taxinvoices->pluck('taxinvoice_number');
        // $debits = debitModel::whereIn('debit_taxinvoice_number', $taxinvoiceIds)->get();

        $quoteProducts = quoteProductModel::where('quote_id', $quotationModel->quote_id)
            ->select('quote_product.*', 'products.product_pax')
            ->leftJoin('products', 'products.id', '=', 'quote_product.product_id')
            ->where('quote_product.expense_type', 'income')
            ->get();
        $quoteProductsDiscount = quoteProductModel::where('quote_id', $quotationModel->quote_id)
            ->select('quote_product.*', 'products.product_pax')
            ->leftJoin('products', 'products.id', '=', 'quote_product.product_id')
            ->where('expense_type', 'discount')
            ->get();
        $document = WithholdingTaxDocument::where('quote_id', $quotationModel->quote_id)->first();
        return View::make('quotations.quote-table', compact('document', 'quoteProductsDiscount', 'quoteProducts', 'quotations', 'quotationModel', 'invoices', 'taxinvoices', 'invoiceModel'))->render();
    }

    public function modalEdit(quotationModel $quotationModel, Request $request)
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
        $quoteProducts = quoteProductModel::where('quote_id', $quotationModel->quote_id)
            ->where('expense_type', 'income')
            ->get();
        $quoteProductsDiscount = quoteProductModel::where('quote_id', $quotationModel->quote_id)
            ->where('expense_type', 'discount')
            ->get();
        $campaignSource = DB::table('campaign_source')->get();
        $mode = $request->get('mode', 'view');
        return view('quotations.modal-edit', compact('mode', 'campaignSource', 'customer', 'quoteProducts', 'quotationModel', 'sales', 'country', 'airline', 'numDays', 'wholesale', 'products', 'productDiscount', 'quoteProductsDiscount'));
    }

    public function modalEditCopy(quotationModel $quotationModel)
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
        $quoteProducts = quoteProductModel::where('quote_id', $quotationModel->quote_id)
            ->where('expense_type', 'income')
            ->get();
        $quoteProductsDiscount = quoteProductModel::where('quote_id', $quotationModel->quote_id)
            ->where('expense_type', 'discount')
            ->get();
        $campaignSource = DB::table('campaign_source')->get();

        return view('quotations.modal-copy', compact('campaignSource', 'customer', 'quoteProducts', 'quotationModel', 'sales', 'country', 'airline', 'numDays', 'wholesale', 'products', 'productDiscount', 'quoteProductsDiscount'));
    }

    public function update(quotationModel $quotationModel, Request $request)
    {
        //dd($request);
        $country = DB::connection('mysql2')
            ->table('tb_country')
            ->select('iso2')
            ->where('id', $request->quote_country)
            ->first();

        $runningCodeTourUpdate = $this->generateRunningCodeTourUpdate($country->iso2, $request->quote_tour_code_old, $request->quote_date_start, $request->quote_wholesale);

        $request->merge([
            'quote_tour_code' => $runningCodeTourUpdate,
            'updated_by' => Auth::user()->name,
        ]);

        //dd($runningCodeTourUpdate);

        $checkPaymentTotal = paymentModel::where('payment_quote_id', $quotationModel->quote_id)
            ->where('payment_status', 'success')
            ->sum('payment_total');
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

        return redirect()
            ->route('quote.editNew', $quotationModel->quote_id)
            ->with('success', 'Update Quotation Successfully.');
    }

    public function cancel(Request $request, quotationModel $quotationModel)
    {
        $quotationModel->update(['quote_cancel_note' => $request->quote_cancel_note, 'quote_status' => 'cancel']);
        $invoice = invoiceModel::where('invoice_quote_id', $quotationModel->quote_id)->update(['invoice_status' => 'cancel']);
        $checkInvioce = invoiceModel::select('invoice_id')
            ->where('invoice_quote_id', $quotationModel->quote_id)
            ->first();
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
        //ตรวจสอบว่ามีการเปิดใบแจ้งหนี้หรือยัง
        $checkInvoice = invoiceModel::select('invoice_id')
            ->where('invoice_quote_id', $quotationModel->quote_id)
            ->first();
        if ($checkInvoice) {
            // Update สถานะ ใบแจ้งหนี้
            $checkTaxinvoice = taxinvoiceModel::select('invoice_id')
                ->where('invoice_id', $checkInvoice->invoice_id)
                ->first();
            // Update สถานะ กรณีมีการออกกำกับแล้ว
            if ($checkTaxinvoice) {
                taxinvoiceModel::where('invoice_id', $checkInvoice->invoice_id)->update(['taxinvoice_status' => 'success']);
            }
        }
        if ($checkTaxinvoice) {
            $checkInvoice->update(['invoice_status' => 'success']);
        } else {
            $checkInvoice->update(['invoice_status' => 'wait']);
        }

        return redirect()->back();
    }

    public function modalCancel(quotationModel $quotationModel)
    {
        return view('quotations.modal-cancel', compact('quotationModel'));
    }

    public function createNew()
    {
        $products = productModel::where('product_type', '!=', 'discount')->get();
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
