@extends('bisnis.layouts.app')
@section('content')
    <div class="mx-4">
        <div class="row">
            <div class="col-8">
                <h1 class="mb-0 fw-bold">Data Rekon </h1>
                <h3>{{ $maskapai_pusat->name }} -
                    {{ date('F Y', strtotime($bulan)) }}</h3>
            </div>
            <div class="col-4 text-end">
                <a href="{{ route('bisnis.datarekon.maskapai', $maskapai_pusat->id) }}"
                    class="btn btn-secondary btn-sm">Kembali</a>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body table-responsive">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary text-white btn-sm mb-1" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Invoice
                        </button>
                        @php
                            $invoice_kosong = 0;
                        @endphp
                        <table class="table" id="example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bandara</th>
                                    <th>No Berita Acara</th>
                                    <th>Berita Acara</th>
                                    <th>Tanggal Berita Acara</th>
                                    <th>Total Produksi</th>
                                    <th>Estimasi Pendapatan</th>
                                    <th>Invoice</th>
                                    {{-- <th>Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rekon as $item)
                                    @php
                                        $berita_acara = App\Http\Controllers\Bisnis\DataRekonController::cek_berita_acara($item->id);
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->bandara ? $item->bandara->user->name : '' }}</td>
                                        <td>
                                            @if ($item->admin_acc and $item->maskapai_acc)
                                                {{ $berita_acara[0] }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->admin_acc and $item->maskapai_acc)
                                                @if ($item->no_invoice)
                                                    <a href="{{ route('admin.datarekon.lihat_berita', $item->id) }}"
                                                        target="_blank" class="btn btn-success text-white btn-sm m-1">Berita
                                                        Acara 1</a>
                                                    <a href="{{ route('admin.datarekon.lihat_berita_2', $item->id) }}"
                                                        target="_blank" class="btn btn-success text-white btn-sm m-1">Berita
                                                        Acara 2</a>
                                                @else
                                                    No Invoice Belum ditambahkan
                                                    @php
                                                        $invoice_kosong++;
                                                    @endphp
                                                @endif
                                            @else
                                                Proses Rekon
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->admin_acc and $item->maskapai_acc)
                                                @if ($item->admin_acc >= $item->maskapai_acc)
                                                    {{-- {{ Carbon\Carbon::parse($item->admin_acc)->isoFormat('MMMM Y') }} --}}
                                                    {{ date('H:i:s d/m/Y', strtotime($item->admin_acc)) }}
                                                @else
                                                    {{ date('H:i:s d/m/Y', strtotime($item->maskapai_acc)) }}
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->admin_acc and $item->maskapai_acc)
                                                {{ $berita_acara[1] }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->admin_acc and $item->maskapai_acc)
                                                {{ number_format((int) $berita_acara[1] * 2500, 0, ',', '.') }}
                                            @endif
                                        </td>
                                        <td>
                                            <span>
                                                @if ($item->no_invoice)
                                                    No Invoice: {{ $item->no_invoice }}
                                                @endif <br />
                                                @if ($item->no_faktur_pajak)
                                                    No Faktur Pajak: {{ $item->no_faktur_pajak }}
                                                @endif <br />
                                                @if ($item->users_invoice)
                                                    BS: {{ $item->users_invoice->name }}
                                                @endif <br />
                                                @if ($item->tanggal_invoice)
                                                    {{ date('H:i:s d/M/Y', strtotime($item->tanggal_invoice)) }}
                                                @endif
                                            </span>
                                        </td>
                                        {{-- <td>
                                            @if ($item->admin_acc and $item->maskapai_acc)
                                                @if (!$item->no_invoice)
                                                    <a href="{{ route('bisnis.datarekon.tambah_invoice', $item->id) }}"
                                                        class="btn btn-warning text-white btn-sm">Invoice</a>
                                                @endif
                                            @endif
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        {{ $invoice_kosong > 0 ? 'Tipe Invoice' : 'Semua Invoice sudah selesai diinput' }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @if ($invoice_kosong > 0)
                    <div class="modal-body d-flex justify-content-between">
                        <a href="{{ route('bisnis.datarekon.one_invoice', $item->id) }}" class="btn btn-primary">One
                            Invoice</a>
                        <form action="{{ route('bisnis.datarekon.multiple_invoice', $item->id) }}">
                            <input type="hidden" name="bulan" value="{{ $bulan }}" />
                            <button type="submit" class="btn btn-info text-white">Multiple Invoice</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
