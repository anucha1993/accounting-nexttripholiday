<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class apiTourController extends Controller
{
    //

    public function index(Request $request)
    {
        $search = $request->input('search');
        $tours = DB::connection('mysql2')
            ->table('tb_tour')
            //->select('tb_tour.wholesale_id', 'tb_tour.code', 'tb_tour.name','tb_wholesale.code as wholesale_code')
            //->leftJoin('tb_wholesale', 'tb_wholesale.id', '=', 'tb_tour.wholesale_id')
            ->where('tb_tour.name', 'like', "%{$search}%")
            ->orWhere('tb_tour.code', 'like', "%{$search}%")
            ->orWhere('tb_tour.code1', 'like', "%{$search}%")
            ->where('tb_tour.status', 'on')
            ->get();
        
        return response()->json($tours);
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
     
     
     
     
     
}
