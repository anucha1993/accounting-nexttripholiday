<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MPDF\MailController;
use App\Http\Controllers\selects\periodSelect;
use App\Http\Controllers\api\apiTourController;
use App\Http\Controllers\debits\debitController;
use App\Http\Controllers\credits\creditController;
use App\Http\Controllers\MPDF\MailQuoteController;
use App\Http\Controllers\airline\airlineController;
use App\Http\Controllers\booking\BookingController;
use App\Http\Controllers\invoices\invoiceController;
use App\Http\Controllers\payments\paymentController;
use App\Http\Controllers\products\productController;
use App\Http\Controllers\quotations\quoteController;
use App\Http\Controllers\MPDF\MPDF_invoiceController;
use App\Http\Controllers\MPDF\MPDF_PaymentController;
use App\Http\Controllers\customers\customerController;
use App\Http\Controllers\FPDF\FPDF_QuotatioController;
use App\Http\Controllers\inputTax\inputTaxController;
use App\Http\Controllers\invoices\taxInvoiceController;
use App\Http\Controllers\MPDF\MPDF_QuotationController;
use App\Http\Controllers\MPDF\MPDF_taxReceiptController;
use App\Http\Controllers\wholeSales\wholeSaleController;
use App\Http\Controllers\payments\paymentDebitController;
use App\Http\Controllers\quotefiles\QuoteFilesController;
use App\Http\Controllers\MPDF\MPDF_DebitReceiptController;
use App\Http\Controllers\MPDF\MPDF_CreditReceiptController;
use App\Http\Controllers\MPDF\MPDF_PaymentDebitController;
use App\Http\Controllers\MPDF\MPDF_WithholdingController;
use App\Http\Controllers\payments\paymentCreditController;
use App\Http\Controllers\Invoices\InvoiceBookingController;
use App\Http\Controllers\invoices\invoiceDashboardController;
use App\Http\Controllers\MPDF\MPDF_WithhodingDocumentController;
use App\Http\Controllers\quotations\salesInformationController;
use App\Http\Controllers\paymentWholesale\paymentWholesaleController;
use App\Http\Controllers\quotations\quotationViewController;
use App\Http\Controllers\quotations\quoteLog;
use App\Http\Controllers\withholding\withholdingTaxController;
use App\Models\quotations\quotationModel;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// api
Route::get('/api/tours',[apiTourController::class,'index'])->name('api.tours');
Route::get('/api/wholesale',[apiTourController::class,'wholesale'])->name('api.wholesale');
Route::get('/api/country',[apiTourController::class,'country'])->name('api.country');
Route::get('/api/customer',[apiTourController::class,'customer'])->name('api.customer');
Route::get('/api/period',[apiTourController::class,'period'])->name('api.period');

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
Route::get('/booking/convert/{bookingModel}',[BookingController::class,'convert'])->name('booking.convert');
Route::get('/booking/edit/{bookingModel}',[BookingController::class,'edit'])->name('booking.edit');
Route::put('/booking/update/{bookingModel}',[BookingController::class,'update'])->name('booking.update');
Route::get('/booking/delete/{bookingModel}',[BookingController::class,'destroy'])->name('booking.delete');

//invoices
Route::get('invoice/create/{quotationModel}',[invoiceController::class,'create'])->name('invoice.create');
Route::get('invoice/edit/{invoiceModel}',[invoiceController::class,'edit'])->name('invoice.edit');
Route::get('invoice/cancel/{invoiceModel}',[invoiceController::class,'cancel'])->name('invoice.cancel');
Route::get('invoice/modal/cancel/{invoiceModel}',[invoiceController::class,'modalCancel'])->name('invoice.modalCancel');
Route::put('invoice/update/{invoiceModel}',[invoiceController::class,'update'])->name('invoice.update');
Route::post('invoice/store',[invoiceController::class,'store'])->name('invoice.store');
Route::post('upload-invoice-image', [InvoiceController::class, 'uploadInvoiceImage'])->name('uploadInvoiceImage');
Route::delete('delete-invoice-image', [InvoiceController::class, 'deleteInvoiceImage'])->name('deleteInvoiceImage');


