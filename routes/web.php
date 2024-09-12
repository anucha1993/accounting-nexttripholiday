<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\selects\periodSelect;
use App\Http\Controllers\debits\debitController;
use App\Http\Controllers\credits\creditController;
use App\Http\Controllers\airline\airlineController;
use App\Http\Controllers\booking\BookingController;
use App\Http\Controllers\invoices\invoiceController;
use App\Http\Controllers\payments\paymentController;
use App\Http\Controllers\products\productController;
use App\Http\Controllers\quotations\quoteController;
use App\Http\Controllers\customers\customerController;
use App\Http\Controllers\FPDF\FPDF_QuotatioController;
use App\Http\Controllers\invoices\taxInvoiceController;
use App\Http\Controllers\wholeSales\wholeSaleController;
use App\Http\Controllers\payments\paymentDebitController;
use App\Http\Controllers\quotefiles\QuoteFilesController;
use App\Http\Controllers\payments\paymentCreditController;
use App\Http\Controllers\Invoices\InvoiceBookingController;
use App\Http\Controllers\invoices\invoiceDashboardController;
use App\Http\Controllers\quotations\salesInformationController;
use App\Http\Controllers\paymentWholesale\paymentWholesaleController;
use App\Http\Controllers\MPDF\MPDF_QuotationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

//wholesale
Route::get('/wholesale',[wholeSaleController::class,'index'])->name('wholesale.index');
Route::get('/wholesale/edit/{wholesaleModel}',[wholeSaleController::class,'edit'])->name('wholesale.edit');
Route::put('/wholesale/update/{wholesaleModel}',[wholeSaleController::class,'update'])->name('wholesale.update');
Route::get('/wholesale/create',[wholeSaleController::class,'create'])->name('wholesale.create');
Route::post('/wholesale/store',[wholeSaleController::class,'store'])->name('wholesale.store');
Route::get('/wholesale/delete/{wholesaleModel}',[wholeSaleController::class,'destroy'])->name('wholesale.destroy');


//airline
Route::get('/airline',[airlineController::class,'index'])->name('airline.index');
Route::get('/airline/edit/{airlineModel}',[airlineController::class,'edit'])->name('airline.edit');
Route::put('/airline/edit/{airlineModel}',[airlineController::class,'update'])->name('airline.update');
Route::post('/airline/store',[airlineController::class,'store'])->name('airline.store');
Route::get('/airline/create',[airlineController::class,'create'])->name('airline.create');
Route::get('/airline/delete/{airlineModel}',[airlineController::class,'destroy'])->name('airline.destroy');

//Booking
Route::get('/booking',[BookingController::class,'index'])->name('booking.index');
Route::get('/booking/create',[BookingController::class,'create'])->name('booking.create');
Route::post('/booking/store',[BookingController::class,'store'])->name('booking.store');
Route::get('/booking/convert',[BookingController::class,'convert'])->name('booking.convert');
Route::get('/booking/edit/{bookingModel}',[BookingController::class,'edit'])->name('booking.edit');
Route::put('/booking/update/{bookingModel}',[BookingController::class,'update'])->name('booking.update');
Route::get('/booking/delete/{bookingModel}',[BookingController::class,'destroy'])->name('booking.delete');

//invoices
Route::get('invoice/create/{quotationModel}',[invoiceController::class,'create'])->name('invoice.create');
Route::get('invoice/edit/{invoiceModel}',[invoiceController::class,'edit'])->name('invoice.edit');
Route::get('invoice/cancel/{invoiceModel}',[invoiceController::class,'cancel'])->name('invoice.cancel');
Route::put('invoice/update/{invoiceModel}',[invoiceController::class,'update'])->name('invoice.update');
Route::post('invoice/store',[invoiceController::class,'store'])->name('invoice.store');

//taxtinvoice
Route::get('taxinvoice/{invoiceModel}',[taxInvoiceController::class,'store'])->name('invoice.taxinvoice');
Route::get('taxinvoice/edit/{invoiceModel}',[taxInvoiceController::class,'edit'])->name('taxinvoice.edit');
Route::put('taxinvoice/update/{invoiceModel}',[taxInvoiceController::class,'update'])->name('taxinvoice.update');

//invoice booking
Route::get('invoice/booking',[InvoiceBookingController::class,'index'])->name('invoiceBooking.index');
Route::get('invoice/booking/edit',[InvoiceBookingController::class,'edit'])->name('invoiceBooking.edit');
Route::post('invoice/booking/update',[InvoiceBookingController::class,'update'])->name('invoiceBooking.update');
// invoice Dashboard
Route::get('invoice/dashboard',[invoiceDashboardController::class,'index'])->name('invoice.dashboardIndex');

//Products
Route::get('/products',[productController::class,'index'])->name('product.index');
Route::get('/products/list',[productController::class,'products'])->name('product.products');
Route::get('/product/edit/{id}',[productController::class,'edit'])->name('product.edit');
Route::delete('/product/delete/{id}',[productController::class,'destroy'])->name('product.destroy');
Route::put('/product/update/{id}',[productController::class,'update'])->name('product.update');
Route::post('/product/store',[productController::class,'store'])->name('product.store');


