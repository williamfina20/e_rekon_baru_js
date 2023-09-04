<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bandara;
use App\Models\BeritaAcara;
use App\Models\Maskapai;
use App\Models\MaskapaiStaf;
use App\Models\Rekon;
use App\Models\RiwayatRekon;
use App\Models\User;
use Illuminate\Http\Request;
use PDO;

class DataMaskapaiController extends Controller
{
    public function index($id)
    {
        $maskapai_pusat = User::find($id);
        $data_maskapai = Maskapai::where('maskapai_pusat_id', $maskapai_pusat->id)->get();
        return view('admin.datamaskapai.index', [
            'maskapai_pusat' => $maskapai_pusat,
            'data_maskapai' => $data_maskapai,
        ]);
    }

    // public function view($id)
    // {
    //     $data_maskapai = Maskapai::where('bandara_id', $id)->get();
    //     $bandara = Bandara::find($id);
    //     return view('admin.datamaskapai.view', [
    //         'data_maskapai' => $data_maskapai,
    //         'bandara' => $bandara
    //     ]);
    // }

    public function create($id)
    {
        $maskapai_pusat = User::find($id);
        $data_maskapai = Maskapai::where('maskapai_pusat_id', $maskapai_pusat->id)->get();
        $bandara = Bandara::all();
        $data_bandara = [];
        foreach ($bandara as $b) {
            $array_maskapai = [];
            foreach ($data_maskapai as $dm) {
                array_push($array_maskapai, $dm->bandara_id);
            }
            if (!in_array($b->id, $array_maskapai)) {
                array_push($data_bandara, $b);
            }
        }

        return view('admin.datamaskapai.create', [
            'maskapai_pusat' => $maskapai_pusat,
            'data_maskapai' => $data_maskapai,
            'data_bandara' => $data_bandara,
        ]);
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'bandara' => 'required',
            'nama_pimpinan' => 'required',
            'jabatan_pimpinan' => 'required',
            'kode_jabatan' => 'required',
            // 'alamat' => 'required',
            // 'no_telepon' => 'required',
            // 'email' => 'required',
            'username' => 'required|unique:users,email',
            'password' => 'required|min:4',
        ]);

        $maskapai_pusat = User::find($id);

        $user = User::create([
            'name' => $maskapai_pusat->name,
            'email' => $request->username,
            'password' => bcrypt($request->password),
            'level' => 'maskapai',
        ]);

        Maskapai::create([
            'nama_pimpinan' => $request->nama_pimpinan,
            'jabatan_pimpinan' => $request->jabatan_pimpinan,
            'kode_jabatan' => $request->kode_jabatan,
            // 'alamat' => $request->kode_jabatan,
            // 'no_telepon' => $request->no_telepon,
            // 'email' => $request->email,
            'bandara_id' => $request->bandara,
            'users_id' => $user->id,
            'maskapai_pusat_id' => $maskapai_pusat->id,
        ]);

        return redirect()->route('admin.datamaskapai', $id)->with('message', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data_maskapai = Maskapai::find($id);
        $maskapai_pusat = User::find($data_maskapai->maskapai_pusat_id);
        $data_maskapai_cek = Maskapai::where('maskapai_pusat_id', $maskapai_pusat->id)->get();
        $bandara = Bandara::all();
        $data_bandara = [];
        foreach ($bandara as $b) {
            $array_maskapai = [];
            foreach ($data_maskapai_cek as $dm) {
                array_push($array_maskapai, $dm->bandara_id);
            }
            if (!in_array($b->id, $array_maskapai)) {
                array_push($data_bandara, $b);
            }
        }

        if (!$data_bandara) {
            array_push($data_bandara, Bandara::find($data_maskapai->bandara_id));
        }

        return view('admin.datamaskapai.edit', [
            'maskapai_pusat' => $maskapai_pusat,
            'data_maskapai' => $data_maskapai,
            'data_bandara' => $data_bandara,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data_maskapai = Maskapai::find($id);
        $maskapai_pusat = User::find($data_maskapai->maskapai_pusat_id);
        $user = User::find($data_maskapai->users_id);

        $request->validate([
            'bandara' => 'required',
            'nama_pimpinan' => 'required',
            'jabatan_pimpinan' => 'required',
            'kode_jabatan' => 'required',
            // 'alamat' => 'required',
            // 'no_telepon' => 'required',
            // 'email' => 'required',
            'username' => 'required|min:4|unique:users,email,' . $data_maskapai->users_id,
        ]);

        $data_maskapai->update([
            'nama_pimpinan' => $request->nama_pimpinan,
            'jabatan_pimpinan' => $request->jabatan_pimpinan,
            'kode_jabatan' => $request->kode_jabatan,
            // 'alamat' => $request->kode_jabatan,
            // 'no_telepon' => $request->no_telepon,
            // 'email' => $request->email,
            'bandara_id' => $request->bandara,
        ]);

        if ($request->password) {
            $user->update([
                'name' => $maskapai_pusat->name,
                'email' => $request->username,
                'password' => bcrypt($request->password),
            ]);
        } else {
            $user->update([
                'name' => $maskapai_pusat->name,
                'email' => $request->username,
            ]);
        }

        return redirect()->route('admin.datamaskapai', $maskapai_pusat->id)->with('message', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $data_maskapai = Maskapai::find($id);

        $user = User::find($data_maskapai->users_id);

        $rekon = Rekon::where('maskapai_id', $id)->get();
        if ($rekon) {
            foreach ($rekon as $r) {
                BeritaAcara::where('rekons_id', $r->id)->delete();
                RiwayatRekon::where('rekons_id', $r->id)->delete();
            }
        }
        Rekon::where('maskapai_id', $id)->delete();

        MaskapaiStaf::where('maskapai_id', $data_maskapai->id)->delete();
        $user->delete();
        $data_maskapai->delete();

        return redirect()->back()->with('message', 'Data berhasil dihapus');
    }
}
