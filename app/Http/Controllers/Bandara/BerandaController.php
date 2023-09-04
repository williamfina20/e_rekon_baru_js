<?php

namespace App\Http\Controllers\Bandara;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BerandaController extends Controller
{
    public function index()
    {
        return view('bandara.beranda.index');
    }

    public function profil()
    {
        return view('bandara.beranda.profil');
    }

    public function profil_update(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $request->validate([
            'username' => 'required|unique:users,email,' . $user->id,
        ]);

        if ($request->password) {
            $user->update([
                'email' => $request->username,
                'password' => Hash::make($request->password)
            ]);
        } else {
            $user->update([
                'email' => $request->username,
            ]);
        }

        return redirect()->back()->with('message', 'Data berhasil diubah');
    }
}
