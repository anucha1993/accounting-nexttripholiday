<?php

namespace App\Http\Controllers\payments;

use Illuminate\Http\Request;
use App\Models\bank\bankModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use App\Models\bank\bankCompanyModel;
use App\Models\invoices\invoiceModel;
use App\Models\payments\paymentModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\quotations\quotationModel;
use App\Services\NotificationService;

class paymentController extends Controller
{
    //
    public function index(quotationModel $quotationModel, Request $request)
    {
        //dd($quotationModel->quote_number);
        $quotationModel = quotationModel::where('quotation.quote_id', $quotationModel->quote_id)
            ->leftjoin('invoices', 'invoices.invoice_quote_id', 'quotation.quote_id')
            // ->leftjoin('debit_note','debit_note.debit_invoice_id','invoices.invoice_id')
            // ->leftjoin('credit_note','credit_note.credit_invoice_id','invoices.invoice_id')
            ->first();

        $quotation = quotationModel::where('quote_id', $quotationModel->quote_id)->first();

        $payments = paymentModel::where('payment_quote_id', $quotationModel->quote_id)
            // ->leftjoin('bank','bank.bank_id','payments.payment_bank')
            ->where('payment_doc_type', 'quote')
            ->get();

        $paymentModel = paymentModel::where('payment_quote_id', $quotationModel->quote_id)->first();

        return View::make('payments.payment-table', compact('payments', 'quotationModel', 'quotation', 'paymentModel'))->render();
    }

    public function quotation(quotationModel $quotationModel, Request $request)
    {
        $bank = bankModel::where('bank_status', 'active')->get();
        $bankCompany = bankCompanyModel::where('bank_company_status', 'active')->get();
        $totaPayment = 0;
        $paymentType = '';
        if ($quotationModel->payment <= 0) {
            if ($quotationModel->quote_payment_type === 'full') {
                $totaPayment = $quotationModel->quote_grand_total;
                $paymentType = 'full';
            } else {
                $totaPayment = $quotationModel->quote_payment_total ? $quotationModel->quote_payment_total : 0;
                $paymentType = 'deposit';
            }
        } else {
            $totaPayment = $quotationModel->quote_grand_total - $quotationModel->payment;
            $paymentType = 'full';
        }
        return view('payments.quote-modal', compact('quotationModel', 'bank', 'bankCompany', 'totaPayment', 'paymentType'));
    }
    // function Runnumber Payment
    public function generateRunningCodePM()
    {
        $code = paymentModel::latest()->first();
        if (!empty($code)) {
            $codeNumber = $code->payment_number;
        } else {
            $codeNumber = 'PM' . date('y') . date('m') . '-' . '0000';
        }
        $prefix = 'PM';
        $year = date('y');
        $month = date('m');
        $lastFourDigits = substr($codeNumber, -4);
        $incrementedNumber = intval($lastFourDigits) + 1;
        $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
        $runningCode = $prefix . $year . $month . '-' . $newNumber;
        return $runningCode;
    }

