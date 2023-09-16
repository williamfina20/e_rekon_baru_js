<?php

namespace App\Http\Controllers;

use App\Models\Bandara;
use App\Models\BandaraStaf;
use App\Models\Maskapai;
use App\Models\MaskapaiStaf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanggilFungsiController extends Controller
{
    public static function tampil_akun_bandara_atau_maskapai($akun_tipe, $akun_id)
    {
        $tampil = '';
        if ($akun_tipe == 'bandara') {
            $akun = DB::table('bandara')->join('users', 'bandara.users_id', '=', 'users.id')->select('users.name')->where('bandara.id', $akun_id)->first();
            $tampil = '<b>' . $akun->name . '</b>';
        } elseif ($akun_tipe == 'bandara_staf') {
            $akun = BandaraStaf::find($akun_id);
            if ($akun->bandara) {
                $tampil = $akun->bandara->user->name  . '<br/><b>' . $akun->user->name . '</b>';
            } else {
                $tampil = '<b>' . $akun->user->name . '</b>';
            }
        } elseif ($akun_tipe == 'maskapai') {
            $akun = DB::table('maskapai')->join('users', 'maskapai.users_id', '=', 'users.id')->select('users.name')->where('maskapai.id', $akun_id)->first();
            $tampil = '<b>' . $akun->name . '</b>';
        } elseif ($akun_tipe == 'maskapai_staf') {
            $akun = MaskapaiStaf::find($akun_id);
            if ($akun->maskapai) {
                $tampil = $akun->maskapai->user->name  . '<br/><b>' . $akun->user->name . '</b>';
            } else {
                $tampil = '<b>' . $akun->user->name . '</b>';
            }
        } else {
            $tampil = '';
        }

        return $tampil;
    }

    public static function tampil_rekon_admin($id)
    {
        // $start_time = microtime(true);

        $data_rekon = DB::table('rekons')->where('id', $id)->first();
        $data_maskapai = DB::table('maskapai')->where('id', $data_rekon->maskapai_id)->first();

        $data_rekon_admin = json_decode($data_rekon->rekon_admin_text, true);
        $data_rekon_maskapai = json_decode($data_rekon->rekon_maskapai_text, true);

        $data_a = $data_rekon_admin;
        $data_b = $data_rekon_maskapai;

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

        // ===============================================================
        // $end_time = microtime(true);
        // echo $execution_time = ($end_time - $start_time);

        // ******************************************************************
        // Kode dari view bandingkan

        $jumlah_error_bandara = 0;

        foreach ($data_a as $a_items => $a_item) {
            if ($a_validasi_awb_tidak_ada[$a_items] == 'tidak') {
                $data_a[$a_items]['status_rekon'] = 'hapus';
                $jumlah_error_bandara++;
            } else {
                if ($a_validasi_awb_sama[$a_items] > 1) {
                    $data_a[$a_items]['status_rekon'] = 'sama';
                    $jumlah_error_bandara++;
                } else {
                    foreach ($data_b as $b_items => $b_item) {
                        if (in_array($a_item['AWB'], $b_item)) {
                            $jumlah_kolom_error = 0;
                            foreach ($a_item as $a_kunci => $a_isi) {
                                if ($a_isi != $b_item[$a_kunci]) {
                                    if ($a_kunci != 'NO') {
                                        $data_a[$a_items][$a_kunci] = $a_isi . '->' . $b_item[$a_kunci];
                                        $jumlah_kolom_error++;
                                    }
                                }
                            }
                            if ($jumlah_kolom_error > 0) {
                                $data_a[$a_items]['status_rekon'] = 'edit';
                                $jumlah_error_bandara++;
                            }
                        }
                    }
                }
            }
        }

        foreach ($data_b as $b_items => $b_item) {
            if (!in_array($b_item['AWB'], $tampung_kunci)) {
                $data_b[$b_items]['status_rekon'] = 'tambah';
                array_push($data_a, $data_b[$b_items]);
            }
        }

        return $data_a;
    }
}
