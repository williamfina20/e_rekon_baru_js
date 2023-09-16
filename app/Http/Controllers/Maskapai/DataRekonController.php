<?php

namespace App\Http\Controllers\Maskapai;

use App\Http\Controllers\Controller;
use App\Models\Maskapai;
use App\Models\Rekon;
use App\Models\RiwayatRekon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;

class DataRekonController extends Controller
{
    public function index()
    {
        $maskapai = Maskapai::where('users_id', Auth::user()->id)->first();
        $data_rekon = Rekon::where('maskapai_id', $maskapai->id)->orderBy('bulan', 'ASC')->get();
        return view(
            'maskapai.datarekon.index',
            [
                'data_rekon' => $data_rekon,
            ]
        );
    }

    public function edit($id)
    {
        $data_rekon = Rekon::find($id);
        $data_maskapai = Maskapai::find($data_rekon->maskapai_id);
        return view('maskapai.datarekon.edit', [
            'data_rekon' => $data_rekon,
            'data_maskapai' => $data_maskapai,
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
                'rekon_maskapai_text' => $d_a,
                'rekon_maskapai' => 1
            ]);
            RiwayatRekon::create(
                [
                    'maskapai_id' => $rekon->maskapai_id,
                    'rekons_id' => $id,
                    'riwayat_ubah' => json_encode($request->riwayat),
                ]
            );
        } else {
            $rekon->update([
                'rekon_maskapai_text' => $d_a,
            ]);
        }

