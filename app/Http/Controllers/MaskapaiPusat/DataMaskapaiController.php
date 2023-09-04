<?php

namespace App\Http\Controllers\MaskapaiPusat;

use App\Http\Controllers\Controller;
use App\Models\Bandara;
use App\Models\BeritaAcara;
use App\Models\Maskapai;
use App\Models\MaskapaiStaf;
use App\Models\Rekon;
use App\Models\RiwayatRekon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataMaskapaiController extends Controller
{
    public function index()
    {
        $data_maskapai = Maskapai::where('maskapai_pusat_id', Auth::user()->id)->get();
        return view('maskapai_pusat.datamaskapai.index', [
            'data_maskapai' => $data_maskapai
        ]);
    }

    public function create()
    {
        $bandara = Bandara::all();
        return view('maskapai_pusat.datamaskapai.create', [
            'bandara' => $bandara,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bandara' => 'required',
            'nama_maskapai' => 'required',
            'nama_pimpinan' => 'required',
            'jabatan_pimpinan' => 'required',
            'username' => 'required|unique:users,email',
            'password' => 'required|min:4',
            'kode_jabatan' => 'required',
            'alamat' => 'required',
            'no_telepon' => 'required',
            'email' => 'required',
        ]);

        $bandara = Bandara::find($request->bandara);
        $cek_nama_maskapai_in_bandara = Maskapai::where('bandara_id', $bandara->id)->get();
        foreach ($cek_nama_maskapai_in_bandara as $item) {
            if ($item->user) {
                if ($item->user->name == $request->nama_maskapai) {
                    return redirect()->back()->with('error', 'Maskapai ' . $request->nama_maskapai . ' Sudah ada di ' . $bandara->user->name);
                }
            }
        }

        $user = User::create([
            'name' => $request->nama_maskapai,
            'email' => $request->username,
            'password' => bcrypt($request->password),
            'level' => 'maskapai',
        ]);

        Maskapai::create([
            'nama_pimpinan' => $request->nama_pimpinan,
            'jabatan_pimpinan' => $request->jabatan_pimpinan,
            'bandara_id' => $bandara->id,
            'users_id' => $user->id,
            'maskapai_pusat_id' => Auth::user()->id,
            'kode_jabatan' => $request->kode_jabatan,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'email' => $request->email,
        ]);

        return redirect()->route('maskapai_pusat.datamaskapai')->with('message', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data_maskapai = Maskapai::find($id);
        $bandara = Bandara::all();
        return view('maskapai_pusat.datamaskapai.edit', [
            'data_maskapai' => $data_maskapai,
            'bandara' => $bandara,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data_maskapai = Maskapai::find($id);
        $user = User::find($data_maskapai->users_id);

        $request->validate([
            'nama_maskapai' => 'required|unique:users,name,' . $data_maskapai->users_id,
            'username' => 'required|min:4|unique:users,email,' . $data_maskapai->users_id,
            'nama_pimpinan' => 'required',
            'jabatan_pimpinan' => 'required',
            'kode_jabatan' => 'required',
            'alamat' => 'required',
            'no_telepon' => 'required',
            'email' => 'required',
        ]);

        $data_maskapai->update([
            'nama_pimpinan' => $request->nama_pimpinan,
            'jabatan_pimpinan' => $request->jabatan_pimpinan,
            'kode_jabatan' => $request->kode_jabatan,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $user->update([
                'name' => $request->nama_maskapai,
                'email' => $request->username,
                'password' => bcrypt($request->password),
            ]);
        } else {
            $user->update([
                'name' => $request->nama_maskapai,
                'email' => $request->username,
            ]);
        }

        return redirect()->route('maskapai_pusat.datamaskapai')->with('message', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $data_maskapai = Maskapai::find($id);

        $user = User::find($data_maskapai->users_id);
        $user->delete();

        $rekon = Rekon::where('maskapai_id', $data_maskapai->id)->get();
        if ($rekon) {
            foreach ($rekon as $r) {
                BeritaAcara::where('rekons_id', $r->id)->delete();
                RiwayatRekon::where('rekons_id', $r->id)->delete();
            }
        }
        Rekon::where('maskapai_id', $data_maskapai->id)->delete();

        $maskapai_staf = MaskapaiStaf::where('maskapai_id', $data_maskapai->id)->get();
        if ($maskapai_staf) {
            foreach ($maskapai_staf as $ms => $ms2) {
                User::where('id', $ms2->users_id)->delete();
            }
        }
        MaskapaiStaf::where('maskapai_id', $data_maskapai->id)->delete();

        $data_maskapai->delete();

        return redirect()->back()->with('message', 'Data berhasil dihapus');
    }
}
