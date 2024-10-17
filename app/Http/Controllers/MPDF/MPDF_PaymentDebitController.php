<?php

namespace App\Http\Controllers\MPDF;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\bank\bankModel;
use App\Models\booking\bookingModel;
use App\Models\customers\customerModel;
use App\Models\debits\debitModel;
use App\Models\invoices\invoiceModel;
use App\Models\payments\paymentModel;
use App\Models\quotations\quotationModel;
use App\Models\quotations\quoteProductModel;


class MPDF_PaymentDebitController extends Controller
{
    // TEST 
    public function generatePDF(paymentModel $paymentModel)
    {
        // การตั้งค่า font สำหรับภาษาไทย
        $debitModel = debitModel::where('debit_number',$paymentModel->payment_doc_number)->first();
        $bank = bankModel::where('bank_id', $paymentModel->payment_bank_number)->first();

       

        $invoice = invoiceModel::where('invoice_id',$debitModel->debit_invoice_id)->first();

        //dd($invoice->invoice_quote_id);

        $quotationModel = quotationModel::where('quote_id',$invoice->invoice_quote_id)->first();

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        
        $customer = customerModel::where('customer_id',$quotationModel->customer_id)->first();
        $sale = saleModel::where('id',$quotationModel->quote_sale)->first();
        $tour = DB::connection('mysql2')
        ->table('tb_tour')
        ->where('id', $quotationModel->tour_id)
        ->first();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->where('id', $quotationModel->quote_airline)->first();

        $booking = bookingModel::where('code', $quotationModel->quote_booking)->first();
        $productLists = quoteProductModel::where('quote_id',$quotationModel->quote_id)->get();
        // ดึง HTML จาก Blade Template
        $html = view('MPDF.mpdf_paymentDebit',compact('quotationModel','customer','sale','airline','booking','productLists','paymentModel','invoice','bank'))->render();
    
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
        $mpdf->SetMargins(-2.64, -2.64, 3, 0); // ซ้าย, ขวา, บน, ล่าง (หน่วยเป็นมิลลิเมตร)
        // เขียน HTML ลงใน PDF
        $mpdf->WriteHTML($html);
    
        // ส่งออกไฟล์ PDF ไปยังเบราว์เซอร์เพื่อดาวน์โหลด
        return $mpdf->Output('document.pdf', 'I'); // 'I' เพื่อแสดงในเบราว์เซอร์
    }
}