    public function payment(Request $request)
    {
        // ตรวจสอบข้อมูลที่รับมาจาก request
        $quote = quotationModel::where('quote_number', $request->payment_doc_number)->first();
        $request->merge([
            'payment_number' => $this->generateRunningCodePM(),
        ]);
        $paymentModel = paymentModel::create($request->all());
        // สร้างพาธที่ถูกต้อง
        $folderPath = 'public/' . $quote->customer_id . '/' . $quote->quote_number;
        $absolutePath = storage_path('app/' . $folderPath);
        // เช็คว่าไดเร็กทอรีมีอยู่แล้วหรือไม่ หากไม่มีให้สร้างขึ้นมา
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0775, true);
        }
        $file = $request->file('payment_file');
        if ($file) {
            $extension = $file->getClientOriginalExtension(); // นามสกุลไฟล์
            $uniqueName = $paymentModel->payment_id . '_' . $paymentModel->payment_doc_number . '_' . date('Ymd') . '.' . $extension;
            $filePath = $quote->customer_id . '/' . $quote->quote_number . '/' . $uniqueName;

            // ย้ายไฟล์ไปยังตำแหน่งที่ต้องการ
            $file->move($absolutePath, $uniqueName);
            // อัปเดตพาธไฟล์ในฐานข้อมูล
            $paymentModel->update(['payment_file_path' => $filePath]);
        }
        // การจัดการการชำระเงิน
        $totalOld = $quote->payment !== null ? $quote->payment : 0;
        $total = $totalOld + $request->payment_total;
        quotationModel::where('quote_number', $request->payment_doc_number)->update(['payment' => $total]);
        // การอัปเดตสถานะของใบเสนอราคา
        if ($total >= $quote->quote_grand_total) {
            quotationModel::where('quote_number', $request->payment_doc_number)->update(['quote_status' => 'success', 'quote_payment_status' => 'success']);
        } else {
            quotationModel::where('quote_number', $request->payment_doc_number)->update(['quote_payment_status' => 'payment']);
        }

        // $payment = paymentModel::where('payment_stauss','')->where()->sum();
        //check Status Payment Quotations
        $paymentStatus = 'wait';

        if ($request->payment_total <= 0) {
            $paymentStatus = 'cancel';
        }
        $request->merge([
            'payment_status' => $paymentStatus,
        ]);
        $paymentModel->update($request->all());
        // quote
        $quotationModel = quotationModel::where('quote_id', $paymentModel->payment_quote_id)->first();
        $deposit = $quotationModel->GetDeposit() - $quotationModel->Refund();
        $quotePayment = 'payment';

        if ($deposit <= 0) {
            $quotePayment = 'wait';
        }

        if ($deposit >= $quotationModel->quote_grand_total) {
            $quoteStatus = 'success';
        } else {
            $quoteStatus = 'wait';
        }

        $quotationModel->update([
            'payment' => $deposit,
            'quote_status' => $quoteStatus,
            'quote_payment_status' => $quotePayment,
        ]);
        // ===== แจ้งเตือน refund =====
        if ($request->payment_type === 'refund') {
            $notificationService = new NotificationService();
            $quoteUrl = route('quote.editNew', $quotationModel->quote_id);
            $saleId = $quotationModel->quote_sale;
            $quoteNumber = $quotationModel->quote_number;
            $refundAmount = $request->payment_total;
            $customerName = $quotationModel->quoteCustomer ? $quotationModel->quoteCustomer->customer_name : '';
            // 1. มีคำขอคืนเงินลูกค้า → แจ้งบัญชี
            $msgAcc = "มีคำขอคืนเงินลูกค้า {$customerName} จำนวนเงิน: " . number_format($refundAmount,2) . " บาท เลขที่ใบเสนอราคา #{$quoteNumber}";
            $notificationService->sendToAccounting($msgAcc, $quoteUrl, $quotationModel->quote_id, 'refund-request');
            // 2. แจ้ง sale (ถ้ามีการแนบสลิป)
            if ($file && $saleId) {
                $msgSale = "บัญชีแนบสลิปคืนเงินให้ลูกค้า {$customerName} จำนวนเงิน: " . number_format($refundAmount,2) . " บาท เลขที่ใบเสนอราคา #{$quoteNumber}";
                $notificationService->sendToSale($saleId, $msgSale, $quoteUrl, $quotationModel->quote_id, 'refund-slip');
            }
            // 3. แจ้ง SA ทั้งสองกรณี
            $msgSA = $file
                ? "บัญชีแนบสลิปคืนเงินให้ลูกค้า {$customerName} จำนวนเงิน: " . number_format($refundAmount,2) . " บาท เลขที่ใบเสนอราคา #{$quoteNumber}"
                : "มีคำขอคืนเงินลูกค้า {$customerName} จำนวนเงิน: " . number_format($refundAmount,2) . " บาท เลขที่ใบเสนอราคา #{$quoteNumber}";
            $notificationService->sendToSuperAdmin($msgSA, $quoteUrl, $quotationModel->quote_id, 'refund');
        }
        // ===== END แจ้งเตือน =====

        return redirect()->back()->with('success', 'Payment processed successfully.');
    }

    public function cancelModal(paymentModel $paymentModel)
    {
        return view('payments.cancel-payment', compact('paymentModel'));
    }

    public function RefreshCancel(paymentModel $paymentModel)
    {
        $paymentModel->update(['payment_status' => 'success']);
        return redirect()->back();
    }

    public function cancel(paymentModel $paymentModel, Request $request)
    {
        $paymentStatus = 'refund';
        if ($request->payment_total <= 0) {
            $paymentStatus = 'cancel';
        }
        $request->merge([
            'payment_status' => $paymentStatus,
        ]);
        $paymentModel->update($request->all());
        // quote
        $quotationModel = quotationModel::where('quote_id', $paymentModel->payment_quote_id)->first();
        $deposit = $quotationModel->GetDeposit() - $quotationModel->Refund();
        $quotePayment = 'payment';

        if ($deposit <= 0) {
            $quotePayment = 'wait';
        }

        if ($deposit >= $quotationModel->quote_grand_total) {
            $quoteStatus = 'success';
        } else {
            $quoteStatus = 'wait';
        }

        $quotationModel->update([
            'payment' => $deposit,
            'quote_status' => $quoteStatus,
            'quote_payment_status' => $quotePayment,
        ]);

        $folderPath = 'public/' . $quotationModel->customer_id . '/' . $quotationModel->quote_number;
        $absolutePath = storage_path('app/' . $folderPath);
        // เช็คว่าไดเร็กทอรีมีอยู่แล้วหรือไม่ หากไม่มีให้สร้างขึ้นมา
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0775, true);
        }
        $file = $request->file('payment_cancel_file_path');
        if ($file) {
            $extension = $file->getClientOriginalExtension(); // นามสกุลไฟล์
            $uniqueName = $paymentModel->payment_id . '_' . $paymentModel->payment_doc_number . '_cancel_' . date('Ymd') . '.' . $extension;
            $filePath = $quotationModel->customer_id . '/' . $quotationModel->quote_number . '/' . $uniqueName;
            $file->move($absolutePath, $uniqueName);
            $paymentModel->update(['payment_cancel_file_path' => $filePath]);
        }

       
        return redirect()->back();
    }

    public function edit(paymentModel $paymentModel)
    {
        $bank = bankModel::where('bank_status', 'active')->get();
        $bankCompany = bankCompanyModel::where('bank_company_status', 'active')->get();
        $quotationModel = quotationModel::where('quote_number', $paymentModel->payment_doc_number)->first();
        return view('payments.quote-modal-edit', compact('quotationModel', 'paymentModel', 'bankCompany', 'bank'));
    }

    public function update(paymentModel $paymentModel, Request $request)
{
    //dd($request->payment_type);
    $totalOld = $request->payment_total_old;
    $totalNew = $request->payment_total;

    // อัปเดตข้อมูลเบื้องต้น (ยกเว้นไฟล์)
    $paymentModel->update($request->except('payment_file'));

    // ดึงใบเสนอราคาที่เกี่ยวข้อง
    $quote = quotationModel::where('quote_number', $paymentModel->payment_doc_number)->first();

    // ปรับยอดชำระเดิมออก และเพิ่มยอดใหม่เข้า
    $quote->update(['payment' => $quote->payment - $totalOld]);
    $quote->update(['payment' => $quote->payment + $totalNew]);

    // อัปโหลดไฟล์ใหม่ ถ้ามี
    $file = $request->file('payment_file');
    if ($file) {
        // ลบไฟล์เดิมถ้ามี
        $absoluteFilePath = storage_path('app/public/' . $paymentModel->payment_file_path);
        if (File::exists($absoluteFilePath)) {
            File::delete($absoluteFilePath);
        }

        // เตรียมโฟลเดอร์จัดเก็บ
        $folderPath = 'public/' . $quote->customer_id . '/' . $quote->quote_number;
        $absolutePath = storage_path('app/' . $folderPath);
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0775, true);
        }

        // ตั้งชื่อไฟล์ใหม่
        $extension = $file->getClientOriginalExtension();
        $uniqueName = $paymentModel->payment_id . '_' . $paymentModel->payment_doc_number . '_' . date('Ymd') . '.' . $extension;
        $filePath = $quote->customer_id . '/' . $quote->quote_number . '/' . $uniqueName;

        // ย้ายไฟล์เข้าโฟลเดอร์
        $file->move($absolutePath, $uniqueName);

        // อัปเดต path ไฟล์ในฐานข้อมูล
        $paymentModel->update(['payment_file_path' => $filePath]);
    }

    // === ตรวจสอบสถานะของ payment ===
    if (!empty($paymentModel->payment_file_path) && $paymentModel->payment_status === 'wait') {
        $paymentStatus = 'success';
    } else {
        $paymentStatus = 'wait';
    }

    // ถ้ายอดชำระ <= 0 ให้ถือว่ายกเลิก
    if ($request->payment_total <= 0) {
        $paymentStatus = 'cancel';
    }

    // อัปเดตสถานะ payment
    $paymentModel->update(['payment_status' => $paymentStatus]);

    // === ตรวจสอบและอัปเดตสถานะใบเสนอราคาอีกครั้ง ===
    $quotationModel = quotationModel::where('quote_id', $paymentModel->payment_quote_id)->first();

    $deposit = $quotationModel->GetDeposit() - $quotationModel->Refund();
