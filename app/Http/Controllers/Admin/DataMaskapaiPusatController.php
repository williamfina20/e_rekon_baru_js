<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeritaAcara;
use App\Models\Maskapai;
use App\Models\MaskapaiStaf;
use App\Models\Rekon;
use App\Models\RiwayatRekon;
use App\Models\User;
use Illuminate\Http\Request;

class DataMaskapaiPusatController extends Controller
{
    public function index()
    {
        $data_maskapai_pusat = User::where('level', 'maskapai_pusat')->get();
        return view('admin.datamaskapaipusat.index', [
            'data_maskapai_pusat' => $data_maskapai_pusat
        ]);
    }

    public function create()
    {
        return view('admin.datamaskapaipusat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:users,name',
            'username' => 'required|unique:users,email',
            'password' => 'required|min:4',
            'nama_pimpinan' => 'required',
            'jabatan_pimpinan' => 'required',
            'dasar_hukum' => 'required',
            'harga' => 'required',
        ]);

        User::create([
            'name' => $request->nama,
            'email' => $request->username,
            'password' => bcrypt($request->password),
            'level' => 'maskapai_pusat',
            'nama_pimpinan' => $request->nama_pimpinan,
            'jabatan_pimpinan' => $request->jabatan_pimpinan,
            'dasar_hukum' => $request->dasar_hukum,
            'harga' => $request->harga,
        ]);

        return redirect()->route('admin.datamaskapaipusat')->with('message', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data_maskapai_pusat = User::find($id);
        return view('admin.datamaskapaipusat.edit', [
            'data_maskapai_pusat' => $data_maskapai_pusat
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|unique:users,name,' . $id,
            'username' => 'required|unique:users,email,' . $id,
            'nama_pimpinan' => 'required',
            'jabatan_pimpinan' => 'required',
            'dasar_hukum' => 'required',
            'harga' => 'required',
        ]);

        $user = User::find($id);

        if ($request->password) {
            $user->update([
                'name' => $request->nama,
                'email' => $request->username,
                'password' => bcrypt($request->password),
                'nama_pimpinan' => $request->nama_pimpinan,
                'jabatan_pimpinan' => $request->jabatan_pimpinan,
                'dasar_hukum' => $request->dasar_hukum,
                'harga' => $request->harga,
            ]);
        } else {
            $user->update([
                'name' => $request->nama,
                'email' => $request->username,
                'nama_pimpinan' => $request->nama_pimpinan,
                'jabatan_pimpinan' => $request->jabatan_pimpinan,
                'dasar_hukum' => $request->dasar_hukum,
                'harga' => $request->harga,
            ]);
        }

        return redirect()->route('admin.datamaskapaipusat')->with('message', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $maskapai_pusat = User::find($id);
        $maskapai = Maskapai::where('maskapai_pusat_id', $maskapai_pusat->id)->get();
        if ($maskapai) {
            foreach ($maskapai as $m => $m2) {
                User::where('id', $m2->users_id)->delete();
                $rekon = Rekon::where('maskapai_id', $m2->id)->get();
                if ($rekon) {
                    foreach ($rekon as $r) {
                        BeritaAcara::where('rekons_id', $r->id)->delete();
                        RiwayatRekon::where('rekons_id', $r->id)->delete();
                    }
                }
                Rekon::where('maskapai_id', $m2->id)->delete();
                $maskapai_staf = MaskapaiStaf::where('maskapai_id', $m2->id)->get();
                if ($maskapai_staf) {
                    foreach ($maskapai_staf as $ms => $ms2) {
                        User::where('id', $ms2->users_id)->delete();
                    }
                }
                MaskapaiStaf::where('maskapai_id', $m2->id)->delete();
                Maskapai::where('id', $m2->id)->delete();
            }
        }
        $maskapai_pusat->delete();

        return redirect()->back()->with('message', 'Data berhasil dihapus');
    }
}
