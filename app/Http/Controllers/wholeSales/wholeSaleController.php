<?php

namespace App\Http\Controllers\wholeSales;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\wholesale\wholesaleModel;

class wholeSaleController extends Controller
{
    //

    public function __construct()
    {
       $this->middleware('auth');

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
        $wholesales = $wholesales->orderBy('id', 'DESC')->paginate(10);

        return view('wholesales.index',compact('wholesales'));
    }

    public function edit(wholesaleModel $wholesaleModel)
    {
        return view('wholesales.edit-wholesale',compact('wholesaleModel'));
    }

    public function create()
    {
        return view('wholesales.create-wholesale');
    }

    public function update(wholesaleModel $wholesaleModel, Request $request)
    {

        
        $wholesaleModel->update($request->all());
        return redirect()->route('wholesale.index')->with('success','Updated Wholesale Successfully!');
    }

    public function store(Request $request) 
    {
        try {
            // บันทึกข้อมูลใหม่ในโมเดล
            WholesaleModel::create($request->all());
            return redirect()->route('wholesale.index')->with('success', 'Created Wholesale Successfully!');
        } catch (Exception $e) {
            // เก็บข้อผิดพลาดใน session
            return redirect()->route('wholesale.index')->with('error', 'Error Creating Wholesale: ' . $e->getMessage());
        }
    }

    public function destroy(wholesaleModel $wholesaleModel)
    {
        
        try {
            $wholesaleModel->delete();
            return redirect()->back()->with('success','Deleted Wholesale Successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error','delete Wholesale error!');
        }
        
        
    }
}
