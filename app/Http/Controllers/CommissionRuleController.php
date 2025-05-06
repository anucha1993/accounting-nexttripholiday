<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommissionRule;
use Illuminate\Validation\Rule;
use App\Models\CommissionSetting;

class CommissionRuleController extends Controller
{
    public function index()
    {
        $commissions = CommissionRule::latest()->get();
        return view('commissions.index', compact('commissions'));
    }

    public function store(Request $req)
    {
        $item = CommissionRule::create($this->rules($req));
        $rowHtml = view('commissions.partials.row', compact('item'))->render();
    
        return response()->json(['html' => $rowHtml]);
    }

 public function update(Request $req, CommissionRule $commission)
{
    $commission->update($this->rules($req));
    $rowHtml = view('commissions.partials.row', ['item'=>$commission])->render();

    return response()->json(['html' => $rowHtml]);
}

private function rules($r)
{
    return $r->validate([
        'name'       => 'required|max:191',
        'type'       => 'required|in:step,percent',
        'min_profit' => 'required|numeric|min:0',
        'max_profit' => 'nullable|numeric|min:0',
        'value'      => 'required|numeric|min:0',
        'unit'       => 'required|in:baht,percent',
        'status'     => 'required|in:active,inactive',
    ]);
}

    public function destroy(CommissionRule $commission)
    {
        $commission->delete();
        return response()->json(['deleted' => true]);
    }
    
}
