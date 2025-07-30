<?php

namespace App\Http\Controllers\MPDF;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\bank\bankModel;
use App\Models\booking\bookingModel;
use App\Models\customers\customerModel;
use App\Models\invoices\invoiceModel;
use App\Models\payments\paymentModel;
use App\Models\quotations\quotationModel;
use App\Models\quotations\quoteProductModel;


class MPDF_PaymentController extends Controller
{
    // TEST //
    public function generatePDF(paymentModel $paymentModel)
    {
        // การตั้งค่า font สำหรับภาษาไทย
        $quotationModel = quotationModel::where('quote_id',$paymentModel->payment_quote_id)->first();

        $bank = bankModel::where('bank_id', $paymentModel->payment_bank_number)->first();
        
        $invoice = invoiceModel::where('invoice_quote_id',$quotationModel->quote_id)->first();
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        
        
        $customer = customerModel::where('customer_id',$quotationModel->customer_id)->first();
        $sale = saleModel::where('id',$quotationModel->quote_sale)->first();
       
        $airline = DB::connection('mysql2')->table('tb_travel_type')->where('id', $quotationModel->quote_airline)->first();
        $productLists = quoteProductModel::where('quote_id',$quotationModel->quote_id)->get();
        // ดึง HTML จาก Blade Template
        $html = view('MPDF.mpdf_payment',compact('quotationModel','customer','sale','airline','productLists','paymentModel','invoice','bank'))->render();
    
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
        return $mpdf->Output($paymentModel->payment_number . '.pdf', 'I'); // 'I' เพื่อแสดงในเบราว์เซอร์
    }

    // ส่งอีเมลพร้อมแนบไฟล์ PDF ที่สร้างจาก mPDF
    public function sendMailWithPDF(Request $request)
    {
        $to = $request->input('email');
        $subject = $request->input('subject', 'แจ้งรายการชำระเงิน');
        $detail = $request->input('text_detail');
        $payment_id = $request->input('payment_id');

        // ดึง paymentModel และข้อมูลที่เกี่ยวข้อง
        $paymentModel = \App\Models\payments\paymentModel::find($payment_id);
        if (!$paymentModel) {
            return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูลการชำระเงิน']);
        }
        $quotationModel = \App\Models\quotations\quotationModel::where('quote_id', $paymentModel->payment_quote_id)->first();
        $bank = \App\Models\bank\bankModel::where('bank_id', $paymentModel->payment_bank_number)->first();
        $invoice = \App\Models\invoices\invoiceModel::where('invoice_quote_id', $quotationModel->quote_id)->first();
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $customer = \App\Models\customers\customerModel::where('customer_id', $quotationModel->customer_id)->first();
        $sale = \App\Models\sales\saleModel::where('id', $quotationModel->quote_sale)->first();
        $airline = \DB::connection('mysql2')->table('tb_travel_type')->where('id', $quotationModel->quote_airline)->first();
        $productLists = \App\Models\quotations\quoteProductModel::where('quote_id', $quotationModel->quote_id)->get();
        $html = view('MPDF.mpdf_payment', compact('quotationModel','customer','sale','airline','productLists','paymentModel','invoice','bank'))->render();
        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [storage_path('app/fonts/')]),
            'fontdata' => $fontData + [
                'sarabun_new' => [
                    'R' => 'THSarabunNew.ttf',
                    'I' => 'THSarabunNew Italic.ttf',
                    'B' => 'THSarabunNew Bold.ttf',
                ],
            ],
            'default_font' => 'sarabun_new',
        ]);
        $mpdf->SetMargins(-2.64, -2.64, 3, 0);
        $mpdf->WriteHTML($html);
        $pdfContent = $mpdf->Output('', 'S');

        try {
            \Mail::send([], [], function ($message) use ($to, $subject, $detail, $pdfContent, $paymentModel) {
                $message->to($to)
                    ->subject($subject)
                    ->html($detail, 'text/html')
                    ->attachData($pdfContent, $paymentModel->payment_number . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
            });
            return response()->json(['success' => true, 'message' => 'ส่งอีเมลพร้อมไฟล์ PDF สำเร็จ']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการส่งอีเมล: ' . $e->getMessage()]);
        }
    }

}
