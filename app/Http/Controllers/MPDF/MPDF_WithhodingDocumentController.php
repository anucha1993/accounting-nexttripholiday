<?php

namespace App\Http\Controllers\MPDF;

use Mpdf\Mpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\customers\customerModel;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;
use App\Models\withholding\WithholdingTaxItem;
use App\Models\withholding\WithholdingTaxDocument;
use App\Http\Controllers\quotations\quoteController;
use Barryvdh\DomPDF\Facade\Pdf;
class MPDF_WithhodingDocumentController extends Controller
{
    //

    public function generatePDF(WithholdingTaxDocument $WithholdingTaxDocument)
    {
        $customer = customerModel::where('customer_id',$WithholdingTaxDocument->customer_id)->first();
        $imageSignature = DB::table('image_signature')->where('image_signture_id',$WithholdingTaxDocument->image_signture_id)->first();
        $item = WithholdingTaxItem::where('document_id',$WithholdingTaxDocument->id)->first();
        return view('MPDF.mpdf_withholdingDocument',compact('WithholdingTaxDocument','customer','item','imageSignature'));
    }



    public function printEnvelope(WithholdingTaxDocument $WithholdingTaxDocument)
    {
        // สร้างตัวแปรข้อมูลที่ใช้ในหน้าซอง
        $customer = customerModel::where('customer_id',$WithholdingTaxDocument->customer_id)->first();
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
