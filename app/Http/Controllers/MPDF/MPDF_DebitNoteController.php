<?php

namespace App\Http\Controllers\MPDF;

use App\Http\Controllers\Controller;
use App\Models\customers\customerModel;
use App\Models\debitnote\debitNoteModel;
use Illuminate\Http\Request;

class MPDF_DebitNoteController extends Controller
{
    //



    public function generatePDF(debitNoteModel $debitNoteModel)
    {
        // การตั้งค่า font สำหรับภาษาไทย
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $customer = customerModel::where('customer_id',$debitNoteModel->quote->customer_id)->first();
        $html1 = view('MPDF.mpdf_debitNote',compact('debitNoteModel','customer'))->render();

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
        // $mpdf->AddPage();
    
        // ส่งออกไฟล์ PDF ไปยังเบราว์เซอร์เพื่อดาวน์โหลด
        return $mpdf->Output('TexReceipt.pdf', 'I'); // 'I' เพื่อแสดงในเบราว์เซอร์
    }


}
