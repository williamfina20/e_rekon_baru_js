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
        Berita Acara E-Rekon
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
        .table-condensed {
            font-size: 10px;
        }
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
            <table class="table table-bordered table-condensed">
                @php
                    $data_rekon_admin = json_decode($data_rekon->rekon_admin_text, true);
                    $j1 = 0;
                    $j2 = 0;
                    $j3 = 0;
                    $key_values = array_column($data_rekon_admin, 'JAM MASUK');
                    if ($key_values) {
                        array_multisort($key_values, SORT_ASC, $data_rekon_admin);
                    }
                @endphp
                <thead>
                    <tr>
                        @foreach (array_keys($data_rekon_admin[0]) as $item)
                            <th>{{ $item }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_rekon_admin as $items => $item)
                        <tr>
                            @php
                                $kunci = 0;
                            @endphp
                            <td>{{ $loop->iteration }}</td>
                            @foreach ($item as $i => $isi)
                                @if ($i != 'NO' and $i != 'No')
                                    <td>{{ $isi }}</td>
                                    @php
                                        if ($kunci == 8) {
                                            $j1 += (int) $isi;
                                        }
                                        if ($kunci == 9) {
                                            $j2 += (int) $isi;
                                        }
                                        if ($kunci == 10) {
                                            $j3 += (int) $isi;
                                        }
                                        
                                        $kunci++;
                                    @endphp
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="9">
                            Total:
                        </th>
                        <th>{{ $j1 }}</th>
                        <th>{{ $j2 }}</th>
                        <th>{{ $j3 }}</th>
                    </tr>
                </tbody>
            </table>
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
