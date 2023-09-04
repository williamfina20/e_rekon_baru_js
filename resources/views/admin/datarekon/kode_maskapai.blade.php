<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords"
        content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Flexy lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Flexy admin lite design, Flexy admin lite dashboard bootstrap 5 dashboard template">
    <meta name="description"
        content="Flexy Admin Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <title>
        E-Rekon Kode Maskapai
        {{ $data_rekon->maskapai ? $data_rekon->maskapai->bandara->user->name : '' }} -
        {{ $data_rekon->maskapai ? $data_rekon->maskapai->user->name : '' }} -
        ({{ date('F Y', strtotime($data_rekon->bulan)) }})
    </title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/Flexy-admin-lite/" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('flexy/assets/images/favicon.png') }}">
    <!-- Custom CSS -->
    <link href="{{ asset('flexy/dist/css/style.min.css') }}" rel="stylesheet">

    {{-- datatables --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    {{-- toast --}}
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    {{-- CK Editor    --}}
    <script src="https://cdn.ckeditor.com/4.21.0/full/ckeditor.js"></script>
    <style>
        /* @media print {
            @page {
                margin-top: 10px;
                margin-bottom: 0;
            }

            body {
                padding-top: 72px;
                padding-bottom: 72px;
            }
        } */
    </style>
</head>

<body>
    <div>
        <div class="text-end">
            <img src="{{ asset('aplog.jpeg') }}" width="300">
        </div>
        <h5 class="text-center" style="text-transform: uppercase">
            BERITA ACARA <br />
            REKONSILIASI PRODUKSI ATAS PERJANJIAN KERJASAMA PENANGANAN KARGO & KOS<br />
            {{ $data_rekon->bandara ? $data_rekon->bandara->user->name : '' }}
            DAN
            {{ $data_rekon->maskapai ? $data_rekon->maskapai->user->name : '' }}
            {{-- DI BANDAR UDARA INTERNATIONAL ELTARI KUPANG --}}
            <BR />
            PERIODE {{ date('F Y', strtotime($data_rekon->bulan)) }}
        </h5>
        <center>
            @php
                $total = 0;
                $kunci = [];
            @endphp
            <div class="border-top border-dark py-2">
                <div class="text-start">
                    Informasi surat ini dinyatakan sah dan sesuai dengan sistem E-Rekon milik PT. Angkasa Pura Logistik
                </div>
                {{-- <div class="text-start">Pada Hari ini, {{ date('d/m/Y', strtotime($data_rekon->updated_at)) }}
                Bertempat
                Di Terminal Kargo dan Pos {{ $data_rekon->bandara ? $data_rekon->bandara->user->name : '' }} telah
                dilakukan rekonsiliasi periode bulan {{ date('F Y', strtotime($data_rekon->bulan)) }} sebagai
                berikut:
            </div> --}}
                <div>
                    <div class="text-start">
                        <div class="text-center mt-2">
                            Informasi Rekonsiliasi Produksi
                        </div>
                        <center>
                            <table class="table table-bordered" style="width: 90%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Produksi</th>
                                        <th>Berat (Kg)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_produksi as $items => $item)
                                        @php
                                            $total += (int) $item;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $items }}</td>
                                            <td>{{ $item }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="2">Total Produksi</td>
                                        <td>{{ $total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </center>
                        <p>
                            Nomor Berita Acara Rekonsiliasi :
                            ER.0{{ $no_berita_acara }}/Hk.06.03/{{ date('Y', strtotime($data_rekon->bulan)) }}/{{ $data_rekon->bandara ? $data_rekon->bandara->kode_jabatan : '' }}
                            <br />
                            @php
                                $waktu_admin = new DateTime($data_rekon->admin_acc);
                                $waktu_maskapai = new DateTime($data_rekon->maskapai_acc);
                            @endphp
                            Tanggal Berita Acara :
                            @if ($waktu_admin >= $waktu_maskapai)
                                {{ date('H:i:s d/m/Y', strtotime($data_rekon->admin_acc)) }}
                            @else
                                {{ date('H:i:s d/m/Y', strtotime($data_rekon->maskapai_acc)) }}
                            @endif
                            <br />
                            Perihal:
                            Berita Acara Rekonsiliasi Produksi PT. Angkasa Pura Logistik
                        </p>
                        <p>
                            Verifikator Angkasa Pura Logistik :
                            @if ($data_rekon->bandara)
                                @if ($data_rekon->bandara->user)
                                    {{ $berita_acara->bandara_nama_pimpinan }} -
                                    {{ $berita_acara->bandara_jabatan_pimpinan }}
                                @endif
                            @endif
                            <br />
                            Tim Rekonsiliasi Angkasa Pura Logistik :
                            <br />
                            @if ($data_rekon->bandara->bandara_staf)
                                @foreach ($data_rekon->bandara->bandara_staf as $item)
                                    @if ($item->user)
                                        {{ $item->user->name }} - {{ $item->jabatan_staf }}
                                        <br />
                                    @endif
                                @endforeach
                            @endif
                        </p>
                        <p>
                            Verifikator Maskapai :
                            @if ($data_rekon->maskapai)
                                {{ $berita_acara->maskapai_nama_pimpinan }} -
                                {{ $berita_acara->maskapai_jabatan_pimpinan }}
                            @endif
                            <br />
                            Tim Rekonsiliasi Maskapai :
                            <br />
                            @if ($data_rekon->maskapai->maskapai_staf)
                                @foreach ($data_rekon->maskapai->maskapai_staf as $item)
                                    @if ($item->user)
                                        {{ $item->user->name }} - {{ $item->jabatan_staf }}
                                        <br />
                                    @endif
                                @endforeach
                            @endif
                            <br />
                            Tanggal Rekon : {{ date('H:i:s d/m/Y', strtotime($data_rekon->created_at)) }}
                        </p>
                        <div class="text-center">
                            <span>Riwayat Perubahan Rekonsiliasi</span>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Akun</th>
                                    <th>Proses</th>
                                    <th>Riwayat Ubah</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayat_rekon as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {!! $akun = App\Http\Controllers\PanggilFungsiController::tampil_akun_bandara_atau_maskapai(
                                                $item->akun_tipe,
                                                $item->akun_id,
                                            ) !!}
                                        </td>
                                        <td>{{ $item->proses }}</td>
                                        <td>
                                            @php
                                                $riwayat_ubah = json_decode($item->riwayat_ubah, true);
                                                foreach ($riwayat_ubah as $kunci_ru => $isi_ru) {
                                                    if ($kunci_ru != 'NO') {
                                                        echo $kunci_ru . ': ' . $isi_ru . ',&nbsp;&nbsp;';
                                                    }
                                                }
                                            @endphp
                                        </td>
                                        <td>{{ date('H:i:s d/m/Y', strtotime($item->created_at)) }}</td>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </center>
    </div>


    <script>
        // var css = '@page { size: portrait; }',
        //     head = document.head || document.getElementsByTagName('head')[0],
        //     style = document.createElement('style');

        // style.type = 'text/css';
        // style.media = 'print';

        // if (style.styleSheet) {
        //     style.styleSheet.cssText = css;
        // } else {
        //     style.appendChild(document.createTextNode(css));
        // }

        // head.appendChild(style);

        window.print();
    </script>
</body>

</html>
