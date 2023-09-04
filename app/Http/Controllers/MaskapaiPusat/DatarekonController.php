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
        $data_rekon = Rekon::find($id);
        $data_maskapai = Maskapai::find($data_rekon->maskapai_id);

        $data_rekon_admin = json_decode($data_rekon->rekon_admin_text, true);
        $data_rekon_maskapai = json_decode($data_rekon->rekon_maskapai_text, true);

        $data_a = $data_rekon_maskapai;
        $data_b = $data_rekon_admin;

        $tampung_kunci = [];
        $a_validasi_awb_sama = [];
        $a_validasi_awb_tidak_ada = [];

        // ====cek data awb yang tidak ada
        foreach ($data_a as $a_items => $a_item) {
            foreach ($data_b as $b_items => $b_item) {
                if ($a_item['AWB'] == $b_item['AWB']) {
                    array_push($tampung_kunci, $a_item['AWB']);
                }
            }
        }
        foreach ($data_a as $a_items => $a_item) {
            if (in_array($a_item['AWB'], $tampung_kunci)) {
                $a_validasi_awb_tidak_ada[$a_items] = 'ada';
            } else {
                $a_validasi_awb_tidak_ada[$a_items] = 'tidak';
            }
        }

        // ====cek data awb yang sama
        foreach ($data_a as $a_items => $a_item) {
            $i = 0;
            foreach ($data_a as $a2_items => $a2_item) {
                if ($a_item['AWB'] == $a2_item['AWB']) {
                    $i++;
                }
            }
            $a_validasi_awb_sama[$a_items] = $i;
        }

        // ===============================================================
        // Untuk Bandara

        $data_a = $data_rekon_admin;
        $data_b = $data_rekon_maskapai;

        $tampung_kunci_2 = [];
        $b_validasi_awb_sama = [];
        $b_validasi_awb_tidak_ada = [];

        // ====cek data awb yang tidak ada
        foreach ($data_a as $a_items => $a_item) {
            foreach ($data_b as $b_items => $b_item) {
                if ($a_item['AWB'] == $b_item['AWB']) {
                    array_push($tampung_kunci_2, $a_item['AWB']);
                }
            }
        }
        foreach ($data_a as $a_items => $a_item) {
            if (in_array($a_item['AWB'], $tampung_kunci_2)) {
                $b_validasi_awb_tidak_ada[$a_items] = 'ada';
            } else {
                $b_validasi_awb_tidak_ada[$a_items] = 'tidak';
            }
        }

        // ====cek data awb yang sama
        foreach ($data_a as $a_items => $a_item) {
            $i = 0;
            foreach ($data_a as $a2_items => $a2_item) {
                if ($a_item['AWB'] == $a2_item['AWB']) {
                    $i++;
                }
            }
            $b_validasi_awb_sama[$a_items] = $i;
        }


        $jumlah_error_bandara = 0;
        foreach ($data_b as $b_items => $b_item) {
            if (!in_array($b_item['AWB'], $tampung_kunci_2)) {
                $jumlah_error_bandara++;
            }
        }
        foreach ($data_a as $a_items => $a_item) {
            if ($b_validasi_awb_tidak_ada[$a_items] == 'tidak') {
                $jumlah_error_bandara++;
            } else {
                if ($b_validasi_awb_sama[$a_items] > 1) {
                    $jumlah_error_bandara++;
                } else {
                    foreach ($data_b as $b_items => $b_item) {
                        if (in_array($a_item['AWB'], $b_item)) {
                            $jumlah_kolom_error = 0;
                            foreach ($a_item as $a_kunci => $a_isi) {
                                if ($a_isi == $b_item[$a_kunci]) {
                                } else {
                                    if ($a_kunci != 'NO') {
                                        $jumlah_kolom_error++;
                                    }
                                }
                            }
                            if ($jumlah_kolom_error > 0) {
                                $jumlah_error_bandara++;
                            }
                            break;
                        }
                    }
                }
            }
        }
        // ===============================================================

        $data_a = $data_rekon_maskapai;
        $data_b = $data_rekon_admin;

        $riwayat_rekon = RiwayatRekon::where('rekons_id', $id)->latest()->get();

        return view('maskapai_pusat.datarekon.bandingkan_php', [
            'data_rekon' => $data_rekon,
            'data_a' => $data_a,
            'data_b' => $data_b,
            'data_maskapai' => $data_maskapai,
            'a_validasi_awb_sama' =>  $a_validasi_awb_sama,
            'a_validasi_awb_tidak_ada' =>  $a_validasi_awb_tidak_ada,
            'tampung_kunci' => $tampung_kunci,
            'riwayat_rekon' => $riwayat_rekon,
            'jumlah_error_bandara' => $jumlah_error_bandara
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
