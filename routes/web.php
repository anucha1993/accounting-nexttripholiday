<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
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


Route::resources([
    'roles' => RoleController::class,
    'users' => UserController::class,
    'products' => ProductController::class,
]);

