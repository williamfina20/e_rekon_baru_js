<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index()
    {
        $berita = Berita::all();
        return view('admin.berita.index', ['berita' => $berita]);
    }

    public function create()
    {
        return view('admin.berita.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'isi' => 'required',
        ]);

        Berita::create([
            'nama' => $request->nama,
            'isi' => $request->isi,
        ]);

        return redirect()->route('admin.berita')->with('message', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $berita = Berita::find($id);
        return view('admin.berita.edit', [
            'berita' => $berita
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'isi' => 'required',
        ]);

        $berita = Berita::find($id);

        $berita->update([
            'nama' => $request->nama,
            'isi' => $request->isi,
        ]);

        return redirect()->route('admin.berita')->with('message', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $berita = Berita::find($id);
        $berita->delete();

        return redirect()->route('admin.berita')->with('message', 'Data berhasil dihapus');
    }
}
