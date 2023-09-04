<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BisnisController extends Controller
{
    public function index()
    {
        $bisnis = User::where('level', 'bisnis')->get();
        return view('admin.bisnis.index', [
            'bisnis' => $bisnis
        ]);
    }

    public function create()
    {
        return view('admin.bisnis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:users,name',
            'username' => 'required|unique:users,email',
            'password' => 'required|min:4',
        ]);

        User::create([
            'name' => $request->nama,
            'email' => $request->username,
            'password' => bcrypt($request->password),
            'level' => 'bisnis',
        ]);

        return redirect()->route('admin.bisnis')->with('message', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $bisnis = User::find($id);
        return view('admin.bisnis.edit', [
            'bisnis' => $bisnis
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|unique:users,name,' . $id,
            'username' => 'required|unique:users,email,' . $id,
        ]);

        $bisnis = User::find($id);

        if ($request->password) {
            $bisnis->update([
                'name' => $request->nama,
                'email' => $request->username,
                'password' => bcrypt($request->password),
            ]);
        } else {
            $bisnis->update([
                'name' => $request->nama,
                'email' => $request->username,
            ]);
        }

        return redirect()->route('admin.bisnis')->with('message', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $bisnis = User::find($id);
        $bisnis->delete();

        return redirect()->route('admin.bisnis')->with('message', 'Data berhasil dihapus');
    }
}