//taxtinvoice
Route::get('taxinvoice/{invoiceModel}',[taxInvoiceController::class,'store'])->name('invoice.taxinvoice');
Route::get('taxinvoice/edit/{invoiceModel}',[taxInvoiceController::class,'edit'])->name('taxinvoice.edit');
Route::put('taxinvoice/update/{invoiceModel}',[taxInvoiceController::class,'update'])->name('taxinvoice.update');
Route::put('taxinvoice/cancel/{taxinvoiceModel}',[taxInvoiceController::class,'cancel'])->name('taxinvoice.cancel');
Route::get('taxinvoice/modal/cancel/{taxinvoiceModel}',[taxInvoiceController::class,'modalCancel'])->name('taxinvoice.modalCancel');

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
Route::get('/',[quoteController::class,'index'])->name('quote.index');
Route::post('quote/store',[quoteController::class,'store'])->name('quote.store');
Route::get('quote/edit/{quotationModel}',[quoteController::class,'edit'])->name('quote.edit');
Route::put('quote/update/{quotationModel}',[quoteController::class,'update'])->name('quote.update');//
Route::put('quote/update/ajax/{quotationModel}',[quoteController::class,'AjaxUpdate'])->name('quote.AjaxUpdate');//
Route::put('quote/cancel/{quotationModel}',[quoteController::class,'cancel'])->name('quote.cancel');
Route::get('quote/create/new',[quoteController::class,'createNew'])->name('quote.createNew');

Route::get('quote/edit/new/{quotationModel}',[quoteController::class,'editNew'])->name('quote.editNew');

Route::get('quote/ajax/new/{quotationModel}',[quoteController::class,'editQuote'])->name('quote.editAjax');
Route::get('quote/modal/edit/{quotationModel}', [quoteController::class, 'modalEdit'])->name('quote.modalEdit');


Route::get('quote/modal/copy/edit/{quotationModel}',[quoteController::class,'modalEditCopy'])->name('quote.modalEditCopy');
Route::get('quote/modal/cancel/{quotationModel}',[quoteController::class,'modalCancel'])->name('quote.modalCancel');
Route::get('quote/recancel/{quotationModel}',[quoteController::class,'Recancel'])->name('quote.recancel');




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
Route::get('payments/cancelModal/{paymentModel}',[paymentController::class,'cancelModal'])->name('payment.cancelModal');
Route::get('payments/delete/{paymentModel}',[paymentController::class,'delete'])->name('payment.delete');

Route::get('payment/quotation/{quotationModel}',[paymentController::class,'quotation'])->name('payment.quotation');
Route::put('payment/cancel/{paymentModel}',[paymentController::class,'cancel'])->name('payment.cancel');
Route::post('payment/quotation/store',[paymentController::class,'payment'])->name('payment.payment');

//payment debit
Route::get('payments/debit/edit/{paymentModel}',[paymentDebitController::class,'edit'])->name('payment.debit-edit');
Route::put('payments/debit/update/{paymentModel}',[paymentDebitController::class,'update'])->name('payment.debit-update');
Route::get('payment/debit/{debitModel}',[paymentDebitController::class,'debit'])->name('payment.debit');
Route::get('payment/debit/cancel/{paymentModel}',[paymentDebitController::class,'cancel'])->name('payment.debit-cancel');
Route::post('payment/debit/store/{debitModel}',[paymentDebitController::class,'payment'])->name('payment.debit-payment');

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
Route::get('quotefile/modal/mail{quoteFileModel}',[QuoteFilesController::class,'modalMail'])->name('quotefile.modalMail');
Route::post('quotefile/send/mail{quoteFileModel}',[QuoteFilesController::class,'sendMail'])->name('quotefile.sendMail');

