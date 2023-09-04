<?php

namespace App\Http\Controllers\Bisnis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        return view('bisnis.beranda.index');
    }
}
