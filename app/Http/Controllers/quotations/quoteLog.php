<?php

namespace App\Http\Controllers\quotations;

use Storage;
use Illuminate\Http\Request;
use App\Models\QuoteLogModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\quotations\quotationModel;
use App\Services\NotificationService;
use App\Models\User;

class quoteLog extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

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

    $quoteLog = QuoteLogModel::where('quote_id', $quoteId)->first();
    $quotation = quotationModel::with('quoteCustomer')->find($quoteId);

    if (!$quoteLog) {
        $quoteLog = QuoteLogModel::create([
            'quote_id' => $quoteId,
            "{$field}_status" => $status,
            "{$field}_updated_at" => $status === 'ยังไม่ได้' || $status === 'ยังไม่ได้ส่ง' || $status === 'ยังไม่ได้ออก' || $status === 'ยังไม่ได้รับ' || $status === 'ยังไม่ได้คืนเงิน' ? null : now(),
            "{$field}_created_by" => $status === 'ยังไม่ได้' || $status === 'ยังไม่ได้ส่ง' || $status === 'ยังไม่ได้ออก' || $status === 'ยังไม่ได้รับ' || $status === 'ยังไม่ได้คืนเงิน' ? null : $createdBy,
        ]);
    } else {
        $quoteLog->update([
            "{$field}_status" => $status,
            "{$field}_updated_at" => $status === 'ยังไม่ได้' || $status === 'ยังไม่ได้ส่ง' || $status === 'ยังไม่ได้ออก' || $status === 'ยังไม่ได้รับ' || $status === 'ยังไม่ได้คืนเงิน' ? null : now(),
            "{$field}_created_by" => $status === 'ยังไม่ได้' || $status === 'ยังไม่ได้ส่ง' || $status === 'ยังไม่ได้ออก' || $status === 'ยังไม่ได้รับ' || $status === 'ยังไม่ได้คืนเงิน' ? null : $createdBy,
        ]);
    }

    // Create notifications based on the specific field and status
    try {
        if ($quotation) {
            $customerName = $quotation->quoteCustomer->customer_name ?? 'ลูกค้า';
            $tourName = $quotation->quote_tour_name ?? $quotation->quote_number;
            $actionUrl = "/quote/edit/new/{$quoteId}";

            // Notify for passport submission
            if ($field === 'passport' && $status === 'ส่งแล้ว') {
                $this->notifyPassportSubmission($quoteId, $customerName, $tourName, $quotation->quote_sale, $actionUrl);
            } 
            // Notify for appointment letter submission
            elseif ($field === 'appointment' && $status === 'ส่งแล้ว') {
                $this->notifyAppointmentSubmission($quoteId, $customerName, $tourName, $quotation->quote_sale, $actionUrl);
            }
            // Notify for customer refund
            elseif ($field === 'customer_refund' && $status === 'คืนเงินสำเร็จ') {
                $this->notifyCustomerRefund($quoteId, $customerName, $tourName, $actionUrl);
            }
            // Notify for wholesale refund
            elseif ($field === 'wholesale_refund' && $status === 'คืนเงินสำเร็จ') {
                $this->notifyWholesaleRefund($quoteId, $customerName, $tourName, $actionUrl);
            }
        }
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error("ไม่สามารถสร้างการแจ้งเตือนได้: " . $e->getMessage());
    }

    return response()->json([
        'message' => 'Status updated successfully',
        'status' => $status,
        'field' => $field,
        'updated_at' => $status === 'ยังไม่ได้' || $status === 'ยังไม่ได้ส่ง' || $status === 'ยังไม่ได้ออก' || $status === 'ยังไม่ได้รับ' || $status === 'ยังไม่ได้คืนเงิน' ? null : now()->format('d-m-Y H:i:s'),
        'created_by' => $status === 'ยังไม่ได้' || $status === 'ยังไม่ได้ส่ง' || $status === 'ยังไม่ได้ออก' || $status === 'ยังไม่ได้รับ' || $status === 'ยังไม่ได้คืนเงิน' ? null : $createdBy,
    ]);
}


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
            'uploaded_files' => json_encode($allFiles),
            'invoice_quote_status' => 'ได้แล้ว',
            'invoice_quote_updated_at' => now(),
            'invoice_quote_created_by' => auth()->user()->name,
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
        \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);

        return response()->json(['message' => 'File deleted successfully', 'updated_files' => $uploadedFiles]);
    }

    return response()->json(['error' => 'File not found'], 404);
}

