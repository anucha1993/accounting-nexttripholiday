<?php

namespace App\Http\Controllers\quotefiles;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
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
        $filePath = 'storage/' . $quote->customer_id . '/files/' . $quote->quote_number . '/' . $uniqueName;

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

}
