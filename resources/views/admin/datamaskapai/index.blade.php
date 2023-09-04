@extends('admin.layouts.app')
@section('content')
    <div class="mx-4">
        <div class="d-flex justify-content-between">
            <div>
                <h1 class="mb-0 fw-bold">Data Maskapai</h1>
                <h3>{{ $maskapai_pusat->name }}</h3>
            </div>
            <div>
                <a href="{{ route('admin.datamaskapaipusat') }}" class="btn btn-secondary btn-sm mb-2">Kembali</a>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body table-responsive">
                        <a href="{{ route('admin.datamaskapai.create', $maskapai_pusat->id) }}"
                            class="btn btn-primary btn-sm mb-2">Tambah</a>
                        <table class="table" id="example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Maskapai</th>
                                    <th>Bandara</th>
                                    <th>Maskapai Pimpinan</th>
                                    <th>Username</th>
                                    <th>Jumlah Staf</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_maskapai as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->user ? $item->user->name : '' }}</td>
                                        <td>{{ $item->bandara->user ? $item->bandara->user->name : '' }}</td>
                                        <td>{{ $item->nama_pimpinan }}</td>
                                        <td>{{ $item->user ? $item->user->email : '' }}</td>
                                        <td>
                                            @if ($item->maskapai_staf)
                                                {{ $item->maskapai_staf->count() }}
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.datamaskapai.destroy', $item->id) }}"
                                                method="post">
                                                @csrf
                                                @method('delete')
                                                <a href="{{ route('admin.datamaskapaistaf', $item->id) }}"
                                                    class="btn btn-info btn-sm text-white">Lihat Staf</a>
                                                <a href="{{ route('admin.datamaskapai.edit', $item->id) }}"
                                                    class="btn btn-warning btn-sm text-white">Edit</a>
                                                <button type="submit"
                                                    onclick="return confirm(`Yakin ingin menghapus {{ $item->name }} ?`)"
                                                    class="btn btn-danger text-white btn-sm">Hapus</button>
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
