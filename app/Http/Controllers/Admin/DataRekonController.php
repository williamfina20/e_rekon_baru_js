<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\TesImport;
use App\Models\Bandara;
use App\Models\BeritaAcara;
use App\Models\Maskapai;
use App\Models\Rekon;
use App\Models\RiwayatRekon;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;

class DataRekonController extends Controller
{
    public function index()
    {
        $data_bandara = Bandara::all();
        return view('admin.datarekon.index', [
            'data_bandara' => $data_bandara
        ]);
    }

    public function maskapai($id)
    {
        $maskapai = Maskapai::where('bandara_id', $id)->get();
        $bandara = Bandara::find($id);
        return view('admin.datarekon.maskapai', [
            'maskapai' => $maskapai,
            'bandara' => $bandara
        ]);
    }

    public function show($id)
    {
        $data_maskapai = Maskapai::find($id);
        $data_rekon = Rekon::where('maskapai_id', $id)->orderBy('bulan', 'ASC')->get();
        return view('admin.datarekon.show', [
            'data_maskapai' => $data_maskapai,
            'data_rekon' => $data_rekon
        ]);
    }

    public function create($id)
    {
        $data_maskapai = Maskapai::find($id);
        return view('admin.datarekon.create', [
            'data_maskapai' => $data_maskapai
        ]);
    }

