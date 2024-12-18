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

class MPDF_WithhodingDocumentController extends Controller
{
    //

    public function generatePDF(WithholdingTaxDocument $WithholdingTaxDocument)
    {
        $customer = customerModel::where('customer_id',$WithholdingTaxDocument->customer_id)
        ->first();
        $imageSignature = DB::table('image_signature')->where('image_signture_id',$WithholdingTaxDocument->image_signture_id)->first();
        $item = WithholdingTaxItem::where('document_id',$WithholdingTaxDocument->id)->first();
        return view('MPDF.mpdf_withholdingDocument',compact('WithholdingTaxDocument','customer','item','imageSignature'));
    }

    // public function generatePDF(WithholdingTaxDocument $WithholdingTaxDocument)
    // {
    //     // การตั้งค่า font สำหรับภาษาไทย
    //     $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
    //     $fontDirs = $defaultConfig['fontDir'];
    //     $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
    //     $fontData = $defaultFontConfig['fontdata'];
        
        
    //     // $wholesale = wholesaleModel::where('id',$quotationModel->quote_wholesale)->first();
    //     // ดึง HTML จาก Blade Template
    //     $html = view('MPDF.mpdf_withholdingDocument',compact('WithholdingTaxDocument'))->render();
    
    //     // กำหนดค่าเริ่มต้นของ mPDF และเพิ่มฟอนต์ภาษาไทย
    //     $mpdf = new Mpdf([
    //         'format' => 'A4', // ใช้กระดาษขนาด A4
    //         'fontDir' => array_merge($fontDirs, [storage_path('app/fonts/')]),
    //         'fontdata' => $fontData + [
    //             'sarabun_new' => [
    //                 'R' => 'ANGSA.ttf',
    //                 'I' => 'ANGSAI.ttf',
    //                 'B' => 'angsab.ttf',
    //             ],
    //         ],
    //         'default_font' => 'ANGSA', 
    //         'margin_top' => 0,  // ปรับระยะขอบด้านบน
    //         'margin_bottom' => 0, // ปรับระยะขอบด้านล่าง
    //         'margin_left' => 0, // ปรับระยะขอบด้านซ้าย
    //         'margin_right' => 0, // ปรับระยะขอบด้านขวา
    //     ]);
        
    //     $mpdf->SetMargins(0, 0, 3, 0); // ซ้าย, ขวา, บน, ล่าง (หน่วยเป็นมิลลิเมตร)
    //     // เขียน HTML ลงใน PDF
        
    //     $mpdf->WriteHTML($html);
    
    //     // ส่งออกไฟล์ PDF ไปยังเบราว์เซอร์เพื่อดาวน์โหลด
    //     return $mpdf->Output('withholdingDocument.pdf', 'I'); // 'I' เพื่อแสดงในเบราว์เซอร์
    // }
    
}
