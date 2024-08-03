<?php

namespace App\Http\Controllers\booking;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\booking\bookingModel;
use App\Models\sales\saleModel;

use function Laravel\Prompts\table;

class BookingController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-booking|edit-booking|delete-booking|view-booking', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-booking', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-booking', ['only' => ['edit', 'update']]);
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

        $sales = saleModel::select('name', 'id')
            ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
            ->get();

        $booking = DB::connection('mysql2')
            ->table('tb_booking_form')
            ->select(
                'tb_booking_form.id',
                'tb_booking_form.code',
                'tb_booking_form.name',
                'tb_booking_form.surname',
                'tb_booking_form.start_date',
                'tb_booking_form.end_date',
                'tb_booking_form.total_qty',
                'tb_booking_form.status',
                'tb_booking_form.created_at',
                'tb_booking_form.email',
                'tb_booking_form.phone',
                'tb_booking_form.sale_id',
                //รายการสินค่า
                //จำนวนผู้ใหญ่พักคู่
                'tb_booking_form.num_twin',
                //ราคาต่อคนผู้ใหญ่พักคู่
                'tb_booking_form.price1',
                //ราคารวมผู้ใหญ่พักคู่
                'tb_booking_form.sum_price1',

                //จำนวนผู้ใหญ่พักเดี่ยว
                'tb_booking_form.num_single',
                //ราคาต่อคนผู้ใหญ่พักเดี่ยว
                'tb_booking_form.price2',
                //ราคาต่อคนผู้ใหญ่พักเดี่ยว
                'tb_booking_form.sum_price2',

                //จำนวนเด็กมีเตียง
                'tb_booking_form.num_child',
                //ราคาต่อคนเด็กมีเตียง
                'tb_booking_form.price3',
                //ราคารวมเด็กมีเตียง
                'tb_booking_form.sum_price3',

                //จำนวนเด็กไม่มีเตียง
                'tb_booking_form.num_childnb',
                //	ราคาต่อคนเด็กไม่มีเตียง	
                'tb_booking_form.price4',
                //ราคารวมเด็กไม่มีเตียง
                'tb_booking_form.sum_price4',
                //ราคารวมทั้งหมด
                'tb_booking_form.total_price',

                //tb_tour
                'tb_tour.code as tour_code',
                'tb_tour.id as tour_id',
                'tb_tour.wholesale_id as wholesale_id',
                'tb_tour.name as tour_name',
                'tb_tour.country_id as tour_country',
                'tb_tour.num_day',
                //sales
                'users.name as sale_name',
                'users.id as sale_id',
                 //wholesale
                 'tb_wholesale.wholesale_name_th as wholesale_name_th',
                 //airline
                 'tb_travel_type.travel_name as airline_name','tb_travel_type.id as travel_type_id',
                 //country
                 'tb_tour.country_id as country_id'
            )
            ->leftJoin('tb_tour', 'tb_tour.id', 'tb_booking_form.tour_id')
            ->leftJoin('users', 'users.id', 'tb_booking_form.sale_id')
            ->leftJoin('tb_wholesale', 'tb_wholesale.id', 'tb_tour.wholesale_id')
            ->leftJoin('tb_travel_type', 'tb_travel_type.id', 'tb_tour.airline_id')
            ->leftJoin('tb_country', 'tb_country.id', 'tb_tour.country_id')
            ->where('tb_booking_form.status', 'Success');

        if (!empty($keyword_name)) {
            $booking = $booking->where(function ($query) use ($keyword_name) {
                $query->where('tb_booking_form.name', 'LIKE', "%$keyword_name%")->orWhere('tb_booking_form.surname', 'LIKE', "%$keyword_name%");
            });
        }

        if (!empty($keyword_sale) && $keyword_sale !== 'all') {
            $booking = $booking->where(function ($query) use ($keyword_sale) {
                $query->where('tb_booking_form.sale_id', $keyword_sale);
            });
        }

        if ($keyword_tour_start && $keyword_tour_end) {
            $booking->where(function ($query) use ($keyword_tour_start, $keyword_tour_end) {
                $query->whereDate('tb_booking_form.start_date', '>=', $keyword_tour_start)->whereDate('tb_booking_form.start_date', '<=', $keyword_tour_end);
            });
        }

        if ($keyword_created_start && $keyword_created_end) {
            $booking->where(function ($query) use ($keyword_created_start, $keyword_created_end) {
                $query->whereDate('tb_booking_form.created_at', '>=', $keyword_created_start)->whereDate('tb_booking_form.created_at', '<=', $keyword_created_end);
            });
        }



        $booking = $booking->orderByDesc('id')->paginate(10);
        return view('bookings.index', compact('booking', 'sales', 'keyword_sale'));
    }

    public function convert(Request $request)
    {
        $checkCustomer = DB::connection('mysql')
            ->table('customer')
            ->where('customer_name', $request->customer_name)
            ->orWhere('customer_email',$request->customer_email)
            ->orWhere('customer_tel',$request->customer_tel)
            ->first();

        $country_name = '';
        $array = json_decode($request->tour_country);
        $country_ids = explode(',', $request->tour_country);
        $countrys =  DB::connection('mysql2')->table('tb_country')->whereIn('id', $array)->get();

        foreach ($countrys as $country) {
            $country_name .= $country->country_name_th . ' ';
        }
       // dd($country_name);
        return view('bookings.convert-booking', compact('checkCustomer', 'request', 'country_name'));
    }
    

    public function edit(bookingModel $bookingModel)
    {
        $sales = saleModel::whereNot('role',1)->get();
        $tours = DB::connection('mysql2')->table('tb_tour')->where('status','on')->get();
        $periods = DB::connection('mysql2')->table('tb_tour_period')->where('tour_id',$bookingModel->tour_id)->get();
        $sale =  DB::connection('mysql2')->table('users')->where('id',$bookingModel->sale_id)->first();

        $tour = DB::connection('mysql2')->table('tb_tour')
        ->select('tb_tour.country_id','tb_tour.name as tour_name', 'tb_wholesale.wholesale_name_th as wholesale_name_th','tb_tour.num_day','tb_travel_type.travel_name as airline_name')
        ->leftJoin('tb_wholesale', 'tb_wholesale.id', 'tb_tour.wholesale_id')
        ->leftJoin('tb_travel_type', 'tb_travel_type.id', 'tb_tour.airline_id')
        ->where('tb_tour.id',$bookingModel->tour_id)->first();
        //dd($tour);
        
        return view('bookings.edit-booking',compact('bookingModel','sales','tours','periods','sale','tour'));
    }

    public function update(bookingModel $bookingModel, Request $request)
    {
        $check = $bookingModel->update($request->all());

        if($check){
            return redirect()->back()->with('success','Updated booking Successfully');
        }else{
            return redirect()->back()->with('error','Update booking Error');
        }
    }

    public function create()
    {
        $sales = saleModel::whereNot('role',1)->get();
        $tours = DB::connection('mysql2')->table('tb_tour')->where('status','on')->get();
        return view('bookings.create-booking',compact('sales','tours'));
    }

    //รันเลขอัตโนมัติในรูปแบบ BYYMM001
    
    public static function generateRunningCode()
    {
        $prefix = 'B';
        $year = date('y'); // ปีสองหลัก เช่น 24
        $month = date('m'); // เดือนสองหลัก เช่น 07

        $latestCode = DB::connection('mysql2')->table('tb_booking_form')
            ->where('code', 'like', $prefix . $year . $month . '%')
            ->orderBy('code', 'desc')
            ->value('code');

        if ($latestCode) {
            $lastNumber = (int)substr($latestCode, 5); // ตัด prefix, ปี และเดือนออก
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $year . $month . $newNumber;
    }


    public function store(Request $request)
    {
        $code = $this->generateRunningCode();
        $request->merge(['code'=> $code]);

        $periods = DB::connection('mysql2')->table('tb_tour_period')->where('id',$request->period_id)->first();
        $request->merge(['start_date'=> $periods->start_date]);
        $request->merge(['end_date'=> $periods->end_date]);

        $request->merge(['total_price'=> $request->sum_price1]);
        $request->merge(['total_qty'=> $request->num_twin]);


        $check = bookingModel::create($request->all());

        if($check){
            return redirect()->route('booking.edit',$check->id)->with('success','Created booking Successfully');
        }else{
            return redirect()->back()->with('error','Create booking Error');
        }
    }

    public function destroy(bookingModel $bookingModel) 
    {
        $check = $bookingModel->delete();

        if($check){
            return redirect()->back()->with('success','Deleted booking Successfully');
        }else{
            return redirect()->back()->with('error','Delete booking Error');
        }

    }
}
