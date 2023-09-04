@extends('admin.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Data Monitoring</h1>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body table-responsive">
                        <a href="{{ route('admin.pimpinan.create') }}" class="btn btn-primary btn-sm mb-2">Tambah</a>
                        <table class="table" id="example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Divisi</th>
                                    <th>Username</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pimpinan as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->jabatan_pimpinan }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>
                                            <form action="{{ route('admin.pimpinan.destroy', $item->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <a href="{{ route('admin.pimpinan.edit', $item->id) }}"
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
