<?php

namespace App\Http\Controllers\MaskapaiPusat;

use App\Http\Controllers\Controller;
use App\Models\BeritaAcara;
use App\Models\Maskapai;
use App\Models\Rekon;
use App\Models\RiwayatRekon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DatarekonController extends Controller
{
    public function index()
    {
        $data_maskapai = Maskapai::where('maskapai_pusat_id', Auth::user()->id)->get();

        return view('maskapai_pusat.datarekon.index', [
            'data_maskapai' => $data_maskapai
        ]);
    }

    public function show($id)
    {
        $data_rekon = Rekon::where('maskapai_id', $id)->orderBy('bulan', 'ASC')->get();
        $data_maskapai = Maskapai::find($id);
        return view('maskapai_pusat.datarekon.show', [
            'data_rekon' => $data_rekon,
            'data_maskapai' => $data_maskapai
        ]);
    }

    public function bandingkan($id)
    {
        $data_rekon = Rekon::where('id', $id)->first();

        return view('maskapai_pusat.datarekon.bandingkan_2', [
            'data_rekon' => $data_rekon,
        ]);
    }

    public function persetujuan(Request $request, $id)
    {

        $data_rekon = Rekon::find($id);
        $data_rekon->update([
            'maskapai_status' => 2,
            'maskapai_pusat_acc' => now()
        ]);

        if ($data_rekon->admin_status == 2 and $data_rekon->maskapai_status == 2) {
            BeritaAcara::create([
                'rekons_id' => $id,
                'maskapai_nama_pimpinan' => $data_rekon->maskapai->nama_pimpinan,
                'maskapai_jabatan_pimpinan' => $data_rekon->maskapai->jabatan_pimpinan,
                'bandara_nama_pimpinan' => $data_rekon->bandara->nama_pimpinan,
                'bandara_jabatan_pimpinan' => $data_rekon->bandara->jabatan_pimpinan,
            ]);
        }

        return redirect()->route('maskapai_pusat.datarekon.show', $data_rekon->maskapai_id)->with('message', 'Data berhasil disimpan');
    }
}
