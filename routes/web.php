<?php

;
use App\Exports\invoiceExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MPDF\MailController;
use App\Http\Controllers\quotations\quoteLog;
use App\Http\Controllers\selects\periodSelect;
use App\Http\Controllers\api\apiTourController;
use App\Http\Controllers\WebTourSyncController;
use App\Http\Controllers\debits\debitController;
use App\Http\Controllers\CommissionRuleController;
use App\Http\Controllers\credits\creditController;
use App\Http\Controllers\MPDF\MailQuoteController;
use App\Http\Controllers\airline\airlineController;
use App\Http\Controllers\booking\BookingController;

use App\Http\Controllers\reports\saleTaxController;
use App\Http\Controllers\invoices\invoiceController;
use App\Http\Controllers\payments\paymentController;
use App\Http\Controllers\products\productController;
use App\Http\Controllers\quotations\quoteController;
use App\Http\Controllers\inputTax\inputTaxController;
use App\Http\Controllers\MPDF\MPDF_invoiceController;
use App\Http\Controllers\MPDF\MPDF_PaymentController;
use App\Http\Controllers\customers\customerController;
use App\Http\Controllers\FPDF\FPDF_QuotatioController;
use App\Http\Controllers\report\quoteReportController;
use App\Http\Controllers\reports\saleReportController;
use App\Http\Controllers\DebitNote\DebitNoteController;
use App\Http\Controllers\exports\QuoteExportController;
use App\Http\Controllers\exports\salesExportController;
use App\Http\Controllers\invoices\taxInvoiceController;
use App\Http\Controllers\MPDF\MPDF_DebitNoteController;
use App\Http\Controllers\MPDF\MPDF_QuotationController;
use App\Http\Controllers\exports\receiptExportControlle;
use App\Http\Controllers\MPDF\MPDF_creditNoteController;
use App\Http\Controllers\MPDF\MPDF_taxReceiptController;
use App\Http\Controllers\quotations\QuoteListController;
use App\Http\Controllers\wholeSales\wholeSaleController;
use App\Http\Controllers\CreditNote\creditNoteController;

use App\Http\Controllers\exports\invoiceExportController;
use App\Http\Controllers\exports\saleTaxExportController;
use App\Http\Controllers\MPDF\MPDF_WithholdingController;
use App\Http\Controllers\payments\paymentDebitController;
use App\Http\Controllers\quotefiles\QuoteFilesController;
use App\Http\Controllers\reports\invoiceReportController;
use App\Http\Controllers\reports\receiptReportController;
use App\Http\Controllers\reports\saleTaxReportController;
use App\Http\Controllers\commissions\CommissionController;
use App\Http\Controllers\exports\inputTaxExportController;
use App\Http\Controllers\MPDF\MPDF_DebitReceiptController;
use App\Http\Controllers\MPDF\MPDF_PaymentDebitController;
use App\Http\Controllers\payments\paymentCreditController;
use App\Http\Controllers\reports\inputTaxReportController;
use App\Http\Controllers\Invoices\InvoiceBookingController;

use App\Http\Controllers\MPDF\MPDF_CreditReceiptController;
use App\Http\Controllers\exports\taxinvoiceExportController;
use App\Http\Controllers\quotations\quotationViewController;
use App\Http\Controllers\reports\taxinvoiceReportController;
use App\Http\Controllers\invoices\invoiceDashboardController;
use App\Http\Controllers\withholding\withholdingTaxController;
use App\Http\Controllers\quotations\salesInformationController;
use App\Http\Controllers\MPDF\MPDF_WithhodingDocumentController;
use App\Http\Controllers\reports\paymentWholesaleReportController;
use App\Http\Controllers\paymentWholesale\paymentWholesaleController;

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
Route::put('invoice/mark-revised/{invoiceModel}', [invoiceController::class, 'markAsRevised'])->name('invoice.markRevised');
Route::put('invoice/unmark-revised/{invoiceModel}', [invoiceController::class, 'unmarkRevised'])->name('invoice.unmarkRevised');
Route::delete('invoice/delete/{invoiceModel}',[invoiceController::class,'delete'])->name('invoice.delete');


