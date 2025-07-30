<?php

namespace App\Http\Controllers\products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\products\productModel;
use Spatie\Permission\Models\Permission;

class productController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');

    }
    
    public function index()
    {
        $roles = DB::table('roles')->get();
        $canEdit = auth()->user()->can('edit-product');
        $canDelete = auth()->user()->can('delete-product');
        return view('products.index',compact('roles','canEdit','canDelete'));
    }

    public function products()
    {
      
       $products = productModel::all();
       $roles = DB::table('roles')->get();
       return response()->json(['products' => $products, 'roles' => $roles ]);
    }

    

    public function store(Request $request)
    {
        $product = productModel::create([
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_roles' => implode(',', $request->product_roles),  // แปลง array เป็น string ก่อนบันทึก
            'product_pax' => $request->product_pax,
            'product_type' => $request->product_type
        ]);
        if($product) {
            return response()->json($product);
        }else{
            return response()->json(['errors' => 'Create Product Error!'], 422);
        }
    }

    public function edit($id)
    {
        $product = productModel::findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = productModel::findOrFail($id);
        $product->update([
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_roles' => implode(',', $request->product_roles),  // แปลง array เป็น string ก่อนบันทึก
            'product_pax' => $request->product_pax,
            'product_type' => $request->product_type
        ]);
        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = productModel::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }


}
