<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Bandara\DataRekonController as  BandaraDatarekon;
use App\Http\Controllers\Maskapai\DataRekonController as  MaskapaiDatarekon;

use App\Http\Controllers\Api\RekonBandaraController as ApiRekonBandara;
use App\Http\Controllers\Api\RekonMaskapaiController as ApiRekonMaskapai;

use App\Http\Controllers\PanggilFungsiController;

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

// ================================================================

Route::get('/bandara/datarekon/{id}/cek_rekon_admin', [BandaraDataRekon::class, 'cek_rekon_admin'])->name('bandara.datarekon.cek_rekon_admin');

Route::post('/bandara/datarekon/{id}/store', [BandaraDataRekon::class, 'store'])->name('bandara.datarekon.store');
Route::put('/bandara/datarekon/{id}/update', [BandaraDataRekon::class, 'update'])->name('bandara.datarekon.update');
Route::get('/bandara/datarekon/{id}/api_bandingkan_bandara', [ApiRekonBandara::class, 'api_bandingkan_bandara'])->name('bandara.api_bandingkan_bandara');
Route::post('/bandara/datarekon/{id}/api_bandingkan_bandara_tambah', [ApiRekonBandara::class, 'api_bandingkan_bandara_tambah'])->name('bandara.api_bandingkan_bandara_tambah');
Route::post('/bandara/datarekon/{id}/api_bandingkan_bandara_hapus', [ApiRekonBandara::class, 'api_bandingkan_bandara_hapus'])->name('bandara.api_bandingkan_bandara_hapus');
Route::post('/bandara/datarekon/{id}/api_bandingkan_bandara_edit', [ApiRekonBandara::class, 'api_bandingkan_bandara_edit'])->name('bandara.api_bandingkan_bandara_edit');

Route::post('/bandara/datarekon/{id}/api_kirim_rekon', [ApiRekonBandara::class, 'api_kirim_rekon'])->name('bandara.api_kirim_rekon');
Route::post('/bandara/datarekon/{id}/api_persetujuan_pusat', [ApiRekonBandara::class, 'api_persetujuan_pusat'])->name('bandara.api_persetujuan_pusat');
Route::post('/bandara/datarekon/{id}/api_persetujuan_daerah', [ApiRekonBandara::class, 'api_persetujuan_daerah'])->name('bandara.api_persetujuan_daerah');

Route::get('/bandara/datarekon/{id}/api_error_maskapai', [ApiRekonBandara::class, 'api_error_maskapai'])->name('bandara.api_error_maskapai');
Route::get('/bandara/datarekon/{id}/api_riwayat_rekon', [ApiRekonBandara::class, 'api_riwayat_rekon'])->name('bandara.api_riwayat_rekon');

// ================================================================

Route::put('/maskapai/datarekon/{id}/update', [MaskapaiDataRekon::class, 'update'])->name('maskapai.datarekon.update');
Route::get('/maskapai/datarekon/{id}/api_bandingkan_maskapai', [ApiRekonMaskapai::class, 'api_bandingkan_maskapai'])->name('maskapai.api_bandingkan_maskapai');
Route::post('/maskapai/datarekon/{id}/api_bandingkan_maskapai_tambah', [ApiRekonMaskapai::class, 'api_bandingkan_maskapai_tambah'])->name('maskapai.api_bandingkan_maskapai_tambah');
Route::post('/maskapai/datarekon/{id}/api_bandingkan_maskapai_hapus', [ApiRekonMaskapai::class, 'api_bandingkan_maskapai_hapus'])->name('maskapai.api_bandingkan_maskapai_hapus');
Route::post('/maskapai/datarekon/{id}/api_bandingkan_maskapai_edit', [ApiRekonMaskapai::class, 'api_bandingkan_maskapai_edit'])->name('maskapai.api_bandingkan_maskapai_edit');

Route::post('/maskapai/datarekon/{id}/api_kirim_rekon', [ApiRekonMaskapai::class, 'api_kirim_rekon'])->name('maskapai.api_kirim_rekon');
Route::post('/maskapai/datarekon/{id}/api_persetujuan_pusat', [ApiRekonMaskapai::class, 'api_persetujuan_pusat'])->name('maskapai.api_persetujuan_pusat');
Route::post('/maskapai/datarekon/{id}/api_persetujuan_daerah', [ApiRekonMaskapai::class, 'api_persetujuan_daerah'])->name('maskapai.api_persetujuan_daerah');

Route::get('/maskapai/datarekon/{id}/api_error_bandara', [ApiRekonMaskapai::class, 'api_error_bandara'])->name('bandara.api_error_bandara');
Route::get('/maskapai/datarekon/{id}/api_riwayat_rekon', [ApiRekonMaskapai::class, 'api_riwayat_rekon'])->name('maskapai.api_riwayat_rekon');

// ================================================================

Route::get('/tampil_akun_bandara_atau_maskapai/{akun_tipe}/{akun_id}', [PanggilFungsiController::class, 'tampil_akun_bandara_atau_maskapai']);

Route::middleware(['auth', 'ceklevel:bandara'])->group(function () {
});
