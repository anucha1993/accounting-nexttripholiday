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
use App\Models\QuoteLogModel;
use App\Models\User;

class paymentWholesaleController extends Controller
{
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

        // แจ้งเตือนผู้เกี่ยวข้อง (SuperAdmin เท่านั้น)
        if (function_exists('getUserGroup') && function_exists('routeNotificationModel')) {
            $user = Auth::user();
            $message = 'มีการชำระเงินโฮลเซลล์ใหม่จำนวนเงิน:'.number_format($paymentWholesale->payment_wholesale_total,2).'บาท เลขที่ใบเสนอราคา #' . ($quote->quote_number ?? '-');
            // สร้าง url แบบ manual ให้เป็น /quote/edit/new/{id}
            $url = url('/quote/edit/new/' . $quote->quote_id); // เพิ่ม / ข้างหน้าเสมอ
            $relatedId = $quote->quote_id ?? null;
            $relatedType = 'payment_wholesale';
            $notifyService = app(\App\Services\NotificationService::class);
            // แจ้งเตือน SuperAdmin เท่านั้น
            $notifyService->sendToSuperAdmin($message, $url, $relatedId, $relatedType);
            // // แจ้งเตือน Sale
            if ($quote && $quote->quote_sale) {
                $notifyService->sendToSale($quote->quote_sale, $message, $url, $relatedId, $relatedType);
            }
            // // แจ้งเตือน Accounting
            // $notifyService->sendToAccounting($message, $url, $relatedId, $relatedType);
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
        $hasRefundSlip = false;
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
                $hasRefundSlip = true;
            }
        }
        $request->merge(['payment_wholesale_refund_status' => $status]);
        $paymentWholesaleModel->update($request->all());
        
        // แจ้งเตือนการคืนเงิน (เฉพาะถ้ามี refund type)
        if (!is_null($request->payment_wholesale_refund_type)) {
            $refundType = $request->payment_wholesale_refund_type;
            $refundTypeText = $refundType === 'full' ? 'คืนเงินเต็มจำนวน' : 'คืนเงินบางส่วน';
            $message = 'มีการ' . $refundTypeText . 'สำหรับโฮลเซลล์  เลขที่ใบเสนอราคา #' . ($quote->quote_number ?? '-') . ' จำนวนเงิน: ' . number_format($paymentWholesaleModel->payment_wholesale_refund_total,2) . ' บาท';
            $url = url('/quote/edit/new/' . $quote->quote_id);
            $relatedId = $quote->quote_id ?? null;
            $relatedType = 'payment_wholesale_refund';
            $notifyService = app(\App\Services\NotificationService::class);
            // แจ้งเตือน Accounting
            $notifyService->sendToAccounting($message, $url, $relatedId, $relatedType);
            // แจ้งเตือน Super Admin
            $notifyService->sendToSuperAdmin($message, $url, $relatedId, $relatedType);
        }
        // แจ้งเตือนถ้ามีแนบสลิป refund (แจ้ง sale, super admin)
        if ($hasRefundSlip) {
            $message = 'มีการแนบสลิปคืนเงินโฮลเซลล์ เลขที่ใบเสนอราคา #' . ($quote->quote_number ?? '-') . ' กรุณาตรวจสอบ'. ' จำนวนเงิน: ' . number_format($paymentWholesaleModel->payment_wholesale_refund_total,2) . ' บาท';
            $url = url('/quote/edit/new/' . $quote->quote_id);
            $relatedId = $quote->quote_id ?? null;
            $relatedType = 'payment_wholesale_refund_slip';
            $notifyService = app(\App\Services\NotificationService::class);
            // แจ้ง sale
            if ($quote && $quote->quote_sale) {
                $notifyService->sendToSale($quote->quote_sale, $message, $url, $relatedId, $relatedType);
            }
            // แจ้ง super admin
            $notifyService->sendToSuperAdmin($message, $url, $relatedId, $relatedType);
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
        // ดึง path จริงของไฟล์แนบ
        $relativePath = $paymentWholesaleModel->payment_wholesale_file_path; // เช่น 'storage/xxx/wholesalePayment/yyy/filename.pdf'
        $filePath = null;
        if ($relativePath) {
            // ตัด 'storage/' ออก แล้วต่อกับ storage_path('app/public/')
            $filePath = storage_path('app/public/' . ltrim(str_replace('storage/', '', $relativePath), '/'));
        }
        $fileName = $paymentWholesaleModel->payment_wholesale_file_name;
        $mimeType = $filePath && file_exists($filePath) ? mime_content_type($filePath) : null;

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
                "
                );
            // แนบไฟล์ถ้ามี
            if ($filePath && file_exists($filePath)) {
                $message->attach($filePath, [
                    'as' => $fileName,
                    'mime' => $mimeType,
                ]);
            }
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

  
}