        return response()->json([
            'pesan' => 'berhasil'
        ]);
    }

    // public function bandingkan($id)
    // {
    //     $data_rekon = Rekon::find($id);
    //     $data_maskapai = Maskapai::find($data_rekon->maskapai_id);

    //     $data_rekon_admin = json_decode($data_rekon->rekon_admin_text, true);
    //     $data_rekon_maskapai = json_decode($data_rekon->rekon_maskapai_text, true);

    //     $data_a = $data_rekon_maskapai;
    //     $data_b = $data_rekon_admin;

    //     $tampung_kunci = [];
    //     $a_validasi_awb_sama = [];
    //     $a_validasi_awb_tidak_ada = [];

    //     // ====cek data awb yang tidak ada
    //     foreach ($data_a as $a_items => $a_item) {
    //         foreach ($data_b as $b_items => $b_item) {
    //             if ($a_item['AWB'] == $b_item['AWB']) {
    //                 array_push($tampung_kunci, $a_item['AWB']);
    //             }
    //         }
    //     }
    //     foreach ($data_a as $a_items => $a_item) {
    //         if (in_array($a_item['AWB'], $tampung_kunci)) {
    //             $a_validasi_awb_tidak_ada[$a_items] = 'ada';
    //         } else {
    //             $a_validasi_awb_tidak_ada[$a_items] = 'tidak';
    //         }
    //     }

    //     // ====cek data awb yang sama
    //     foreach ($data_a as $a_items => $a_item) {
    //         $i = 0;
    //         foreach ($data_a as $a2_items => $a2_item) {
    //             if ($a_item['AWB'] == $a2_item['AWB']) {
    //                 $i++;
    //             }
    //         }
    //         $a_validasi_awb_sama[$a_items] = $i;
    //     }

    //     // ===============================================================
    //     // Untuk Bandara

    //     $data_a = $data_rekon_admin;
    //     $data_b = $data_rekon_maskapai;

    //     $tampung_kunci_2 = [];
    //     $b_validasi_awb_sama = [];
    //     $b_validasi_awb_tidak_ada = [];

    //     // ====cek data awb yang tidak ada
    //     foreach ($data_a as $a_items => $a_item) {
    //         foreach ($data_b as $b_items => $b_item) {
    //             if ($a_item['AWB'] == $b_item['AWB']) {
    //                 array_push($tampung_kunci_2, $a_item['AWB']);
    //             }
    //         }
    //     }
    //     foreach ($data_a as $a_items => $a_item) {
    //         if (in_array($a_item['AWB'], $tampung_kunci_2)) {
    //             $b_validasi_awb_tidak_ada[$a_items] = 'ada';
    //         } else {
    //             $b_validasi_awb_tidak_ada[$a_items] = 'tidak';
    //         }
    //     }

    //     // ====cek data awb yang sama
    //     foreach ($data_a as $a_items => $a_item) {
    //         $i = 0;
    //         foreach ($data_a as $a2_items => $a2_item) {
    //             if ($a_item['AWB'] == $a2_item['AWB']) {
    //                 $i++;
    //             }
    //         }
    //         $b_validasi_awb_sama[$a_items] = $i;
    //     }


    //     $jumlah_error_bandara = 0;
    //     foreach ($data_b as $b_items => $b_item) {
    //         if (!in_array($b_item['AWB'], $tampung_kunci_2)) {
    //             $jumlah_error_bandara++;
    //         }
    //     }
    //     foreach ($data_a as $a_items => $a_item) {
    //         if ($b_validasi_awb_tidak_ada[$a_items] == 'tidak') {
    //             $jumlah_error_bandara++;
    //         } else {
    //             if ($b_validasi_awb_sama[$a_items] > 1) {
    //                 $jumlah_error_bandara++;
    //             } else {
    //                 foreach ($data_b as $b_items => $b_item) {
    //                     if (in_array($a_item['AWB'], $b_item)) {
    //                         $jumlah_kolom_error = 0;
    //                         foreach ($a_item as $a_kunci => $a_isi) {
    //                             if ($a_isi == $b_item[$a_kunci]) {
    //                             } else {
    //                                 if ($a_kunci != 'NO') {
    //                                     $jumlah_kolom_error++;
    //                                 }
    //                             }
    //                         }
    //                         if ($jumlah_kolom_error > 0) {
    //                             $jumlah_error_bandara++;
    //                         }
    //                         break;
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     // ===============================================================

    //     $data_a = $data_rekon_maskapai;
    //     $data_b = $data_rekon_admin;

    //     $riwayat_rekon = RiwayatRekon::where('rekons_id', $id)->latest()->get();

    //     return view('maskapai.datarekon.bandingkan_php', [
    //         'data_rekon' => $data_rekon,
    //         'data_a' => $data_a,
    //         'data_b' => $data_b,
    //         'data_maskapai' => $data_maskapai,
    //         'a_validasi_awb_sama' =>  $a_validasi_awb_sama,
    //         'a_validasi_awb_tidak_ada' =>  $a_validasi_awb_tidak_ada,
    //         'tampung_kunci' => $tampung_kunci,
    //         'riwayat_rekon' => $riwayat_rekon,
    //         'jumlah_error_bandara' => $jumlah_error_bandara
    //     ]);
    // }

    public function bandingkan($id)
    {
        $data_rekon = Rekon::where('id', $id)->first();

        return view('maskapai.datarekon.bandingkan_2', [
            'data_rekon' => $data_rekon,
        ]);
    }

    public function bandingkan_edit(Request $request, $id)
    {
        $data_rekon = Rekon::find($id);
        $i = 0;
        $data_rekon_maskapai = json_decode($data_rekon->rekon_maskapai_text, true);
        $data_rekon_maskapai_new = [];
        $data_rekon_maskapai_ubah = [];
        foreach ($data_rekon_maskapai as $items => $item) {
            if ($i == $request->baris_id) {
                $data_rekon_maskapai_new[$items] = $request->data_edit;
                foreach ($item as $a_kunci => $a_isi) {
                    foreach ($request->data_edit as $b_kunci => $b_isi) {
                        if ($a_kunci == $b_kunci) {
                            if ($a_isi == $b_isi) {
                                $data_rekon_maskapai_ubah[$a_kunci] = $a_isi;
                            } else {
                                $data_rekon_maskapai_ubah[$a_kunci] = ' [ ' . $a_isi . ' => ' . $b_isi . ' ] ';
                            }
                        }
                    }
                }
            } else {
                $data_rekon_maskapai_new[$items] = $item;
            }
            $i++;
        }

        $akun = Maskapai::where('users_id', Auth::user()->id)->first();

        RiwayatRekon::create([
            'rekons_id' => $data_rekon->id,
            'proses' => 'Mengubah Data',
            'riwayat_ubah' => json_encode($data_rekon_maskapai_ubah),
            'akun_tipe' => 'maskapai',
            'akun_id' => $akun->id,
        ]);

        $data_rekon->update([
            'rekon_maskapai_text' => json_encode($data_rekon_maskapai_new)
        ]);

        return redirect()->back()->with('message', 'Data berhasil diubah');
    }

    public function bandingkan_hapus(Request $request, $id)
    {
        $data_rekon = Rekon::find($id);
        $i = 0;
        $data_rekon_maskapai = json_decode($data_rekon->rekon_maskapai_text, true);
        $data_rekon_maskapai_new = [];
        $data_yang_dihapus = '';
        foreach ($data_rekon_maskapai as $items => $item) {
            if ($i == $request->baris_id) {
                // $data_rekon_maskapai_new[$items] = $request->data_edit;
                $data_yang_dihapus = $item;
            } else {
                array_push($data_rekon_maskapai_new, $item);
            }
            $i++;
        }

        $akun = Maskapai::where('users_id', Auth::user()->id)->first();

        RiwayatRekon::create([
            'rekons_id' => $data_rekon->id,
            'proses' => 'Menghapus Data',
            'riwayat_ubah' => json_encode($data_yang_dihapus),
            'akun_tipe' => 'maskapai',
            'akun_id' => $akun->id,
        ]);


        $data_rekon->update([
            'rekon_maskapai_text' => json_encode($data_rekon_maskapai_new)
        ]);

        return redirect()->back()->with('message', 'Data berhasil diubah');
    }

    public function bandingkan_tambah(Request $request, $id)
    {
        $data_rekon = Rekon::find($id);
        $data_rekon_admin = json_decode($data_rekon->rekon_admin_text, true);
        $data_rekon_maskapai = json_decode($data_rekon->rekon_maskapai_text, true);
        $data_rekon_admin_add = [];
        $data_rekon_maskapai_new = [];

        $i = 0;
        foreach ($data_rekon_admin as $items => $item) {
            if ($i == $request->baris_id) {
                $data_rekon_admin_add = $item;
            }
            $i++;
        }
        $data_rekon_maskapai_new = $data_rekon_maskapai;
        array_push($data_rekon_maskapai_new, $data_rekon_admin_add);

        $akun = Maskapai::where('users_id', Auth::user()->id)->first();

        RiwayatRekon::create([
            'rekons_id' => $data_rekon->id,
            'proses' => 'Menambahkan Data',
            'riwayat_ubah' => json_encode($data_rekon_admin_add),
            'akun_tipe' => 'maskapai',
            'akun_id' => $akun->id,
        ]);

        $data_rekon->update([
            'rekon_maskapai_text' => json_encode($data_rekon_maskapai_new)
        ]);

        return redirect()->back()->with('message', 'Data berhasil diubah');
    }

    public function kirim($id)
    {

        $data_rekon = Rekon::find($id);
        $data_rekon->update([
            'maskapai_status' => '1'
        ]);

        return redirect()->route('maskapai.datarekon');
    }

    public function persetujuan($id)
    {
        $data_rekon = Rekon::find($id);
        $data_rekon->update([
            'maskapai_acc' => now()
        ]);

        return redirect()->route('maskapai.datarekon');
    }

    public function berita_acara($id)
    {

        $data_rekon = Rekon::find($id);
        $data_rekon->update([
            'status' => '1'
        ]);

        return redirect()->route('maskapai.datarekon', $data_rekon->users_id);
    }

    public function lihat_berita($id)
    {
        $data_rekon = Rekon::find($id);

        $data_a = $data_rekon->rekon_admin_text;
        $data_a = json_decode($data_a, true);

        return view('maskapai.datarekon.lihat_berita', [
            'data_rekon' => $data_rekon,
            'data_a' => $data_a
        ]);
    }

    public function lihat_berita_2($id)
    {
        $data_rekon = Rekon::find($id);

        $data_a = $data_rekon->rekon_admin_text;
        $data_a = json_decode($data_a, true);

        $produksi = [];
        foreach ($data_a as $items => $item) {
            foreach ($item as $item1 => $item2) {
                if (in_array($item2['B'], $produksi)) {
                } else {
                    array_push($produksi, $item2['B']);
                }
            }
        }

        $data_produksi = [];
        foreach ($produksi as $p => $p2) {
            if ($p != 0) {
                $data_produksi[$p2] = 0;
                foreach ($data_a as $items => $item) {
                    foreach ($item as $item1 => $item2) {
                        if ($item2['B'] == $p2) {
                            $data_produksi[$p2] += (int)$item2['L'];
                        }
                    }
                }
            }
        }

        return view('maskapai.datarekon.lihat_berita_2', [
            'data_rekon' => $data_rekon,
            'data_produksi' => $data_produksi
        ]);
    }

    // public function bandingkan($id)
    // {
    //     $data_rekon = Rekon::find($id);

    //     $data_a = $data_rekon->rekon_maskapai_text;
    //     $data_a = json_decode($data_a, true);

    //     $data_b = $data_rekon->rekon_admin_text;
    //     $data_b = json_decode($data_b, true);

    //     $kunci = 'G';

    //     $data_hasil = [];
    //     foreach ($data_a as $a1 => $a2) {
    //         $data_hasil[$a1] = [];
    //         foreach ($a2 as $a2a => $a2b) {
    //             $data_hasil[$a1][$a2a] = [];
    //             foreach ($data_b as $b1 => $b2) {
    //                 foreach ($b2 as $b2a => $b2b) {
    //                     if (in_array($a2b[$kunci], $b2b)) {
    //                         foreach ($a2b as $a2b_i => $a2b_data) {
    //                             if ($a2b_data == $b2b[$a2b_i]) {
    //                                 $data_hasil[$a1][$a2a][$a2b_i] = 'sama';
    //                                 // echo 'sama &nbsp;';
    //                             } else {
    //                                 $data_hasil[$a1][$a2a][$a2b_i] = 'tidak';
    //                                 // echo 'tidak &nbsp;';
    //                             }
    //                         }
    //                     }
    //                 }
    //                 // echo "<br/>";
    //             }
    //         }
    //     }


    //     return view('maskapai.datarekon.bandingkan', [
    //         'data_a' => $data_a,
    //         'data_b' => $data_b,
    //         'data_hasil' => $data_hasil,
    //         'kunci' => $kunci,
    //         'data_rekon' => $data_rekon,
    //     ]);
    // }
}
