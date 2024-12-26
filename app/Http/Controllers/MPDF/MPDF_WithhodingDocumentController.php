<?php

namespace App\Http\Controllers\MPDF;

use Mpdf\Mpdf;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\customers\customerModel;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;
use App\Models\withholding\WithholdingTaxItem;
use App\Models\withholding\WithholdingTaxDocument;
use App\Http\Controllers\quotations\quoteController;

class MPDF_WithhodingDocumentController extends Controller
{
    //

    public function generatePDF(WithholdingTaxDocument $WithholdingTaxDocument)
    {
        $customer = customerModel::where('customer_id', $WithholdingTaxDocument->customer_id)->first();
        $imageSignature = DB::table('image_signature')->where('image_signture_id', $WithholdingTaxDocument->image_signture_id)->first();
        $item = WithholdingTaxItem::where('document_id', $WithholdingTaxDocument->id)->first();
        return view('MPDF.mpdf_withholdingDocument', compact('WithholdingTaxDocument', 'customer', 'item', 'imageSignature'));
    }



    public function generatePDFwithholding(WithholdingTaxDocument $WithholdingTaxDocument)
    {
        $customer = customerModel::where('customer_id', $WithholdingTaxDocument->customer_id)->first();
        $imageSignature = DB::table('image_signature')->where('image_signture_id', $WithholdingTaxDocument->image_signture_id)->first();
        $item = WithholdingTaxItem::where('document_id', $WithholdingTaxDocument->id)->first();

           // การตั้งค่า font สำหรับภาษาไทย
           $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
           $fontDirs = $defaultConfig['fontDir'];
           $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
           $fontData = $defaultFontConfig['fontdata'];
           // ดึง HTML จาก Blade Template
           $html = view('MPDF.mpdf_withholding_new',compact('WithholdingTaxDocument', 'customer', 'item', 'imageSignature'));
       
           // กำหนดค่าเริ่มต้นของ mPDF และเพิ่มฟอนต์ภาษาไทย
           $mpdf = new \Mpdf\Mpdf([
               'format' => 'A4', // ใช้กระดาษขนาด A4
               'fontDir' => array_merge($fontDirs, [storage_path('app/fonts/')]),
               'fontdata' => $fontData + [
                  'sarabun_new' => [
                    'R' => 'ANGSA.ttf',
                    'I' => 'ANGSAI.ttf',
                    'B' => 'angsab.ttf',
                ],
            ],
            'default_font' => 'ANGSA', 
               'margin_top' => 0,  // ปรับระยะขอบด้านบน
               'margin_bottom' => 0, // ปรับระยะขอบด้านล่าง
               'margin_left' => 0, // ปรับระยะขอบด้านซ้าย
               'margin_right' => 0, // ปรับระยะขอบด้านขวา
           ]);
           
           $mpdf->SetMargins(0, 0, 3, 0); // ซ้าย, ขวา, บน, ล่าง (หน่วยเป็นมิลลิเมตร)
           // เขียน HTML ลงใน PDF
           $mpdf->WriteHTML($html);
       
           // ส่งออกไฟล์ PDF ไปยังเบราว์เซอร์เพื่อดาวน์โหลด
           return $mpdf->Output('ใบหัก_ณ_ที่จ่าย_'.$WithholdingTaxDocument->document_number.'_'.$customer->customer_name.'.pdf', 'I'); // 'I' เพื่อแสดงในเบราว์เซอร์
    }

    public function downloadPDFwithholding(WithholdingTaxDocument $WithholdingTaxDocument)
    {
        $customer = customerModel::where('customer_id', $WithholdingTaxDocument->customer_id)->first();
        $imageSignature = DB::table('image_signature')->where('image_signture_id', $WithholdingTaxDocument->image_signture_id)->first();
        $item = WithholdingTaxItem::where('document_id', $WithholdingTaxDocument->id)->first();

           // การตั้งค่า font สำหรับภาษาไทย
           $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
           $fontDirs = $defaultConfig['fontDir'];
           $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
           $fontData = $defaultFontConfig['fontdata'];
           // ดึง HTML จาก Blade Template
           $html = view('MPDF.mpdf_withholding_new',compact('WithholdingTaxDocument', 'customer', 'item', 'imageSignature'));
       
           // กำหนดค่าเริ่มต้นของ mPDF และเพิ่มฟอนต์ภาษาไทย
           $mpdf = new \Mpdf\Mpdf([
               'format' => 'A4', // ใช้กระดาษขนาด A4
               'fontDir' => array_merge($fontDirs, [storage_path('app/fonts/')]),
               'fontdata' => $fontData + [
                  'sarabun_new' => [
                    'R' => 'ANGSA.ttf',
                    'I' => 'ANGSAI.ttf',
                    'B' => 'angsab.ttf',
                ],
            ],
            'default_font' => 'ANGSA', 
               'margin_top' => 0,  // ปรับระยะขอบด้านบน
               'margin_bottom' => 0, // ปรับระยะขอบด้านล่าง
               'margin_left' => 0, // ปรับระยะขอบด้านซ้าย
               'margin_right' => 0, // ปรับระยะขอบด้านขวา
           ]);
           
           $mpdf->SetMargins(0, 0, 3, 0); // ซ้าย, ขวา, บน, ล่าง (หน่วยเป็นมิลลิเมตร)
           // เขียน HTML ลงใน PDF
           $mpdf->WriteHTML($html);
       
           // ส่งออกไฟล์ PDF ไปยังเบราว์เซอร์เพื่อดาวน์โหลด
           return $mpdf->Output('ใบหัก_ณ_ที่จ่าย_'.$WithholdingTaxDocument->document_number.'_'.$customer->customer_name.'.pdf', 'D'); // 'I' เพื่อแสดงในเบราว์เซอร์
    }




    public function printEnvelope(WithholdingTaxDocument $WithholdingTaxDocument)
    {
        // สร้างตัวแปรข้อมูลที่ใช้ในหน้าซอง
        $customer = customerModel::where('customer_id', $WithholdingTaxDocument->customer_id)->first();
        // การตั้งค่า font สำหรับภาษาไทย
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        // เริ่มต้นการสร้าง PDF
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
            'format' => [110, 220],  // ขนาดของซอง (DL)
            'orientation' => 'L',    // แนวตั้ง (Portrait)
        ]);

        // กำหนดรูปแบบของเอกสาร (เช่น ขนาดหน้าซอง)
        $mpdf->AddPage();  // ขนาด A4 สามารถเปลี่ยนเป็นขนาดอื่นได้

        // สร้างเนื้อหาที่จะพิมพ์
        $html = view('MPDF.envelope_template', compact('customer'))->render();

        // ใส่เนื้อหาลงใน PDF
        $mpdf->WriteHTML($html);

        // ส่งออกไฟล์ PDF
        return $mpdf->Output('envelope.pdf', 'I');
    }
}
