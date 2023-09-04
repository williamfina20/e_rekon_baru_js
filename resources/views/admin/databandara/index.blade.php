@extends('admin.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Data Bandara</h1>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body table-responsive">
                        <a href="{{ route('admin.databandara.create') }}" class="btn btn-primary btn-sm mb-2">Tambah</a>
                        <table class="table" id="example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kode Bandara</th>
                                    <th>Wilayah</th>
                                    <th>Username</th>
                                    <th>Jumlah Staf</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_bandara as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($item->user)
                                                {{ $item->user->name }}
                                            @endif
                                        </td>
                                        <td>{{ $item->kode_bandara }}</td>
                                        <td>{{ $item->wilayah }}</td>
                                        <td>
                                            @if ($item->user)
                                                {{ $item->user->email }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->bandara_staf)
                                                {{ $item->bandara_staf->count() }}
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.databandara.destroy', $item->id) }}"
                                                method="post">
                                                @csrf
                                                @method('delete')
                                                <a href="{{ route('admin.bandara_staf', $item->id) }}"
                                                    class="btn btn-info btn-sm text-white">Lihat Staf</a>
                                                <a href="{{ route('admin.databandara.edit', $item->id) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <button type="submit" onclick="return confirm('Yakin ingin menghapus?')"
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
