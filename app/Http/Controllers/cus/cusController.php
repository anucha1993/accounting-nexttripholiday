<?php
namespace App\Http\Controllers\cus;

use Illuminate\Http\Request;
use App\Exports\CustomerExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\customers\customerModel;
use App\Models\wholesale\wholesaleModel;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\signTures\imageSigntureModel;

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
        $customers = $query->orderBy('customer_number', 'desc')->paginate(50);
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
    public function create()
    {
        $customers = customerModel::latest()->get();
        $imageSingture = imageSigntureModel::get();
        $campaignSource = DB::table('campaign_source')->get();
        $wholesales = wholesaleModel::latest()->get();
        return view('cus.create', compact('customers', 'imageSingture', 'campaignSource', 'wholesales'));
    }

    public function generateRunningCodeCUS()
    {
        $customer = customerModel::select('customer_number')->orderBy('customer_number', 'desc')->first();
        if (!empty($customer)) {
            $CusNumber = $customer->customer_number;
        } else {
            $CusNumber = 'CUS-' . date('y') . date('m') . '0000';
        }
        $prefix = 'CUS-';
        $year = date('y');
        $month = date('m');
        $lastFourDigits = substr($CusNumber, -4);
        $incrementedNumber = intval($lastFourDigits) + 1;
        $newNumber = str_pad($incrementedNumber, 4, '0', STR_PAD_LEFT);
        $runningCode = $prefix . $year . $month . $newNumber;
        return $runningCode;
    }

    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'customer_name' => 'required|string|max:255',
        //     'customer_email' => 'nullable|email|max:255',
        //     'customer_tel' => 'nullable|string|max:50',
        //     'customer_address' => 'nullable|string|max:500',
        // ]);
        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        // $validated = $validator->validated();
        // $existingCustomer = customerModel::where(function($query) use ($validated) {
        //     $query->where('customer_name', $validated['customer_name'])
        //         ->orWhere('customer_email', $validated['customer_email'])
        //         ->orWhere('customer_tel', $validated['customer_tel']);
        // })->first();

        // if ($existingCustomer) {
        //     $existingCustomer->update($validated);
        // } else {
        //     $runningCodeCus = $this->generateRunningCodeCUS();
        //      $request->merge(['customer_number' => $runningCodeCus]);
        //     customerModel::create($validated);
        // }

        $runningCodeCus = $this->generateRunningCodeCUS();
        $request->merge(['customer_number' => $runningCodeCus]);
        $customerModel = customerModel::create($request->all());

        return redirect()->route('cus.index')->with('success', 'เพิ่มข้อมูลลูกค้าสำเร็จ');
    }

    public function storeQuote(Request $request)
    {
        $runningCodeCus = $this->generateRunningCodeCUS();
        $request->merge(['customer_number' => $runningCodeCus]);
        $customerModel = customerModel::create($request->all());

        // return redirect()->back()->with('success', 'เพิ่มข้อมูลลูกค้าสำเร็จ');
    }

    public function edit($id)
    {
        $imageSingture = imageSigntureModel::get();
        $campaignSource = DB::table('campaign_source')->get();
        $wholesales = wholesaleModel::latest()->get();

        $customer = customerModel::findOrFail($id);
        return view('cus.edit', compact('customer', 'imageSingture', 'campaignSource', 'wholesales'));
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
        $validated = $validator->validated();

        // ถ้าชื่อ อีเมล เบอร์โทร ไม่ตรงกับข้อมูลเก่า ทั้ง 3 อย่าง ให้สร้างลูกค้าใหม่
        if ($validated['customer_name'] !== $customer->customer_name && $validated['customer_email'] !== $customer->customer_email && $validated['customer_tel'] !== $customer->customer_tel) {
            $newCustomer = customerModel::create($validated);
            // อาจจะต้องนำ customer_id ใหม่ไปใช้ต่อใน business logic อื่น ๆ
            // ตัวอย่าง: return redirect()->route('cus.index')->with('success', 'สร้างลูกค้าใหม่สำเร็จ');
            return redirect()->route('cus.index')->with('success', 'สร้างลูกค้าใหม่สำเร็จ');
        } else {
            $customer->update($validated);
            return redirect()->route('cus.index')->with('success', 'แก้ไขข้อมูลลูกค้าสำเร็จ');
        }
    }
    public function destroy($id)
    {
        $customer = customerModel::findOrFail($id);
        $customer->delete();
        return redirect()->route('cus.index')->with('success', 'ลบข้อมูลลูกค้าสำเร็จ');
    }
}
