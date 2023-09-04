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
        Berita Acara 2 E-Rekon
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
            <span>
                Nomor:
                ER.0{{ $no_berita_acara }}/Hk.06.03/{{ date('Y', strtotime($data_rekon->bulan)) }}/{{ $data_rekon->bandara ? $data_rekon->bandara->kode_jabatan : '' }}</span>
            @php
                $total = 0;
                $kunci = [];
            @endphp
            <div class="border-top border-dark py-2">
                <div class="text-start">Pada Hari ini, {{ date('d/m/Y', strtotime($data_rekon->updated_at)) }}
                    Bertempat
                    Di Terminal Kargo dan Pos {{ $data_rekon->bandara ? $data_rekon->bandara->user->name : '' }} telah
                    dilakukan rekonsiliasi periode bulan {{ date('F Y', strtotime($data_rekon->bulan)) }} sebagai
                    berikut:
                </div>
                <div>
                    <div class="d-flex mt-2" style="width: 90%">
                        <div>1.&nbsp;&nbsp;</div>
                        <div class="text-start">
                            <p>
                                Dasar: <br />
                                Perjanjian Kerjasama Penanganan Kargo dan Pos pada Terminal di
                                {{ $data_rekon->bandara ? $data_rekon->bandara->user->name : '' }} antara
                                {{ $data_rekon->maskapai ? $data_rekon->maskapai->user->name : '' }}
                                dan PT. Angkasa Pura Logistik Nomor:
                                {{ $data_rekon->maskapai ? $data_rekon->maskapai->maskapai_pusat->dasar_hukum : '' }}
                            </p>
                        </div>
                    </div>
                    <div class="d-flex" style="width: 90%">
                        <div>2.&nbsp;&nbsp;</div>
                        <div class="text-start">
                            <p>
                                Hasil Rekonsiliasi:<br />
                                Produksi {{ $data_rekon->maskapai ? $data_rekon->maskapai->user->name : '' }} Periode
                                Bulan {{ date('F Y', strtotime($data_rekon->bulan)) }}
                            </p>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-bordered" style="width: 90%">
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
                <p class="text-start" style="width: 90%">
                    Demikian Berita Acara ini dibuat, agar dipergunakan sebagaimana mestinya
                </p>
            </div>
            <br />
            <table style="width: 90%; margin-top: -20px;">
                <tr>
                    <td style="text-align: center;width: 45%;vertical-align: top;" class="text-capitalize">
                        @if ($data_rekon->bandara)
                            @if ($data_rekon->bandara->user)
                                {{ $data_rekon->bandara->user->name }}
                            @endif
                        @endif
                        <br />
                        {{ $berita_acara->bandara_jabatan_pimpinan }}
                    </td>
                    <td style="width: 10%"></td>
                    <td style="text-align: center;width: 45%;vertical-align: top;" class="text-capitalize">
                        @if ($data_rekon->maskapai)
                            @if ($data_rekon->maskapai->user)
                                {{ $data_rekon->maskapai->user->name }}
                            @endif
                        @endif
                        <br />
                        {{ $berita_acara->maskapai_jabatan_pimpinan }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;width: 45%" class="text-center">
                        @php
                            echo DNS2D::getBarcodeSVG(route('admin.datarekon.kode_bandara', $data_rekon->id), 'QRCode', 3, 3);
                        @endphp
                    </td>
                    <td style="width: 10%">
                    </td>
                    <td style="text-align: center;width: 45%">
                        @php
                            echo DNS2D::getBarcodeSVG(route('admin.datarekon.kode_maskapai', $data_rekon->id), 'QRCode', 3, 3);
                        @endphp
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;width: 45%">
                        {{ $berita_acara->bandara_nama_pimpinan }}
                    </td>
                    <td style="width: 10%"></td>
                    <td style="text-align: center;width: 45%">
                        {{ $berita_acara->maskapai_nama_pimpinan }}
                    </td>
                </tr>
            </table>
            <p class="mt-3" style="font-size: 10px;">
                {{ $data_rekon->bandara ? $data_rekon->bandara->user->name : '' }}<br />
                alamat: {{ $data_rekon->bandara->alamat }}, no telepon: {{ $data_rekon->bandara->no_telepon }},<br />
                email: {{ $data_rekon->bandara->email }}
            </p>
        </center>
    </div>


    <script>
        var css = '@page { size: portrait; }',
            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style');

        style.type = 'text/css';
        style.media = 'print';

        if (style.styleSheet) {
            style.styleSheet.cssText = css;
        } else {
            style.appendChild(document.createTextNode(css));
        }

        head.appendChild(style);

        window.print();
    </script>
</body>

</html>
