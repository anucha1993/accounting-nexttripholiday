<?php

namespace App\Http\Controllers\MPDF;

use Mpdf\Mpdf;
use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use App\Models\mumday\numDayModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\booking\bookingModel;
use Illuminate\Support\Facades\Mail;
use App\Models\products\productModel;
use App\Models\customers\customerModel;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;
use App\Models\quotations\quoteProductModel;

class MPDF_QuotationController extends Controller
{
    //
    public function generatePDF(quotationModel $quotationModel)
    {
        // การตั้งค่า font สำหรับภาษาไทย
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        
        $bookingModel = bookingModel::where('code',$quotationModel->quote_booking)->first();
       
       
        $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
        $numDays = numDayModel::orderBy('num_day_total')->get();
        $wholesale = wholesaleModel::where('status', 'on')->get();
        $products = productModel::where('product_type','income')->get();
        $productDiscount = productModel::where('product_type','discount')->get();
        $quoteProducts = quoteProductModel::where('quote_id',$quotationModel->quote_id)->where('expense_type','income')->get();
        $quoteProductsDiscount = quoteProductModel::where('quote_id',$quotationModel->quote_id)->where('expense_type','discount')->get();



        
        $customer = customerModel::where('customer_id', $quotationModel->customer_id)->first();
        $sale = saleModel::select('name', 'id','email')->where('id',$quotationModel->quote_sale)->first();
        $airline = DB::connection('mysql2')->table('tb_travel_type')->where('id',$quotationModel->quote_airline)->first();
        $productLists = quoteProductModel::where('quote_id',$quotationModel->quote_id)->get();


        // ดึง HTML จาก Blade Template
        $html = view('MPDF.mpdf_quote',compact('quotationModel','customer','sale','airline','productLists'))->render();
    
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


    

    public function sendPdf(quotationModel $quotationModel, Request $request)
    {
      // การตั้งค่า font สำหรับภาษาไทย
    $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];
    $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $bookingModel = bookingModel::where('code', $quotationModel->quote_booking)->first();
    $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
    $airline = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
    $numDays = numDayModel::orderBy('num_day_total')->get();
    $wholesale = wholesaleModel::where('status', 'on')->get();
    $products = productModel::where('product_type', 'income')->get();
    $productDiscount = productModel::where('product_type', 'discount')->get();
    $quoteProducts = quoteProductModel::where('quote_id', $quotationModel->quote_id)->where('expense_type', 'income')->get();
    $quoteProductsDiscount = quoteProductModel::where('quote_id', $quotationModel->quote_id)->where('expense_type', 'discount')->get();

    $customer = customerModel::where('customer_id', $quotationModel->customer_id)->first();
    $sale = saleModel::select('name', 'id', 'email')->where('id', $quotationModel->quote_sale)->first();
    $airline = DB::connection('mysql2')->table('tb_travel_type')->where('id', $quotationModel->quote_airline)->first();
    $productLists = quoteProductModel::where('quote_id', $quotationModel->quote_id)->get();

    // ดึง HTML จาก Blade Template
    $html = view('MPDF.mpdf_quote', compact('quotationModel', 'customer', 'sale', 'airline', 'productLists'))->render();

    // กำหนดค่าเริ่มต้นของ mPDF และเพิ่มฟอนต์ภาษาไทย
    $mpdf = new Mpdf([
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

    // เก็บ PDF ในตัวแปรเป็น string
    $pdfOutput = $mpdf->Output('', 'S'); // 'S' หมายถึงเก็บเป็น string เพื่อส่งทางอีเมล

    // ส่งอีเมลพร้อมแนบไฟล์ PDF
    try {
        // ส่งอีเมล
        Mail::send([],[] , function($message) use ($pdfOutput, $sale, $request, $quotationModel) {
            $message->to($request->email) // ส่งอีเมลถึงที่อยู่ปลายทาง
                    ->subject($request->subject)
                    ->attachData($pdfOutput, $quotationModel->quote_number.'.pdf', [
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