// Payment Wholesale
Route::get('payment/wholesales/{quotationModel}',[paymentWholesaleController::class,'index'])->name('paymentWholesale.index');
Route::get('payment/mail/wholesales/{paymentWholesaleModel}',[paymentWholesaleController::class,'modalMailWholesale'])->name('paymentWholesale.modalMailWholesale');
Route::post('payment/send/mail/wholesales/{paymentWholesaleModel}',[paymentWholesaleController::class,'sendMail'])->name('paymentWholesale.sendMail');
Route::get('payment/wholesales/payment/{quotationModel}',[paymentWholesaleController::class,'payment'])->name('wholesale.payment');
Route::get('payment/wholesale/delete/{paymentWholesaleModel}',[paymentWholesaleController::class,'delete'])->name('paymentWholesale.delete');
Route::get('payment/wholesale/refund/{paymentWholesaleModel}',[paymentWholesaleController::class,'refund'])->name('paymentWholesale.refund');
Route::get('payment/wholesale/edit/{paymentWholesaleModel}',[paymentWholesaleController::class,'edit'])->name('paymentWholesale.edit');
Route::PUT('payment/wholesale/update/{paymentWholesaleModel}',[paymentWholesaleController::class,'update'])->name('paymentWholesale.update');
Route::PUT('payment/wholesale/refund/{paymentWholesaleModel}',[paymentWholesaleController::class,'updateRefund'])->name('paymentWholesale.updateRefund');
Route::get('payment/wholesale/edit/refund/{paymentWholesaleModel}',[paymentWholesaleController::class,'editRefund'])->name('paymentWholesale.editRefund');
Route::get('payment/wholesales/quote/{quotationModel}',[paymentWholesaleController::class,'quote'])->name('paymentWholesale.quote');
Route::post('payment/wholesales/store',[paymentWholesaleController::class,'store'])->name('paymentWholesale.store');

// FPDF Quotation
Route::get('fpdf/quote/{quotationModel}',[FPDF_QuotatioController::class,'generatePDF'])->name('quote.generatePDF');

// MPDF 
Route::get('mpdf/quote/{quotationModel}',[MPDF_QuotationController::class,'generatePDF'])->name('mpdf.quote');
Route::get('mpdf/payment/{paymentModel}',[MPDF_PaymentController::class,'generatePDF'])->name('mpdf.payment');
Route::get('mpdf/payment/debit/{paymentModel}',[MPDF_PaymentDebitController::class,'generatePDF'])->name('mpdf.paymentDebit');
Route::get('mpdf/invoice/{invoiceModel}',[MPDF_invoiceController::class,'generatePDF'])->name('mpdf.invoice');
Route::get('mpdf/taxreceipt/{invoiceModel}',[MPDF_taxReceiptController::class,'generatePDF'])->name('mpdf.taxreceipt');
Route::get('mpdf/debitreceipt/{debitModel}',[MPDF_DebitReceiptController::class,'generatePDF'])->name('mpdf.debitreceipt');
Route::get('mpdf/creditreceipt/{creditModel}',[MPDF_CreditReceiptController::class,'generatePDF'])->name('mpdf.creditreceipt');
Route::get('mpdf/withholding/{inputTaxModel}',[MPDF_WithholdingController::class,'generatePDF'])->name('mpdf.withholding');

//Send Mail  Quote 
Route::post('mpdf/mail/quote/{quotationModel}',[MPDF_QuotationController::class,'sendPdf'])->name('mpdf.quote.sendPdf');
Route::get('modal/mail/quote/{quotationModel}',[MailController::class,'formMailQuote'])->name('mail.quote.formMail');
//Send Mail  invoice
Route::post('mpdf/mail/invoice/{invoiceModel}',[MPDF_invoiceController::class,'sendPdf'])->name('mpdf.invoice.sendPdf');
Route::get('modal/mail/invoice/{invoiceModel}',[MailController::class,'formMailInvoice'])->name('mail.invoice.formMail');
//Send Mail  TaxReceipt
Route::post('mpdf/mail/taxreceipt/{invoiceModel}',[MPDF_taxReceiptController::class,'sendPdf'])->name('mpdf.taxreceipt.sendPdf');
Route::get('modal/mail/taxreceipt/{invoiceModel}',[MailController::class,'formMailtaxReceipt'])->name('mail.taxreceipt.formMail');
//Send Mail  Debit
Route::post('mpdf/mail/debit/{debitModel}',[MPDF_DebitReceiptController::class,'sendPdf'])->name('mpdf.debitReceipt.sendPdf');
Route::get('modal/mail/debit/{debitModel}',[MailController::class,'formMailDebitReceipt'])->name('mail.debitReceipt.formMail');
//Send Mail  Creadit
Route::post('mpdf/mail/credit/{creditModel}',[MPDF_CreditReceiptController::class,'sendPdf'])->name('mpdf.creditReceipt.sendPdf');
Route::get('modal/mail/credit/{creditModel}',[MailController::class,'formMailCreditReceipt'])->name('mail.creditReceipt.formMail');

