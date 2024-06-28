<?php

namespace App\Http\Controllers\wholeSales;

use App\Http\Controllers\Controller;
use App\Models\wholesale\wholesaleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class wholeSaleController extends Controller
{
    //

    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:create-wholesale|edit-wholesale|delete-wholesale|view-wholesale', ['only' => ['index','show']]);
       $this->middleware('permission:create-wholesale', ['only' => ['create','store']]);
       $this->middleware('permission:edit-wholesale', ['only' => ['edit','update']]);
       $this->middleware('permission:delete-wholesale', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        $keyword = $request->input('search');
        $wholesales = wholesaleModel::select('*');
        if (!empty($keyword)) {
            $wholesales = $wholesales->where(function ($query) use ($keyword) {
                $query->where('wholesale_name_th', 'LIKE', "%$keyword%")
                    ->orWhere('wholesale_name_en', 'LIKE', "%$keyword%")
                    ->orWhere('contact_person', 'LIKE', "%$keyword%")
                    ->orWhere('tel', 'LIKE', "%$keyword%");
            });
        }
        $wholesales =  $wholesales->paginate(10);
      
        return view('wholesales.index',compact('wholesales'));
    }

    public function edit(wholesaleModel $wholesaleModel)
    {
        return view('wholesales.edit-wholesale',compact('wholesaleModel'));
    }
}
