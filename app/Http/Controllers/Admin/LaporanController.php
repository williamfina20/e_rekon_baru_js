<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bandara;
use App\Models\BeritaAcara;
use App\Models\Maskapai;
use App\Models\Rekon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class LaporanController extends Controller
{
    public function index()
    {
        $rekon = Rekon::orderBy('bulan', 'ASC')->select('bulan')->get();
        // $bulan = [];
        // foreach ($rekon as $r) {
        //     if (!in_array($r->bulan, $bulan)) {
        //         array_push($bulan, $r->bulan);
        //     }
        // }
        // dd($bulan);
        return view('admin.laporan.index');
    }
}
