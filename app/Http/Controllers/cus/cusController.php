<?php
namespace App\Http\Controllers\cus;

use App\Exports\CustomerExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\customers\customerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class cusController extends Controller
{

        public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $query = customerModel::query();
        if ($request->filled('name')) {
            $query->where('customer_name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('email')) {
            $query->where('customer_email', 'like', '%' . $request->email . '%');
        }
        if ($request->filled('phone')) {
            $query->where('customer_tel', 'like', '%' . $request->phone . '%');
        }
        $customers = $query->orderBy('customer_id', 'desc')->paginate(50);
        if ($request->has('export')) {
            $filters = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ];
            return Excel::download(new CustomerExport($filters), 'cus_export.xlsx');
        }
        return view('cus.index', compact('customers'));
    }
    public function create() { return view('cus.create'); }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_tel' => 'nullable|string|max:50',
            'customer_address' => 'nullable|string|max:500',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        customerModel::create($validator->validated());
        return redirect()->route('cus.index')->with('success', 'เพิ่มข้อมูลลูกค้าสำเร็จ');
    }
    public function edit($id)
    {
        $customer = customerModel::findOrFail($id);
        return view('cus.edit', compact('customer'));
    }
    public function update(Request $request, $id)
    {
        $customer = customerModel::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_tel' => 'nullable|string|max:50',
            'customer_address' => 'nullable|string|max:500',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $customer->update($validator->validated());
        return redirect()->route('cus.index')->with('success', 'แก้ไขข้อมูลลูกค้าสำเร็จ');
    }
    public function destroy($id)
    {
        $customer = customerModel::findOrFail($id);
        $customer->delete();
        return redirect()->route('cus.index')->with('success', 'ลบข้อมูลลูกค้าสำเร็จ');
    }
}
