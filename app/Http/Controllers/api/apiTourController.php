<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\customers\customerModel;

class apiTourController extends Controller
{
    //

   public function index(Request $request)
{
    $search = $request->input('search');
    $today = Carbon::now()->toDateString();

    $tours = DB::connection('mysql2')
        ->table('tb_tour')
        ->leftJoin('tb_tour_period', 'tb_tour.id', '=', 'tb_tour_period.tour_id')
        ->where(function($query) use ($search) {
            $query->where('tb_tour.name', 'like', "%{$search}%")
                ->orWhere('tb_tour.code', 'like', "%{$search}%")
                ->orWhere('tb_tour.code1', 'like', "%{$search}%");
        })
        ->where('tb_tour.status', 'on')
        ->where('tb_tour_period.start_date', '>', $today)
        ->select('tb_tour.*', 'tb_tour_period.start_date', 'tb_tour_period.end_date', 'tb_tour_period.id as period_id')
         ->select('tb_tour.*')
    ->distinct()
    ->get();

    return response()->json($tours);
}
     public function period(Request $request)
     {
        $search = $request->input('search');
        $period = DB::connection('mysql2')->table('tb_tour_period')->where('tour_id',$search)->get();
        return response()->json($period);
     }
    

     public function wholesale(Request $request)
     {
         $search = $request->input('search');
         // ดึงข้อมูลจาก DB ที่เชื่อมต่อ mysql2
         $wholesale = DB::connection('mysql2')->table('tb_wholesale')->where('id', $search)->first();
         return response()->json($wholesale);
     }

     public function country(Request $request)
     {
         // ดึงค่าจาก input 'search'
         $search = $request->input('search');
         
         // ดึงข้อมูลทัวร์จากฐานข้อมูล
         $tour = DB::connection('mysql2')->table('tb_tour')->where('code', $search)->first();
         
         // แปลงค่า $tour->country_id ที่เป็น string ["216"] ให้เป็น 216
        
         $countryId = trim($tour->country_id, "[]"); // ลบ [] ออก
         $countryId = explode(',', $countryId);
         $countryId = trim($countryId[0]);
        // $countryId = (int) $countryId; // แปลงเป็นตัวเลข
         $countryIds = json_decode($countryId, true);
         
        // ใช้ where เพื่อค้นหาข้อมูลประเทศ
         $country = DB::connection('mysql2')
                      ->table('tb_country')
                      ->where('id', $countryIds) // ใช้ where เพื่อค้นหาข้อมูล
                      ->first();
     
         return response()->json($country);
     }

     public function customer(Request $request)
     {
         $search = $request->input('search');
         // ดึงข้อมูลจาก DB ที่เชื่อมต่อ mysql2
         $customer = customerModel::where('customer_name','like', "%{$search}%")
         ->orWhere('customer_email','like', "%{$search}%")
         ->orWhere('customer_texid','like', "%{$search}%")
         ->orWhere('customer_tel','like', "%{$search}%")
         ->get();
         return response()->json($customer);
     }

    

     public function invoice(Request $request) 
     {
        
     }
     

     
     
     
     
     
}
