<?php

namespace App\Http\Controllers\quotefiles;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Models\customers\customerModel;
use App\Models\quotations\quotationModel;
use App\Models\quotefiles\quoteFileModel;

class QuoteFilesController extends Controller
{
    //

    public function index(quotationModel $quotationModel, Request $request)
    {
        $quoteFiles = DB::table('quote_file')->where('quote_number',$quotationModel->quote_number)->get();
        return view('quoteFiles.files-table',compact('quotationModel','quoteFiles'));
    }

    public function upload(Request $request)
  {
    // ดึงข้อมูลใบเสนอราคาจาก quote_number
    $quote = quotationModel::where('quote_number', $request->quote_number)->first();

    // สร้างพาธที่ถูกต้อง
    $folderPath = 'public/' . $quote->customer_id . '/files/' . $quote->quote_number;
    $absolutePath = storage_path('app/' . $folderPath);

    // เช็คว่าไดเร็กทอรีมีอยู่แล้วหรือไม่ หากไม่มีให้สร้างขึ้นมา
    if (!File::exists($absolutePath)) {
        File::makeDirectory($absolutePath, 0775, true);
    }

    $file = $request->file('file');

    if ($file) {
        // สร้างชื่อไฟล์ที่ไม่ซ้ำกัน
        $extension = $file->getClientOriginalExtension();
        $uniqueName = $request->file_name . date('Ymd') . '.' . $extension;

        // ย้ายไฟล์ไปยังตำแหน่งที่ต้องการ
        $file->move($absolutePath, $uniqueName);

        // สร้างพาธสัมพันธ์เพื่อจัดเก็บไฟล์ในฐานข้อมูล
        $filePath = 'storage/' . $quote->customer_id . '/files/' . $quote->quote_number . '/passport/' . $uniqueName;

        // อัปเดตข้อมูลใน request เพื่อเก็บในฐานข้อมูล
        $request->merge([
            'quote_file_name' => $uniqueName,
            'quote_file_path' => $filePath
        ]);

        // บันทึกข้อมูลไฟล์ในฐานข้อมูล
        quoteFileModel::create($request->all());
    }

    return redirect()->back()->with('success', 'ไฟล์ถูกอัปโหลดเรียบร้อยแล้ว');
}


public function delete(quoteFileModel $quoteFileModel)
{
    // เช็คว่าไฟล์มีอยู่หรือไม่ ถ้ามีจะลบไฟล์
    if (File::exists($quoteFileModel->quote_file_path)) {
        File::delete($quoteFileModel->quote_file_path); // ลบไฟล์
    }
    // ลบเรคคอร์ดออกจากฐานข้อมูล
    $quoteFileModel->delete();
    return redirect()->back()->with('success', 'ไฟล์ถูกลบเรียบร้อยแล้ว');
}

public function modalMail(quoteFileModel $quoteFileModel)
{
    $quotationModel = quotationModel::where('quote_number',$quoteFileModel->quote_number)->first();
    $customer =  customerModel::where('customer_id',$quotationModel->customer_id)->first();
    return view('quoteFiles.modal-mail-file',compact('quotationModel','customer','quoteFileModel'));
}


public function sendMail(quoteFileModel $quoteFileModel, Request $request)
{
    $quotationModel = quotationModel::where('quote_number', $quoteFileModel->quote_number)->first();
    $customer = customerModel::where('customer_id', $quotationModel->customer_id)->first();
    $sale = saleModel::select('name', 'id', 'email')->where('id', $quotationModel->quote_sale)->first();

    try {
        $filePath = $quoteFileModel->quote_file_path;
        $fileName = $quoteFileModel->quote_file_path;
        $mimeType = mime_content_type($filePath);

        Mail::send([], [], function ($message) use ($sale, $request, $quotationModel, $customer, $filePath, $fileName, $mimeType) {
            $message->to($request->email)
                    ->subject($request->subject)
                    ->html("
                        <h2>เรียน คุณ {$customer->customer_name}</h2>
                       
                        <br>
                        {$request->text_detail}
                    ")
                    ->attach($filePath, [
                        'as' => $fileName,
                        'mime' => $mimeType,
                    ]);
        });

        return response()->json(['success' => true, 'message' => 'ส่งอีเมลพร้อมไฟล์สำเร็จ']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการส่งอีเมล: ' . $e->getMessage()]);
    }
}


}
