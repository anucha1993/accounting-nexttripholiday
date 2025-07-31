<?php

namespace App\Http\Controllers\airline;

use App\Http\Controllers\Controller;
use App\Models\airline\airlineModel;
use Illuminate\Http\Request;

class airlineController extends Controller
{
    //

    public function __construct()
    {
       $this->middleware('auth');

    }

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

    public function edit(airlineModel $airlineModel)
    {
        return view('airline.edit-airline',compact('airlineModel'));
    }

    public function create()
    {
        return view('airline.create-airline');
    }

    public function update(airlineModel $airlineModel, Request $request)
    {
        //dd($request);
       $check =  $airlineModel->update($request->all());
       if($check){
        return redirect()->route('airline.index')->with('success','Updated Airline Successfully');
       }else{
        return redirect()->route('airline.index')->with('error','Updated Airline Error');
       }
        
    }

    public function store( Request $request)
    {
        //dd($request);
       $check = airlineModel::create($request->all());
       if($check){
        return redirect()->route('airline.index')->with('success','Created Airline Successfully');
       }else{
        return redirect()->route('airline.index')->with('error','Created Airline Error');
       }
        
    }

    public function destroy(airlineModel $airlineModel)
    {
        //dd($request);
       $check =  $airlineModel->delete();
       if($check){
        return redirect()->route('airline.index')->with('success','Deleted Airline Successfully');
       }else{
        return redirect()->route('airline.index')->with('error','Deleted Airline Error');
       }
        
    }

    
}