//     dd([
//     'deposit' => $deposit,
//     'quote_grand_total' => $quotationModel->quote_grand_total,
//     'deposit_type' => gettype($deposit),
//     'quote_grand_total_type' => gettype($quotationModel->quote_grand_total),
//     'rounded_deposit' => round($deposit, 2),
//     'rounded_grand_total' => round($quotationModel->quote_grand_total, 2),
//     'comparison_result' => round($deposit, 2) >= round($quotationModel->quote_grand_total, 2),
// ]);
    // ใช้ round เปรียบเทียบตัวเลขแบบแม่นยำ
    if (round($deposit, 2) >= round($quotationModel->quote_grand_total, 2)) {
        $quoteStatus = 'success';
    } else {
        $quoteStatus = 'wait';
    }

    $quotePayment = $deposit <= 0 ? 'wait' : 'success';

    // อัปเดตสถานะสุดท้ายของใบเสนอราคา
    $quotationModel->update([
        'payment' => $deposit,
        'quote_status' => $quoteStatus,
        'quote_payment_status' => $quotePayment,
    ]);

    // แจ้งเตือน refund กรณีแนบไฟล์ที่หลัง (update)
    if ($request->payment_type === 'refund' && $file) {
        $notificationService = new NotificationService();
        $quoteUrl = route('quote.editNew', $quotationModel->quote_id);
        $saleId = $quotationModel->quote_sale;
        $quoteNumber = $quotationModel->quote_number;
        $refundAmount = $request->payment_total;
        $customerName = $quotationModel->quoteCustomer ? $quotationModel->quoteCustomer->customer_name : '';
        // 1. แจ้ง sale (แนบสลิป)
        if ($saleId) {
            $msgSale = "บัญชีแนบสลิปคืนเงินให้ลูกค้า {$customerName} จำนวนเงิน: " . number_format($refundAmount,2) . " บาท เลขที่ใบเสนอราคา #{$quoteNumber}";
            $notificationService->sendToSale($saleId, $msgSale, $quoteUrl, $quotationModel->quote_id, 'refund-slip');
        }
        // 2. แจ้ง SA
        $msgSA = "บัญชีแนบสลิปคืนเงินให้ลูกค้า {$customerName} จำนวนเงิน: " . number_format($refundAmount,2) . " บาท เลขที่ใบเสนอราคา #{$quoteNumber}";
        $notificationService->sendToSuperAdmin($msgSA, $quoteUrl, $quotationModel->quote_id, 'refund');
    }

    return redirect()->back();
}



    public function delete(paymentModel $paymentModel)
    {
        // กำหนดตำแหน่งไฟล์ตามเส้นทางจริงในระบบ
        $filePath1 = public_path('storage/' . $paymentModel->payment_cancel_file_path);
        $filePath2 = public_path('storage/' . $paymentModel->payment_file_path);

        // ตรวจสอบและลบไฟล์จากตำแหน่งที่กำหนด
        if (File::exists($filePath1)) {
            File::delete($filePath1);
        }

        if (File::exists($filePath2)) {
            File::delete($filePath2);
        }

        // ลบข้อมูลใน paymentModel
        $paymentModel->delete();

        // ค้นหา quotationModel ที่ตรงกับ quote_id

        $quotationModel = quotationModel::where('quote_id', $paymentModel->payment_quote_id)->first();

        $quotationModel->update([
            'payment' => 0,
            'quote_status' => 'wait',
            'quote_payment_status' => 'wait',
        ]);

        // ตรวจสอบว่ามี quotationModel หรือไม่
        if ($quotationModel) {
            // ดึงค่า deposit
            $deposit = $quotationModel->GetDeposit();

            // ตั้งค่าสถานะเริ่มต้น
            $quotePaymentStatus = $deposit <= 0 ? 'wait' : 'payment';
            $quoteStatus = $deposit >= $quotationModel->quote_grand_total ? 'success' : 'wait';

            // อัปเดตข้อมูล quotationModel
            $quotationModel->update([
                'payment' => $deposit,
                'quote_status' => $quoteStatus,
                'quote_payment_status' => $quotePaymentStatus,
            ]);
        } else {
            // หากไม่มี quotationModel ให้สร้างใหม่พร้อมค่าเริ่มต้น
            quotationModel::where('quote_id', $paymentModel->payment_quote_id)->update([
                'payment' => 0,
                'quote_status' => 'wait',
                'quote_payment_status' => 'wait',
            ]);
        }

        return redirect()->back();
    }
}