//taxtinvoice
Route::get('taxinvoice/{invoiceModel}',[taxInvoiceController::class,'store'])->name('invoice.taxinvoice');
Route::get('taxinvoice/edit/{invoiceModel}',[taxInvoiceController::class,'edit'])->name('taxinvoice.edit');
Route::put('taxinvoice/update/{invoiceModel}',[taxInvoiceController::class,'update'])->name('taxinvoice.update');
Route::put('taxinvoice/cancel/{taxinvoiceModel}',[taxInvoiceController::class,'cancel'])->name('taxinvoice.cancel');
Route::get('taxinvoice/modal/cancel/{taxinvoiceModel}',[taxInvoiceController::class,'modalCancel'])->name('taxinvoice.modalCancel');
Route::delete('taxinvoice/delete/{taxinvoiceModel}',[taxInvoiceController::class,'delete'])->name('taxinvoice.delete');

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
Route::get('quotations/',[QuoteListController::class,'index'])->name('quote.index');
Route::get('/',[QuoteListController::class,'index'])->name('quote.index')->middleware(['auth', 'permission:quote.view']);
Route::post('quote/store',[quoteController::class,'store'])->name('quote.store');
Route::get('quote/edit/{quotationModel}',[quoteController::class,'edit'])->name('quote.edit');
Route::put('quote/update/{quotationModel}',[quoteController::class,'update'])->name('quote.update');//
Route::put('quote/update/ajax/{quotationModel}',[quoteController::class,'AjaxUpdate'])->name('quote.AjaxUpdate');//
Route::put('quote/cancel/{quotationModel}',[quoteController::class,'cancel'])->name('quote.cancel');
Route::get('quote/create/new',[quoteController::class,'createNew'])->name('quote.createNew');
Route::get('quote/create/new/modern',[quoteController::class,'createModern'])->name('quote.createModern');

Route::get('quote/edit/new/{quotationModel}',[quoteController::class,'editNew'])->name('quote.editNew');

Route::get('quote/ajax/new/{quotationModel}',[quoteController::class,'editQuote'])->name('quote.editAjax');
Route::get('quote/modal/edit/{quotationModel}', [quoteController::class, 'modalEdit'])->name('quote.modalEdit');
Route::get('quote/modal/view/{quotationModel}', [quoteController::class, 'modalView'])->name('quote.modalView');


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
Route::get('payments/refresh/cancel/{paymentModel}',[paymentController::class,'RefreshCancel'])->name('payment.RefreshCancel');
Route::post('payments/send-mail', [paymentController::class, 'sendMail'])->name('payments.sendMail');
Route::post('payments/send-mail/pdf', [MPDF_PaymentController::class, 'sendMailWithPDF'])->name('payments.sendMailWithPDF');

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
Route::get('quotefile/modal/mail{quotationModel}',[QuoteFilesController::class,'modalMail'])->name('quotefile.modalMail');
Route::post('quotefile/send/mail{quotationModel}',[QuoteFilesController::class,'sendMail'])->name('quotefile.sendMail');

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
Route::get('inputtax/delete/{inputTaxModel}',[inputTaxController::class,'delete'])->name('inputtax.delete');
Route::get('inputtax/delete/file/{inputTaxModel}',[inputTaxController::class,'deletefile'])->name('inputtax.deletefile');

// public View
Route::get('quotation/view/{encryptedId}', [quotationViewController::class, 'index'])->name('quotationView.index');


