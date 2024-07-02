<?php

namespace App\Http\Controllers\booking;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use function Laravel\Prompts\table;

class BookingController extends Controller
{
    //

    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:create-booking|edit-booking|delete-booking|view-booking', ['only' => ['index','show']]);
       $this->middleware('permission:create-booking', ['only' => ['create','store']]);
       $this->middleware('permission:edit-booking', ['only' => ['edit','update']]);
       $this->middleware('permission:delete-booking', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        $keyword_name = $request->input('search_name');
        $keyword_tour_start = $request->input('search_tour_date_start');
        $keyword_tour_end = $request->input('search_tour_date_end');

        $keyword_created_start = $request->input('search_tour_date_start_created');
        $keyword_created_end = $request->input('search_tour_date_end_created');
        $keyword_sale = $request->input('search_sale');

      //dd($keyword_tour_end);

        $sales = DB::connection('mysql2')->table('users')->select('name','id')->whereNotIn('name',['admin','Admin Liw','Admin'])->get();

        $booking = DB::connection('mysql2')
        ->table('tb_booking_form')
        ->select('tb_booking_form.code','tb_booking_form.name','tb_booking_form.surname','tb_booking_form.start_date','tb_booking_form.total_qty',
        'tb_booking_form.status','tb_booking_form.created_at','tb_booking_form.email','tb_booking_form.phone',
        //tb_tour
        'tb_tour.code as tour_code','tb_tour.name as tour_name',
        //sales
        'users.name as sale_name','users.id as sale_id')
        ->leftJoin('tb_tour','tb_tour.id','tb_booking_form.tour_id')
        ->leftJoin('users','users.id','tb_booking_form.sale_id')
        ->where('tb_booking_form.status','Success');

        if (!empty($keyword_name)) {
            $booking = $booking->where(function ($query) use ($keyword_name) {
                $query->where('tb_booking_form.name', 'LIKE', "%$keyword_name%")
                      ->orWhere('tb_booking_form.surname', 'LIKE', "%$keyword_name%");

            });
        }

        if (!empty($keyword_sale) && $keyword_sale !== 'all' ) {
            $booking = $booking->where(function ($query) use ($keyword_sale) {
                $query->where('tb_booking_form.sale_id', $keyword_sale);

            });
        }

        if ($keyword_tour_start && $keyword_tour_end) {

            $booking->where(function ($query) use ($keyword_tour_start, $keyword_tour_end) {
                $query->whereDate('tb_booking_form.start_date', '>=', $keyword_tour_start)
                    ->whereDate('tb_booking_form.start_date', '<=', $keyword_tour_end);
            });
        }

        if ($keyword_created_start && $keyword_created_end) {

            $booking->where(function ($query) use ($keyword_created_start, $keyword_created_end) {
                $query->whereDate('tb_booking_form.created_at', '>=', $keyword_created_start)
                    ->whereDate('tb_booking_form.created_at', '<=', $keyword_created_end);
            });
        }


        $booking = $booking->paginate(10);
        return view('bookings.index',compact('booking','sales','keyword_sale'));
    }


     public function convert(Request $request)
     {

        $checkCustomer =  DB::connection('mysql')->table('customer')->where('customer_name',$request->customer_name)->first();
        return view('bookings.convert-booking',compact('checkCustomer','request'));
     }
}
