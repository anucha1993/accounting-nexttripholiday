<?php

namespace App\Http\Controllers\MPDF;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\booking\bookingModel;
use App\Models\invoices\invoiceModel;
use App\Models\customers\customerModel;
use App\Models\debits\debitModel;
use App\Models\invoices\invoicePorductsModel;
use App\Models\payments\paymentModel;
use App\Models\quotations\quotationModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\quotations\quoteProductModel;

class MPDF_DebitReceiptController extends Controller
{
    //

    public function generatePDF(debitModel $debitModel)
    {
        // การตั้งค่า font สำหรับภาษาไทย
        
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $invoiceModel = invoiceModel::where('invoice_number',$debitModel->invoice_number)->first();
        $quotationModel = quotationModel::where('quote_number',$invoiceModel->quote_number)->first();
        
        $customer = customerModel::where('customer_id',$invoiceModel->customer_id)->first();
        $sale = saleModel::where('id',$invoiceModel->invoice_sale)->first();
        $tour = DB::connection('mysql2')
        ->table('tb_tour')
        ->where('id', $invoiceModel->tour_id)
        ->first();
         $airline = DB::connection('mysql2')
        ->table('tb_travel_type')
        ->select('travel_name')
        ->where('id', $tour->airline_id)
        ->first();
        $booking = bookingModel::where('code', $invoiceModel->invoice_booking)->first();
        $productLists = invoicePorductsModel::where('invoice_id',$invoiceModel->invoice_id)->get();
        $NonVat = invoicePorductsModel::where('invoice_id',$invoiceModel->invoice_id)
        ->where('expense_type', 'income')
        ->where('vat', 'Y')
        ->sum('product_sum');
        $VatTotal = invoicePorductsModel::where('invoice_id',$invoiceModel->invoice_id)
        ->where('expense_type', 'income')
        ->where('vat', 'N')
        ->sum('product_sum');
        $paymentDeposit = paymentModel::where('payment_doc_number',$invoiceModel->quote_number)->sum('payment_total');
        $payment = paymentModel::where('payment_doc_number',$invoiceModel->quote_number)
        ->leftjoin('bank','bank.bank_id','payments.payment_bank_number')
        ->latest('payments.payment_date_time')->first();
        // ดึง HTML จาก Blade Template
        $html = view('MPDF.mpdf_DebitReceipt',compact('debitModel','payment','paymentDeposit','VatTotal','NonVat','quotationModel','invoiceModel','customer','sale','airline','booking','productLists'))->render();
    
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

}
