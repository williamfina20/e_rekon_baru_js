<?php

namespace App\Http\Controllers\Bandara;

use App\Http\Controllers\Controller;
use App\Models\Bandara;
use App\Models\BandaraStaf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataStafController extends Controller
{
    public function index()
    {
        $bandara = Bandara::where('users_id', Auth::user()->id)->first();
        $bandara_staf = BandaraStaf::where('bandara_id', $bandara->id)->get();
        return view('bandara.datastaf.index', [
            'bandara' => $bandara,
            'bandara_staf' => $bandara_staf,
        ]);
    }
}