/**
 * แจ้งเตือนเมื่อมีการส่งพาสปอร์ต
 */
private function notifyPassportSubmission($quoteId, $customerName, $tourName, $saleId, $actionUrl)
{
    // แจ้งเตือนให้ admin, accounting และ Super Admin
    $notifyRoles = ['admin', 'accounting', 'Super Admin'];
    $users = User::whereHas('roles', function ($query) use ($notifyRoles) {
        $query->whereIn('name', $notifyRoles);
    })->get();

    // แจ้งเตือนให้ sales ที่รับผิดชอบ
    $salesPerson = User::where('id', $saleId)->first();
    if ($salesPerson && !$users->contains('id', $salesPerson->id)) {
        $users->push($salesPerson);
    }

    $message = "ได้รับพาสปอร์ตของลูกค้า {$customerName} สำหรับทัวร์ {$tourName} แล้ว";
    
    foreach ($users as $user) {
        // ไม่ส่งแจ้งเตือนให้ผู้ใช้ที่เป็นคนอัพเดทเอง
        if ($user->id != Auth::id()) {
            $this->notificationService->createForUser(
                $user->id,
                $message,
                'quotation',
                $quoteId,
                $actionUrl
            );
        }
    }
}

/**
 * แจ้งเตือนเมื่อมีการส่งใบนัดหมาย
 */
private function notifyAppointmentSubmission($quoteId, $customerName, $tourName, $saleId, $actionUrl)
{
    // แจ้งเตือนให้ admin, accounting และ Super Admin
    $notifyRoles = ['admin', 'accounting', 'Super Admin'];
    $users = User::whereHas('roles', function ($query) use ($notifyRoles) {
        $query->whereIn('name', $notifyRoles);
    })->get();

    // แจ้งเตือนให้ sales ที่รับผิดชอบ
    $salesPerson = User::where('id', $saleId)->first();
    if ($salesPerson && !$users->contains('id', $salesPerson->id)) {
        $users->push($salesPerson);
    }

    $message = "ได้ส่งใบนัดหมายให้ลูกค้า {$customerName} สำหรับทัวร์ {$tourName} แล้ว";
    
    foreach ($users as $user) {
        // ไม่ส่งแจ้งเตือนให้ผู้ใช้ที่เป็นคนอัพเดทเอง
        if ($user->id != Auth::id()) {
            $this->notificationService->createForUser(
                $user->id,
                $message,
                'quotation',
                $quoteId,
                $actionUrl
            );
        }
    }
}

/**
 * แจ้งเตือนเมื่อมีการคืนเงินให้ลูกค้า
 */
private function notifyCustomerRefund($quoteId, $customerName, $tourName, $actionUrl)
{
    // แจ้งเตือนให้ admin, accounting, sales และ Super Admin
    $notifyRoles = ['admin', 'accounting', 'sales', 'Super Admin'];
    $users = User::whereHas('roles', function ($query) use ($notifyRoles) {
        $query->whereIn('name', $notifyRoles);
    })->get();

    $message = "ได้คืนเงินให้ลูกค้า {$customerName} สำหรับทัวร์ {$tourName} เรียบร้อยแล้ว";
    
    foreach ($users as $user) {
        // ไม่ส่งแจ้งเตือนให้ผู้ใช้ที่เป็นคนอัพเดทเอง
        if ($user->id != Auth::id()) {
            $this->notificationService->createForUser(
                $user->id,
                $message,
                'quotation',
                $quoteId,
                $actionUrl
            );
        }
    }
}

/**
 * แจ้งเตือนเมื่อมีการคืนเงินกับโฮลเซล
 */
private function notifyWholesaleRefund($quoteId, $customerName, $tourName, $actionUrl)
{
    // แจ้งเตือนให้ admin, accounting และ Super Admin
    $notifyRoles = ['admin', 'accounting', 'Super Admin'];
    $users = User::whereHas('roles', function ($query) use ($notifyRoles) {
        $query->whereIn('name', $notifyRoles);
    })->get();

    $message = "ได้คืนเงินกับโฮลเซลสำหรับทัวร์ {$tourName} ของลูกค้า {$customerName} เรียบร้อยแล้ว";
    
    foreach ($users as $user) {
        // ไม่ส่งแจ้งเตือนให้ผู้ใช้ที่เป็นคนอัพเดทเอง
        if ($user->id != Auth::id()) {
            $this->notificationService->createForUser(
                $user->id,
                $message,
                'quotation',
                $quoteId,
                $actionUrl
            );
        }
    }
}
}
