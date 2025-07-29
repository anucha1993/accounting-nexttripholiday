<?php

namespace App\Http\Controllers\reports;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\inputTax\inputTaxModel;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;

class inputTaxReportController extends Controller
{
    //

    public function index(Request $request)
    {
        $searchDateStart = $request->input('date_start');
        $searchDateEnd = $request->input('date_end');
        $status = $request->input('status');
        $sellerId = $request->input('seller_id');
        $documentNumber = $request->input('document_number');
        $referenceNumber = $request->input('reference_number');
        $sellerName = $request->input('seller_name');
        $wholesale = wholesaleModel::where('status', 'on')->get();

        $inputTaxs = inputTaxModel::whereNotNull('input_tax_number_tax')
        ->when($searchDateStart && $searchDateEnd, function ($query) use ($searchDateStart, $searchDateEnd) {
            return $query->whereBetween('input_tax_date_tax', [$searchDateStart, $searchDateEnd]);
        })
        ->when($status ,function ($query) use ($status) {
            if ($status === 'not_null') {
                 return $query->whereNotNull('input_tax_file');
            } else {
                return $query->whereNull('input_tax_file');
            }
        })
        ->when($sellerId, function ($query) use ($sellerId) {
            return $query->whereHas('quote', function ($q) use ($sellerId) {
                $q->where('quote_sale', $sellerId);
            });
        })
        ->when($documentNumber, function ($query) use ($documentNumber) {
            return $query->where('input_tax_number_tax', 'like', '%' . $documentNumber . '%');
        })
        ->when($referenceNumber, function ($query) use ($referenceNumber) {
            return $query->whereHas('invoice.taxinvoice', function ($q) use ($referenceNumber) {
                $q->where('taxinvoice_number', 'like', '%' . $referenceNumber . '%');
            });
        })
        ->when($sellerName, function ($query) use ($sellerName) {
            // ดึง wholesale_id ที่ตรงกับชื่อผู้จำหน่ายจากฐานข้อมูล mysql2
            $wholesaleIds = DB::connection('mysql2')->table('tb_wholesale')
                ->where('wholesale_name_th', 'like', '%' . $sellerName . '%')
                ->pluck('id');
            
            // หา quote_id ที่มี quote_wholesale ตรงกับ wholesale_id ที่หาได้
            return $query->whereHas('quote', function ($q) use ($wholesaleIds) {
                $q->whereIn('quote_wholesale', $wholesaleIds);
            });
        })
        ->when($request->input('wholesale_id'), function ($query) use ($request) {
            return $query->whereHas('quote', function ($q) use ($request) {
                $q->where('quote_wholesale', $request->input('wholesale_id'));
            });
        })
        ->get();

        $grandTotalSum = $inputTaxs->sum(function ($inputTax) {
            return $inputTax->input_tax_service_total;
        });
        $vat = $inputTaxs->sum(function ($inputTax) {
            return $inputTax->input_tax_vat;
        });

        $sellers = saleModel::select('name', 'id')
            ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
            ->get();

        return view('reports.input-tax-form',compact('inputTaxs','grandTotalSum','vat','sellers','wholesale'));
    }


}
