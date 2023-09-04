<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bandara;
use App\Models\BeritaAcara;
use App\Models\Maskapai;
use App\Models\Rekon;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Str;

class LaporanMaskapaiController extends Controller
{
    public function index()
    {
        $bandara = Bandara::all();
        $periode = Rekon::groupBy('bulan')->select('bulan')->orderBy('bulan', 'ASC')->get();
        return view('admin.laporan.laporan_maskapai.index', [
            'bandara' => $bandara,
            'periode' => $periode
        ]);
    }

    public function cetak(Request $request)
    {
        if ($request->bandara == 'semua') {
            if ($request->periode == 'semua') {
                $rekon = Rekon::where('admin_acc', '!=', '')->where('maskapai_acc', '!=', '')->orderBy('bulan', 'ASC')->get();
                if (count($rekon) == 0) {
                    return redirect()->back()->with('danger', 'Data Rekon Tidak Ditemukan');
                }
                $berita_acara = [];
                $no_berita_acara = [];
                $rekon_admin = [];
                $produksi = [];
                $data_produksi = [];
                $data_list = [];

                foreach ($rekon as $r => $r2) {
                    $berita_acara[$r2->id] = BeritaAcara::where('rekons_id', $r2->id)->first();
                    $semua_rekon_pada_bandara_yang_disetujui = Rekon::where('bandara_id', $r2->bandara_id)->where('admin_acc', '!=', '')->where('maskapai_acc', '!=', '')->orderBy('bulan', 'ASC')->get();
                    $no_berita_acara[$r2->id] = 0;
                    foreach ($semua_rekon_pada_bandara_yang_disetujui as $items => $item) {
                        if ($item->id == $r2->id) {
                            $no_berita_acara[$r2->id] = $items + 1;
                        }
                    }

                    $rekon_admin[$r2->id] = json_decode($r2->rekon_admin_text, true);

                    $produksi[$r2->id] = [];
                    foreach ($rekon_admin[$r2->id] as  $item) {
                        $i = 0;
                        foreach ($item as $key => $isi) {
                            if ($i == 1) {
                                if (in_array($isi, $produksi[$r2->id])) {
                                } else {
                                    array_push($produksi[$r2->id], $isi);
                                }
                            }
                            $i++;
                        }
                    }

                    sort($produksi[$r2->id]);

                    $data_produksi[$r2->id] = [];
                    foreach ($produksi[$r2->id] as $p => $p2) {
                        $data_produksi[$r2->id][$p2] = 0;
                        foreach ($rekon_admin[$r2->id] as  $item) {
                            if (in_array($p2, $item)) {
                                $i = 0;
                                foreach ($item as $key => $isi) {
                                    if ($i == 11) {
                                        $data_produksi[$r2->id][$p2] += (int)$isi;
                                    }
                                    $i++;
                                }
                            }
                        }
                    }

                    $data_list[$r2->id] =
                        [
                            'Maskapai' => Str::title($r2->maskapai->user->name),
                            'Bandara' => Str::title($r2->bandara->user->name),
                            'Nomor Berita Acara' => 'ER.0' . $no_berita_acara[$r2->id] . '/Hk.06.03/' . date('Y', strtotime($r2->bulan)) . '/' . ($r2->bandara ? $r2->bandara->kode_jabatan : ''),

                        ];

                    foreach ($data_produksi[$r2->id] as $dp => $dp2) {
                        $data_list[$r2->id][$dp] = $dp2;
                    }


                    $data_list[$r2->id]['Total Produksi'] = array_sum($data_produksi[$r2->id]);
                    $data_list[$r2->id]['Estimasi Pendapatan'] = array_sum($data_produksi[$r2->id]) * $r2->maskapai->maskapai_pusat->harga;
                }

                // asort($data_list);
                usort($data_list, function ($a, $b) {
                    return $a['Maskapai'] <=> $b['Maskapai'];
                });

                $list = collect($data_list);

                return (new FastExcel($list))->download('E-rekon_Laporan_Maskapai_' . now() . '.xlsx');
                // ++++++++++++++++++++++++++++++++++++++++++
            } else {

                $rekon = Rekon::where('bulan', $request->periode)->where('admin_acc', '!=', '')->where('maskapai_acc', '!=', '')->orderBy('bulan', 'ASC')->get();
                if (count($rekon) == 0) {
                    return redirect()->back()->with('danger', 'Data Rekon Periode' . date('F Y', strtotime($request->periode)) . ' Tidak Ditemukan');
                }
                $berita_acara = [];
                $no_berita_acara = [];
                $rekon_admin = [];
                $produksi = [];
                $data_produksi = [];
                $data_list = [];

                foreach ($rekon as $r => $r2) {
                    $berita_acara[$r2->id] = BeritaAcara::where('rekons_id', $r2->id)->first();
                    $semua_rekon_pada_bandara_yang_disetujui = Rekon::where('bandara_id', $r2->bandara_id)->where('admin_acc', '!=', '')->where('maskapai_acc', '!=', '')->orderBy('bulan', 'ASC')->get();
                    $no_berita_acara[$r2->id] = 0;
                    foreach ($semua_rekon_pada_bandara_yang_disetujui as $items => $item) {
                        if ($item->id == $r2->id) {
                            $no_berita_acara[$r2->id] = $items + 1;
                        }
                    }

                    $rekon_admin[$r2->id] = json_decode($r2->rekon_admin_text, true);

                    $produksi[$r2->id] = [];
                    foreach ($rekon_admin[$r2->id] as  $item) {
                        $i = 0;
                        foreach ($item as $key => $isi) {
                            if ($i == 1) {
                                if (in_array($isi, $produksi[$r2->id])) {
                                } else {
                                    array_push($produksi[$r2->id], $isi);
                                }
                            }
                            $i++;
                        }
                    }

                    sort($produksi[$r2->id]);

                    $data_produksi[$r2->id] = [];
                    foreach ($produksi[$r2->id] as $p => $p2) {
                        $data_produksi[$r2->id][$p2] = 0;
                        foreach ($rekon_admin[$r2->id] as  $item) {
                            if (in_array($p2, $item)) {
                                $i = 0;
                                foreach ($item as $key => $isi) {
                                    if ($i == 11) {
                                        $data_produksi[$r2->id][$p2] += (int)$isi;
                                    }
                                    $i++;
                                }
                            }
                        }
                    }

                    $data_list[$r2->id] =
                        [
                            'Maskapai' => Str::title($r2->maskapai->user->name),
                            'Bandara' => Str::title($r2->bandara->user->name),
                            'Nomor Berita Acara' => 'ER.0' . $no_berita_acara[$r2->id] . '/Hk.06.03/' . date('Y', strtotime($r2->bulan)) . '/' . ($r2->bandara ? $r2->bandara->kode_jabatan : ''),

                        ];

                    foreach ($data_produksi[$r2->id] as $dp => $dp2) {
                        $data_list[$r2->id][$dp] = $dp2;
                    }


                    $data_list[$r2->id]['Total Produksi'] = array_sum($data_produksi[$r2->id]);
                    $data_list[$r2->id]['Estimasi Pendapatan'] = array_sum($data_produksi[$r2->id]) * $r2->maskapai->maskapai_pusat->harga;
                }

                // asort($data_list);
                usort($data_list, function ($a, $b) {
                    return $a['Maskapai'] <=> $b['Maskapai'];
                });

                $list = collect($data_list);

                return (new FastExcel($list))->download('E-rekon_Laporan_Maskapai_Periode_' . date('Y F', strtotime($request->periode)) . '_' . now() . '.xlsx');
                // ++++++++++++++++++++++++++++++++++++++++++
            }
            // ++++++++++++++++++++++++++++++++++++++++++
        } else {
            if ($request->periode == 'semua') {
                $bandara = Bandara::find($request->bandara);

                $rekon = Rekon::where('bandara_id', $bandara->id)->where('admin_acc', '!=', '')->where('maskapai_acc', '!=', '')->orderBy('bulan', 'ASC')->get();
                if (count($rekon) == 0) {
                    return redirect()->back()->with('danger', 'Data Rekon ' . $bandara->user->name . ' Tidak Ditemukan');
                }
                $berita_acara = [];
                $no_berita_acara = [];
                $rekon_admin = [];
                $produksi = [];
                $data_produksi = [];
                $data_list = [];

                foreach ($rekon as $r => $r2) {
                    $berita_acara[$r2->id] = BeritaAcara::where('rekons_id', $r2->id)->first();
                    $semua_rekon_pada_bandara_yang_disetujui = Rekon::where('bandara_id', $r2->bandara_id)->where('admin_acc', '!=', '')->where('maskapai_acc', '!=', '')->orderBy('bulan', 'ASC')->get();
                    $no_berita_acara[$r2->id] = 0;
                    foreach ($semua_rekon_pada_bandara_yang_disetujui as $items => $item) {
                        if ($item->id == $r2->id) {
                            $no_berita_acara[$r2->id] = $items + 1;
                        }
                    }

                    $rekon_admin[$r2->id] = json_decode($r2->rekon_admin_text, true);

                    $produksi[$r2->id] = [];
                    foreach ($rekon_admin[$r2->id] as  $item) {
                        $i = 0;
                        foreach ($item as $key => $isi) {
                            if ($i == 1) {
                                if (in_array($isi, $produksi[$r2->id])) {
                                } else {
                                    array_push($produksi[$r2->id], $isi);
                                }
                            }
                            $i++;
                        }
                    }

                    sort($produksi[$r2->id]);

                    $data_produksi[$r2->id] = [];
                    foreach ($produksi[$r2->id] as $p => $p2) {
                        $data_produksi[$r2->id][$p2] = 0;
                        foreach ($rekon_admin[$r2->id] as  $item) {
                            if (in_array($p2, $item)) {
                                $i = 0;
                                foreach ($item as $key => $isi) {
                                    if ($i == 11) {
                                        $data_produksi[$r2->id][$p2] += (int)$isi;
                                    }
                                    $i++;
                                }
                            }
                        }
                    }

                    $data_list[$r2->id] =
                        [
                            'Maskapai' => Str::title($r2->maskapai->user->name),
                            'Bandara' => Str::title($r2->bandara->user->name),
                            'Nomor Berita Acara' => 'ER.0' . $no_berita_acara[$r2->id] . '/Hk.06.03/' . date('Y', strtotime($r2->bulan)) . '/' . ($r2->bandara ? $r2->bandara->kode_jabatan : ''),

                        ];

                    foreach ($data_produksi[$r2->id] as $dp => $dp2) {
                        $data_list[$r2->id][$dp] = $dp2;
                    }


                    $data_list[$r2->id]['Total Produksi'] = array_sum($data_produksi[$r2->id]);
                    $data_list[$r2->id]['Estimasi Pendapatan'] = array_sum($data_produksi[$r2->id]) * $r2->maskapai->maskapai_pusat->harga;
                }

                // asort($data_list);
                usort($data_list, function ($a, $b) {
                    return $a['Maskapai'] <=> $b['Maskapai'];
                });

                $list = collect($data_list);

                return (new FastExcel($list))->download('E-rekon_Laporan_Maskapai_Di_' . $bandara->user->name . '_' . now() . '.xlsx');
                // ++++++++++++++++++++++++++++++++++++++++++
            } else {
                $bandara = Bandara::find($request->bandara);

                $rekon = Rekon::where('bandara_id', $bandara->id)->where('bulan', $request->periode)->where('admin_acc', '!=', '')->where('maskapai_acc', '!=', '')->orderBy('bulan', 'ASC')->get();
                if (count($rekon) == 0) {
                    return redirect()->back()->with('danger', 'Data Rekon ' . $bandara->user->name . ' Periode ' . date('F Y', strtotime($request->periode)) . ' Tidak Ditemukan');
                }
                $berita_acara = [];
                $no_berita_acara = [];
                $rekon_admin = [];
                $produksi = [];
                $data_produksi = [];
                $data_list = [];

                foreach ($rekon as $r => $r2) {
                    $berita_acara[$r2->id] = BeritaAcara::where('rekons_id', $r2->id)->first();
                    $semua_rekon_pada_bandara_yang_disetujui = Rekon::where('bandara_id', $r2->bandara_id)->where('admin_acc', '!=', '')->where('maskapai_acc', '!=', '')->orderBy('bulan', 'ASC')->get();
                    $no_berita_acara[$r2->id] = 0;
                    foreach ($semua_rekon_pada_bandara_yang_disetujui as $items => $item) {
                        if ($item->id == $r2->id) {
                            $no_berita_acara[$r2->id] = $items + 1;
                        }
                    }

                    $rekon_admin[$r2->id] = json_decode($r2->rekon_admin_text, true);

                    $produksi[$r2->id] = [];
                    foreach ($rekon_admin[$r2->id] as  $item) {
                        $i = 0;
                        foreach ($item as $key => $isi) {
                            if ($i == 1) {
                                if (in_array($isi, $produksi[$r2->id])) {
                                } else {
                                    array_push($produksi[$r2->id], $isi);
                                }
                            }
                            $i++;
                        }
                    }

                    sort($produksi[$r2->id]);

                    $data_produksi[$r2->id] = [];
                    foreach ($produksi[$r2->id] as $p => $p2) {
                        $data_produksi[$r2->id][$p2] = 0;
                        foreach ($rekon_admin[$r2->id] as  $item) {
                            if (in_array($p2, $item)) {
                                $i = 0;
                                foreach ($item as $key => $isi) {
                                    if ($i == 11) {
                                        $data_produksi[$r2->id][$p2] += (int)$isi;
                                    }
                                    $i++;
                                }
                            }
                        }
                    }

                    $data_list[$r2->id] =
                        [
                            'Maskapai' => Str::title($r2->maskapai->user->name),
                            'Bandara' => Str::title($r2->bandara->user->name),
                            'Nomor Berita Acara' => 'ER.0' . $no_berita_acara[$r2->id] . '/Hk.06.03/' . date('Y', strtotime($r2->bulan)) . '/' . ($r2->bandara ? $r2->bandara->kode_jabatan : ''),

                        ];

                    foreach ($data_produksi[$r2->id] as $dp => $dp2) {
                        $data_list[$r2->id][$dp] = $dp2;
                    }


                    $data_list[$r2->id]['Total Produksi'] = array_sum($data_produksi[$r2->id]);
                    $data_list[$r2->id]['Estimasi Pendapatan'] = array_sum($data_produksi[$r2->id]) * $r2->maskapai->maskapai_pusat->harga;
                }

                // asort($data_list);
                usort($data_list, function ($a, $b) {
                    return $a['Maskapai'] <=> $b['Maskapai'];
                });

                $list = collect($data_list);

                return (new FastExcel($list))->download('E-rekon_Laporan_Maskapai_Di_' . $bandara->user->name . '_Periode_' . date('Y F', strtotime($request->periode)) . '_' . now() . '.xlsx');
                // ++++++++++++++++++++++++++++++++++++++++++
            }
            // ++++++++++++++++++++++++++++++++++++++++++
        }
    }
}
