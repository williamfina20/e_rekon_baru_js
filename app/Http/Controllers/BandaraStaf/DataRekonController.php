<?php

namespace App\Http\Controllers\BandaraStaf;

use App\Http\Controllers\Controller;
use App\Models\BandaraStaf;
use App\Models\Maskapai;
use App\Models\Rekon;
use App\Models\RiwayatRekon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DataRekonController extends Controller
{
    public function index()
    {
        $data_maskapai = Maskapai::where('bandara_id', Auth::user()->bandara_staf->bandara->id)->get();

        return view('bandara_staf.datarekon.index', [
            'data_maskapai' => $data_maskapai
        ]);
    }

    public function show($id)
    {
        $data_rekon = Rekon::where('maskapai_id', $id)->orderBy('bulan', 'ASC')->get();
        $data_maskapai = Maskapai::find($id);
        return view('bandara_staf.datarekon.show', [
            'data_rekon' => $data_rekon,
            'data_maskapai' => $data_maskapai
        ]);
    }

    public function create($id)
    {
        $data_maskapai = Maskapai::find($id);

        return view('bandara_staf.datarekon.create', [
            'data_maskapai' => $data_maskapai
        ]);
    }

    public function store(Request $request, $id)
    {
        $rules = array(
            'bulan' => 'required|unique:rekons,bulan,' . $request->bulan . ',id,maskapai_id,' . $id,
            'data_rekon' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'pesan' => 'gagal',
                'aksi' => $validator->errors()
            ]);
        }

        $d_a = json_encode($request->data_rekon);

        $maskapai = Maskapai::find($id);

        Rekon::create([
            'bulan' => $request->bulan,
            'bandara_id' => $maskapai->bandara_id,
            'rekon_admin_text' => $d_a,
            'maskapai_id' => $id
        ]);

        return response()->json([
            'pesan' => 'berhasil'
        ]);
    }

    public function edit($id)
    {
        $data_rekon = Rekon::find($id);
        $data_maskapai = Maskapai::find($data_rekon->maskapai_id);
        return view('bandara_staf.datarekon.edit', [
            'data_rekon' => $data_rekon,
            'data_maskapai' => $data_maskapai
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'data_rekon' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'pesan' => 'gagal',
                'aksi' => $validator->errors()
            ]);
        }

        $d_a = json_encode($request->data_rekon);

        $rekon = Rekon::find($id);

        if ($request->riwayat) {
            $rekon->update([
                'rekon_admin_text' => $d_a,
                'rekon_admin' => 1
            ]);
            RiwayatRekon::create(
                [
                    'bandara_id' => $rekon->bandara_id,
                    'rekons_id' => $id,
                    'riwayat_ubah' => json_encode($request->riwayat),
                ]
            );
        } else {
            $rekon->update([
                'rekon_admin_text' => $d_a,
            ]);
        }

        return response()->json([
            'pesan' => 'berhasil'
        ]);
    }

    public function destroy($id)
    {
        $data_rekon = Rekon::find($id);

        $riwayat_rekon = RiwayatRekon::where('rekons_id', $id)->get();
        if ($riwayat_rekon) {
            RiwayatRekon::where('rekons_id', $id)->delete();
        }

        $data_rekon->delete();

        return redirect()->back()->with('message', 'Data berhasil dihapus');
    }

    public function bandingkan($id)
    {
        $data_rekon = Rekon::find($id);
        $data_maskapai = Maskapai::find($data_rekon->maskapai_id);

        $data_rekon_admin = json_decode($data_rekon->rekon_admin_text, true);
        $data_rekon_maskapai = json_decode($data_rekon->rekon_maskapai_text, true);

        $data_a = $data_rekon_admin;
        $data_b = $data_rekon_maskapai;

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
        // Untuk Maskapai

        $data_a = $data_rekon_maskapai;
        $data_b = $data_rekon_admin;

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


        $jumlah_error_maskapai = 0;
        foreach ($data_b as $b_items => $b_item) {
            if (!in_array($b_item['AWB'], $tampung_kunci_2)) {
                $jumlah_error_maskapai++;
            }
        }
        foreach ($data_a as $a_items => $a_item) {
            if ($b_validasi_awb_tidak_ada[$a_items] == 'tidak') {
                $jumlah_error_maskapai++;
            } else {
                if ($b_validasi_awb_sama[$a_items] > 1) {
                    $jumlah_error_maskapai++;
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
                                $jumlah_error_maskapai++;
                            }
                            break;
                        }
                    }
                }
            }
        }
        // ===============================================================

        $data_a = $data_rekon_admin;
        $data_b = $data_rekon_maskapai;

        $riwayat_rekon = RiwayatRekon::where('rekons_id', $id)->latest()->get();


        return view('bandara_staf.datarekon.bandingkan_php', [
            'data_rekon' => $data_rekon,
            'data_a' => $data_a,
            'data_b' => $data_b,
            'data_maskapai' => $data_maskapai,
            'a_validasi_awb_sama' =>  $a_validasi_awb_sama,
            'a_validasi_awb_tidak_ada' =>  $a_validasi_awb_tidak_ada,
            'tampung_kunci' => $tampung_kunci,
            'riwayat_rekon' => $riwayat_rekon,
            'jumlah_error_maskapai' => $jumlah_error_maskapai
        ]);
    }

    public function bandingkan_edit(Request $request, $id)
    {
        $data_rekon = Rekon::find($id);
        $i = 0;
        $data_rekon_admin = json_decode($data_rekon->rekon_admin_text, true);
        $data_rekon_admin_new = [];
        $data_rekon_admin_ubah = [];
        foreach ($data_rekon_admin as $items => $item) {
            if ($i == $request->baris_id) {
                $data_rekon_admin_new[$items] = $request->data_edit;
                foreach ($item as $a_kunci => $a_isi) {
                    foreach ($request->data_edit as $b_kunci => $b_isi) {
                        if ($a_kunci == $b_kunci) {
                            if ($a_isi == $b_isi) {
                                $data_rekon_admin_ubah[$a_kunci] = $a_isi;
                            } else {
                                $data_rekon_admin_ubah[$a_kunci] = ' [ ' . $a_isi . ' => ' . $b_isi . ' ] ';
                            }
                        }
                    }
                }
            } else {
                $data_rekon_admin_new[$items] = $item;
            }
            $i++;
        }

        $akun = BandaraStaf::where('users_id', Auth::user()->id)->first();

        RiwayatRekon::create([
            'rekons_id' => $data_rekon->id,
            'proses' => 'Mengubah Data',
            'riwayat_ubah' => json_encode($data_rekon_admin_ubah),
            'akun_tipe' => 'bandara_staf',
            'akun_id' => $akun->id,
        ]);

        $data_rekon->update([
            'rekon_admin_text' => json_encode($data_rekon_admin_new)
        ]);

        return redirect()->back()->with('message', 'Data berhasil diubah');
    }

    public function bandingkan_hapus(Request $request, $id)
    {
        $data_rekon = Rekon::find($id);
        $i = 0;
        $data_rekon_admin = json_decode($data_rekon->rekon_admin_text, true);
        $data_rekon_admin_new = [];
        $data_yang_dihapus = '';
        foreach ($data_rekon_admin as $items => $item) {
            if ($i == $request->baris_id) {
                // $data_rekon_admin_new[$items] = $request->data_edit;
                $data_yang_dihapus = $item;
            } else {
                array_push($data_rekon_admin_new, $item);
            }
            $i++;
        }

        $akun = BandaraStaf::where('users_id', Auth::user()->id)->first();

        RiwayatRekon::create([
            'rekons_id' => $data_rekon->id,
            'proses' => 'Menghapus Data',
            'riwayat_ubah' => json_encode($data_yang_dihapus),
            'akun_tipe' => 'bandara_staf',
            'akun_id' => $akun->id,
        ]);


        $data_rekon->update([
            'rekon_admin_text' => json_encode($data_rekon_admin_new)
        ]);

        return redirect()->back()->with('message', 'Data berhasil diubah');
    }

    public function bandingkan_tambah(Request $request, $id)
    {
        $data_rekon = Rekon::find($id);
        $data_rekon_admin = json_decode($data_rekon->rekon_admin_text, true);
        $data_rekon_maskapai = json_decode($data_rekon->rekon_maskapai_text, true);
        $data_rekon_admin_new = [];
        $data_rekon_maskapai_add = [];

        $i = 0;
        foreach ($data_rekon_maskapai as $items => $item) {
            if ($i == $request->baris_id) {
                $data_rekon_maskapai_add = $item;
            }
            $i++;
        }
        $data_rekon_admin_new = $data_rekon_admin;
        array_push($data_rekon_admin_new, $data_rekon_maskapai_add);

        $akun = BandaraStaf::where('users_id', Auth::user()->id)->first();

        RiwayatRekon::create([
            'rekons_id' => $data_rekon->id,
            'proses' => 'Menambahkan Data',
            'riwayat_ubah' => json_encode($data_rekon_maskapai_add),
            'akun_tipe' => 'bandara_staf',
            'akun_id' => $akun->id,
        ]);

        $data_rekon->update([
            'rekon_admin_text' => json_encode($data_rekon_admin_new)
        ]);

        return redirect()->back()->with('message', 'Data berhasil diubah');
    }
}
