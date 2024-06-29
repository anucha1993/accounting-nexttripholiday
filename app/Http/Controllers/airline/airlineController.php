<?php

namespace App\Http\Controllers\airline;

use App\Http\Controllers\Controller;
use App\Models\airline\airlineModel;
use Illuminate\Http\Request;

class airlineController extends Controller
{
    //

    public function index(Request $request)
    {
        $keyword = $request->input('search');
        $airline = airlineModel::select('*');
        if (!empty($keyword)) {
            $airline = $airline->where(function ($query) use ($keyword) {
                $query->where('code', 'LIKE', "%$keyword%")
                    ->orWhere('code1', 'LIKE', "%$keyword%")
                    ->orWhere('travel_name', 'LIKE', "%$keyword%");
            });
        }
        $airline = $airline->orderBy('id', 'DESC')->paginate(10);

        return view('airline.index',compact('airline'));
    }
}
