<?php

namespace App\Http\Controllers;

use App\Models\Bandara;
use App\Models\BandaraStaf;
use App\Models\Maskapai;
use App\Models\MaskapaiStaf;
use Illuminate\Http\Request;

class PanggilFungsiController extends Controller
{
    public static function tampil_akun_bandara_atau_maskapai($akun_tipe, $akun_id)
    {
        $tampil = '';
        if ($akun_tipe == 'bandara') {
            $akun = Bandara::find($akun_id);
            $tampil = '<b>' . $akun->user->name . '</b>';
        } elseif ($akun_tipe == 'bandara_staf') {
            $akun = BandaraStaf::find($akun_id);
            if ($akun->bandara) {
                $tampil = $akun->bandara->user->name  . '<br/><b>' . $akun->user->name . '</b>';
            } else {
                $tampil = '<b>' . $akun->user->name . '</b>';
            }
        } elseif ($akun_tipe == 'maskapai') {
            $akun = Maskapai::find($akun_id);
            $tampil = '<b>' . $akun->user->name . '</b>';
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
}
