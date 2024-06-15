<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangmasukController;
use App\Http\Controllers\BarangkeluarController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/', function () {
//     return view('');
// });

// Route::get('/', function () {
//     return view('dashboard');
// });

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('/kategori', KategoriController::class)->middleware('auth');
Route::resource('/categori', CategoryController::class);
Route::resource('/barang', BarangController::class)->middleware('auth');
Route::resource('/barangmasuk', BarangmasukController::class)->middleware('auth');
Route::resource('/barangkeluar', BarangkeluarController::class)->middleware('auth');

Route::get('/login', [LoginController::class,'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class,'authenticate']);

Route::post('/logout', [LoginController::class,'logout'])->name('logout');

//route resource for products
Route::resource('/products', \App\Http\Controllers\ProductController::class);

Route::post('register', [RegisterController::class,'store']);
Route::get('/register', [RegisterController::class,'create']);
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');