Route::get('quote/logs/{quotationModel}',[quoteLog::class,'index'])->name('quoteLog.index');
Route::post('/send-wholesale-mail/{quotationModel}', [quoteLog::class, 'sendWholesaleMail'])->name('quote.sendWholesaleMail');
Route::get('modal/mail/quote/wholesale/{quotationModel}',[MailController::class,'formMailQuoteWholesale'])->name('mail.quote.formMailQuoteWholesale');
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
Route::get('withholding/export/excel',[withholdingTaxController::class,'exportExcel'])->name('withholding.export.excel');
// withholding MPDF
Route::get('mpdf/withholding/doc/{WithholdingTaxDocument}',[MPDF_WithhodingDocumentController::class,'generatePDF'])->name('MPDF.withholding');
Route::get('mpdf/printEnvelope/doc/{WithholdingTaxDocument}',[MPDF_WithhodingDocumentController::class,'printEnvelope'])->name('MPDF.printEnvelope');
Route::get('mpdf/withholding/new/doc/{WithholdingTaxDocument}',[MPDF_WithhodingDocumentController::class,'generatePDFwithholding'])->name('MPDF.generatePDFwithholding');
Route::get('mpdf/withholding/download/doc/{WithholdingTaxDocument}',[MPDF_WithhodingDocumentController::class,'downloadPDFwithholding'])->name('MPDF.downloadPDFwithholding');


// Debit Note
Route::get('/debit-note', [DebitNoteController::class, 'index'])->name('debit-note.index');
Route::get('/debit-note/create', [DebitNoteController::class, 'create'])->name('debit-note.create');
Route::post('/debit-note/store', [DebitNoteController::class, 'store'])->name('debit-note.store');
Route::put('/debit-note/update/{debitNoteModel}', [DebitNoteController::class, 'update'])->name('debit-note.update');
Route::get('/debit-note/edit/{debitNoteModel}', [DebitNoteController::class, 'edit'])->name('debit-note.edit');
Route::get('/debit-note/delete/{debitNoteModel}', [DebitNoteController::class, 'delete'])->name('debit-note.delete');
Route::get('/debit-note/copy/{debitNoteModel}', [DebitNoteController::class, 'copy'])->name('debit-note.copy');
Route::get('/debit-note/mpdf/{debitNoteModel}', [MPDF_DebitNoteController::class, 'generatePDF'])->name('MPDF.debit-note.generatePDF');
Route::post('mpdf/mail/debitnote/{debitNoteModel}',[MPDF_DebitNoteController::class,'sendPdf'])->name('mpdf.debitNoteModel.sendPdf');
Route::get('/debit-note/modal/mail/debitnote/{debitNoteModel}',[MailController::class,'formMailDebitNote'])->name('mail.debitNoteModel.formMail');

// Creadit Note
Route::get('/credit-note', [creditNoteController::class, 'index'])->name('credit-note.index');
Route::get('/credit-note/create', [creditNoteController::class, 'create'])->name('credit-note.create');
Route::post('/credit-note/store', [creditNoteController::class, 'store'])->name('credit-note.store');
Route::put('/credit-note/update/{creditNoteModel}', [creditNoteController::class, 'update'])->name('credit-note.update');
Route::get('/credit-note/edit/{creditNoteModel}', [creditNoteController::class, 'edit'])->name('credit-note.edit');
Route::get('/credit-note/delete/{creditNoteModel}', [creditNoteController::class, 'delete'])->name('credit-note.delete');
Route::get('/credit-note/copy/{creditNoteModel}', [creditNoteController::class, 'copy'])->name('credit-note.copy');
Route::get('/credit-note/mpdf/{creditNoteModel}', [MPDF_creditNoteController::class, 'generatePDF'])->name('MPDF.credit-note.generatePDF');
Route::post('mpdf/mail/creditnote/{creditNoteModel}',[MPDF_creditNoteController::class,'sendPdf'])->name('mpdf.creditNoteModel.sendPdf');
Route::get('/credit-note/modal/mail/creditnote/{creditNoteModel}',[MailController::class,'formMailCreditNote'])->name('mail.creditNoteModel.formMail');

