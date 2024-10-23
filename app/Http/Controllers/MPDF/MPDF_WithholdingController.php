<?php

namespace App\Http\Controllers\MPDF;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\inputTax\inputTaxModel;
use App\Models\wholesale\wholesaleModel;

class MPDF_WithholdingController extends Controller
{
    //

    public function generatePDF(inputTaxModel $inputTaxModel)
    {
        // การตั้งค่า font สำหรับภาษาไทย
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        
        $wholesale = wholesaleModel::where('id',$inputTaxModel->input_tax_wholesale)->first();
        // ดึง HTML จาก Blade Template
        $html = view('MPDF.mpdf_withholding',compact('wholesale','inputTaxModel'))->render();
    
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
        return $mpdf->Output('withholding.pdf', 'I'); // 'I' เพื่อแสดงในเบราว์เซอร์
    }

}
