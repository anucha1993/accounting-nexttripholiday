<?php

namespace App\Http\Controllers\uploadfiles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class uploadfileQuoteController extends Controller
{
    public function uploadFile(Request $request, $quoteNumber, $customerId)
    {
        // ตรวจสอบว่ามีไฟล์ใน request หรือไม่
        //dd($customerId);
        if ($request->hasFile('file')) {
            // สร้าง path โฟลเดอร์ที่ต้องการเก็บไฟล์
            $folderPath = 'public/' . $customerId . '/inputtax/' . $quoteNumber;
            $absolutePath = storage_path('app/' . $folderPath);
            
            // สร้างไดเร็กทอรีถ้ายังไม่มี
            if (!File::exists($absolutePath)) {
                File::makeDirectory($absolutePath, 0775, true);
            }
    
            $file = $request->file('file');
    
            if ($file) {
                $extension = $file->getClientOriginalExtension(); // นามสกุลไฟล์
                
                // สร้างชื่อไฟล์ใหม่โดยเพิ่ม UUID และวันที่เข้าไปเพื่อไม่ให้ซ้ำกัน
                $uniqueName = $quoteNumber . '_inputtax_' . uniqid() . '.' . $extension; 
    
                // กำหนด path สำหรับบันทึกไฟล์
                $filePath = $customerId . '/inputtax/' . $quoteNumber . '/' . $uniqueName;
    
                // ย้ายไฟล์ไปยังโฟลเดอร์ที่ระบุ
                $file->move($absolutePath, $uniqueName);
    
                // คืนค่า path สำหรับบันทึกในฐานข้อมูล
                return $filePath;
            }
        }
    
        return null; // คืนค่า null หากไม่มีไฟล์อัปโหลด
    }
    
}
