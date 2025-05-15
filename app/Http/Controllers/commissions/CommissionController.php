<?php

namespace App\Http\Controllers\commissions;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use App\Http\Controllers\Controller;
use App\Models\commissions\commissionListModel;
use App\Models\commissions\commissionGroupModel;

class CommissionController extends Controller
{
  public function index()
{
    $commissions = commissionGroupModel::with('commissionLists')->get();
    $sales = saleModel::all();

    // เก็บ mapping: sale_id => group_id
    $usedSalesMap = [];

    foreach ($commissions as $group) {
        foreach ($group->sale_ids ?? [] as $sale_id) {
            $usedSalesMap[$sale_id] = $group->id;
        }
    }

    return view('commissions.index', compact('commissions', 'sales', 'usedSalesMap'));
}

 public function store(Request $request)
{
    $group = commissionGroupModel::create([
        'name' => $request->name,
        'sale_ids' => $request->sale_ids, // ✅ array ถูกต้อง
        'type' => $request->type,
    ]);

    foreach ($request->commission_lists ?? [] as $item) {
        commissionListModel::create([
            'commission_group_id' => $group->id,
            'min_amount' => $item['min_amount'],
            'max_amount' => $item['max_amount'],
            'commission_calculate' => $item['commission_calculate'],
        ]);
    }

    return redirect()->route('commissions.index');
}

    public function edit($id)
    {
        $group = commissionGroupModel::with('commissionLists')->findOrFail($id);
        return response()->json($group);
    }

    public function update(Request $request)
{
    $group = commissionGroupModel::findOrFail($request->commission_id);
    $group->update([
        'name' => $request->name,
        'sale_ids' => $request->sale_ids,
        'type' => $request->type,
    ]);

    commissionListModel::where('commission_group_id', $group->id)->delete();

    foreach ($request->commission_lists ?? [] as $item) {
        commissionListModel::create([
            'commission_group_id' => $group->id,
            'min_amount' => $item['min_amount'],
            'max_amount' => $item['max_amount'],
            'commission_calculate' => $item['commission_calculate'],
        ]);
    }

    return redirect()->route('commissions.index');
}
public function destroy($id)
{
    commissionGroupModel::findOrFail($id)->delete();
    commissionListModel::where('commission_group_id', $id)->delete();
    return redirect()->route('commissions.index')->with('success', 'ลบเรียบร้อยแล้ว');
}


}