// quote
Route::get('quotations/',[quoteController::class,'index'])->name('quote.index');
Route::post('quote/store',[quoteController::class,'store'])->name('quote.store');
Route::get('quote/edit/{quotationModel}',[quoteController::class,'edit'])->name('quote.edit');
Route::put('quote/update/{quotationModel}',[quoteController::class,'update'])->name('quote.update');//
Route::get('quote/cancel/{quotationModel}',[quoteController::class,'cancel'])->name('quote.cancel');
Route::get('quote/create/new',[quoteController::class,'createNew'])->name('quote.createNew');

// Sales info
Route::get('quote/sales/{quotationModel}',[salesInformationController::class,'index'])->name('saleInfo.index');
Route::get('quote/sales/info/{quotationModel}',[salesInformationController::class,'info'])->name('saleInfo.info');
//selects
Route::get('/selects/period',[periodSelect::class,'index'])->name('select.period');

Route::resources([
    'roles' => RoleController::class,
    'users' => UserController::class,
    'products' => ProductController::class,
]);


// Customer
Route::post('customer/ajax/edit',[customerController::class,'ajaxEdit'])->name('customer.ajaxEdit');
Route::post('customer/ajax/update',[customerController::class,'ajaxUpdate'])->name('customer.ajaxUpdate');


//Debits
Route::get('debit/create/{invoiceModel}',[debitController::class,'create'])->name('debit.create');
Route::get('debit/edit/{debitModel}',[debitController::class,'edit'])->name('debit.edit');
Route::put('debit/update/{debitModel}',[debitController::class,'update'])->name('debit.update');
Route::post('debit/store/',[debitController::class,'store'])->name('debit.store');
// Credits 
Route::get('credit/create/{invoiceModel}',[creditController::class,'create'])->name('credit.create');
Route::get('credit/edit/{creditModel}',[creditController::class,'edit'])->name('credit.edit');
Route::put('credit/update/{creditModel}',[creditController::class,'update'])->name('credit.update');
Route::post('credit/store/',[creditController::class,'store'])->name('credit.store');

//Payment
Route::get('payments/{quotationModel}',[paymentController::class,'index'])->name('payments');
Route::get('payments/edit/{paymentModel}',[paymentController::class,'edit'])->name('payment.edit');
Route::put('payments/update/{paymentModel}',[paymentController::class,'update'])->name('payment.update');
Route::get('payment/quotation/{quotationModel}',[paymentController::class,'quotation'])->name('payment.quotation');
Route::get('payment/cancel/{paymentModel}',[paymentController::class,'cancel'])->name('payment.cancel');
Route::post('payment/quotation/store',[paymentController::class,'payment'])->name('payment.payment');

//payment debit
Route::get('payments/debit/edit/{paymentModel}',[paymentDebitController::class,'edit'])->name('payment.debit-edit');
Route::put('payments/debit/update/{paymentModel}',[paymentDebitController::class,'update'])->name('payment.debit-update');
Route::get('payment/debit/{debitModel}',[paymentDebitController::class,'debit'])->name('payment.debit');
Route::get('payment/debit/cancel/{paymentModel}',[paymentDebitController::class,'cancel'])->name('payment.debit-cancel');
Route::post('payment/debit/store',[paymentDebitController::class,'payment'])->name('payment.debit-payment');

//payment credit
Route::get('payments/credit/edit/{paymentModel}',[paymentCreditController::class,'edit'])->name('payment.credit-edit');
Route::put('payments/credit/update/{paymentModel}',[paymentCreditController::class,'update'])->name('payment.credit-update');
Route::get('payment/credit/{creditModel}',[paymentCreditController::class,'credit'])->name('payment.credit');
Route::get('payment/credit/cancel/{paymentModel}',[paymentCreditController::class,'cancel'])->name('payment.credit-cancel');
Route::post('payment/credit/store',[paymentCreditController::class,'payment'])->name('payment.credit-payment');

// Quote Files Upload
Route::get('quotefiles/{quotationModel}',[QuoteFilesController::class,'index'])->name('quotefile.index');
Route::get('quotefile/delete/{quoteFileModel}',[QuoteFilesController::class,'delete'])->name('quotefile.delete');
Route::post('quotefile/upload',[QuoteFilesController::class,'upload'])->name('quotefile.upload');

// Payment Wholesale
Route::get('payment/wholesales/{quotationModel}',[paymentWholesaleController::class,'index'])->name('paymentWholesale.index');
Route::get('payment/wholesale/delete/{paymentWholesaleModel}',[paymentWholesaleController::class,'delete'])->name('paymentWholesale.delete');
Route::get('payment/wholesales/quote/{quotationModel}',[paymentWholesaleController::class,'quote'])->name('paymentWholesale.quote');
Route::post('payment/wholesales/store',[paymentWholesaleController::class,'store'])->name('paymentWholesale.store');

// FPDF Quotation
Route::get('fpdf/quote/{quotationModel}',[FPDF_QuotatioController::class,'generatePDF'])->name('quote.generatePDF');

// MPDF Quotation
Route::get('mpdf/quote/{quotationModel}',[MPDF_QuotationController::class,'generatePDF'])->name('mpdf.quote');