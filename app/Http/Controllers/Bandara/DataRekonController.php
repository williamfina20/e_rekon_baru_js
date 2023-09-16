<?php

namespace App\Http\Controllers\Bandara;

use App\Http\Controllers\Controller;
use App\Models\Bandara;
use App\Models\BeritaAcara;
use App\Models\Maskapai;
use App\Models\Rekon;
use App\Models\RiwayatRekon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;

class DataRekonController extends Controller
{
    public function index()
    {
        $data_maskapai = Maskapai::where('bandara_id', Auth::user()->bandara->id)->get();

        return view('bandara.datarekon.index', [
            'data_maskapai' => $data_maskapai
        ]);
    }

    public function show($id)
    {
        $data_rekon = Rekon::where('maskapai_id', $id)->orderBy('bulan', 'ASC')->get();
        $data_maskapai = Maskapai::find($id);
        return view('bandara.datarekon.show', [
            'data_rekon' => $data_rekon,
            'data_maskapai' => $data_maskapai
        ]);
    }

    public function create($id)
    {
        $data_maskapai = Maskapai::find($id);

        return view('bandara.datarekon.create', [
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

        DB::table('rekons')->insert([
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
        return view('bandara.datarekon.edit', [
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

        if ($data_rekon) {
            BeritaAcara::where('rekons_id', $id)->delete();
            RiwayatRekon::where('rekons_id', $id)->delete();
        }

        $data_rekon->delete();

        return redirect()->back()->with('message', 'Data berhasil dihapus');
    }

    public function bandingkan($id)
    {
        $data_rekon = Rekon::where('id', $id)->first();

        return view('bandara.datarekon.bandingkan_2', [
            'data_rekon' => $data_rekon,
        ]);
    }

    public function kirim($id)
    {

        $data_rekon = Rekon::find($id);
        $data_rekon->update([
            'admin_status' => '1'
        ]);

        return redirect()->route('bandara.datarekon.show', $data_rekon->maskapai_id);
    }

    public function persetujuan($id)
    {

        $data_rekon = Rekon::find($id);
        $data_rekon->update([
            'admin_acc' => now()
        ]);

        return redirect()->route('bandara.datarekon.show', $data_rekon->maskapai_id);
    }

    public function berita_acara($id)
    {

        $data_rekon = Rekon::find($id);
        $data_rekon->update([
            'status' => '1'
        ]);

        return redirect()->route('bandara.datarekon.show', $data_rekon->maskapai_id);
    }

    public function lihat_berita($id)
    {
        $data_rekon = Rekon::find($id);

        $data_a = $data_rekon->rekon_admin_text;
        $data_a = json_decode($data_a, true);

        return view('bandara.datarekon.lihat_berita', [
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

        return view('bandara.datarekon.lihat_berita_2', [
            'data_rekon' => $data_rekon,
            'data_produksi' => $data_produksi
        ]);
    }

    public function cek_rekon_admin($id)
    {
        $rekon = Rekon::find($id);
        return response()->json([
            'pesan' => 'berhasil',
            'rekon_admin_text' => $rekon->rekon_admin_text
        ]);
    }
}
