<?php

use App\Http\Controllers\airline\airlineController;
use App\Http\Controllers\booking\BookingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\selects\periodSelect;
use App\Http\Controllers\wholeSales\wholeSaleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

//wholesale
Route::get('/wholesale',[wholeSaleController::class,'index'])->name('wholesale.index');
Route::get('/wholesale/edit/{wholesaleModel}',[wholeSaleController::class,'edit'])->name('wholesale.edit');
Route::put('/wholesale/update/{wholesaleModel}',[wholeSaleController::class,'update'])->name('wholesale.update');
Route::get('/wholesale/create',[wholeSaleController::class,'create'])->name('wholesale.create');
Route::post('/wholesale/store',[wholeSaleController::class,'store'])->name('wholesale.store');
Route::get('/wholesale/delete/{wholesaleModel}',[wholeSaleController::class,'destroy'])->name('wholesale.destroy');


//airline
Route::get('/airline',[airlineController::class,'index'])->name('airline.index');
Route::get('/airline/edit/{airlineModel}',[airlineController::class,'edit'])->name('airline.edit');
Route::put('/airline/edit/{airlineModel}',[airlineController::class,'update'])->name('airline.update');
Route::post('/airline/store',[airlineController::class,'store'])->name('airline.store');
Route::get('/airline/create',[airlineController::class,'create'])->name('airline.create');
Route::get('/airline/delete/{airlineModel}',[airlineController::class,'destroy'])->name('airline.destroy');

//Booking
Route::get('/booking',[BookingController::class,'index'])->name('booking.index');
Route::get('/booking/create',[BookingController::class,'create'])->name('booking.create');
Route::post('/booking/store',[BookingController::class,'store'])->name('booking.store');
Route::get('/booking/convert',[BookingController::class,'convert'])->name('booking.convert');
Route::get('/booking/edit/{bookingModel}',[BookingController::class,'edit'])->name('booking.edit');
Route::put('/booking/update/{bookingModel}',[BookingController::class,'update'])->name('booking.update');
Route::get('/booking/delete/{bookingModel}',[BookingController::class,'destroy'])->name('booking.delete');


//selects
Route::get('/selects/period',[periodSelect::class,'index'])->name('select.period');

Route::resources([
    'roles' => RoleController::class,
    'users' => UserController::class,
    'products' => ProductController::class,
]);

