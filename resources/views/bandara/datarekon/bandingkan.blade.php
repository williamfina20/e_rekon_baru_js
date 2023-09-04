@extends('bandara.layouts.app')
@section('content')
    <div class="mx-4">
        <div class="row">
            <div class="col-8">
                <h2 class="mb-0 fw-bold">Data Rekon</h2>
                <h4>
                    {{ $data_rekon->bandara->user ? $data_rekon->bandara->user->name : '' }} <i
                        class="mdi mdi-arrow-right"></i>
                    {{ $data_rekon->maskapai ? $data_rekon->maskapai->user->name : '' }}({{ date('F Y', strtotime($data_rekon->bulan)) }})
                </h4>
            </div>
            <div class="col-4 text-end">
                <a href="{{ route('bandara.datarekon.show', $data_rekon->maskapai_id) }}"
                    class="btn btn-secondary btn-sm">Kembali</a>
            </div>
        </div>
    </div>
    @php
        use PhpOffice\PhpSpreadsheet\Shared\Date;
    @endphp
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <div class="d-flex">
                            <div class="d-flex me-5">
                                <i class="mdi mdi-play-circle text-primary"></i>
                                Rekon Admin
                            </div>
                            <div class="d-flex">
                                <i class="mdi mdi-play-circle text-danger"></i>
                                Rekon Maskapai
                            </div>
                        </div>
                        <div id="bandara_rekon_bandingkan"></div>
                        @php
                            $data_rekon_2 = json_encode($data_rekon);
                            $data_maskapai_2 = json_encode($data_maskapai);
                        @endphp
                        @if ($data_rekon->rekon_admin_text == $data_rekon->rekon_maskapai_text)
                            <div class="table-responsive">
                                @php
                                    $data_rekon_admin = json_decode($data_rekon->rekon_admin_text, true);
                                @endphp
                                <table class="table table-striped" id="example">
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
                                                @foreach ($item as $isi)
                                                    <td>{{ $isi }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if (!$data_rekon->status)
                                <form action="{{ route('bandara.datarekon.kirim', $data_rekon->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-success text-white btn-sm my-2">Kirim Rekon Ke
                                        Pusat</button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Riwayat Ubah</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayat_rekon as $item)
                                    <tr>
                                        <td>
                                            @php
                                                $riwayat_ubah = json_decode($item->riwayat_ubah);
                                            @endphp
                                            @if ($item->bandara)
                                                {{ $item->bandara->user->name }}
                                            @endif
                                            @if ($item->maskapai)
                                                {{ $item->maskapai->user->name }}
                                            @endif
                                            @foreach ($riwayat_ubah as $item2)
                                                <br />{{ 'Mengubah data ke-' . ((int) $item2->id + 1) . ' kolom ' . $item2->id_kolom . ' menjadi ' . $item2->isi }}
                                            @endforeach
                                        </td>
                                        <td>{{ date('H:i:s d/m/Y', strtotime($item->created_at)) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
        <script>
            window.data_rekon = <?= $data_rekon_2 ?>;
            window.data_maskapai = <?= $data_maskapai_2 ?>;
        </script>
    </div>
@endsection
