<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Bandara\DataRekonController as  BandaraDatarekon;
use App\Http\Controllers\Maskapai\DataRekonController as  MaskapaiDatarekon;

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

Route::get('/bandara/datarekon/{id}/cek_rekon_admin', [BandaraDataRekon::class, 'cek_rekon_admin'])->name('bandara.datarekon.cek_rekon_admin');

Route::post('/bandara/datarekon/{id}/store', [BandaraDataRekon::class, 'store'])->name('bandara.datarekon.store');
Route::put('/bandara/datarekon/{id}/update', [BandaraDataRekon::class, 'update'])->name('bandara.datarekon.update');

Route::put('/maskapai/datarekon/{id}/update', [MaskapaiDataRekon::class, 'update'])->name('maskapai.datarekon.update');

Route::middleware(['auth', 'ceklevel:bandara'])->group(function () {
});
