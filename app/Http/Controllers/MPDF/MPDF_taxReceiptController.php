<?php

namespace App\Http\Controllers\MPDF;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\booking\bookingModel;
use Illuminate\Support\Facades\Mail;
use App\Models\invoices\invoiceModel;
use App\Models\payments\paymentModel;
use App\Models\customers\customerModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\invoices\taxinvoiceModel;
use App\Models\quotations\quotationModel;
use App\Models\quotations\quoteProductModel;
use App\Models\invoices\invoicePorductsModel;

class MPDF_taxReceiptController extends Controller
{
    //

    public function generatePDF(invoiceModel $invoiceModel)
    {
        // การตั้งค่า font สำหรับภาษาไทย
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $quotationModel = quotationModel::where('quote_id',$invoiceModel->invoice_quote_id)->first();
        
        $customer = customerModel::where('customer_id',$invoiceModel->customer_id)->first();
        $sale = saleModel::where('id',$invoiceModel->invoice_sale)->first();
        $tour = DB::connection('mysql2')
        ->table('tb_tour')
        ->where('id', $invoiceModel->tour_id)
        ->first();

        $airline = DB::connection('mysql2')->table('tb_travel_type')->where('id', $quotationModel->quote_airline)->first();

      
        $booking = bookingModel::where('code', $invoiceModel->invoice_booking)->first();
        $productLists = invoicePorductsModel::where('invoice_id',$invoiceModel->invoice_id)->get();
        $NonVat = invoicePorductsModel::where('invoice_id',$invoiceModel->invoice_id)
        ->where('expense_type', 'income')
        ->where('vat_status', 'nonvat')
        ->sum('product_sum');

        $VatTotal = invoicePorductsModel::where('invoice_id',$invoiceModel->invoice_id)
        ->where('expense_type', 'income')
        ->where('vat_status', 'vat')
        ->sum('product_sum');
        $texreceipt = taxinvoiceModel::where('invoice_number',$invoiceModel->invoice_number)->first();
        $paymentDeposit = paymentModel::where('payment_doc_number',$invoiceModel->quote_number)->sum('payment_total');
        $payment = paymentModel::where('payment_doc_number',$invoiceModel->invoice_quote)
        ->leftjoin('bank','bank.bank_id','payments.payment_bank_number')
        ->latest('payments.payment_date_time')->first();
        // ดึง HTML จาก Blade Template
        $html = view('MPDF.mpdf_taxReceipt',compact('texreceipt','payment','paymentDeposit','VatTotal','NonVat','quotationModel','invoiceModel','customer','sale','airline','booking','productLists'))->render();
    
        // กำหนดค่าเริ่มต้นของ mPDF และเพิ่มฟอนต์ภาษาไทย
        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [storage_path('app/fonts/')]),
            'fontdata' => $fontData + [
                'sarabun_new' => [
                    'R' => 'THSarabunNew.ttf',
                    'I' => 'THSarabunNew Italic.ttf',
                    'B' => 'THSarabunNew Bold.ttf',
                ],
            ],
            'default_font' => 'sarabun_new', // ตั้งฟอนต์เริ่มต้นเป็น THSarabunNew
        ]);
        $mpdf->SetMargins(0, 0, 3, 0); // ซ้าย, ขวา, บน, ล่าง (หน่วยเป็นมิลลิเมตร)
        // เขียน HTML ลงใน PDF
        $mpdf->WriteHTML($html);
    
        // ส่งออกไฟล์ PDF ไปยังเบราว์เซอร์เพื่อดาวน์โหลด
        return $mpdf->Output('TexReceipt.pdf', 'I'); // 'I' เพื่อแสดงในเบราว์เซอร์
    }


    public function sendPdf(invoiceModel $invoiceModel, Request $request)
    {
       // การตั้งค่า font สำหรับภาษาไทย
       $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
       $fontDirs = $defaultConfig['fontDir'];
       $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
       $fontData = $defaultFontConfig['fontdata'];

       $quotationModel = quotationModel::where('quote_number',$invoiceModel->invoice_quote)->first();
       
       $customer = customerModel::where('customer_id',$invoiceModel->customer_id)->first();
       $sale = saleModel::where('id',$invoiceModel->invoice_sale)->first();
       $tour = DB::connection('mysql2')
       ->table('tb_tour')
       ->where('id', $invoiceModel->tour_id)
       ->first();
        $airline = DB::connection('mysql2')
       ->table('tb_travel_type')
       ->select('travel_name')
       ->where('id', $quotationModel->quote_airline)
       ->first();

       $booking = bookingModel::where('code', $invoiceModel->invoice_booking)->first();
       $productLists = invoicePorductsModel::where('invoice_id',$invoiceModel->invoice_id)->get();
       $NonVat = invoicePorductsModel::where('invoice_id',$invoiceModel->invoice_id)
       ->where('expense_type', 'income')
       ->where('vat_status', 'nonvat')
       ->sum('product_sum');

       $VatTotal = invoicePorductsModel::where('invoice_id',$invoiceModel->invoice_id)
       ->where('expense_type', 'income')
       ->where('vat_status', 'vat')
       ->sum('product_sum');
       $texreceipt = taxinvoiceModel::where('invoice_number',$invoiceModel->invoice_number)->first();
       $paymentDeposit = paymentModel::where('payment_doc_number',$invoiceModel->quote_number)->sum('payment_total');
       $payment = paymentModel::where('payment_doc_number',$invoiceModel->invoice_quote)
       ->leftjoin('bank','bank.bank_id','payments.payment_bank_number')
       ->latest('payments.payment_date_time')->first();
       // ดึง HTML จาก Blade Template
       $html = view('MPDF.mpdf_taxReceipt',compact('texreceipt','payment','paymentDeposit','VatTotal','NonVat','quotationModel','invoiceModel','customer','sale','airline','booking','productLists'))->render();
   
       // กำหนดค่าเริ่มต้นของ mPDF และเพิ่มฟอนต์ภาษาไทย
       $mpdf = new \Mpdf\Mpdf([
           'fontDir' => array_merge($fontDirs, [storage_path('app/fonts/')]),
           'fontdata' => $fontData + [
               'sarabun_new' => [
                   'R' => 'THSarabunNew.ttf',
                   'I' => 'THSarabunNew Italic.ttf',
                   'B' => 'THSarabunNew Bold.ttf',
               ],
           ],
           'default_font' => 'sarabun_new', // ตั้งฟอนต์เริ่มต้นเป็น THSarabunNew
       ]);
       $mpdf->SetMargins(0, 0, 3, 0); // ซ้าย, ขวา, บน, ล่าง (หน่วยเป็นมิลลิเมตร)
       // เขียน HTML ลงใน PDF
       $mpdf->WriteHTML($html);
   
        $pdfOutput = $mpdf->Output('', 'S'); // 'S' หมายถึงเก็บเป็น string เพื่อส่งทางอีเมล
        // ส่งอีเมลพร้อมแนบไฟล์ PDF
        try {
            // ส่งอีเมล
            Mail::send([], [], function ($message) use ($pdfOutput, $texreceipt, $request, $invoiceModel, $customer) {
                $message->to($request->email) // ส่งอีเมลถึงที่อยู่ปลายทาง
                    ->subject($request->subject)
                    ->html("
                        <h2>เรียน คุณ {$customer->customer_name}</h2>
                        <p>ใบกำกับภาษีเลขที่ #{$texreceipt->taxinvoice_number}</p>
                        <p>กรุณาตรวจสอบไฟล์แนบใบกำกับภาษีที่ส่งมาพร้อมกับอีเมลนี้</p>
                        <br>
                        {$request->text_detail}
                    ", 'text/html') // ใช้เครื่องหมายอัญประกาศคู่สำหรับ PHP แทนการเชื่อมต่อข้อความ

                    ->attachData($pdfOutput, $texreceipt->taxinvoice_number . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
            });
            // ส่งการตอบกลับในรูปแบบ JSON ถ้าสำเร็จ
            return response()->json(['success' => true, 'message' => 'ส่งอีเมลพร้อมไฟล์ PDF สำเร็จ']);
        } catch (\Exception $e) {
            // จับข้อผิดพลาดและส่งการตอบกลับในรูปแบบ JSON
            return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการส่งอีเมล: ' . $e->getMessage()]);
        }
    }



}
