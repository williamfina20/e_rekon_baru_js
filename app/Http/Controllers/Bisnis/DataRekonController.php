<?php

namespace App\Http\Controllers\Bisnis;

use App\Http\Controllers\Controller;
use App\Models\Bandara;
use App\Models\BeritaAcara;
use App\Models\Maskapai;
use App\Models\Rekon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DataRekonController extends Controller
{
    public function index()
    {
        $maskapai_pusat = User::where('level', 'maskapai_pusat')->get();
        return view('bisnis.datarekon.index', [
            'maskapai_pusat' => $maskapai_pusat
        ]);
    }

    public function maskapai($id)
    {
        $maskapai_pusat = User::find($id);
        $maskapai = Maskapai::where('maskapai_pusat_id', $id)->get();
        $rekon = Rekon::orderBy('bulan', 'ASC')->whereNotNull('admin_acc')->whereNotNull('maskapai_acc')->get();
        $rekon_baru = [];
        foreach ($maskapai as $item) {
            foreach ($rekon as $r) {
                if ($r->maskapai_id == $item->id) {
                    array_push($rekon_baru, $r);
                }
            }
        }
        $periode_jumlah = [];
        foreach ($rekon_baru as $rb) {
            if (in_array($rb->bulan, array_keys($periode_jumlah))) {
                $periode_jumlah[$rb->bulan] = $periode_jumlah[$rb->bulan] + 1;
            } else {
                $periode_jumlah[$rb->bulan] = 1;
            }
        }

        return view('bisnis.datarekon.maskapai', [
            'maskapai_pusat' => $maskapai_pusat,
            'periode_jumlah' => $periode_jumlah
        ]);
    }

    public function show(Request $request, $id)
    {
        $maskapai_pusat = User::find($id);
        $maskapai = Maskapai::where('maskapai_pusat_id', $id)->get();
        if (Session::get('bulan')) {
            $bulan = Session::get('bulan');
            $rekon = Rekon::where('bulan', $bulan)->whereNotNull('admin_acc')->whereNotNull('maskapai_acc')->get();
        } else {
            $bulan = $request->bulan;
            $rekon = Rekon::where('bulan', $bulan)->whereNotNull('admin_acc')->whereNotNull('maskapai_acc')->get();
        }

        $rekon_baru = [];

        foreach ($maskapai as $m) {
            foreach ($rekon as $r) {
                if ($m->id == $r->maskapai_id) {
                    array_push($rekon_baru, $r);
                }
            }
        }

        return view('bisnis.datarekon.show', [
            'maskapai_pusat' => $maskapai_pusat,
            'rekon' => $rekon_baru,
            'bulan' => $bulan
        ]);
    }

    public function tambah_invoice($id)
    {
        $data_rekon = Rekon::find($id);
        $data_maskapai = Maskapai::find($data_rekon->maskapai_id);
        return view('bisnis.datarekon.tambah_invoice', [
            'data_rekon' => $data_rekon,
            'data_maskapai' => $data_maskapai
        ]);
    }

    public function simpan_invoice(Request $request, $id)
    {

        $request->validate([
            'no_invoice' => 'required',
            'no_faktur_pajak' => 'required',
        ]);

        $data_rekon = Rekon::find($id);

        if ($request->tipe_invoice == 'one_invoice') {
            $data_rekon->update([
                'no_invoice' => $request->no_invoice,
                'no_faktur_pajak' => $request->no_faktur_pajak,
                'tanggal_invoice' => now(),
                'user_invoice' => Auth::user()->id,
            ]);
        } else {
            $maskapai_pusat_id = $data_rekon->maskapai->maskapai_pusat_id;
            $maskapai = Maskapai::where('maskapai_pusat_id', $maskapai_pusat_id)->get();
            foreach ($maskapai as $m) {
                DB::table('rekons')->where('maskapai_id', $m->id)->where('bulan', $data_rekon->bulan)->limit(1)->update(
                    [
                        'no_invoice' => $request->no_invoice,
                        'no_faktur_pajak' => $request->no_faktur_pajak,
                        'tanggal_invoice' => now(),
                        'user_invoice' => Auth::user()->id,
                    ]
                );
            }
        }

        // return redirect()->route('bisnis.datarekon.show', $data_rekon->maskapai->maskapai_pusat_id)->with(['bulan' => $data_rekon->bulan]);
        return redirect('/bisnis/datarekon/' . $data_rekon->maskapai->maskapai_pusat_id . '/show?bulan=' . $data_rekon->bulan)->with('message', 'Data berhasil disimpan');
    }

    public function one_invoice($id)
    {
        $data_rekon = Rekon::find($id);
        $data_maskapai = Maskapai::find($data_rekon->maskapai_id);
        return view('bisnis.datarekon.one_invoice', [
            'data_rekon' => $data_rekon,
            'data_maskapai' => $data_maskapai
        ]);
    }

    public function one_invoice_simpan(Request $request, $id)
    {

        $request->validate([
            'no_invoice' => 'required',
            'no_faktur_pajak' => 'required',
        ]);

        $data_rekon = Rekon::find($id);
        $maskapai_pusat_id = $data_rekon->maskapai->maskapai_pusat_id;
        $maskapai = Maskapai::where('maskapai_pusat_id', $maskapai_pusat_id)->get();

        foreach ($maskapai as $m) {
            $rekon = Rekon::where('maskapai_id', $m->id)->where('bulan', $data_rekon->bulan)->whereNotNull('admin_acc')->whereNotNull('maskapai_acc')->first();
            if ($rekon) {
                if (!$rekon->no_invoice) {
                    $rekon->update([
                        'no_invoice' => $request->no_invoice,
                        'no_faktur_pajak' => $request->no_faktur_pajak,
                        'tanggal_invoice' => now(),
                        'user_invoice' => Auth::user()->id,
                    ]);
                }
            }
        }


        // return redirect()->route('bisnis.datarekon.show', $data_rekon->maskapai->maskapai_pusat_id)->with(['bulan' => $data_rekon->bulan]);
        return redirect('/bisnis/datarekon/' . $data_rekon->maskapai->maskapai_pusat_id . '/show?bulan=' . $data_rekon->bulan)->with('message', 'Data berhasil disimpan');
    }

    public function multiple_invoice(Request $request, $id)
    {
        $data_rekon = Rekon::find($id);
        $data_maskapai = Maskapai::find($data_rekon->maskapai_id);

        $maskapai_pusat = User::find($data_maskapai->maskapai_pusat_id);
        $maskapai = Maskapai::where('maskapai_pusat_id', $maskapai_pusat->id)->get();

        $bulan = $request->bulan;
        $rekon = Rekon::where('bulan', $bulan)->whereNotNull('admin_acc')->whereNotNull('maskapai_acc')->get();

        $rekon_baru = [];

        foreach ($maskapai as $m) {
            foreach ($rekon as $r) {
                if ($m->id == $r->maskapai_id) {
                    array_push($rekon_baru, $r);
                }
            }
        }

        return view('bisnis.datarekon.multiple_invoice', [
            'maskapai_pusat' => $maskapai_pusat,
            'rekon' => $rekon_baru,
            'bulan' => $bulan,
            'data_rekon' => $data_rekon,

        ]);
    }

    public function multiple_invoice_simpan(Request $request, $id)
    {
        $no_invoice = $request->no_invoice;
        $no_faktur_pajak = $request->no_faktur_pajak;

        $jumlah_no_invoice = 0;
        foreach ($no_invoice as $item) {
            if ($item != null) {
                $jumlah_no_invoice++;
            }
        }

        if ($jumlah_no_invoice == 0) {
            return redirect()->back()->with('danger', 'Belum ada No Invoice yang diisi');
        }

        $data_rekon = Rekon::find($id);

        foreach ($no_invoice as $i => $item) {
            if ($item != null) {
                $rekon = Rekon::find($i);
                $rekon->update([
                    'no_invoice' => $item,
                    'no_faktur_pajak' => $no_faktur_pajak[$i],
                    'tanggal_invoice' => now(),
                    'user_invoice' => Auth::user()->id,
                ]);
            }
        }
        return redirect('/bisnis/datarekon/' . $data_rekon->maskapai->maskapai_pusat_id . '/show?bulan=' . $data_rekon->bulan)->with('message', 'Data berhasil disimpan');
    }

    // =============================================================

    public static function cek_berita_acara($id)
    {
        if (Auth::user()->level == 'bisnis') {
            $data_rekon = Rekon::find($id);

            $berita_acara = BeritaAcara::where('rekons_id', $data_rekon->id)->first();
            $semua_rekon_pada_bandara_yang_disetujui = Rekon::where('bandara_id', $data_rekon->bandara_id)->where('admin_status', '>=', 2)->whereNotNull('admin_acc')->whereNotNull('maskapai_acc')->get();
            $no_berita_acara = 0;
            foreach ($semua_rekon_pada_bandara_yang_disetujui as $items => $item) {
                if ($item->id == $data_rekon->id) {
                    $no_berita_acara = $items + 1;
                }
            }


            $data_rekon_admin = json_decode($data_rekon->rekon_admin_text, true);

            $produksi = [];
            foreach ($data_rekon_admin as  $item) {
                $i = 0;
                foreach ($item as $key => $isi) {
                    if ($i == 1) {
                        if (in_array($isi, $produksi)) {
                        } else {
                            array_push($produksi, $isi);
                        }
                    }
                    $i++;
                }
            }

            sort($produksi);

            $data_produksi = [];
            foreach ($produksi as $p => $p2) {
                $data_produksi[$p2] = 0;
                foreach ($data_rekon_admin as  $item) {
                    if (in_array($p2, $item)) {
                        $i = 0;
                        foreach ($item as $key => $isi) {
                            if ($i == 11) {
                                $data_produksi[$p2] += (int)$isi;
                            }
                            $i++;
                        }
                    }
                }
            }
            $format_berita_acara = 'ER.0' . $no_berita_acara . '/Hk.06.03/' . date('Y', strtotime($data_rekon->bulan)) . '/' . $data_rekon->bandara->kode_jabatan;
            $total_produksi = array_sum($data_produksi);

            return  [
                $format_berita_acara,
                $total_produksi
            ];
        } else {
            return ' ';
        }
    }
}
