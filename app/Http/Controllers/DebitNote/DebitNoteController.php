<?php

namespace App\Http\Controllers\DebitNote;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DebitNoteController extends Controller
{
    //

    public function index()
    {
        // สมมติว่ามีรายการสินค้าจากฐานข้อมูล
        $products = [
            ['id' => 1, 'name' => 'Product A', 'price' => 1000],
            ['id' => 2, 'name' => 'Product B', 'price' => 2000],
            ['id' => 3, 'name' => 'Product C', 'price' => 3000],
        ];

        return view('debit-note.debit-note', compact('products'));
    }

    public function calculate(Request $request)
    {
        // รับข้อมูลจากฟอร์ม
        $data = $request->all();
        return response()->json($data); // ส่งกลับเป็น JSON สำหรับการทดสอบ
    }

}
