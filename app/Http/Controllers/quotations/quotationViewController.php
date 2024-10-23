<?php

namespace App\Http\Controllers\quotations;

use Illuminate\Http\Request;
use App\Models\sales\saleModel;
use App\Models\mumday\numDayModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\booking\bookingModel;
use App\Models\products\productModel;
use Illuminate\Support\Facades\Crypt;
use App\Models\customers\customerModel;
use Illuminate\Database\Eloquent\Model;
use App\Models\wholesale\wholesaleModel;
use App\Models\quotations\quotationModel;
use App\Models\quotations\quoteProductModel;
use Illuminate\Contracts\Encryption\DecryptException;

class quotationViewController extends Controller
{
    //
    public function index($encryptedId)
    {
        try {
            // ถอดรหัส encryptedId เพื่อให้ได้ ID จริง
            $id = Crypt::decryptString($encryptedId);
    
            // ค้นหาข้อมูลใบเสนอราคาจาก ID
            $quotationModel = QuotationModel::findOrFail($id);
    
            // ดึงข้อมูลอื่น ๆ ที่เกี่ยวข้อง
            $bookingModel = bookingModel::where('code', $quotationModel->quote_booking)->first();
            $country = DB::connection('mysql2')->table('tb_country')->where('status', 'on')->get();
            $airline = DB::connection('mysql2')->table('tb_travel_type')->where('status', 'on')->get();
            $numDays = numDayModel::orderBy('num_day_total')->get();
            $wholesale = wholesaleModel::where('status', 'on')->get();
            $products = productModel::where('product_type', 'income')->get();
            $productDiscount = productModel::where('product_type', 'discount')->get();
            $quoteProducts = quoteProductModel::where('quote_id', $quotationModel->quote_id)->where('expense_type', 'income')->get();
            $quoteProductsDiscount = quoteProductModel::where('quote_id', $quotationModel->quote_id)->where('expense_type', 'discount')->get();
    
            $customer = customerModel::where('customer_id', $quotationModel->customer_id)->first();
            $sale = saleModel::select('name', 'id', 'email')->where('id', $quotationModel->quote_sale)->first();
            $airline = DB::connection('mysql2')->table('tb_travel_type')->where('id', $quotationModel->quote_airline)->first();
            $productLists = quoteProductModel::where('quote_id', $quotationModel->quote_id)->get();
    
            // ดึง HTML จาก Blade Template
            return view('quotationView.index', compact('quotationModel', 'customer', 'sale', 'airline', 'productLists'));
    
        } catch (DecryptException $e) {
            // หากถอดรหัสไม่ได้ ให้แสดงหน้า 404
            abort(404, 'Invalid Quotation ID');
        }
    }
}
