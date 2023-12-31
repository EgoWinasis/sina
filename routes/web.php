<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// //device
// Route::get('/device', [App\Http\Controllers\DeviceController::class, 'index'])->name('device');
// Route::get('/device', [App\Http\Controllers\DeviceController::class, 'index'])->name('create');
// route master data devices
Route::resource('device', \App\Http\Controllers\DeviceController::class)->middleware('auth');
// slik route
Route::resource('slik', \App\Http\Controllers\SlikController::class)->middleware('auth');
// perangkat route
Route::resource('perangkat', \App\Http\Controllers\PerangkatController::class)->middleware('auth');
// memo route
Route::resource('memo', \App\Http\Controllers\MemoController::class)->middleware('auth');
Route::get('memo/{id}/cetak', [\App\Http\Controllers\MemoController::class, 'cetak'])->name('memo.cetak');