//Report
Route::get('/report/inputtax/form',[inputTaxReportController::class,'index'])->name('report.input-tax');
Route::get('/report/invoice/form',[invoiceReportController::class,'index'])->name('report.invoice');
Route::get('/report/invoice/form/export',[invoiceReportController::class,'getExportData'])->name('report.invoice.export');
Route::get('/report/taxinvoice/form',[taxinvoiceReportController::class,'index'])->name('report.taxinvoice');
Route::get('/report/receipt/form',[receiptReportController::class,'index'])->name('report.receipt');
Route::get('/report/saletax/form',[saleTaxReportController::class,'index'])->name('report.saletax');
Route::get('/report/sales/form',[saleReportController::class,'index'])->name('report.sales');
Route::get('/report/sales/export',[saleReportController::class,'export'])->name('reports.sales.export');
Route::get('/report/payment-wholesale/form',[paymentWholesaleReportController::class,'index'])->name('report.payment-wholesale');
Route::get('/report/payment-wholesale/export', [paymentWholesaleReportController::class, 'exportExcel'])->name('report.payment-wholesale.export');

// Export Excel 
Route::post('export/excel/quote', [QuoteExportController::class, 'export'])->name('export.quote');
Route::post('export/excel/invoice', [invoiceExportController::class, 'export'])->name('export.invoice');
Route::post('export/excel/taxinvoice', [taxinvoiceExportController::class, 'export'])->name('export.taxinvoice');
Route::post('export/excel/receipt', [receiptExportControlle::class, 'export'])->name('export.receipt');
Route::post('export/excel/saletax', [saleTaxExportController::class, 'export'])->name('export.saletax');
Route::post('export/excel/inputtax', [inputTaxExportController::class, 'export'])->name('export.inputtax');



Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions.index');
Route::post('/commissions/store', [CommissionController::class, 'store'])->name('commissions.store');
Route::post('/commissions/update', [CommissionController::class, 'update'])->name('commissions.update');
Route::delete('/commissions/{id}', [CommissionController::class, 'destroy'])->name('commissions.destroy');

