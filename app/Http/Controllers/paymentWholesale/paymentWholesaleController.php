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

class paymentWholesaleController extends Controller
{
    //

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
    // public function RefundRenew(paymentWholesaleModel $paymentWholesaleModel, Request $request)
    // {

    // }

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
        // $file = $request->file('file');
        // $file1 = $request->file('file1');
        // $file2 = $request->file('file2');
        // if($file){
        //     //ลบไฟล์เดิม
        //     if (File::exists($paymentWholesaleModel->payment_wholesale_refund_file_path)) {
        //         File::delete($paymentWholesaleModel->payment_wholesale_refund_file_path); // ลบไฟล์
        //     }
        //     //ลงไฟล์ใหม่

        //      // สร้างชื่อไฟล์ที่ไม่ซ้ำกัน
        //      $extension = $file->getClientOriginalExtension();
        //      $uniqueName = $this->generateRunningCodeWS() .'REFUND'. date('Ymd') . '.' . $extension;

        //      // ย้ายไฟล์ไปยังตำแหน่งที่ต้องการ
        //      $file->move($absolutePath, $uniqueName);

        //      // สร้างพาธสัมพันธ์เพื่อจัดเก็บไฟล์ในฐานข้อมูล
        //      $filePath = 'storage/' . $quote->customer_id . '/wholesalePayment/' . $quote->quote_number . '/' . $uniqueName;

        //      $request->merge([
        //         'payment_wholesale_refund_file_name' => $uniqueName,
        //         'payment_wholesale_refund_file_path' => $filePath,
        //         'created_by' => Auth::user()->name
        //     ]);
        // }
        // if($file1){
        //     //ลบไฟล์เดิม
        //     if (File::exists($paymentWholesaleModel->payment_wholesale_refund_file_path)) {
        //         File::delete($paymentWholesaleModel->payment_wholesale_refund_file_path); // ลบไฟล์
        //     }
        //     //ลงไฟล์ใหม่

        //      // สร้างชื่อไฟล์ที่ไม่ซ้ำกัน
        //      $extension = $file1->getClientOriginalExtension();
        //      $uniqueName = $this->generateRunningCodeWS() .'REFUND'. date('Ymd') . '.' . $extension;

        //      // ย้ายไฟล์ไปยังตำแหน่งที่ต้องการ
        //      $file1->move($absolutePath, $uniqueName);

        //      // สร้างพาธสัมพันธ์เพื่อจัดเก็บไฟล์ในฐานข้อมูล
        //      $filePath1 = 'storage/' . $quote->customer_id . '/wholesalePayment/' . $quote->quote_number . '/' . $uniqueName;

        //      $request->merge([
        //         'payment_wholesale_refund_file_name1' => $uniqueName,
        //         'payment_wholesale_refund_file_path1' => $filePath1,
        //         'created_by' => Auth::user()->name
        //     ]);
        // }
        // if($file2){
        //     //ลบไฟล์เดิม
        //     if (File::exists($paymentWholesaleModel->payment_wholesale_refund_file_path)) {
        //         File::delete($paymentWholesaleModel->payment_wholesale_refund_file_path); // ลบไฟล์
        //     }
        //     //ลงไฟล์ใหม่

        //      // สร้างชื่อไฟล์ที่ไม่ซ้ำกัน
        //      $extension = $file2->getClientOriginalExtension();
        //      $uniqueName = $this->generateRunningCodeWS() .'REFUND'. date('Ymd') . '.' . $extension;

        //      // ย้ายไฟล์ไปยังตำแหน่งที่ต้องการ
        //      $file2->move($absolutePath, $uniqueName);

        //      // สร้างพาธสัมพันธ์เพื่อจัดเก็บไฟล์ในฐานข้อมูล
        //      $filePath2 = 'storage/' . $quote->customer_id . '/wholesalePayment/' . $quote->quote_number . '/' . $uniqueName;

        //      $request->merge([
        //         'payment_wholesale_refund_file_name2' => $uniqueName,
        //         'payment_wholesale_refund_file_path2' => $filePath2,
        //         'created_by' => Auth::user()->name
        //     ]);
        // }
       
        $paymentWholesaleModel->update($request->all());
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
}
