<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PimpinanController extends Controller
{
    public function index()
    {
        $pimpinan = User::where('level', 'pimpinan')->get();
        return view('admin.pimpinan.index', [
            'pimpinan' => $pimpinan
        ]);
    }

    public function create()
    {
        return view('admin.pimpinan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pimpinan' => 'required|unique:users,name',
            'divisi' => 'required',
            'username' => 'required|unique:users,email',
            'password' => 'required|min:4',
        ]);

        User::create([
            'name' => $request->nama_pimpinan,
            'jabatan_pimpinan' => $request->divisi,
            'email' => $request->username,
            'password' => bcrypt($request->password),
            'level' => 'pimpinan',
        ]);

        return redirect()->route('admin.pimpinan')->with('message', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pimpinan = User::find($id);
        return view('admin.pimpinan.edit', [
            'pimpinan' => $pimpinan
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pimpinan' => 'required|unique:users,name,' . $id,
            'username' => 'required|unique:users,email,' . $id,
            'divisi' => 'required',
        ]);

        $pimpinan = User::find($id);

        if ($request->password) {
            $pimpinan->update([
                'name' => $request->nama_pimpinan,
                'jabatan_pimpinan' => $request->divisi,
                'email' => $request->username,
                'password' => bcrypt($request->password),
            ]);
        } else {
            $pimpinan->update([
                'name' => $request->nama_pimpinan,
                'jabatan_pimpinan' => $request->divisi,
                'email' => $request->username,
            ]);
        }

        return redirect()->route('admin.pimpinan')->with('message', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $pimpinan = User::find($id);
        $pimpinan->delete();

        return redirect()->route('admin.pimpinan')->with('message', 'Data berhasil dihapus');
    }
}
