<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bandara;
use App\Models\BandaraStaf;
use App\Models\BeritaAcara;
use App\Models\Rekon;
use App\Models\RiwayatRekon;
use App\Models\User;
use Illuminate\Http\Request;

class DataBandaraController extends Controller
{
    public function index()
    {
        $data_bandara = Bandara::all();
        return view('admin.databandara.index', [
            'data_bandara' => $data_bandara
        ]);
    }

    public function create()
    {
        return view('admin.databandara.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:users,name',
            'kode_bandara' => 'required',
            'wilayah' => 'required',
            'username' => 'required|unique:users,email',
            'password' => 'required|min:4',
            'nama_pimpinan' => 'required',
            'jabatan_pimpinan' => 'required',
            'kode_jabatan' => 'required',
            'alamat' => 'required',
            'no_telepon' => 'required',
            'email' => 'required',
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->username,
            'password' => bcrypt($request->password),
            'level' => 'bandara',
        ]);

        Bandara::create([
            'kode_bandara' => $request->kode_bandara,
            'wilayah' => $request->wilayah,
            'nama_pimpinan' => $request->nama_pimpinan,
            'jabatan_pimpinan' => $request->jabatan_pimpinan,
            'kode_jabatan' => $request->kode_jabatan,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'email' => $request->email,
            'users_id' => $user->id,
        ]);

        return redirect()->route('admin.databandara')->with('message', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data_bandara = Bandara::find($id);
        return view('admin.databandara.edit', [
            'data_bandara' => $data_bandara
        ]);
    }

    public function update(Request $request, $id)
    {
        $data_bandara = Bandara::find($id);
        $user = User::find($data_bandara->users_id);

        $request->validate([
            'nama' => 'required|unique:users,name,' . $user->id,
            'username' => 'required|unique:users,email,' . $user->id,
            'kode_bandara' => 'required',
            'wilayah' => 'required',
            'nama_pimpinan' => 'required',
            'jabatan_pimpinan' => 'required',
            'kode_jabatan' => 'required',
            'alamat' => 'required',
            'no_telepon' => 'required',
            'email' => 'required',
        ]);

        $data_bandara->update([
            'kode_bandara' => $request->kode_bandara,
            'wilayah' => $request->wilayah,
            'nama_pimpinan' => $request->nama_pimpinan,
            'jabatan_pimpinan' => $request->jabatan_pimpinan,
            'kode_jabatan' => $request->kode_jabatan,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'email' => $request->email,
        ]);
        if ($request->password) {
            $user->update([
                'name' => $request->nama,
                'email' => $request->username,
                'password' => bcrypt($request->password),
            ]);
        } else {
            $user->update([
                'name' => $request->nama,
                'email' => $request->username,
            ]);
        }

        return redirect()->route('admin.databandara')->with('message', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $data_bandara = Bandara::find($id);

        $bandara_staf = BandaraStaf::where('bandara_id', $id)->get();
        if ($bandara_staf) {
            foreach ($bandara_staf as $bs => $bs2) {
                User::where('id', $bs2->users_id)->delete();
            }
        }
        BandaraStaf::where('bandara_id', $id)->delete();

        $rekon = Rekon::where('bandara_id', $id)->get();
        if ($rekon) {
            foreach ($rekon as $r) {
                BeritaAcara::where('rekons_id', $r->id)->delete();
                RiwayatRekon::where('rekons_id', $r->id)->delete();
            }
        }
        Rekon::where('bandara_id', $id)->delete();

        $user = User::find($data_bandara->users_id);
        $user->delete();
        $data_bandara->delete();

        return redirect()->route('admin.databandara')->with('message', 'Data berhasil dihapus');
    }
}
