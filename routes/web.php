<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\BerandaController as AdminBeranda;
use App\Http\Controllers\Admin\DataMaskapaiPusatController as AdminDataMaskapaiPusat;
use App\Http\Controllers\Admin\DataMaskapaiController as AdminDataMaskapai;
use App\Http\Controllers\Admin\DataMaskapaiStafController as AdminDataMaskapaiStaf;
use App\Http\Controllers\Admin\DataBandaraController as AdminDataBandara;
use App\Http\Controllers\Admin\BandaraStafController as AdminBandaraStaf;
use App\Http\Controllers\Admin\DataRekonController as AdminDataRekon;
use App\Http\Controllers\Admin\BeritaController as AdminBerita;
use App\Http\Controllers\Admin\PimpinanController as AdminPimpinan;
use App\Http\Controllers\Admin\BisnisController as AdminBisnis;
use App\Http\Controllers\Admin\LaporanController as AdminLaporan;
use App\Http\Controllers\Admin\LaporanBandaraController as AdminLaporanBandara;
use App\Http\Controllers\Admin\LaporanMaskapaiController as AdminLaporanMaskapai;
use App\Http\Controllers\Admin\LaporanPeriodeController as AdminLaporanPeriode;

use App\Http\Controllers\MaskapaiPusat\BerandaController as MaskapaiPusatBeranda;
use App\Http\Controllers\MaskapaiPusat\DataMaskapaiController as MaskapaiPusatDataMaskapai;
use App\Http\Controllers\MaskapaiPusat\MaskapaiStafController as MaskapaiPusatMaskapaiStaf;
use App\Http\Controllers\MaskapaiPusat\DatarekonController as MaskapaiPusatDatarekon;

use App\Http\Controllers\Maskapai\BerandaController as MaskapaiBeranda;
use App\Http\Controllers\Maskapai\DataRekonController as MaskapaiDataRekon;
use App\Http\Controllers\Maskapai\DataStafController as MaskapaiDataStaf;

use App\Http\Controllers\MaskapaiStaf\BerandaController as MaskapaiStafBeranda;
use App\Http\Controllers\MaskapaiStaf\DataRekonController as MaskapaiStafDataRekon;


use App\Http\Controllers\Bandara\BerandaController as BandaraBeranda;
use App\Http\Controllers\Bandara\DataRekonController as BandaraDatarekon;
use App\Http\Controllers\Bandara\DataMaskapaiController as BandaraDataMaskapai;
use App\Http\Controllers\Bandara\DataStafController as BandaraDataStaf;

use App\Http\Controllers\BandaraStaf\BerandaController as BandaraStafBeranda;
use App\Http\Controllers\BandaraStaf\DataRekonController as BandaraStafDatarekon;


use App\Http\Controllers\Bisnis\BerandaController as BisnisBeranda;
use App\Http\Controllers\Bisnis\DataRekonController as BisnisDataRekon;
use App\Models\Maskapai;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;


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

Route::get('/linkstorage', function () {
    // Artisan::call('storage:link');
    dd(config('app.url'));
});

Route::get('/', function () {
    if (Auth::user()) {
        return redirect('/dashboard');
    }
    return view('welcome');
});

