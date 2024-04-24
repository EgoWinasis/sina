<?php

use App\Http\Controllers\PasswordController;
use Illuminate\Support\Facades\Auth;
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


Auth::routes();


// home
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('password', PasswordController::class)->middleware('auth');
// profile
Route::resource('profile', \App\Http\Controllers\ProfileController::class)->middleware('auth');
// route master data devices
Route::group(['middleware' => 'profile.check'], function () {
    // device
    Route::resource('device', \App\Http\Controllers\DeviceController::class)->middleware('auth');
    // sosmed
    Route::resource('sosmed', \App\Http\Controllers\SosmedController::class)->middleware('auth');
    // slik route
    Route::resource('slik', \App\Http\Controllers\SlikController::class)->middleware('auth');
    // report route
    Route::resource('report', \App\Http\Controllers\ReportSlikController::class)->middleware('auth');
    // ao baru
    Route::resource('aobaru', \App\Http\Controllers\AoBaruController::class)->middleware('auth');
    // pencapaian
    Route::resource('pencapaian', \App\Http\Controllers\PencapaianController::class)->middleware('auth');
    // pengguna
    Route::resource('pengguna', \App\Http\Controllers\PenggunaController::class)->middleware('auth');
    // perangkat route
    Route::resource('perangkat', \App\Http\Controllers\PerangkatController::class)->middleware('auth');
    // ip address route
    Route::resource('ipaddress', \App\Http\Controllers\IpAddressController::class)->middleware('auth');
    // banner
    Route::resource('banner', \App\Http\Controllers\BannerController::class)->middleware('auth');
    // hari besar
    Route::resource('hari-besar', \App\Http\Controllers\HariBesarController::class)->middleware('auth');
    // memo route
    Route::resource('memo', \App\Http\Controllers\MemoController::class)->middleware('auth');
    // uploud image
    Route::post('/banner/{id}/upload-image', [App\Http\Controllers\AjaxController::class, 'uploadImage']);

    Route::get('memo/{id}/cetak', [\App\Http\Controllers\MemoController::class, 'cetak'])->name('memo.cetak');
});
