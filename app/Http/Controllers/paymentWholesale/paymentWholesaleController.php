<?php

namespace App\Http\Controllers\paymentWholesale;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use App\Models\payments\paymentModel;
use App\Models\customers\customerModel;
use App\Models\quotations\quotationModel;
use App\Models\payments\paymentWholesaleModel;
use App\Services\NotificationService;
use App\Models\QuoteLogModel;
use App\Models\User;

class paymentWholesaleController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    // function Runnumber paymentWholesale
    public function generateRunningCodeWS()
    {
        $code = paymentWholesaleModel::latest()->first();
        if (!empty($code)) {
            $codeNumber = $code->payment_wholesale_number;
        } else {
            $codeNumber = 'WS' . date('y') . date('m') . '0000';
        }
        $prefix = 'WS';
        $year = date('y');
        $month = date('m');
        $lastFourDigits = substr($codeNumber, -4);
        $incrementedNumber = intval($lastFourDigits) + 1;
        $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
        $runningCode = $prefix . $year . $month . $newNumber;
        return $runningCode;
    }

    public function index(quotationModel $quotationModel, Request $request)
    {
        $paymentWholesale = paymentWholesaleModel::where('payment_wholesale_quote_id', $quotationModel->quote_id)->get();
        return view('paymentWholesale.index', compact('quotationModel', 'paymentWholesale'));
    }

    public function store(Request $request)
    {
       // dd($request);
        $quote = quotationModel::where('quote_id', $request->payment_wholesale_quote_id)->first();
        // สร้างพาธที่ถูกต้อง
        $folderPath = 'public/' . $quote->customer_id . '/wholesalePayment/' . $quote->quote_number;
        $absolutePath = storage_path('app/' . $folderPath);
        // เช็คว่าไดเร็กทอรีมีอยู่แล้วหรือไม่ หากไม่มีให้สร้างขึ้นมา
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0775, true);
        }
        $file = $request->file('file');
        if ($file) {
            // สร้างชื่อไฟล์ที่ไม่ซ้ำกัน
            $extension = $file->getClientOriginalExtension();
            $uniqueName = $this->generateRunningCodeWS() . date('Ymd') . '.' . $extension;

            // ย้ายไฟล์ไปยังตำแหน่งที่ต้องการ
            $file->move($absolutePath, $uniqueName);

            // สร้างพาธสัมพันธ์เพื่อจัดเก็บไฟล์ในฐานข้อมูล
            $filePath = 'storage/' . $quote->customer_id . '/wholesalePayment/' . $quote->quote_number . '/' . $uniqueName;

            // อัปเดตข้อมูลใน request เพื่อเก็บในฐานข้อมูล
            $request->merge([
                'payment_wholesale_file_name' => $uniqueName,
                'payment_wholesale_file_path' => $filePath,
            ]);
        }
        $request->merge(['payment_wholesale_number' => $this->generateRunningCodeWS(), 'created_by' => Auth::user()->name]);
        $paymentWholesale = paymentWholesaleModel::create($request->all());
        
        // อัปเดตสถานะในตาราง quote_logs
        $this->updateQuoteLogWholesalePayment($request->payment_wholesale_quote_id);
        
        // สร้างการแจ้งเตือนการชำระเงินจากโฮลเซลล์
        try {
            // ดึงข้อมูลที่จำเป็นสำหรับการแจ้งเตือน
            $wholesaleName = $request->payment_wholesale_name ?? 'ไม่ระบุ';
            $paymentAmount = $request->payment_wholesale_amount ?? 0;
            
            // กำหนดประเภทการชำระเงิน (มัดจำหรือยอดเต็ม)
            $paymentType = ($request->payment_wholesale_type === 'deposit') ? 'มัดจำ' : 'ยอดเต็ม';
            
            // URL สำหรับดูรายละเอียดการชำระเงิน
            $actionUrl = "/payment/wholesale/edit/{$paymentWholesale->payment_wholesale_id}";
            
            // ส่งการแจ้งเตือนไปยังทุกฝ่ายที่เกี่ยวข้อง (admin, accounting, และ sales)
            $this->sendWholesalePaymentNotifications(
                $paymentWholesale->payment_wholesale_id,
                $request->payment_wholesale_quote_id,
                $wholesaleName,
                $paymentAmount,
                $paymentType,
                $actionUrl
            );
            
        } catch (\Exception $e) {
            // บันทึกข้อผิดพลาดแต่ไม่ให้กระทบกับการบันทึกข้อมูลหลัก
            \Illuminate\Support\Facades\Log::error("ไม่สามารถสร้างการแจ้งเตือนการชำระเงินจากโฮลเซลล์ได้: " . $e->getMessage());
        }
        
        return redirect()->back();
    }

    public function delete(paymentWholesaleModel $paymentWholesaleModel)
    {
        // เช็คว่าไฟล์มีอยู่หรือไม่ ถ้ามีจะลบไฟล์
        if (File::exists($paymentWholesaleModel->payment_wholesale_file_path)) {
            File::delete($paymentWholesaleModel->payment_wholesale_file_path); // ลบไฟล์
        }

        // ลบเรคคอร์ดออกจากฐานข้อมูล
        $paymentWholesaleModel->delete();
        return redirect()->back()->with('success', 'ไฟล์ถูกลบเรียบร้อยแล้ว');
    }

    public function edit(paymentWholesaleModel $paymentWholesaleModel)
    {
        return view('paymentWholesale.modal-edit', compact('paymentWholesaleModel'));
    }
    public function editRefund(paymentWholesaleModel $paymentWholesaleModel)
    {
        return view('paymentWholesale.modal-refund-edit', compact('paymentWholesaleModel'));
    }

    public function update(paymentWholesaleModel $paymentWholesaleModel, Request $request)
    {
        $file = $request->file('file');

        $quote = quotationModel::where('quote_id', $paymentWholesaleModel->payment_wholesale_quote_id)->first();
        $folderPath = 'public/' . $quote->customer_id . '/wholesalePayment/' . $quote->quote_number;
        $absolutePath = storage_path('app/' . $folderPath);
        if ($file) {
            //ลบไฟล์เดิม
            if (File::exists($paymentWholesaleModel->payment_wholesale_file_path)) {
                File::delete($paymentWholesaleModel->payment_wholesale_file_path); // ลบไฟล์
            }
            //ลงไฟล์ใหม่

            // สร้างชื่อไฟล์ที่ไม่ซ้ำกัน
            $extension = $file->getClientOriginalExtension();
            $uniqueName = $this->generateRunningCodeWS() . date('Ymd') . '.' . $extension;

            // ย้ายไฟล์ไปยังตำแหน่งที่ต้องการ
            $file->move($absolutePath, $uniqueName);

            // สร้างพาธสัมพันธ์เพื่อจัดเก็บไฟล์ในฐานข้อมูล
            $filePath = 'storage/' . $quote->customer_id . '/wholesalePayment/' . $quote->quote_number . '/' . $uniqueName;

            $request->merge([
                'payment_wholesale_file_name' => $uniqueName,
                'payment_wholesale_file_path' => $filePath,
                'created_by' => Auth::user()->name,
            ]);
        }

        $paymentWholesaleModel->update($request->all());
        return redirect()->back();
    }

    public function updateRefund(paymentWholesaleModel $paymentWholesaleModel, Request $request)
    {
        //dd($request);
        $quote = quotationModel::where('quote_id', $paymentWholesaleModel->payment_wholesale_quote_id)->first();
        $folderPath = 'public/' . $quote->customer_id . '/wholesalePayment/' . $quote->quote_number;
        $absolutePath = storage_path('app/' . $folderPath);
        $status = 'wait';
        
         // File 1
        $files = ['file', 'file1', 'file2']; // Array of file input names
        foreach ($files as $key => $fileInputName) {
            if ($request->hasFile($fileInputName)) {
                $status = 'success';
                $file = $request->file($fileInputName);
                // ลบไฟล์เดิม (ตรวจสอบ path ก่อนลบ)
                $filePathToDelete = $paymentWholesaleModel->{'payment_wholesale_refund_file_path' . ($key > 0 ? $key : '')}; // สร้าง path แบบ dynamic
                if (File::exists($filePathToDelete)) {
                    File::delete($filePathToDelete);
                }
                // ลงไฟล์ใหม่
                $extension = $file->getClientOriginalExtension();
                $uniqueName = $this->generateRunningCodeWS() . 'REFUND' . date('Ymd') .$key. '.'. $extension;
                $file->move($absolutePath, $uniqueName);
                // สร้างพาธสัมพันธ์
                $filePath = 'storage/' . $quote->customer_id . '/wholesalePayment/' . $quote->quote_number . '/' . $uniqueName;
                $request->merge([
                    'payment_wholesale_refund_file_name' . ($key > 0 ? $key : '') => $uniqueName, // สร้างชื่อ field แบบ dynamic
                    'payment_wholesale_refund_file_path' . ($key > 0 ? $key : '') => $filePath, // สร้าง path field แบบ dynamic
                    'created_by' => Auth::user()->name,
                ]);
            }
        }
        $request->merge(['payment_wholesale_refund_status' => $status]);
        $paymentWholesaleModel->update($request->all());
        
        // สร้างการแจ้งเตือนเมื่อมีการอัพเดทคืนเงินจากโฮลเซลล์
        try {
            if ($status === 'success') {
                $quote = quotationModel::where('quote_id', $paymentWholesaleModel->payment_wholesale_quote_id)->first();
                $bookingId = $request->payment_wholesale_booking_id ?? $paymentWholesaleModel->payment_wholesale_booking_id ?? 0;
                $wholesaleName = $paymentWholesaleModel->payment_wholesale_name ?? 'ไม่ระบุ';
                $refundAmount = $request->payment_wholesale_refund_amount ?? $paymentWholesaleModel->payment_wholesale_refund_amount ?? 0;
                
                // URL สำหรับดูรายละเอียดการคืนเงิน
                $actionUrl = "/payment/wholesale/edit/refund/{$paymentWholesaleModel->payment_wholesale_id}";
                
                // อัปเดตสถานะในตาราง quote_logs
                $this->updateQuoteLogWholesaleRefund($paymentWholesaleModel->payment_wholesale_quote_id);
                
                // ส่งการแจ้งเตือนไปยังทุกฝ่ายที่เกี่ยวข้อง (admin, accounting, และ sales)
                $this->sendWholesaleRefundNotifications(
                    $paymentWholesaleModel->payment_wholesale_id,
                    $paymentWholesaleModel->payment_wholesale_quote_id,
                    $wholesaleName,
                    $refundAmount,
                    $actionUrl
                );
            }
        } catch (\Exception $e) {
            // บันทึกข้อผิดพลาดแต่ไม่ให้กระทบกับการอัพเดทหลัก
            \Illuminate\Support\Facades\Log::error("ไม่สามารถสร้างการแจ้งเตือนการคืนเงินจากโฮลเซลล์ได้: " . $e->getMessage());
        }
        
        return redirect()->back();
    }

    public function quote(quotationModel $quotationModel)
    {
        return view('paymentWholesale.modal-quote', compact('quotationModel'));
    }

    public function payment(quotationModel $quotationModel)
    {
        $paymentWholesale = paymentWholesaleModel::where('payment_wholesale_quote_id', $quotationModel->quote_id)->get();
        return View::make('paymentWholesale.wholesale-table', compact('quotationModel', 'paymentWholesale'))->render();
    }

    public function refund(paymentWholesaleModel $paymentWholesaleModel)
    {
        return view('paymentWholesale.modal-refund', compact('paymentWholesaleModel'));
    }

    public function modalMailWholesale(paymentWholesaleModel $paymentWholesaleModel)
    {
        $quotationModel = quotationModel::where('quote_id', $paymentWholesaleModel->payment_wholesale_quote_id)->first();
        $customer = customerModel::where('customer_id', $quotationModel->customer_id)->first();
        return view('paymentWholesale.modal-mail-wholesale', compact('quotationModel', 'customer', 'paymentWholesaleModel'));
    }

    public function sendMail(paymentWholesaleModel $paymentWholesaleModel, Request $request)
    {
        $quotationModel = quotationModel::where('quote_id', $paymentWholesaleModel->payment_wholesale_quote_id)->first();
        $customer = customerModel::where('customer_id', $quotationModel->customer_id)->first();
        $sale = saleModel::select('name', 'id', 'email')->where('id', $quotationModel->quote_sale)->first();

        try {
            $filePath = $paymentWholesaleModel->payment_wholesale_file_path;
            $fileName = $paymentWholesaleModel->payment_wholesale_file_name;
            $mimeType = mime_content_type($filePath);

            Mail::send([], [], function ($message) use ($sale, $request, $quotationModel, $customer, $filePath, $fileName, $mimeType) {
                $message
                    ->to($request->email)
                    ->subject($request->subject)
                    ->html(
                        "
                        <h2>เรียน คุณ {$customer->customer_name}</h2>
                        <p>ใบเสนอราคาเลขที่ #{$quotationModel->quote_number}</p>
                        <p>ไฟล์เอกสารแนบชำระเงินโฮลเซลล์</p>
                        <br>
                        {$request->text_detail}
                    ",
                    )
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

    /**
     * อัปเดตสถานะการคืนเงินโฮลเซลล์ในตาราง QuoteLogModel
     *
     * @param int $quoteId
     * @return void
     */
    private function updateQuoteLogWholesaleRefund($quoteId)
    {
        // ค้นหาหรือสร้าง QuoteLogModel สำหรับ quote นี้
        $quoteLog = \App\Models\QuoteLogModel::firstOrCreate(['quote_id' => $quoteId]);
        
        // อัปเดตสถานะการคืนเงินโฮลเซลล์
        $quoteLog->update([
            'wholesale_refund_status' => 'คืนเงินสำเร็จ',
            'wholesale_refund_updated_at' => now(),
            'wholesale_refund_created_by' => \Illuminate\Support\Facades\Auth::user()->name,
        ]);
    }

    /**
     * อัปเดตสถานะการชำระเงินจากโฮลเซลล์ในตาราง QuoteLogModel
     *
     * @param int $quoteId
     * @return void
     */
    private function updateQuoteLogWholesalePayment($quoteId)
    {
        // ค้นหาหรือสร้าง QuoteLogModel สำหรับ quote นี้
        $quoteLog = QuoteLogModel::firstOrCreate(['quote_id' => $quoteId]);
        
        // อัปเดตสถานะการรับเงินจากโฮลเซลล์
        $quoteLog->update([
            'wholesale_tax_status' => 'ได้รับแล้ว',
            'wholesale_tax_updated_at' => now(),
            'wholesale_tax_created_by' => Auth::user()->name,
        ]);
    }

    /**
     * ส่งการแจ้งเตือนเมื่อมีการชำระเงินจากโฮลเซลล์ไปยังผู้ที่เกี่ยวข้องทั้งหมด
     *
     * @param int $paymentWholesaleId ID ของการชำระเงิน
     * @param int $quoteId ID ของใบเสนอราคา
     * @param string $wholesaleName ชื่อโฮลเซลล์
     * @param float $paymentAmount จำนวนเงินที่ชำระ
     * @param string $paymentType ประเภทการชำระ ("มัดจำ" หรือ "ยอดเต็ม")
     * @param string $actionUrl URL ไปยังหน้าที่เกี่ยวข้อง
     * @return void
     */
    private function sendWholesalePaymentNotifications($paymentWholesaleId, $quoteId, $wholesaleName, $paymentAmount, $paymentType, $actionUrl)
    {
        try {
            // ดึงข้อมูล Quote และ Sale ที่เกี่ยวข้อง
            $quote = quotationModel::find($quoteId);
            
            if (!$quote) {
                \Illuminate\Support\Facades\Log::error("ไม่พบข้อมูล Quote ID: {$quoteId}");
                return;
            }
            
            // ข้อความสำหรับแจ้งเตือน
            $message = "ได้รับชำระเงิน{$paymentType}จากโฮลเซลล์: {$wholesaleName} จำนวน " . number_format($paymentAmount, 2) . " บาท";
            
            // ข้อมูลเพิ่มเติม
            $data = [
                'payment_wholesale_id' => $paymentWholesaleId,
                'wholesale_name' => $wholesaleName,
                'payment_amount' => $paymentAmount,
                'payment_type' => $paymentType
            ];
            
            // แจ้งเตือนให้ admin, accounting และ Super Admin
            $notifyRoles = ['admin', 'accounting', 'Super Admin'];
            $users = User::whereHas('roles', function ($query) use ($notifyRoles) {
                $query->whereIn('name', $notifyRoles);
            })->get();
            
            // แจ้งเตือนให้ sales ที่รับผิดชอบ
            $salesPerson = User::where('id', $quote->quote_sale)->first();
            if ($salesPerson && !$users->contains('id', $salesPerson->id)) {
                $users->push($salesPerson);
            }
            
            foreach ($users as $user) {
                // ไม่ส่งแจ้งเตือนให้ผู้ใช้ที่เป็นคนอัพเดทเอง
                if ($user->id != Auth::id()) {
                    $this->notificationService->createForUser(
                        $user->id,
                        $message,
                        'wholesale_payment',
                        $paymentWholesaleId,
                        $actionUrl
                    );
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("ไม่สามารถส่งการแจ้งเตือนการชำระเงินจากโฮลเซลล์ได้: " . $e->getMessage());
        }
    }

    /**
     * ส่งการแจ้งเตือนเมื่อมีการคืนเงินโฮลเซลล์ไปยังผู้ที่เกี่ยวข้องทั้งหมด
     *
     * @param int $paymentWholesaleId ID ของการชำระเงิน
     * @param int $quoteId ID ของใบเสนอราคา
     * @param string $wholesaleName ชื่อโฮลเซลล์
     * @param float $refundAmount จำนวนเงินที่คืน
     * @param string $actionUrl URL ไปยังหน้าที่เกี่ยวข้อง
     * @return void
     */
    private function sendWholesaleRefundNotifications($paymentWholesaleId, $quoteId, $wholesaleName, $refundAmount, $actionUrl)
    {
        try {
            // ดึงข้อมูล Quote และ Sale ที่เกี่ยวข้อง
            $quote = quotationModel::find($quoteId);
            
            if (!$quote) {
                \Illuminate\Support\Facades\Log::error("ไม่พบข้อมูล Quote ID: {$quoteId}");
                return;
            }
            
            // ข้อความสำหรับแจ้งเตือน
            $message = "เงินคืนจากโฮลเซลล์: {$wholesaleName} จำนวน " . number_format($refundAmount, 2) . " บาท ได้รับเรียบร้อยแล้ว";
            
            // ข้อมูลเพิ่มเติม
            $data = [
                'payment_wholesale_id' => $paymentWholesaleId,
                'wholesale_name' => $wholesaleName,
                'refund_amount' => $refundAmount
            ];
            
            // แจ้งเตือนให้ admin, accounting และ Super Admin
            $notifyRoles = ['admin', 'accounting', 'Super Admin'];
            $users = User::whereHas('roles', function ($query) use ($notifyRoles) {
                $query->whereIn('name', $notifyRoles);
            })->get();
            
            // แจ้งเตือนให้ sales ที่รับผิดชอบ
            $salesPerson = User::where('id', $quote->quote_sale)->first();
            if ($salesPerson && !$users->contains('id', $salesPerson->id)) {
                $users->push($salesPerson);
            }
            
            foreach ($users as $user) {
                // ไม่ส่งแจ้งเตือนให้ผู้ใช้ที่เป็นคนอัพเดทเอง
                if ($user->id != Auth::id()) {
                    $this->notificationService->createForUser(
                        $user->id,
                        $message,
                        'wholesale_refund',
                        $paymentWholesaleId,
                        $actionUrl
                    );
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("ไม่สามารถส่งการแจ้งเตือนการคืนเงินจากโฮลเซลล์ได้: " . $e->getMessage());
        }
    }
}
