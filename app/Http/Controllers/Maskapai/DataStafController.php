<?php

namespace App\Http\Controllers\Maskapai;

use App\Http\Controllers\Controller;
use App\Models\Maskapai;
use App\Models\MaskapaiStaf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataStafController extends Controller
{
    public function index()
    {
        $maskapai = Maskapai::where('users_id', Auth::user()->id)->first();
        $maskapai_staf = MaskapaiStaf::where('maskapai_id', $maskapai->id)->get();
        return view('maskapai.datastaf.index', [
            'maskapai' => $maskapai,
            'maskapai_staf' => $maskapai_staf,
        ]);
    }
}