// กลุ่ม route ที่ต้องการ auth และ permission
Route::middleware(['auth'])->group(function () {
    // Products
    Route::get('/products', [productController::class, 'index'])->name('product.index');
    Route::get('/products/list', [productController::class, 'products'])->name('product.products');
    Route::get('/product/edit/{id}', [productController::class, 'edit'])->name('product.edit');
    Route::delete('/product/delete/{id}', [productController::class, 'destroy'])->name('product.destroy');
    Route::put('/product/update/{id}', [productController::class, 'update'])->name('product.update');
    Route::post('/product/store', [productController::class, 'store'])->name('product.store');

    // SetPermission Success Quotes 
    Route::get('quotations/', [QuoteListController::class, 'index'])->name('quote.index')->middleware(['auth', 'permission:quote.view']);
    Route::get('/', [QuoteListController::class, 'index'])->name('quote.index')->middleware(['auth', 'permission:quote.view']);
    Route::post('quote/store', [quoteController::class, 'store'])->name('quote.store')->middleware(['auth', 'permission:quote.create']);
    Route::get('quote/edit/{quotationModel}', [quoteController::class, 'edit'])->name('quote.edit')->middleware(['auth', 'permission:quote.edit']);
    Route::put('quote/update/{quotationModel}', [quoteController::class, 'update'])->name('quote.update')->middleware(['auth', 'permission:quote.edit']);
    Route::put('quote/update/ajax/{quotationModel}', [quoteController::class, 'AjaxUpdate'])->name('quote.AjaxUpdate');
    Route::put('quote/cancel/{quotationModel}', [quoteController::class, 'cancel'])->name('quote.cancel')->middleware(['auth', 'permission:quote.edit']);
    Route::get('quote/create/new', [quoteController::class, 'createNew'])->name('quote.createNew')->middleware(['auth', 'permission:quote.create']);
    Route::get('quote/edit/new/{quotationModel}', [quoteController::class, 'editNew'])->name('quote.editNew')->middleware(['auth', 'permission:quote.edit']);
    Route::get('quote/ajax/new/{quotationModel}', [quoteController::class, 'editQuote'])->name('quote.editAjax');
    Route::get('quote/modal/edit/{quotationModel}', [quoteController::class, 'modalEdit'])->name('quote.modalEdit')->middleware(['auth', 'permission:quote.edit']);
    Route::get('quote/modal/copy/edit/{quotationModel}', [quoteController::class, 'modalEditCopy'])->name('quote.modalEditCopy')->middleware(['auth', 'permission:quote.create']);
    Route::get('quote/modal/cancel/{quotationModel}', [quoteController::class, 'modalCancel'])->name('quote.modalCancel')->middleware(['auth', 'permission:quote.edit']);
    Route::get('quote/recancel/{quotationModel}', [quoteController::class, 'Recancel'])->name('quote.recancel')->middleware(['auth', 'permission:quote.edit']);
    ///test
        Route::get('quote/modal/edit/new/{quotationModel}', [quoteController::class, 'modalEditNew'])->name('quote.modalEditNew')->middleware(['auth', 'permission:quote.edit']);

    // Sales info
    Route::get('quote/sales/{quotationModel}', [salesInformationController::class, 'index'])->name('saleInfo.index');
    Route::get('quote/sales/info/{quotationModel}', [salesInformationController::class, 'info'])->name('saleInfo.info');
    // selects
    Route::get('/selects/period', [periodSelect::class, 'index'])->name('select.period');

    // Resource controllers (roles, users, products)
    Route::resources([
        'roles' => RoleController::class,
        'users' => UserController::class,
        'products' => ProductController::class,
    ]);

    // notifications (ต้อง login และมีสิทธิ์ดู notification)
    // Route::middleware(['permission:notification-view'])->group(function () {
        Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/fetch-latest', [App\Http\Controllers\NotificationController::class, 'fetchLatest'])->name('notifications.fetchLatest');
        Route::post('/notifications/mark-as-read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    });
// });

// notifications (ต้อง login และมีสิทธิ์ดู notification)
// Route::middleware(['auth', 'permission:notification-view'])->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/fetch-latest', [App\Http\Controllers\NotificationController::class, 'fetchLatest'])->name('notifications.fetchLatest');
    Route::post('/notifications/mark-as-read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::get('/notifications/go/{id}', [App\Http\Controllers\NotificationController::class, 'goToNotification'])->name('notifications.goTo');
// });

Route::get('quotelist', [QuoteListController::class, 'index'])->name('quotelist.index');
Route::delete('quotelist/destroy/{id}', [QuoteListController::class, 'destroy'])->name('quotelist.destroy');

// Route::prefix('customers')->group(function () {
//     Route::get('/', [\App\Http\Controllers\customers\CustomerController::class, 'index'])->name('customers.index');
//     Route::get('/create', [\App\Http\Controllers\customers\CustomerController::class, 'create'])->name('customers.create');
//     Route::post('/', [\App\Http\Controllers\customers\CustomerController::class, 'store'])->name('customers.store');
//     Route::get('/{id}/edit', [\App\Http\Controllers\customers\CustomerController::class, 'edit'])->name('customers.edit');
//     Route::put('/{id}', [\App\Http\Controllers\customers\CustomerController::class, 'update'])->name('customers.update');
//     Route::delete('/{id}', [\App\Http\Controllers\customers\CustomerController::class, 'destroy'])->name('customers.destroy');
// });

Route::prefix('cus')->group(function () {
    Route::get('/', [\App\Http\Controllers\cus\cusController::class, 'index'])->name('cus.index');
    Route::get('/create', [\App\Http\Controllers\cus\cusController::class, 'create'])->name('cus.create');
    Route::post('/', [\App\Http\Controllers\cus\cusController::class, 'store'])->name('cus.store');
    Route::get('/{id}/edit', [\App\Http\Controllers\cus\cusController::class, 'edit'])->name('cus.edit');
    Route::put('/{id}', [\App\Http\Controllers\cus\cusController::class, 'update'])->name('cus.update');
    Route::delete('/{id}', [\App\Http\Controllers\cus\cusController::class, 'destroy'])->name('cus.destroy');
});

Route::get('/web-tour/sync', [WebTourSyncController::class, 'syncNow']);