    public function store(Request $request, $id)
    {
        $data_maskapai = Maskapai::find($id);
        $request->validate([
            'bulan' => 'required|unique:rekons,bulan,' . $request->bulan . ',id,maskapai_id,' . $id,
            'rekon_admin' => 'required|mimes:xlsx',
            'rekon_maskapai' => 'required|mimes:xlsx',
        ]);
        // ==============================================
        $file_a = $request->file('rekon_admin');
        $reader_a = new ReaderXlsx();
        $spreadsheet_a = $reader_a->load($file_a);
        $sheet_a = $spreadsheet_a->getActiveSheet();

        $highestRow_a = $sheet_a->getHighestRow();
        $highestColumn_a = $sheet_a->getHighestColumn();

        $worksheetInfo_a = $reader_a->listWorksheetInfo($file_a);
        $totalRows_a = $worksheetInfo_a[0]['totalRows'];
        $data_sa = [];
        for ($row = 2; $row <= $highestRow_a; $row++) {
            for ($col = 'A'; $col <= $highestColumn_a; $col++) {
                // read the cell value and store it in the array
                $data_sa[$row][$col] = $sheet_a->getCell($col . $row)->getFormattedValue();
            }
        }
        $data_a = [];
        array_push($data_a, $data_sa);

        $d_a = json_encode($data_a);

        if ($request->file('rekon_admin')) {
            $path_a = $request->file('rekon_admin')->store('/data_rekon', 'public');
        } else {
            $path_a = '';
        }
        // ==============================================
        $file_b = $request->file('rekon_maskapai');
        $reader_b = new ReaderXlsx();
        $spreadsheet_b = $reader_b->load($file_b);
        $sheet_b = $spreadsheet_b->getActiveSheet();

        $highestRow_b = $sheet_b->getHighestRow();
        $highestColumn_b = $sheet_b->getHighestColumn();

        $worksheetInfo_b = $reader_b->listWorksheetInfo($file_b);
        $totalRows_b = $worksheetInfo_b[0]['totalRows'];
        $data_sa = [];
        for ($row = 2; $row <= $highestRow_b; $row++) {
            for ($col = 'A'; $col <= $highestColumn_b; $col++) {
                // read the cell value and store it in the array
                $data_sa[$row][$col] = $sheet_b->getCell($col . $row)->getFormattedValue();
            }
        }
        $data_b = [];
        array_push($data_b, $data_sa);

        $d_b = json_encode($data_b);

        if ($request->file('rekon_maskapai')) {
            $path_b = $request->file('rekon_maskapai')->store('/data_rekon', 'public');
        } else {
            $path_b = '';
        }
        // ==============================================

        Rekon::create([
            'bulan' => $request->bulan,
            'rekon_admin' => $path_a,
            'rekon_maskapai' => $path_b,
            'bandara_id' => $data_maskapai->bandara_id,
            'rekon_admin_text' => $d_a,
            'rekon_maskapai_text' => $d_b,
            'maskapai_id' => $id
        ]);

        return redirect()->route('admin.datarekon.show', $id)->with('message', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data_rekon = Rekon::find($id);
        $data_maskapai = Maskapai::find($data_rekon->maskapai_id);
        return view('admin.datarekon.edit', [
            'data_rekon' => $data_rekon,
            'data_maskapai' => $data_maskapai
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'rekon_admin' => 'mimes:xlsx',
            'rekon_maskapai' => 'mimes:xlsx',
        ]);

        $data_rekon = Rekon::find($id);
        // ======================================

        if ($request->file('rekon_admin')) {
            $file_a = $request->file('rekon_admin');
            $reader_a = new ReaderXlsx();
            $spreadsheet_a = $reader_a->load($file_a);
            $sheet_a = $spreadsheet_a->getActiveSheet();

            $highestRow_a = $sheet_a->getHighestRow();
            $highestColumn_a = $sheet_a->getHighestColumn();

            $worksheetInfo_a = $reader_a->listWorksheetInfo($file_a);
            $totalRows_a = $worksheetInfo_a[0]['totalRows'];
            $data_sa = [];
            for ($row = 2; $row <= $highestRow_a; $row++) {
                for ($col = 'A'; $col <= $highestColumn_a; $col++) {
                    // read the cell value and store it in the array
                    $data_sa[$row][$col] = $sheet_a->getCell($col . $row)->getFormattedValue();
                }
            }
            $data_a = [];
            array_push($data_a, $data_sa);

            $d_a = json_encode($data_a);

            Storage::delete('public/' . $data_rekon->rekon_admin);
            $path_a = $request->file('rekon_admin')->store('/data_rekon', 'public');
            $data_rekon->update([
                'rekon_admin' => $path_a,
                'rekon_admin_text' => $d_a,
            ]);
        } else {
            $path_a = '';
        }
        // ======================================

        if ($request->file('rekon_maskapai')) {
            $file_b = $request->file('rekon_maskapai');
            $reader_b = new ReaderXlsx();
            $spreadsheet_b = $reader_b->load($file_b);
            $sheet_b = $spreadsheet_b->getActiveSheet();

            $highestRow_b = $sheet_b->getHighestRow();
            $highestColumn_b = $sheet_b->getHighestColumn();

            $worksheetInfo_b = $reader_b->listWorksheetInfo($file_b);
            $totalRows_b = $worksheetInfo_b[0]['totalRows'];
            $data_sa = [];
            for ($row = 2; $row <= $highestRow_b; $row++) {
                for ($col = 'A'; $col <= $highestColumn_b; $col++) {
                    // read the cell value and store it in the array
                    $data_sa[$row][$col] = $sheet_b->getCell($col . $row)->getFormattedValue();
                }
            }
            $data_b = [];
            array_push($data_b, $data_sa);

            $d_b = json_encode($data_b);


            Storage::delete('public/' . $data_rekon->rekon_maskapai);
            $path_b = $request->file('rekon_maskapai')->store('/data_rekon', 'public');
            $data_rekon->update([
                'rekon_maskapai' => $path_b,
                'rekon_maskapai_text' => $d_b,
            ]);
        } else {
            $path_b = '';
        }
        // ======================================

        return redirect()->route('admin.datarekon.show', $data_rekon->maskapai_id)->with('message', 'Data berhasil diubah');
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

    // public function bandingkan($id)
    // {
    //     $data_rekon = Rekon::find($id);
    //     $data_maskapai = Maskapai::find($data_rekon->maskapai_id);

    //     $data_rekon_admin = json_decode($data_rekon->rekon_admin_text, true);
    //     $data_rekon_maskapai = json_decode($data_rekon->rekon_maskapai_text, true);

    //     $data_a = $data_rekon_admin;
    //     $data_b = $data_rekon_maskapai;

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
    //     // Untuk Maskapai

    //     $data_a = $data_rekon_maskapai;
    //     $data_b = $data_rekon_admin;

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


    //     $jumlah_error_maskapai = 0;
    //     foreach ($data_b as $b_items => $b_item) {
    //         if (!in_array($b_item['AWB'], $tampung_kunci_2)) {
    //             $jumlah_error_maskapai++;
    //         }
    //     }
    //     foreach ($data_a as $a_items => $a_item) {
    //         if ($b_validasi_awb_tidak_ada[$a_items] == 'tidak') {
    //             $jumlah_error_maskapai++;
    //         } else {
    //             if ($b_validasi_awb_sama[$a_items] > 1) {
    //                 $jumlah_error_maskapai++;
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
    //                             $jumlah_error_maskapai++;
    //                         }
    //                         break;
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     // ===============================================================

    //     $data_a = $data_rekon_admin;
    //     $data_b = $data_rekon_maskapai;

    //     $riwayat_rekon = RiwayatRekon::where('rekons_id', $id)->latest()->get();


    //     return view('admin.datarekon.bandingkan', [
    //         'data_rekon' => $data_rekon,
    //         'data_a' => $data_a,
    //         'data_b' => $data_b,
    //         'data_maskapai' => $data_maskapai,
    //         'a_validasi_awb_sama' =>  $a_validasi_awb_sama,
    //         'a_validasi_awb_tidak_ada' =>  $a_validasi_awb_tidak_ada,
    //         'tampung_kunci' => $tampung_kunci,
    //         'riwayat_rekon' => $riwayat_rekon,
    //         'jumlah_error_maskapai' => $jumlah_error_maskapai
    //     ]);
    // }
    public function bandingkan($id)
    {
        $data_rekon = Rekon::where('id', $id)->first();

        return view('admin.datarekon.bandingkan', [
            'data_rekon' => $data_rekon,
        ]);
    }
    public function persetujuan(Request $request, $id)
    {

        $data_rekon = Rekon::find($id);
        $data_rekon->update([
            'admin_status' => 2,
            'admin_pusat_acc' => now()
        ]);

        if ($data_rekon->maskapai_status == 2 and $data_rekon->admin_status == 2) {
            BeritaAcara::create([
                'rekons_id' => $id,
                'maskapai_nama_pimpinan' => $data_rekon->maskapai->nama_pimpinan,
                'maskapai_jabatan_pimpinan' => $data_rekon->maskapai->jabatan_pimpinan,
                'bandara_nama_pimpinan' => $data_rekon->bandara->nama_pimpinan,
                'bandara_jabatan_pimpinan' => $data_rekon->bandara->jabatan_pimpinan,
            ]);
        }

        return redirect()->route('admin.datarekon.show', $data_rekon->maskapai_id)->with('message', 'Data berhasil disimpan');
    }

    public function lihat_berita($id)
    {
        $data_rekon = Rekon::find($id);

        if (Auth::user()->level == 'bisnis') {
            if (!$data_rekon->no_invoice) {
                return '';
            }
        }

        $berita_acara = BeritaAcara::where('rekons_id', $data_rekon->id)->first();
        $semua_rekon_pada_bandara_yang_disetujui = Rekon::where('bandara_id', $data_rekon->bandara_id)->where('admin_acc', '!=', '')->where('maskapai_acc', '!=', '')->get();
        $no_berita_acara = 0;
        foreach ($semua_rekon_pada_bandara_yang_disetujui as $items => $item) {
            if ($item->id == $data_rekon->id) {
                $no_berita_acara = $items + 1;
            }
        }

        return view('admin.datarekon.lihat_berita', [
            'data_rekon' => $data_rekon,
            'berita_acara' => $berita_acara,
            'no_berita_acara' => $no_berita_acara
        ]);
    }

    public function lihat_berita_2($id)
    {
        $data_rekon = Rekon::find($id);

        if (Auth::user()->level == 'bisnis') {
            if (!$data_rekon->no_invoice) {
                return '';
            }
        }

        $berita_acara = BeritaAcara::where('rekons_id', $data_rekon->id)->first();
        $semua_rekon_pada_bandara_yang_disetujui = Rekon::where('bandara_id', $data_rekon->bandara_id)->where('admin_acc', '!=', '')->where('maskapai_acc', '!=', '')->get();
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

        return view('admin.datarekon.lihat_berita_2', [
            'data_rekon' => $data_rekon,
            'berita_acara' => $berita_acara,
            'data_produksi' => $data_produksi,
            'no_berita_acara' => $no_berita_acara
        ]);
    }

    public function edit_invoice($id)
    {
        $data_rekon = Rekon::find($id);
        $data_maskapai = Maskapai::find($data_rekon->maskapai_id);
        return view('admin.datarekon.edit_invoice', [
            'data_rekon' => $data_rekon,
            'data_maskapai' => $data_maskapai
        ]);
    }

    public function update_invoice(Request $request, $id)
    {

        $request->validate([
            'no_invoice' => 'required',
            'no_faktur_pajak' => 'required',
        ]);

        $data_rekon = Rekon::find($id);

        $data_rekon->update([
            'no_invoice' => $request->no_invoice,
            'no_faktur_pajak' => $request->no_faktur_pajak,
        ]);

        return redirect()->route('admin.datarekon.show', $data_rekon->maskapai_id)->with('message', 'Data berhasil disimpan');
    }

    public function kode_bandara($id)
    {
        $data_rekon = Rekon::find($id);
        $riwayat_rekon = RiwayatRekon::where('rekons_id', $id)->latest()->get();

        $berita_acara = BeritaAcara::where('rekons_id', $data_rekon->id)->first();
        $semua_rekon_pada_bandara_yang_disetujui = Rekon::where('bandara_id', $data_rekon->bandara_id)->where('admin_acc', '!=', '')->where('maskapai_acc', '!=', '')->get();
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

        return view('admin.datarekon.kode_bandara', [
            'data_rekon' => $data_rekon,
            'berita_acara' => $berita_acara,
            'data_produksi' => $data_produksi,
            'no_berita_acara' => $no_berita_acara,
            'riwayat_rekon' => $riwayat_rekon,
        ]);
    }

    public function kode_maskapai($id)
    {
        $data_rekon = Rekon::find($id);
        $riwayat_rekon = RiwayatRekon::where('rekons_id', $id)->latest()->get();

        $berita_acara = BeritaAcara::where('rekons_id', $data_rekon->id)->first();
        $semua_rekon_pada_bandara_yang_disetujui = Rekon::where('bandara_id', $data_rekon->bandara_id)->where('admin_acc', '!=', '')->where('maskapai_acc', '!=', '')->get();
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

        return view('admin.datarekon.kode_maskapai', [
            'data_rekon' => $data_rekon,
            'berita_acara' => $berita_acara,
            'data_produksi' => $data_produksi,
            'no_berita_acara' => $no_berita_acara,
            'riwayat_rekon' => $riwayat_rekon,
        ]);
    }
}