// input tax 
Route::get('inputtax/create/wholesale/{quotationModel}',[inputTaxController::class,'createWholesale'])->name('inputtax.createWholesale');
Route::get('inputtax/inputtax/create/wholesale/{quotationModel}',[inputTaxController::class,'inputtaxCreateWholesale'])->name('inputtax.inputtaxCreateWholesale');
Route::get('inputtax/create/edit/{inputTaxModel}',[inputTaxController::class,'editWholesale'])->name('inputtax.editWholesale');
Route::get('inputtax/wholesale/edit/{inputTaxModel}',[inputTaxController::class,'inputtaxEditWholesale'])->name('inputtax.inputtaxEditWholesale');


Route::get('inputtax/cancel/{inputTaxModel}',[inputTaxController::class,'cancelWholesale'])->name('inputtax.cancelWholesale');
Route::put('inputtax/create/update/{inputTaxModel}',[inputTaxController::class,'update'])->name('inputtax.update');
Route::put('inputtax/cancel/update/{inputTaxModel}',[inputTaxController::class,'updateCancel'])->name('inputtax.updateCancel');
Route::get('inputtax/table/{quotationModel}',[inputTaxController::class,'table'])->name('inputtax.table');
Route::get('inputtax/wholesale/table/{quotationModel}',[inputTaxController::class,'tableWholesale'])->name('inputtax.tableWholesale');
Route::POST('inputtax/store',[inputTaxController::class,'store'])->name('inputtax.store');

// public View
Route::get('quotation/view/{encryptedId}', [quotationViewController::class, 'index'])->name('quotationView.index');


Route::get('quote/logs/{quotationModel}',[quoteLog::class,'index'])->name('quoteLog.index');
// ตรวจสอบเส้นทางนี้ใน web.php หรือ api.php
Route::post('quote-logs/update-status/{quoteId}', [quoteLog::class, 'updateLogStatus'])->name('quote.updateLogStatus');
Route::post('quote/{quote}/upload-files', [quoteLog::class, 'uploadFiles'])->name('quote.uploadFiles');
Route::delete('quote/{quote}/delete-file', [quoteLog::class, 'deleteFile'])->name('quote.deleteFile');



// withholding
Route::get('withholdings',[withholdingTaxController::class,'index'])->name('withholding.index');
Route::get('withholding/create',[withholdingTaxController::class,'create'])->name('withholding.create');
Route::get('withholding/create/modal/{quotationModel}',[withholdingTaxController::class,'createModal'])->name('withholding.createModal');
Route::get('withholding/edit/{id}',[withholdingTaxController::class,'edit'])->name('withholding.edit');
Route::get('withholding/modal/edit/{id}',[withholdingTaxController::class,'modalEdit'])->name('withholding.modalEdit');
Route::get('withholding/editRepear/{id}',[withholdingTaxController::class,'editRepear'])->name('withholding.editRepear');
Route::get('withholding/show/{id}',[withholdingTaxController::class,'show'])->name('withholding.show');
Route::post('withholding/store',[withholdingTaxController::class,'store'])->name('withholding.store');
Route::delete('withholding/delete/{id}',[withholdingTaxController::class,'destroy'])->name('withholding.destroy');
Route::put('withholding/update/{id}',[withholdingTaxController::class,'update'])->name('withholding.update');
Route::get('withholding/taxNumber',[withholdingTaxController::class,'taxNumber'])->name('withholding.taxNumber');
// withholding MPDF
Route::get('mpdf/withholding/doc/{WithholdingTaxDocument}',[MPDF_WithhodingDocumentController::class,'generatePDF'])->name('MPDF.withholding');
Route::get('mpdf/printEnvelope/doc/{WithholdingTaxDocument}',[MPDF_WithhodingDocumentController::class,'printEnvelope'])->name('MPDF.printEnvelope');
Route::get('mpdf/withholding/new/doc/{WithholdingTaxDocument}',[MPDF_WithhodingDocumentController::class,'generatePDFwithholding'])->name('MPDF.generatePDFwithholding');
Route::get('mpdf/withholding/download/doc/{WithholdingTaxDocument}',[MPDF_WithhodingDocumentController::class,'downloadPDFwithholding'])->name('MPDF.downloadPDFwithholding');


