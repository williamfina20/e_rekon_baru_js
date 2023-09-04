<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DataWilayahController extends Controller
{
    public function index()
    {
        $data_wilayah = User::where('level', 'wilayah')->get();
        return view('admin.datawilayah.index', [
            'data_wilayah' => $data_wilayah
        ]);
    }

    public function create()
    {
        return view('admin.datawilayah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:users,name',
            'wilayah' => 'required',
            'username' => 'required|unique:users,email',
            'password' => 'required|min:4'
        ]);

        User::create([
            'name' => $request->nama,
            'wilayah' => $request->wilayah,
            'email' => $request->username,
            'password' => bcrypt($request->password),
            'level' => 'wilayah'
        ]);

        return redirect()->route('admin.datawilayah')->with('message', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data_wilayah = User::find($id);
        return view('admin.datawilayah.edit', [
            'data_wilayah' => $data_wilayah
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|unique:users,name,' . $id,
            'username' => 'required|unique:users,email,' . $id,
            'wilayah' => 'required',
        ]);

        $data_wilayah = User::find($id);

        if ($request->password) {
            $data_wilayah->update([
                'name' => $request->nama,
                'email' => $request->username,
                'password' => bcrypt($request->password),
                'wilayah' => $request->wilayah
            ]);
        } else {
            $data_wilayah->update([
                'name' => $request->nama,
                'email' => $request->username,
                'wilayah' => $request->wilayah
            ]);
        }

        return redirect()->route('admin.datawilayah')->with('message', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $data_wilayah = User::find($id);
        $data_wilayah->delete();

        return redirect()->route('admin.datawilayah')->with('message', 'Data berhasil dihapus');
    }
}