Route::get('/dashboard', function () {
    // return view('dashboard');
    if (auth()->user()->level == 'admin' or auth()->user()->level == 'pimpinan') {
        return redirect()->route('admin.beranda');
    } elseif (auth()->user()->level == 'maskapai_pusat') {
        return redirect()->route('maskapai_pusat.beranda');
    } elseif (auth()->user()->level == 'maskapai') {
        return redirect()->route('maskapai.beranda');
    } elseif (auth()->user()->level == 'maskapai_staf') {
        return redirect()->route('maskapai_staf.beranda');
    } elseif (auth()->user()->level == 'bandara') {
        return redirect()->route('bandara.beranda');
    } elseif (auth()->user()->level == 'bandara_staf') {
        return redirect()->route('bandara_staf.beranda');
    } elseif (auth()->user()->level == 'bisnis') {
        return redirect()->route('bisnis.beranda');
    } else {
        return redirect('/');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'ceklevel:admin,pimpinan'])->group(function () {
    Route::get('/admin/beranda', [AdminBeranda::class, 'index'])->name('admin.beranda');
    Route::get('/admin/profil', [AdminBeranda::class, 'profil'])->name('admin.profil');
    Route::post('/admin/profil_update', [AdminBeranda::class, 'profil_update'])->name('admin.profil_update');

    Route::get('/admin/datamaskapaipusat', [AdminDataMaskapaiPusat::class, 'index'])->name('admin.datamaskapaipusat');
    Route::get('/admin/datamaskapaipusat/{id}/view', [AdminDataMaskapaiPusat::class, 'view'])->name('admin.datamaskapaipusat.view');
    Route::get('/admin/datamaskapaipusat/create', [AdminDataMaskapaiPusat::class, 'create'])->name('admin.datamaskapaipusat.create');
    Route::post('/admin/datamaskapaipusat', [AdminDataMaskapaiPusat::class, 'store'])->name('admin.datamaskapaipusat.store');
    Route::get('/admin/datamaskapaipusat/{id}/edit', [AdminDataMaskapaiPusat::class, 'edit'])->name('admin.datamaskapaipusat.edit');
    Route::put('/admin/datamaskapaipusat/{id}/update', [AdminDataMaskapaiPusat::class, 'update'])->name('admin.datamaskapaipusat.update');
    Route::delete('/admin/datamaskapaipusat/{id}', [AdminDataMaskapaiPusat::class, 'destroy'])->name('admin.datamaskapaipusat.destroy');

    Route::get('/admin/datamaskapai/{id}', [AdminDataMaskapai::class, 'index'])->name('admin.datamaskapai');
    Route::get('/admin/datamaskapai/{id}/create', [AdminDataMaskapai::class, 'create'])->name('admin.datamaskapai.create');
    Route::post('/admin/datamaskapai/{id}/store', [AdminDataMaskapai::class, 'store'])->name('admin.datamaskapai.store');
    Route::get('/admin/datamaskapai/{id}/edit', [AdminDataMaskapai::class, 'edit'])->name('admin.datamaskapai.edit');
    Route::put('/admin/datamaskapai/{id}/update', [AdminDataMaskapai::class, 'update'])->name('admin.datamaskapai.update');
    Route::delete('/admin/datamaskapai/{id}/destroy', [AdminDataMaskapai::class, 'destroy'])->name('admin.datamaskapai.destroy');

    Route::get('/admin/datamaskapaistaf/{id}', [AdminDataMaskapaiStaf::class, 'index'])->name('admin.datamaskapaistaf');
    Route::get('/admin/datamaskapaistaf/{id}/create', [AdminDataMaskapaiStaf::class, 'create'])->name('admin.datamaskapaistaf.create');
    Route::post('/admin/datamaskapaistaf/{id}/store', [AdminDataMaskapaiStaf::class, 'store'])->name('admin.datamaskapaistaf.store');
    Route::get('/admin/datamaskapaistaf/{id}/edit', [AdminDataMaskapaiStaf::class, 'edit'])->name('admin.datamaskapaistaf.edit');
    Route::put('/admin/datamaskapaistaf/{id}/update', [AdminDataMaskapaiStaf::class, 'update'])->name('admin.datamaskapaistaf.update');
    Route::delete('/admin/datamaskapaistaf/{id}', [AdminDataMaskapaiStaf::class, 'destroy'])->name('admin.datamaskapaistaf.destroy');

    Route::get('/admin/databandara', [AdminDataBandara::class, 'index'])->name('admin.databandara');
    Route::get('/admin/databandara/create', [AdminDataBandara::class, 'create'])->name('admin.databandara.create');
    Route::post('/admin/databandara', [AdminDataBandara::class, 'store'])->name('admin.databandara.store');
    Route::get('/admin/databandara/{id}/edit', [AdminDataBandara::class, 'edit'])->name('admin.databandara.edit');
    Route::put('/admin/databandara/{id}/update', [AdminDataBandara::class, 'update'])->name('admin.databandara.update');
    Route::delete('/admin/databandara/{id}', [AdminDataBandara::class, 'destroy'])->name('admin.databandara.destroy');

    Route::get('/admin/bandara_staf/{id}', [AdminBandaraStaf::class, 'index'])->name('admin.bandara_staf');
    Route::get('/admin/bandara_staf/{id}/create', [AdminBandaraStaf::class, 'create'])->name('admin.bandara_staf.create');
    Route::post('/admin/bandara_staf/{id}/store', [AdminBandaraStaf::class, 'store'])->name('admin.bandara_staf.store');
    Route::get('/admin/bandara_staf/{id}/edit', [AdminBandaraStaf::class, 'edit'])->name('admin.bandara_staf.edit');
    Route::put('/admin/bandara_staf/{id}/update', [AdminBandaraStaf::class, 'update'])->name('admin.bandara_staf.update');
    Route::delete('/admin/bandara_staf/{id}', [AdminBandaraStaf::class, 'destroy'])->name('admin.bandara_staf.destroy');

    Route::get('/admin/datarekon', [AdminDataRekon::class, 'index'])->name('admin.datarekon');
    Route::get('/admin/datarekon/{id}/maskapai', [AdminDataRekon::class, 'maskapai'])->name('admin.datarekon.maskapai');
    Route::get('/admin/datarekon/{id}/show', [AdminDataRekon::class, 'show'])->name('admin.datarekon.show');
    Route::get('/admin/datarekon/{id}/create', [AdminDataRekon::class, 'create'])->name('admin.datarekon.create');
    Route::post('/admin/datarekon/{id}/store', [AdminDataRekon::class, 'store'])->name('admin.datarekon.store');
    Route::get('/admin/datarekon/{id}/edit', [AdminDataRekon::class, 'edit'])->name('admin.datarekon.edit');
    Route::put('/admin/datarekon/{id}/update', [AdminDataRekon::class, 'update'])->name('admin.datarekon.update');
    Route::delete('/admin/datarekon/{id}', [AdminDataRekon::class, 'destroy'])->name('admin.datarekon.destroy');
    Route::get('/admin/datarekon/{id}/bandingkan', [AdminDataRekon::class, 'bandingkan'])->name('admin.datarekon.bandingkan');

    Route::put('/admin/datarekon/{id}/persetujuan', [AdminDataRekon::class, 'persetujuan'])->name('admin.datarekon.persetujuan');

    Route::get('/admin/datarekon/{id}/edit_invoice', [AdminDataRekon::class, 'edit_invoice'])->name('admin.datarekon.edit_invoice');
    Route::put('/admin/datarekon/{id}/update_invoice', [AdminDataRekon::class, 'update_invoice'])->name('admin.datarekon.update_invoice');

    Route::get('/admin/berita', [AdminBerita::class, 'index'])->name('admin.berita');
    Route::get('/admin/berita/create', [AdminBerita::class, 'create'])->name('admin.berita.create');
    Route::post('/admin/berita', [AdminBerita::class, 'store'])->name('admin.berita.store');
    Route::get('/admin/berita/{id}/edit', [AdminBerita::class, 'edit'])->name('admin.berita.edit');
    Route::put('/admin/berita/{id}/update', [AdminBerita::class, 'update'])->name('admin.berita.update');
    Route::delete('/admin/berita/{id}', [AdminBerita::class, 'destroy'])->name('admin.berita.destroy');

    Route::get('/admin/pimpinan', [AdminPimpinan::class, 'index'])->name('admin.pimpinan');
    Route::get('/admin/pimpinan/create', [AdminPimpinan::class, 'create'])->name('admin.pimpinan.create');
    Route::post('/admin/pimpinan', [AdminPimpinan::class, 'store'])->name('admin.pimpinan.store');
    Route::get('/admin/pimpinan/{id}/edit', [AdminPimpinan::class, 'edit'])->name('admin.pimpinan.edit');
    Route::put('/admin/pimpinan/{id}/update', [AdminPimpinan::class, 'update'])->name('admin.pimpinan.update');
    Route::delete('/admin/pimpinan/{id}', [AdminPimpinan::class, 'destroy'])->name('admin.pimpinan.destroy');

    Route::get('/admin/bisnis', [AdminBisnis::class, 'index'])->name('admin.bisnis');
    Route::get('/admin/bisnis/create', [AdminBisnis::class, 'create'])->name('admin.bisnis.create');
    Route::post('/admin/bisnis', [AdminBisnis::class, 'store'])->name('admin.bisnis.store');
    Route::get('/admin/bisnis/{id}/edit', [AdminBisnis::class, 'edit'])->name('admin.bisnis.edit');
    Route::put('/admin/bisnis/{id}/update', [AdminBisnis::class, 'update'])->name('admin.bisnis.update');
    Route::delete('/admin/bisnis/{id}', [AdminBisnis::class, 'destroy'])->name('admin.bisnis.destroy');

    Route::get('/admin/laporan', [AdminLaporan::class, 'index'])->name('admin.laporan');

    Route::get('/admin/laporan_bandara', [AdminLaporanBandara::class, 'index'])->name('admin.laporan_bandara');
    Route::get('/admin/laporan_bandara/cetak', [AdminLaporanBandara::class, 'cetak'])->name('admin.laporan_bandara.cetak');

    Route::get('/admin/laporan_maskapai', [AdminLaporanMaskapai::class, 'index'])->name('admin.laporan_maskapai');
    Route::get('/admin/laporan_maskapai/cetak', [AdminLaporanMaskapai::class, 'cetak'])->name('admin.laporan_maskapai.cetak');

    Route::get('/admin/laporan_periode', [AdminLaporanPeriode::class, 'index'])->name('admin.laporan_periode');
    Route::get('/admin/laporan_periode/cetak', [AdminLaporanPeriode::class, 'cetak'])->name('admin.laporan_periode.cetak');
});

Route::middleware(['auth', 'ceklevel:maskapai_pusat'])->group(function () {
    Route::get('/maskapai_pusat/beranda', [MaskapaiPusatBeranda::class, 'index'])->name('maskapai_pusat.beranda');
    Route::get('/maskapai_pusat/profil', [MaskapaiPusatBeranda::class, 'profil'])->name('maskapai_pusat.profil');
    Route::post('/maskapai_pusat/profil_update', [MaskapaiPusatBeranda::class, 'profil_update'])->name('maskapai_pusat.profil_update');

    Route::get('/maskapai_pusat/datamaskapai', [MaskapaiPusatDataMaskapai::class, 'index'])->name('maskapai_pusat.datamaskapai');
    Route::get('/maskapai_pusat/datamaskapai/create', [MaskapaiPusatDataMaskapai::class, 'create'])->name('maskapai_pusat.datamaskapai.create');
    Route::post('/maskapai_pusat/datamaskapai/store', [MaskapaiPusatDataMaskapai::class, 'store'])->name('maskapai_pusat.datamaskapai.store');
    Route::get('/maskapai_pusat/datamaskapai/{id}/edit', [MaskapaiPusatDataMaskapai::class, 'edit'])->name('maskapai_pusat.datamaskapai.edit');
    Route::put('/maskapai_pusat/datamaskapai/{id}/update', [MaskapaiPusatDataMaskapai::class, 'update'])->name('maskapai_pusat.datamaskapai.update');
    Route::delete('/maskapai_pusat/datamaskapai/{id}', [MaskapaiPusatDataMaskapai::class, 'destroy'])->name('maskapai_pusat.datamaskapai.destroy');

    Route::get('/maskapai_pusat/maskapai_staf/{id}', [MaskapaiPusatMaskapaiStaf::class, 'index'])->name('maskapai_pusat.maskapai_staf');
    Route::get('/maskapai_pusat/maskapai_staf/{id}/create', [MaskapaiPusatMaskapaiStaf::class, 'create'])->name('maskapai_pusat.maskapai_staf.create');
    Route::post('/maskapai_pusat/maskapai_staf/{id}/store', [MaskapaiPusatMaskapaiStaf::class, 'store'])->name('maskapai_pusat.maskapai_staf.store');
    Route::get('/maskapai_pusat/maskapai_staf/{id}/edit', [MaskapaiPusatMaskapaiStaf::class, 'edit'])->name('maskapai_pusat.maskapai_staf.edit');
    Route::put('/maskapai_pusat/maskapai_staf/{id}/update', [MaskapaiPusatMaskapaiStaf::class, 'update'])->name('maskapai_pusat.maskapai_staf.update');
    Route::delete('/maskapai_pusat/maskapai_staf/{id}', [MaskapaiPusatMaskapaiStaf::class, 'destroy'])->name('maskapai_pusat.maskapai_staf.destroy');

    Route::get('/maskapai_pusat/datarekon', [MaskapaiPusatDatarekon::class, 'index'])->name('maskapai_pusat.datarekon');
    Route::get('/maskapai_pusat/datarekon/{id}/show', [MaskapaiPusatDatarekon::class, 'show'])->name('maskapai_pusat.datarekon.show');
    Route::get('/maskapai_pusat/datarekon/{id}/bandingkan', [MaskapaiPusatDataRekon::class, 'bandingkan'])->name('maskapai_pusat.datarekon.bandingkan');
    Route::put('/maskapai_pusat/datarekon/{id}/persetujuan', [MaskapaiPusatDataRekon::class, 'persetujuan'])->name('maskapai_pusat.datarekon.persetujuan');
});


Route::middleware(['auth', 'ceklevel:maskapai'])->group(function () {
    Route::get('/maskapai/beranda', [MaskapaiBeranda::class, 'index'])->name('maskapai.beranda');
    Route::get('/maskapai/profil', [MaskapaiBeranda::class, 'profil'])->name('maskapai.profil');
    Route::post('/maskapai/profil_update', [MaskapaiBeranda::class, 'profil_update'])->name('maskapai.profil_update');

    Route::get('/maskapai/datarekon', [MaskapaiDataRekon::class, 'index'])->name('maskapai.datarekon');
    Route::get('/maskapai/datarekon/{id}/edit', [MaskapaiDataRekon::class, 'edit'])->name('maskapai.datarekon.edit');
    Route::put('/maskapai/datarekon/{id}/update', [MaskapaiDataRekon::class, 'update'])->name('maskapai.datarekon.update');
    Route::get('/maskapai/datarekon/{id}/bandingkan', [MaskapaiDataRekon::class, 'bandingkan'])->name('maskapai.datarekon.bandingkan');

    Route::put('/maskapai/datarekon/{id}/bandingkan_edit', [MaskapaiDataRekon::class, 'bandingkan_edit'])->name('maskapai.datarekon.bandingkan_edit');
    Route::put('/maskapai/datarekon/{id}/bandingkan_hapus', [MaskapaiDataRekon::class, 'bandingkan_hapus'])->name('maskapai.datarekon.bandingkan_hapus');
    Route::put('/maskapai/datarekon/{id}/bandingkan_tambah', [MaskapaiDataRekon::class, 'bandingkan_tambah'])->name('maskapai.datarekon.bandingkan_tambah');

    Route::put('/maskapai/datarekon/{id}/berita_acara', [MaskapaiDataRekon::class, 'berita_acara'])->name('maskapai.datarekon.berita_acara');
    Route::get('/maskapai/datarekon/{id}/lihat_berita', [MaskapaiDataRekon::class, 'lihat_berita'])->name('maskapai.datarekon.lihat_berita');
    Route::get('/maskapai/datarekon/{id}/lihat_berita_2', [MaskapaiDataRekon::class, 'lihat_berita_2'])->name('maskapai.datarekon.lihat_berita_2');

    Route::post('/maskapai/datarekon/{id}/kirim', [MaskapaiDataRekon::class, 'kirim'])->name('maskapai.datarekon.kirim');
    Route::post('/maskapai/datarekon/{id}/persetujuan', [MaskapaiDataRekon::class, 'persetujuan'])->name('maskapai.datarekon.persetujuan');

    Route::get('/maskapai/datastaf', [MaskapaiDataStaf::class, 'index'])->name('maskapai.datastaf');
});

Route::middleware(['auth', 'ceklevel:maskapai_staf'])->group(function () {
    Route::get('/maskapai_staf/beranda', [MaskapaiStafBeranda::class, 'index'])->name('maskapai_staf.beranda');
    Route::get('/maskapai_staf/profil', [MaskapaiStafBeranda::class, 'profil'])->name('maskapai_staf.profil');
    Route::post('/maskapai_staf/profil_update', [MaskapaiStafBeranda::class, 'profil_update'])->name('maskapai_staf.profil_update');


    Route::get('/maskapai_staf/datarekon', [MaskapaiStafDataRekon::class, 'index'])->name('maskapai_staf.datarekon');
    Route::get('/maskapai_staf/datarekon/{id}/edit', [MaskapaiStafDataRekon::class, 'edit'])->name('maskapai_staf.datarekon.edit');
    Route::put('/maskapai_staf/datarekon/{id}/update', [MaskapaiStafDataRekon::class, 'update'])->name('maskapai_staf.datarekon.update');
    Route::get('/maskapai_staf/datarekon/{id}/bandingkan', [MaskapaiStafDataRekon::class, 'bandingkan'])->name('maskapai_staf.datarekon.bandingkan');

    Route::put('/maskapai_staf/datarekon/{id}/bandingkan_edit', [MaskapaiStafDataRekon::class, 'bandingkan_edit'])->name('maskapai_staf.datarekon.bandingkan_edit');
    Route::put('/maskapai_staf/datarekon/{id}/bandingkan_hapus', [MaskapaiStafDataRekon::class, 'bandingkan_hapus'])->name('maskapai_staf.datarekon.bandingkan_hapus');
    Route::put('/maskapai_staf/datarekon/{id}/bandingkan_tambah', [MaskapaiStafDataRekon::class, 'bandingkan_tambah'])->name('maskapai_staf.datarekon.bandingkan_tambah');
});


Route::middleware(['auth', 'ceklevel:bandara'])->group(function () {
    Route::get('/bandara/beranda', [BandaraBeranda::class, 'index'])->name('bandara.beranda');
    Route::get('/bandara/profil', [BandaraBeranda::class, 'profil'])->name('bandara.profil');
    Route::post('/bandara/profil_update', [BandaraBeranda::class, 'profil_update'])->name('bandara.profil_update');

    Route::get('/bandara/datamaskapai', [BandaraDataMaskapai::class, 'index'])->name('bandara.datamaskapai');
    Route::get('/bandara/datamaskapai/{id}/view', [BandaraDataMaskapai::class, 'view'])->name('bandara.datamaskapai.view');
    Route::get('/bandara/datamaskapai/create', [BandaraDataMaskapai::class, 'create'])->name('bandara.datamaskapai.create');
    Route::post('/bandara/datamaskapai', [BandaraDataMaskapai::class, 'store'])->name('bandara.datamaskapai.store');
    Route::get('/bandara/datamaskapai/{id}/edit', [BandaraDataMaskapai::class, 'edit'])->name('bandara.datamaskapai.edit');
    Route::put('/bandara/datamaskapai/{id}/update', [BandaraDataMaskapai::class, 'update'])->name('bandara.datamaskapai.update');
    Route::delete('/bandara/datamaskapai/{id}', [BandaraDataMaskapai::class, 'destroy'])->name('bandara.datamaskapai.destroy');

    Route::get('/bandara/datastaf', [BandaraDataStaf::class, 'index'])->name('bandara.datastaf');

    Route::get('/bandara/datarekon', [BandaraDatarekon::class, 'index'])->name('bandara.datarekon');
    Route::get('/bandara/datarekon/{id}/show', [BandaraDataRekon::class, 'show'])->name('bandara.datarekon.show');
    Route::get('/bandara/datarekon/{id}/create', [BandaraDataRekon::class, 'create'])->name('bandara.datarekon.create');
    // Route::post('/bandara/datarekon/{id}/store', [BandaraDataRekon::class, 'store'])->name('bandara.datarekon.store');
    Route::get('/bandara/datarekon/{id}/edit', [BandaraDataRekon::class, 'edit'])->name('bandara.datarekon.edit');
    // Route::put('/bandara/datarekon/{id}/update', [BandaraDataRekon::class, 'update'])->name('bandara.datarekon.update');
    Route::delete('/bandara/datarekon/{id}', [BandaraDataRekon::class, 'destroy'])->name('bandara.datarekon.destroy');
    Route::get('/bandara/datarekon/{id}/bandingkan', [BandaraDataRekon::class, 'bandingkan'])->name('bandara.datarekon.bandingkan');
    Route::put('/bandara/datarekon/{id}/bandingkan_edit', [BandaraDataRekon::class, 'bandingkan_edit'])->name('bandara.datarekon.bandingkan_edit');
    Route::put('/bandara/datarekon/{id}/bandingkan_hapus', [BandaraDataRekon::class, 'bandingkan_hapus'])->name('bandara.datarekon.bandingkan_hapus');
    Route::put('/bandara/datarekon/{id}/bandingkan_tambah', [BandaraDataRekon::class, 'bandingkan_tambah'])->name('bandara.datarekon.bandingkan_tambah');

    Route::post('/bandara/datarekon/{id}/kirim', [BandaraDataRekon::class, 'kirim'])->name('bandara.datarekon.kirim');
    Route::post('/bandara/datarekon/{id}/persetujuan', [BandaraDataRekon::class, 'persetujuan'])->name('bandara.datarekon.persetujuan');

    Route::put('/bandara/datarekon/{id}/berita_acara', [BandaraDataRekon::class, 'berita_acara'])->name('bandara.datarekon.berita_acara');
    Route::get('/bandara/datarekon/{id}/lihat_berita', [BandaraDataRekon::class, 'lihat_berita'])->name('bandara.datarekon.lihat_berita');
    Route::get('/bandara/datarekon/{id}/lihat_berita_2', [BandaraDataRekon::class, 'lihat_berita_2'])->name('bandara.datarekon.lihat_berita_2');
});

Route::middleware(['auth', 'ceklevel:bandara_staf'])->group(function () {
    Route::get('/bandara_staf/beranda', [BandaraStafBeranda::class, 'index'])->name('bandara_staf.beranda');
    Route::get('/bandara_staf/profil', [BandaraStafBeranda::class, 'profil'])->name('bandara_staf.profil');
    Route::post('/bandara_staf/profil_update', [BandaraStafBeranda::class, 'profil_update'])->name('bandara_staf.profil_update');

    Route::get('/bandara_staf/datarekon', [BandaraStafDatarekon::class, 'index'])->name('bandara_staf.datarekon');
    Route::get('/bandara_staf/datarekon/{id}/show', [BandaraStafDatarekon::class, 'show'])->name('bandara_staf.datarekon.show');
    Route::get('/bandara_staf/datarekon/{id}/create', [BandaraStafDatarekon::class, 'create'])->name('bandara_staf.datarekon.create');
    Route::get('/bandara_staf/datarekon/{id}/edit', [BandaraStafDatarekon::class, 'edit'])->name('bandara_staf.datarekon.edit');
    Route::delete('/bandara_staf/datarekon/{id}', [BandaraStafDatarekon::class, 'destroy'])->name('bandara_staf.datarekon.destroy');
    Route::get('/bandara_staf/datarekon/{id}/bandingkan', [BandaraStafDatarekon::class, 'bandingkan'])->name('bandara_staf.datarekon.bandingkan');
    Route::put('/bandara_staf/datarekon/{id}/bandingkan_edit', [BandaraStafDatarekon::class, 'bandingkan_edit'])->name('bandara_staf.datarekon.bandingkan_edit');
    Route::put('/bandara_staf/datarekon/{id}/bandingkan_hapus', [BandaraStafDatarekon::class, 'bandingkan_hapus'])->name('bandara_staf.datarekon.bandingkan_hapus');
    Route::put('/bandara_staf/datarekon/{id}/bandingkan_tambah', [BandaraStafDatarekon::class, 'bandingkan_tambah'])->name('bandara_staf.datarekon.bandingkan_tambah');
});

Route::middleware(['auth', 'ceklevel:bisnis'])->group(function () {
    Route::get('/bisnis/beranda', [BisnisBeranda::class, 'index'])->name('bisnis.beranda');

    Route::get('/bisnis/datarekon', [BisnisDataRekon::class, 'index'])->name('bisnis.datarekon');
    Route::get('/bisnis/datarekon/{id}/maskapai', [BisnisDataRekon::class, 'maskapai'])->name('bisnis.datarekon.maskapai');
    Route::get('/bisnis/datarekon/{id}/show', [BisnisDataRekon::class, 'show'])->name('bisnis.datarekon.show');
    Route::get('/bisnis/datarekon/{id}/lihat_berita', [BisnisDataRekon::class, 'lihat_berita'])->name('bisnis.datarekon.lihat_berita');
    Route::get('/bisnis/datarekon/{id}/lihat_berita_2', [BisnisDataRekon::class, 'lihat_berita_2'])->name('bisnis.datarekon.lihat_berita_2');
    Route::get('/bisnis/datarekon/{id}/tambah_invoice', [BisnisDataRekon::class, 'tambah_invoice'])->name('bisnis.datarekon.tambah_invoice');
    Route::post('/bisnis/datarekon/{id}/simpan_invoice', [BisnisDataRekon::class, 'simpan_invoice'])->name('bisnis.datarekon.simpan_invoice');
    Route::get('/bisnis/datarekon/{id}/one_invoice', [BisnisDataRekon::class, 'one_invoice'])->name('bisnis.datarekon.one_invoice');
    Route::post('/bisnis/datarekon/{id}/one_invoice_simpan', [BisnisDataRekon::class, 'one_invoice_simpan'])->name('bisnis.datarekon.one_invoice_simpan');
    Route::get('/bisnis/datarekon/{id}/multiple_invoice', [BisnisDataRekon::class, 'multiple_invoice'])->name('bisnis.datarekon.multiple_invoice');
    Route::post('/bisnis/datarekon/{id}/multiple_invoice_simpan', [BisnisDataRekon::class, 'multiple_invoice_simpan'])->name('bisnis.datarekon.multiple_invoice_simpan');
});

Route::middleware(['auth', 'ceklevel:admin,pimpinan,bandara,bandara_staf,maskapai_pusat,maskapai,maskapai_staf,bisnis'])->group(function () {
    Route::get('datarekon/{id}/lihat_berita', [AdminDataRekon::class, 'lihat_berita'])->name('admin.datarekon.lihat_berita');
    Route::get('datarekon/{id}/lihat_berita_2', [AdminDataRekon::class, 'lihat_berita_2'])->name('admin.datarekon.lihat_berita_2');
});

Route::get('datarekon/{id}/kode_bandara', [AdminDataRekon::class, 'kode_bandara'])->name('admin.datarekon.kode_bandara');
Route::get('datarekon/{id}/kode_maskapai', [AdminDataRekon::class, 'kode_maskapai'])->name('admin.datarekon.kode_maskapai');
