<?php

namespace App\Http\Controllers\quotations;

use Storage;
use Illuminate\Http\Request;
use App\Models\QuoteLogModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\quotations\quotationModel;

class quoteLog extends Controller
{
    //

    public function index(quotationModel $quotationModel)
    {
        $quoteLog = QuoteLogModel::where('quote_id',$quotationModel->quote_id)->first();
        return view('quote-log.modal-check-lists',compact('quoteLog','quotationModel'));
    }


    public function updateLogStatus(Request $request, $quoteId)
{
    $request->validate([
        'field' => 'required|string',
        'status' => 'required|string',
        'created_by' => 'required|string',
    ]);

    $field = $request->input('field');
    $status = $request->input('status');
    $createdBy = Auth::user()->name;

    // ตรวจสอบว่ามี quote_id ใน QuoteLogModel หรือไม่
    $quoteLog = QuoteLogModel::where('quote_id', $quoteId)->first();

    if (!$quoteLog) {
        // หากไม่มี quote_id ให้สร้างใหม่
        $quoteLog = QuoteLogModel::create([
            'quote_id' => $quoteId,
            "{$field}_status" => $status,
            "{$field}_updated_at" => $status === 'ยังไม่ได้ส่ง' ? null : now(),
            "{$field}_created_by" => $status === 'ยังไม่ได้ส่ง' ? null : $createdBy,
        ]);
    } else {
        // หากมี quote_id อยู่แล้ว ให้ทำการอัปเดต
        $quoteLog->update([
            "{$field}_status" => $status,
            "{$field}_updated_at" => $status === 'ยังไม่ได้ส่ง' ? null : now(),
            "{$field}_created_by" => $status === 'ยังไม่ได้ส่ง' ? null : $createdBy,
        ]);
    }

    return response()->json([
        'message' => 'Status updated successfully',
        'status' => $status,
        'field' => $field,
        'updated_at' => $status === 'ยังไม่ได้ส่ง' ? null : now()->format('d M Y : H:m:s'),
        'created_by' => $status === 'ยังไม่ได้ส่ง' ? null : $createdBy
    ]);
}


// public function uploadFiles(Request $request, $quote)
// {
//     $quotation = quotationModel::where('quote_id',$quote)->first();

//     $request->validate([
//         'files.*' => 'file|mimes:pdf,jpg,png,jpeg|max:2048', // ประเภทและขนาดไฟล์ที่อนุญาต
//     ]);

//     $uploadedFiles = [];
//     if ($request->hasFile('files')) {
//         foreach ($request->file('files') as $file) {
//             $path = $file->store($quotation->customer_id.'/uploads/quote_files', 'public'); // จัดเก็บไฟล์ในโฟลเดอร์ที่กำหนด
//             $uploadedFiles[] = $path;
//         }
//     }

//     // บันทึกสถานะการอัปโหลดในตาราง log หรืออัปเดตข้อมูลที่ต้องการ
//     $quoteLog = QuoteLogModel::updateOrCreate(
//         ['quote_id' => $quote],
//         [
//             'invoice_status' => 'ได้แล้ว',
//             'invoice_updated_at' => now(),
//             'uploaded_files' => $uploadedFiles,
//             'invoice_created_by' => auth()->user()->name,
//         ]
//     );

//     return response()->json([
//         'message' => 'Files uploaded successfully',
//         'uploaded_files' => $uploadedFiles,
//         'updated_at' => now()->format('d M Y'),
//         'created_by' => auth()->user()->name,
//     ]);
// }

public function uploadFiles(Request $request, $quote)
{
    $quotation = quotationModel::where('quote_id', $quote)->first();
    $request->validate([
        'files.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048', // ประเภทและขนาดไฟล์ที่อนุญาต
    ]);

    $uploadedFiles = [];
    if ($request->hasFile('files')) {
        foreach ($request->file('files') as $file) {
            $path = $file->store($quotation->customer_id . '/uploads/quote_files', 'public');
            $uploadedFiles[] = asset('storage/' . $path); // เก็บ URL ของไฟล์
        }
    }

    // ตรวจสอบว่า JSON ของไฟล์เดิมถูกต้อง
    $quoteLog = QuoteLogModel::where('quote_id', $quote)->first();
    $existingFiles = [];
    if ($quoteLog && $quoteLog->uploaded_files) {
        try {
            $existingFiles = json_decode($quoteLog->uploaded_files, true) ?? [];
        } catch (\Exception $e) {
            // หาก JSON เก่ามีปัญหา ให้ใช้ array ว่างแทน
            $existingFiles = [];
        }
    }

    // รวมไฟล์ใหม่กับไฟล์เดิม
    $allFiles = array_merge($existingFiles, $uploadedFiles);

    // อัปเดตข้อมูลในฐานข้อมูล
    $quoteLog = QuoteLogModel::updateOrCreate(
        ['quote_id' => $quote],
        [
            'invoice_status' => 'ได้แล้ว',
            'invoice_updated_at' => now(),
            'invoice_created_by' => auth()->user()->name,
            'uploaded_files' => json_encode($allFiles), // บันทึกที่อยู่ไฟล์ทั้งหมดเป็น JSON
        ]
    );

    return response()->json([
        'message' => 'Files uploaded successfully',
        'uploaded_files' => $allFiles,
        'updated_at' => now()->format('d M Y'),
        'created_by' => auth()->user()->name,
    ]);
}


public function deleteFile(Request $request, $quote)
{
    $filePath = $request->input('file');

    // ตรวจสอบว่าไฟล์นี้มีอยู่ในฐานข้อมูล
    $quoteLog = QuoteLogModel::where('quote_id', $quote)->first();
    $uploadedFiles = json_decode($quoteLog->uploaded_files, true) ?? [];

    // หากไฟล์มีอยู่ใน array ให้ลบออก
    if (($key = array_search($filePath, $uploadedFiles)) !== false) {
        unset($uploadedFiles[$key]);

        // อัปเดตฐานข้อมูล
        $quoteLog->uploaded_files = json_encode(array_values($uploadedFiles));
        $quoteLog->save();

        // ลบไฟล์ออกจากโฟลเดอร์
        // ตัด 'storage/' ออกจากเส้นทางก่อนลบ
        $relativePath = str_replace(asset('storage/'), '', $filePath);
        \Storage::disk('public')->delete($relativePath);

        return response()->json(['message' => 'File deleted successfully', 'updated_files' => $uploadedFiles]);
    }

    return response()->json(['error' => 'File not found'], 404);
}










}
