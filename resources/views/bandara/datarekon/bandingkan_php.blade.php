@extends('bandara.layouts.app')
@section('content')
    <div class="mx-4">
        <div class="row">
            <div class="col-8">
                <h2 class="mb-0 fw-bold">Data Rekon</h2>
                {{-- <h4>
                    {{ $data_rekon->bandara->user ? $data_rekon->bandara->user->name : '' }} <i
                        class="mdi mdi-arrow-right"></i>
                    {{ $data_rekon->maskapai ? $data_rekon->maskapai->user->name : '' }}({{ date('F Y', strtotime($data_rekon->bulan)) }})
                </h4> --}}
            </div>
            <div class="col-4 text-end">
                <a href="{{ route('bandara.datarekon.show', $data_rekon->maskapai_id) }}"
                    class="btn btn-secondary btn-sm">Kembali</a>
            </div>
        </div>
    </div>
    {{-- @php
        use PhpOffice\PhpSpreadsheet\Shared\Date;
    @endphp --}}
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        @php
                            $jumlah_error_bandara = 0;
                        @endphp
                        <table id="example">
                            <thead>
                                <tr>
                                    @foreach ($data_a[0] as $items => $item)
                                        @if ($items != 'NO')
                                            <th>{{ $items }}</th>
                                        @endif
                                    @endforeach
                                    {{-- <th>Keterangan</th> --}}
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_b as $b_items => $b_item)
                                    @if (!in_array($b_item['AWB'], $tampung_kunci))
                                        <tr class="text-info">
                                            @foreach ($b_item as $b_kunci => $b_isi)
                                                @if ($b_kunci != 'NO')
                                                    <td>{{ $b_isi }}</td>
                                                @endif
                                            @endforeach
                                            {{-- <td>Data AWB Maskapai yang tidak ada di Bandara</td> --}}
                                            <td>
                                                @php
                                                    $jumlah_error_bandara++;
                                                @endphp
                                                <form
                                                    action="{{ route('bandara.datarekon.bandingkan_tambah', $data_rekon->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('put')
                                                    <input type="hidden" name="baris_id" value="{{ $b_items }}">
                                                    <button type="submit" class="btn btn-info btn-sm"
                                                        onclick="return confirm('Yakin Ingin Ditambahkan')">Tambah</a>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                {{-- =========================================== --}}
                                @foreach ($data_a as $a_items => $a_item)
                                    @if ($a_validasi_awb_tidak_ada[$a_items] == 'tidak')
                                        <tr class="text-danger">
                                            @foreach ($a_item as $a_kunci => $a_isi)
                                                @if ($a_kunci != 'NO')
                                                    <td>{{ $a_isi }}</td>
                                                @endif
                                            @endforeach
                                            {{-- <td>Data AWB tidak ditemukan</td> --}}
                                            <td>
                                                @php
                                                    $jumlah_error_bandara++;
                                                @endphp
                                                <form
                                                    action="{{ route('bandara.datarekon.bandingkan_hapus', $data_rekon->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('put')
                                                    <input type="hidden" name="baris_id" value="{{ $a_items }}">
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Yakin Ingin Menghapus')">Hapus</a>
                                                </form>
                                            </td>
                                        </tr>
                                    @else
                                        @if ($a_validasi_awb_sama[$a_items] > 1)
                                            <tr class="text-warning">
                                                @foreach ($a_item as $a_kunci => $a_isi)
                                                    @if ($a_kunci != 'NO')
                                                        <td>{{ $a_isi }}</td>
                                                    @endif
                                                @endforeach
                                                {{-- <td>Terdapat Data AWB yang sama</td> --}}
                                                <td>
                                                    @php
                                                        $jumlah_error_bandara++;
                                                    @endphp
                                                    <button type="button"
                                                        onclick="UpdateData({{ $a_items }},{{ json_encode($a_item) }})"
                                                        class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal">
                                                        Edit
                                                    </button>
                                                    <form
                                                        action="{{ route('bandara.datarekon.bandingkan_hapus', $data_rekon->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('put')
                                                        <input type="hidden" name="baris_id" value="{{ $a_items }}">
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Yakin Ingin Menghapus')">Hapus</a>
                                                    </form>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                @foreach ($data_b as $b_items => $b_item)
                                                    @if (in_array($a_item['AWB'], $b_item))
                                                        @php
                                                            $jumlah_kolom_error = 0;
                                                        @endphp
                                                        @foreach ($a_item as $a_kunci => $a_isi)
                                                            @if ($a_isi == $b_item[$a_kunci])
                                                                @if ($a_kunci != 'NO')
                                                                    <td>{{ $a_isi }}</td>
                                                                @endif
                                                            @else
                                                                @if ($a_kunci != 'NO')
                                                                    @php
                                                                        $jumlah_kolom_error++;
                                                                    @endphp
                                                                    <td>
                                                                        <div class="d-flex">
                                                                            {{ $a_isi }}
                                                                            <i class="mdi mdi-arrow-right text-info"></i>
                                                                            {{ $b_item[$a_kunci] }}
                                                                        </div>
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                        @if ($jumlah_kolom_error > 0)
                                                            {{-- <td>Data Yang berbeda</td> --}}
                                                            <td>
                                                                @php
                                                                    $jumlah_error_bandara++;
                                                                @endphp
                                                                <button type="button"
                                                                    onclick="UpdateData({{ $a_items }},{{ json_encode($a_item) }},{{ json_encode($b_item) }})"
                                                                    class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                                    data-bs-target="#exampleModal">
                                                                    Edit
                                                                </button>
                                                            </td>
                                                        @else
                                                            {{-- <td></td> --}}
                                                            <td></td>
                                                        @endif
                                                    @break
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                            {{-- ========================================================= --}}
                        </tbody>
                    </table>
                    {{-- ============================================ --}}
                    {{-- ============================================ --}}
                    <div>
                        <div class="my-2">
                            @if (!$data_rekon->admin_status)
                                @if ($jumlah_error_bandara == 0 and $jumlah_error_maskapai == 0)
                                    <form action="{{ route('bandara.datarekon.kirim', $data_rekon->id) }}"
                                        method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-primary text-white">Kirim Rekon Ke
                                            Pusat <i class="mdi mdi-check"></i></button>
                                    </form>
                                @endif
                            @elseif($data_rekon->admin_status == 2)
                                @if (!$data_rekon->admin_acc)
                                    <form action="{{ route('bandara.datarekon.persetujuan', $data_rekon->id) }}"
                                        method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-success text-white">Persetujuan <i
                                                class="mdi mdi-check"></i></button>
                                    </form>
                                @endif
                            @endif
                        </div>
                        Keterangan:
                        <table width="50%">
                            <tr>
                                <td class="fw-bold">Jumlah Data</td>
                                <td>:</td>
                                <td class="fw-bold">{{ count($data_a) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Bandara Error</td>
                                <td>:</td>
                                <td class="fw-bold">{{ $jumlah_error_bandara }}</td>
                            </tr>
                            {{-- <tr>
                                <td class="fw-bold">Maskapai Error</td>
                                <td>:</td>
                                <td class="fw-bold">{{ $jumlah_error_maskapai }}</td>
                            </tr> --}}
                            <tr>
                                <td class="text-info">Text Biru</td>
                                <td>:</td>
                                <td>Data AWB Maskapai yang tidak ada di Bandara</td>
                            </tr>
                            <tr>
                                <td class="text-danger">Text Merah</td>
                                <td>:</td>
                                <td>Data AWB tidak ditemukan</td>
                            </tr>
                            <tr>
                                <td class="text-warning">Text Orange</td>
                                <td>:</td>
                                <td>Terdapat Data AWB yang sama </td>
                            </tr>
                            <tr>
                                <td><i class="mdi mdi-arrow-right text-info"></i></td>
                                <td>:</td>
                                <td>Data Yang berbeda</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body table-responsive">
                    <h4 class="text-muted mb-3">Perubahan Rekon</h4>
                    <table class="table" id="example2">
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
        <!-- Column -->
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('bandara.datarekon.bandingkan_edit', $data_rekon->id) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="baris_id" class="form-control" id="baris_id">
                        <input type="hidden" name="akun_id" class="form-control" value="{{ Auth::user()->id }}">
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($data_a[0] as $items => $item)
                            @if ($items != 'NO')
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <label>{{ $items }}</label>
                                        <span class="text-muted" id="perbedaan{{ $items }}"></span>
                                    </div>
                                    <input type="text" name="data_edit[{{ $items }}]"
                                        id="{{ $items }}" class="form-control" value="">
                                </div>
                            @else
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        {{-- <label>{{ $items }}</label> --}}
                                        <span class="text-muted" id="perbedaan{{ $items }}"></span>
                                    </div>
                                    <input type="hidden" name="data_edit[{{ $items }}]"
                                        id="{{ $items }}" class="form-control" value="" readonly>
                                </div>
                            @endif
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function UpdateData(baris_id, baris_a, baris_b = false) {
            for (let key in baris_a) {
                document.getElementById(`perbedaan${key}`).innerText = '';
                let inputdata = document.getElementById(key).value = baris_a[key];
                if (baris_b) {
                    if (baris_a[key].toString() !== baris_b[key].toString()) {
                        if (key != 'NO') {
                            document.getElementById(`perbedaan${key}`).innerText = `${baris_a[key]} -> ${baris_b[key]}`;
                        }
                    }
                }
            }
            document.getElementById('baris_id').value = baris_id;
        }
    </script>
</div>
@endsection
