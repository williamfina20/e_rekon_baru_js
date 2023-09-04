<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bandara;
use App\Models\BandaraStaf;
use App\Models\User;
use Illuminate\Http\Request;

class BandaraStafController extends Controller
{
    public function index($id)
    {
        $bandara = Bandara::find($id);
        $bandara_staf = BandaraStaf::where('bandara_id', $id)->get();

        return view('admin.bandara_staf.index', [
            'bandara' => $bandara,
            'bandara_staf' => $bandara_staf,
        ]);
    }

    public function create($id)
    {
        $bandara = Bandara::find($id);
        return view('admin.bandara_staf.create', [
            'bandara' => $bandara,
        ]);
    }

    public function store(Request $request, $id)
    {
        $bandara = Bandara::find($id);

        $request->validate([
            'nama' => 'required|unique:users,name',
            'username' => 'required|unique:users,email',
            'password' => 'required|min:4',
            'jabatan_staf' => 'required',
            'kode_jabatan' => 'required',
            // 'alamat' => 'required',
            // 'no_telepon' => 'required',
            // 'email' => 'required',
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->username,
            'password' => bcrypt($request->password),
            'level' => 'bandara_staf',
        ]);

        BandaraStaf::create([
            'bandara_id' => $id,
            'users_id' => $user->id,
            'jabatan_staf' => $request->jabatan_staf,
            'kode_jabatan' => $request->kode_jabatan,
            // 'alamat' => $request->alamat,
            // 'no_telepon' => $request->no_telepon,
            // 'email' => $request->email,
        ]);

        return redirect()->route('admin.bandara_staf', $id)->with('message', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $bandara_staf = BandaraStaf::find($id);
        $bandara = Bandara::find($bandara_staf->bandara_id);
        return view('admin.bandara_staf.edit', [
            'bandara_staf' => $bandara_staf,
            'bandara' => $bandara,
        ]);
    }

    public function update(Request $request, $id)
    {
        $bandara_staf = BandaraStaf::find($id);
        $user = User::find($bandara_staf->users_id);

        $request->validate([
            'nama' => 'required|unique:users,name,' . $user->id,
            'username' => 'required|unique:users,email,' . $user->id,
            'jabatan_staf' => 'required',
            'kode_jabatan' => 'required',
            // 'alamat' => 'required',
            // 'no_telepon' => 'required',
            // 'email' => 'required',
        ]);

        $bandara_staf->update([
            'jabatan_staf' => $request->jabatan_staf,
            'kode_jabatan' => $request->kode_jabatan,
            // 'alamat' => $request->alamat,
            // 'no_telepon' => $request->no_telepon,
            // 'email' => $request->email,
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

        return redirect()->route('admin.bandara_staf', $bandara_staf->bandara_id)->with('message', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $bandara_staf = BandaraStaf::find($id);
        $bandara = Bandara::find($bandara_staf->bandara_id);
        $user = User::find($bandara_staf->users_id);
        $user->delete();
        $bandara_staf->delete();

        return redirect()->route('admin.bandara_staf', $bandara->id)->with('message', 'Data berhasil dihapus');
    }
}
