<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BandaraStaf;
use App\Models\BeritaAcara;
use App\Models\Maskapai;
use App\Models\MaskapaiStaf;
use App\Models\Rekon;
use App\Models\RiwayatRekon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekonMaskapaiController extends Controller
{
    public function api_bandingkan_maskapai($id)
    {
        // $start_time = microtime(true);

        $data_rekon = DB::table('rekons')->where('id', $id)->first();
        $data_maskapai = DB::table('maskapai')->where('id', $data_rekon->maskapai_id)->first();

        $data_rekon_admin = json_decode($data_rekon->rekon_admin_text, true);
        $data_rekon_maskapai = json_decode($data_rekon->rekon_maskapai_text, true);

        $data_a = $data_rekon_maskapai;
        $data_b = $data_rekon_admin;

        $tampung_kunci = [];
        $a_validasi_awb_sama = [];
        $a_validasi_awb_tidak_ada = [];

        foreach ($data_a as $a_items => $a_item) {
            // ====cek data awb yang tidak ada
            foreach ($data_b as $b_items => $b_item) {
                if ($a_item['AWB'] == $b_item['AWB']) {
                    array_push($tampung_kunci, $a_item['AWB']);
                    break;
                }
            }
            if (in_array($a_item['AWB'], $tampung_kunci)) {
                $a_validasi_awb_tidak_ada[$a_items] = 'ada';
            } else {
                $a_validasi_awb_tidak_ada[$a_items] = 'tidak';
            }

            // ====cek data awb yang sama
            $i = 0;
            foreach ($data_a as $a2_items => $a2_item) {
                if ($a_item['AWB'] == $a2_item['AWB']) {
                    $i++;
                }
            }
            $a_validasi_awb_sama[$a_items] = $i;
        }

        // ******************************************************************
        // Kode dari view bandingkan

        $jumlah_error_maskapai = 0;

        foreach ($data_a as $a_items => $a_item) {
            if ($a_validasi_awb_tidak_ada[$a_items] == 'tidak') {
                $data_a[$a_items]['status_rekon'] = 'hapus';
                $data_a[$a_items]['baris_id'] = $a_items;
                $jumlah_error_maskapai++;
            } else {
                if ($a_validasi_awb_sama[$a_items] > 1) {
                    $data_a[$a_items]['status_rekon'] = 'sama';
                    $data_a[$a_items]['baris_id'] = $a_items;
                    $jumlah_error_maskapai++;
                } else {
                    foreach ($data_b as $b_items => $b_item) {
                        if (in_array($a_item['AWB'], $b_item)) {
                            $jumlah_kolom_error = 0;
                            foreach ($a_item as $a_kunci => $a_isi) {
                                if ($a_isi != $b_item[$a_kunci]) {
                                    if ($a_kunci != 'NO') {
                                        $data_a[$a_items][$a_kunci] = $a_isi . ' => ' . $b_item[$a_kunci];
                                        $jumlah_kolom_error++;
                                    }
                                }
                            }
                            if ($jumlah_kolom_error > 0) {
                                $data_a[$a_items]['status_rekon'] = 'edit';
                                $data_a[$a_items]['baris_id'] = $a_items;
                                $jumlah_error_maskapai++;
                            } else {
                                $data_a[$a_items]['status_rekon'] = '';
                                $data_a[$a_items]['baris_id'] = $a_items;
                            }
                        }
                    }
                }
            }
        }

        foreach ($data_b as $b_items => $b_item) {
            if (!in_array($b_item['AWB'], $tampung_kunci)) {
                $data_b[$b_items]['status_rekon'] = 'tambah';
                $data_b[$b_items]['baris_id'] = $b_items;
                array_push($data_a, $data_b[$b_items]);
                $jumlah_error_maskapai++;
            }
        }

        // dd($data_a);

        // ******************************************************************

        return response()->json([
            'pesan' => 'berhasil',
            'data_rekon' => $data_rekon,
            'data_rekon_text' => $data_a,
            'jumlah_error_maskapai' => $jumlah_error_maskapai,
        ]);
    }

    public function api_riwayat_rekon($id)
    {
        $riwayat_rekon = DB::table('riwayat_rekons')->where('rekons_id', $id)->latest()->get();

        foreach ($riwayat_rekon as $i => $rr) {
            if ($rr->akun_tipe == 'bandara') {
                $akun = DB::table('bandara')->join('users', 'bandara.users_id', '=', 'users.id')->select('users.name')->where('bandara.id', $rr->akun_id)->first();
                $riwayat_rekon[$i]->user =  $akun->name;
            } elseif ($rr->akun_tipe == 'bandara_staf') {
                $akun = BandaraStaf::find($rr->akun_id);
                if ($akun->bandara) {
                    $riwayat_rekon[$i]->user = $akun->bandara->user->name  . ' (' . $akun->user->name . ')';
                } else {
                    $riwayat_rekon[$i]->user =  $akun->user->name;
                }
            } elseif ($rr->akun_tipe == 'maskapai') {
                $akun = DB::table('maskapai')->join('users', 'maskapai.users_id', '=', 'users.id')->select('users.name')->where('maskapai.id', $rr->akun_id)->first();
                $riwayat_rekon[$i]->user =  $akun->name;
            } elseif ($rr->akun_tipe == 'maskapai_staf') {
                $akun = MaskapaiStaf::find($rr->akun_id);
                if ($akun->maskapai) {
                    $riwayat_rekon[$i]->user = $akun->maskapai->user->name  . ' (' . $akun->user->name . ')';
                } else {
                    $riwayat_rekon[$i]->user =  $akun->user->name;
                }
            } else {
                $riwayat_rekon[$i]->user = '';
            }
        }

        return response()->json([
            'pesan' => 'berhasil',
            'riwayat_rekon' => $riwayat_rekon
        ]);
    }

    public function api_error_bandara($id)
    {
        // ===============================================================
        // Untuk Admin

        $data_rekon = DB::table('rekons')->where('id', $id)->first();
        $data_admin = DB::table('bandara')->where('id', $data_rekon->bandara_id)->first();

        $data_rekon_maskapai = json_decode($data_rekon->rekon_maskapai_text, true);
        $data_rekon_admin = json_decode($data_rekon->rekon_admin_text, true);

        $data_a2 = $data_rekon_admin;
        $data_b2 = $data_rekon_maskapai;

        $tampung_kunci_2 = [];
        $b_validasi_awb_sama = [];
        $b_validasi_awb_tidak_ada = [];

        // ====cek data awb yang tidak ada
        foreach ($data_a2 as $a_items => $a_item) {
            foreach ($data_b2 as $b_items => $b_item) {
                if ($a_item['AWB'] == $b_item['AWB']) {
                    array_push($tampung_kunci_2, $a_item['AWB']);
                }
            }
        }
        foreach ($data_a2 as $a_items => $a_item) {
            if (in_array($a_item['AWB'], $tampung_kunci_2)) {
                $b_validasi_awb_tidak_ada[$a_items] = 'ada';
            } else {
                $b_validasi_awb_tidak_ada[$a_items] = 'tidak';
            }
        }

        // ====cek data awb yang sama
        foreach ($data_a2 as $a_items => $a_item) {
            $i = 0;
            foreach ($data_a2 as $a2_items => $a2_item) {
                if ($a_item['AWB'] == $a2_item['AWB']) {
                    $i++;
                }
            }
            $b_validasi_awb_sama[$a_items] = $i;
        }


        $jumlah_error_admin = 0;
        foreach ($data_b2 as $b_items => $b_item) {
            if (!in_array($b_item['AWB'], $tampung_kunci_2)) {
                $jumlah_error_admin++;
                break;
            }
        }
        foreach ($data_a2 as $a_items => $a_item) {
            if ($b_validasi_awb_tidak_ada[$a_items] == 'tidak') {
                $jumlah_error_admin++;
                break;
            } else {
                if ($b_validasi_awb_sama[$a_items] > 1) {
                    $jumlah_error_admin++;
                    break;
                } else {
                    foreach ($data_b2 as $b_items => $b_item) {
                        if (in_array($a_item['AWB'], $b_item)) {
                            $jumlah_kolom_error = 0;
                            foreach ($a_item as $a_kunci => $a_isi) {
                                if ($a_isi == $b_item[$a_kunci]) {
                                } else {
                                    if ($a_kunci != 'NO') {
                                        $jumlah_kolom_error++;
                                        break;
                                    }
                                }
                            }
                            if ($jumlah_kolom_error > 0) {
                                $jumlah_error_admin++;
                                break;
                            }
                            break;
                        }
                    }
                }
            }
        }

        return response()->json([
            'pesan' => 'berhasil',
            'jumlah_error_admin' => $jumlah_error_admin
        ]);
        // ===============================================================
    }

    public function api_bandingkan_maskapai_tambah(Request $request, $id)
    {
        $data_rekon = Rekon::find($id);
        $baris_id = $request->baris_id;
        $user_id = $request->user_id;
        $user_tipe = $request->user_tipe;

        $data_rekon_admin = json_decode($data_rekon->rekon_admin_text, true);
        $data_rekon_maskapai = json_decode($data_rekon->rekon_maskapai_text, true);
        $data_rekon_maskapai_new = [];
        $data_rekon_admin_add = [];

        $i = 0;
        foreach ($data_rekon_admin as $items => $item) {
            if ($i == $baris_id) {
                $data_rekon_admin_add = $item;
            }
            $i++;
        }
        $data_rekon_maskapai_new = $data_rekon_maskapai;
        array_push($data_rekon_maskapai_new, $data_rekon_admin_add);

        if ($user_tipe == 'maskapai') {
            $akun = Maskapai::where('users_id', $user_id)->first();
        } else {
            $akun = MaskapaiStaf::where('users_id', $user_id)->first();
        }

        RiwayatRekon::create([
            'rekons_id' => $data_rekon->id,
            'proses' => 'Menambahkan Data',
            'riwayat_ubah' => json_encode($data_rekon_admin_add),
            'akun_tipe' => $user_tipe,
            'akun_id' => $akun->id,
        ]);

        $data_rekon->update([
            'rekon_maskapai_text' => json_encode($data_rekon_maskapai_new)
        ]);

        return response()->json([
            'pesan' => 'berhasil',
        ]);
    }

    public function api_bandingkan_maskapai_hapus(Request $request, $rekon_id)
    {
        $data_rekon = Rekon::find($rekon_id);
        $baris_id = $request->baris_id;
        $user_id = $request->user_id;
        $user_tipe = $request->user_tipe;

        $i = 0;
        $data_rekon_maskapai = json_decode($data_rekon->rekon_maskapai_text, true);
        $data_rekon_maskapai_new = [];
        $data_yang_dihapus = '';
        foreach ($data_rekon_maskapai as $items => $item) {
            if ($i == $baris_id) {
                $data_yang_dihapus = $item;
            } else {
                array_push($data_rekon_maskapai_new, $item);
            }
            $i++;
        }

        if ($user_tipe == 'maskapai') {
            $akun = Maskapai::where('users_id', $user_id)->first();
        } else {
            $akun = MaskapaiStaf::where('users_id', $user_id)->first();
        }


        RiwayatRekon::create([
            'rekons_id' => $data_rekon->id,
            'proses' => 'Menghapus Data',
            'riwayat_ubah' => json_encode($data_yang_dihapus),
            'akun_tipe' => $user_tipe,
            'akun_id' => $akun->id,
        ]);

        $data_rekon->update([
            'rekon_maskapai_text' => json_encode($data_rekon_maskapai_new, true)
        ]);

        return response()->json([
            'pesan' => 'berhasil',
        ]);
    }

    public function api_bandingkan_maskapai_edit(Request $request, $id)
    {
        $data_rekon = Rekon::find($id);
        $baris_id = $request->baris_id;
        $user_id = $request->user_id;
        $user_tipe = $request->user_tipe;
        $form_edit = $request->form_edit;


        $i = 0;
        $data_rekon_maskapai = json_decode($data_rekon->rekon_maskapai_text, true);
        $data_rekon_maskapai_new = [];
        $data_rekon_maskapai_ubah = [];

        unset($form_edit['status_rekon']);
        unset($form_edit['baris_id']);

        foreach ($data_rekon_maskapai as $items => $item) {
            if ($items == $baris_id) {
                $data_rekon_maskapai_new[$items] = $form_edit;
                foreach ($item as $a_kunci => $a_isi) {
                    foreach ($form_edit as $b_kunci => $b_isi) {
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

        if ($user_tipe == 'maskapai') {
            $akun = Maskapai::where('users_id', $user_id)->first();
        } else {
            $akun = MaskapaiStaf::where('users_id', $user_id)->first();
        }


        RiwayatRekon::create([
            'rekons_id' => $data_rekon->id,
            'proses' => 'Mengubah Data',
            'riwayat_ubah' => json_encode($data_rekon_maskapai_ubah),
            'akun_tipe' => $user_tipe,
            'akun_id' => $akun->id,
        ]);

        $data_rekon->update([
            'rekon_maskapai_text' => json_encode($data_rekon_maskapai_new)
        ]);


        return response()->json([
            'pesan' => 'berhasil',
            'form_edit' => $form_edit,
        ]);
    }

    public function api_kirim_rekon($id)
    {

        $data_rekon = Rekon::find($id);
        $data_rekon->update([
            'maskapai_status' => '1'
        ]);

        return response()->json([
            'pesan' => 'berhasil',
        ]);
    }

    public function api_persetujuan_pusat($id)
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

        return response()->json([
            'pesan' => 'berhasil',
        ]);
    }

    public function api_persetujuan_daerah($id)
    {

        $data_rekon = Rekon::find($id);
        $data_rekon->update([
            'maskapai_acc' => now()
        ]);

        return response()->json([
            'pesan' => 'berhasil',
        ]);
    }
}
