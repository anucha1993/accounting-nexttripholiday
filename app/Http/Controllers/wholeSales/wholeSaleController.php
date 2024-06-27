<?php

namespace App\Http\Controllers\wholeSales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class wholeSaleController extends Controller
{
    //

    public function index()
    {
        // ดึงข้อมูลจากฐานข้อมูลที่สอง (mysql2) จากตาราง users
        $users = DB::connection('mysql2')->table('users')->get();

        return response()->json($users);
    }
}
