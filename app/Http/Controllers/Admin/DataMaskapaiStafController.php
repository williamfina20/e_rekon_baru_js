<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Maskapai;
use App\Models\MaskapaiStaf;
use App\Models\User;
use Illuminate\Http\Request;

class DataMaskapaiStafController extends Controller
{
    public function index($id)
    {
        $maskapai = Maskapai::find($id);
        $maskapai_staf = MaskapaiStaf::where('maskapai_id', $maskapai->id)->get();

        return view('admin.datamaskapaistaf.index', [
            'maskapai' => $maskapai,
            'maskapai_staf' => $maskapai_staf
        ]);
    }

    public function create($id)
    {
        $maskapai = Maskapai::find($id);
        return view('admin.datamaskapaistaf.create', [
            'maskapai' => $maskapai,
        ]);
    }

    public function store(Request $request, $id)
    {
        $maskapai = Maskapai::find($id);

        $request->validate([
            'nama' => 'required|unique:users,name',
            'username' => 'required|unique:users,email',
            'password' => 'required|min:4',
            'jabatan_staf' => 'required',
            'kode_jabatan' => 'required',
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->username,
            'password' => bcrypt($request->password),
            'level' => 'maskapai_staf',
        ]);

        MaskapaiStaf::create([
            'maskapai_id' => $id,
            'users_id' => $user->id,
            'jabatan_staf' => $request->jabatan_staf,
            'kode_jabatan' => $request->kode_jabatan,
        ]);

        return redirect()->route('admin.datamaskapaistaf', $id)->with('message', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $maskapai_staf = MaskapaiStaf::find($id);
        $maskapai = Maskapai::find($maskapai_staf->maskapai_id);
        return view('admin.datamaskapaistaf.edit', [
            'maskapai_staf' => $maskapai_staf,
            'maskapai' => $maskapai,
        ]);
    }

    public function update(Request $request, $id)
    {
        $maskapai_staf = MaskapaiStaf::find($id);
        $user = User::find($maskapai_staf->users_id);

        $request->validate([
            'nama' => 'required|unique:users,name,' . $user->id,
            'username' => 'required|unique:users,email,' . $user->id,
            'jabatan_staf' => 'required',
            'kode_jabatan' => 'required',
        ]);

        $maskapai_staf->update([
            'jabatan_staf' => $request->jabatan_staf,
            'kode_jabatan' => $request->kode_jabatan,
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

        return redirect()->route('admin.datamaskapaistaf', $maskapai_staf->maskapai_id)->with('message', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $maskapai_staf = MaskapaiStaf::find($id);
        $maskapai = Maskapai::find($maskapai_staf->maskapai_id);
        $user = User::find($maskapai_staf->users_id);
        $user->delete();
        $maskapai_staf->delete();

        return redirect()->route('admin.datamaskapaistaf', $maskapai->id)->with('message', 'Data berhasil dihapus');
    }
}
