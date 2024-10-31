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


}
