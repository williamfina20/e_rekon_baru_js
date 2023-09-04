@extends('bandara.layouts.app')
@section('content')
    <div class="mx-4">
        <div class="row">
            <div class="col-8">
                <h1 class="mb-0 fw-bold">Data Rekon - {{ $data_maskapai->user ? $data_maskapai->user->name : '' }}</h1>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body table-responsive">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('bandara.datarekon.create', $data_maskapai->id) }}"
                                class="btn btn-primary btn-sm mb-2">Tambah</a>
                            <a href="{{ route('bandara.datarekon') }}" class="btn btn-secondary btn-sm mb-2">Kembali</a>
                        </div>
                        <table class="table" id="example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bulan</th>
                                    <th>Rekon Admin</th>
                                    <th>Rekon Maskapai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_rekon as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('F Y', strtotime($item->bulan)) }}</td>
                                        <td>
                                            @if (!$item->rekon_admin_text)
                                                Rekon Belum diupload
                                            @endif
                                            @if ($item->admin_acc)
                                                Selesai <i class="mdi mdi-check"></i>
                                            @elseif ($item->admin_status == 2)
                                                Telah Disetujui Pusat
                                            @elseif ($item->admin_status == 1)
                                                Menunggu Konfirmasi Pusat
                                            @elseif ($item->riwayat_rekon->count() != 0)
                                                Proses Rekon
                                            @elseif ($item->rekon_admin_text and $item->rekon_maskapai_text)
                                                Proses Rekon sudah dapat dilakukan
                                            @endif
                                        </td>
                                        <td>
                                            @if (!$item->rekon_maskapai_text)
                                                Rekon Belum diupload
                                            @endif
                                            @if ($item->maskapai_acc)
                                                Selesai <i class="mdi mdi-check"></i>
                                            @elseif ($item->maskapai_status == 2)
                                                Telah Disetujui Pusat
                                            @elseif ($item->maskapai_status == 1)
                                                Menunggu Konfirmasi Pusat
                                            @elseif ($item->riwayat_rekon->count() != 0)
                                                Proses Rekon
                                            @elseif ($item->rekon_admin_text and $item->rekon_maskapai_text)
                                                Proses Rekon sudah dapat dilakukan
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('bandara.datarekon.destroy', $item->id) }}"
                                                method="post">
                                                @csrf
                                                @method('delete')
                                                <a href="{{ route('bandara.datarekon.bandingkan', $item->id) }}"
                                                    class="btn btn-info btn-sm text-white @if (!$item->rekon_admin_text or !$item->rekon_maskapai_text) disabled @endif">
                                                    @if (!$item->admin_status or !$item->maskapai_status)
                                                        Bandingkan
                                                    @else
                                                        Lihat
                                                    @endif
                                                </a>
                                                @if ($item->admin_status == 2 and $item->maskapai_status == 2)
                                                    @if ($item->admin_acc and $item->maskapai_acc)
                                                        <a href="{{ route('admin.datarekon.lihat_berita', $item->id) }}"
                                                            target="_blank" class="btn btn-success text-white btn-sm">Berita
                                                            Acara 1</a>
                                                        <a href="{{ route('admin.datarekon.lihat_berita_2', $item->id) }}"
                                                            target="_blank" class="btn btn-success text-white btn-sm">Berita
                                                            Acara 2</a>
                                                    @endif
                                                @else
                                                    @if ($item->riwayat_rekon->count() == 0)
                                                        @if (!$item->admin_status and !$item->maskapai_status)
                                                            <a href="{{ route('bandara.datarekon.edit', $item->id) }}"
                                                                class="btn btn-warning btn-sm">Edit</a>
                                                        @endif
                                                    @endif
                                                    <button type="submit"
                                                        onclick="return confirm('Yakin ingin menghapus?')"
                                                        class="btn btn-danger text-white btn-sm">Hapus</button>
                                                @endif
                                            </form>
                                        </td>
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
@endsection
