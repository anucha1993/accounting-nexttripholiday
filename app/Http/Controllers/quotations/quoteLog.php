<?php

namespace App\Http\Controllers\quotations;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\quotations\quotationModel;
use App\Models\QuoteLogModel;
use Illuminate\Support\Facades\Auth;

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
        'updated_at' => $status === 'ยังไม่ได้ส่ง' ? null : now()->format('d M Y'),
        'created_by' => $status === 'ยังไม่ได้ส่ง' ? null : $createdBy
    ]);
}


public function uploadFiles(Request $request, $quote)
{
    $request->validate([
        'files.*' => 'file|mimes:pdf,jpg,png,jpeg|max:2048', // ประเภทและขนาดไฟล์ที่อนุญาต
    ]);

    $uploadedFiles = [];
    if ($request->hasFile('files')) {
        foreach ($request->file('files') as $file) {
            $path = $file->store('uploads/quote_files', 'public'); // จัดเก็บไฟล์ในโฟลเดอร์ที่กำหนด
            $uploadedFiles[] = $path;
        }
    }

    // บันทึกสถานะการอัปโหลดในตาราง log หรืออัปเดตข้อมูลที่ต้องการ
    $quoteLog = QuoteLog::updateOrCreate(
        ['quote_id' => $quote],
        [
            'invoice_status' => 'ได้แล้ว',
            'invoice_updated_at' => now(),
            'invoice_created_by' => auth()->user()->name,
        ]
    );

    return response()->json([
        'message' => 'Files uploaded successfully',
        'uploaded_files' => $uploadedFiles,
        'updated_at' => now()->format('d M Y'),
        'created_by' => auth()->user()->name,
    ]);
}




}
