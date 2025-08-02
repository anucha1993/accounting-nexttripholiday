<?php

namespace App\Http\Controllers\MPDF;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\customers\customerModel;
use App\Models\debitnote\debitNoteModel;
use App\Models\debits\debitNoteProductModel;

class MPDF_DebitNoteController extends Controller
{
    //  Debit
    public function generatePDF(debitNoteModel $debitNoteModel)
    {
        // การตั้งค่า font สำหรับภาษาไทย
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $customer = customerModel::where('customer_id',$debitNoteModel->quote->customer_id)->first();
        $productLists = debitNoteProductModel::where('debitnote_id',$debitNoteModel->debitnote_id)->get();
        $html1 = view('MPDF.mpdf_debitNote',compact('debitNoteModel','customer','productLists'))->render();
        $html2 = view('MPDF.mpdf_debitNote_copy',compact('debitNoteModel','customer','productLists'))->render();

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
        $mpdf->WriteHTML($html1);
        $mpdf->AddPage();
        $mpdf->WriteHTML($html2);
        // $mpdf->AddPage();
    
        // ส่งออกไฟล์ PDF ไปยังเบราว์เซอร์เพื่อดาวน์โหลด
        return $mpdf->Output($debitNoteModel->debtinote_number.'.pdf', 'I'); // 'I' เพื่อแสดงในเบราว์เซอร์
    }


    public function sendPdf(debitNoteModel $debitNoteModel, Request $request)
    {
       // การตั้งค่า font สำหรับภาษาไทย
       $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
       $fontDirs = $defaultConfig['fontDir'];
       $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
       $fontData = $defaultFontConfig['fontdata'];
       $customer = customerModel::where('customer_id',$debitNoteModel->quote->customer_id)->first();
       $productLists = debitNoteProductModel::where('debitnote_id',$debitNoteModel->debitnote_id)->get();
       $html1 = view('MPDF.mpdf_debitNote',compact('debitNoteModel','customer','productLists'))->render();
       $sale = saleModel::where('id', $debitNoteModel->quote_sale)->first();
       $html2 = view('MPDF.mpdf_debitNote_copy',compact('debitNoteModel','customer','productLists'))->render();

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
       $mpdf->WriteHTML($html1);

        // เก็บ PDF ในตัวแปรเป็น string
        $pdfOutput = $mpdf->Output('', 'S'); // 'S' หมายถึงเก็บเป็น string เพื่อส่งทางอีเมล
        // ส่งอีเมลพร้อมแนบไฟล์ PDF
        try {
            // ส่งอีเมล
            Mail::send([], [], function ($message) use ($pdfOutput, $sale, $request, $debitNoteModel, $customer) {
                $message->to($request->email) // ส่งอีเมลถึงที่อยู่ปลายทาง
                    ->subject($request->subject)
                    ->html("
                        <h2>เรียน คุณ {$customer->customer_name}</h2>
                        <p>ใบลดหนี้เลขที่ #{$debitNoteModel->debtinote_number}</p>
                        <p>กรุณาตรวจสอบไฟล์แนบใบแจ้งหนี้ที่ส่งมาพร้อมกับอีเมลนี้</p>
                        <br>
                        {$request->text_detail}
                    ", 'text/html') // ใช้เครื่องหมายอัญประกาศคู่สำหรับ PHP แทนการเชื่อมต่อข้อความ

                    ->attachData($pdfOutput, $debitNoteModel->debtinote_number . '.pdf', [
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
