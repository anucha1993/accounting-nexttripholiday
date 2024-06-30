<?php

namespace App\Http\Controllers\booking;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    //
    public function index()
    {
        $booking = DB::connection('mysql2')->table('tb_booking_form')->paginate(10);
        return view('bookings.index',compact('booking'));
    }
}
