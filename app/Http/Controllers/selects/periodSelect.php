<?php

namespace App\Http\Controllers\selects;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class periodSelect extends Controller
{
    //


    public function index(Request $request)
    {
        $tour_id = $request->get('tour');
        $periods = DB::connection('mysql2')->table('tb_tour_period')->where('tour_id',$tour_id)->get();
        $options =  '<option>กรุณาเลือกวันที่</option>';
        foreach ($periods as $period)
        {
            $options.='<option value="'.$period->id.'" >'.$period->start_date.'('.$period->end_date.')'.'</option>';
        }

        return $options;
    }
}
