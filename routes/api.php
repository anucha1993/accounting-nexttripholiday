<?php

use App\Http\Controllers\api\apiInvoiceController;
use App\Http\Controllers\customers\customerController;
use App\Http\Controllers\invoices\invoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('customer/store', [customerController::class, 'store'])->name('apicustomer.store');

Route::get('invoice',[apiInvoiceController::class,'invoice'])->name('api.invoice');

