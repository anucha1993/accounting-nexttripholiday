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
        $tours = DB::connection('mysql2')->table('tb_tour')->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%")->where('status','on')->get();
        return response()->json($tours);
     }
}
