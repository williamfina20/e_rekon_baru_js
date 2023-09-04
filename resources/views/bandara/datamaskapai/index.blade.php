@extends('bandara.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Data Maskapai
        </h1>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body table-responsive">
                        <div class="d-flex justify-content-between">
                            {{-- <a href="{{ route('bandara.datamaskapai.create') }}"
                                class="btn btn-primary btn-sm mb-2">Tambah</a> --}}
                        </div>
                        <table class="table" id="example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Maskapai Pusat</th>
                                    <th>Nama Maskapai</th>
                                    <th>Username</th>
                                    {{-- <th>Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_maskapai as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->maskapai_pusat ? $item->maskapai_pusat->name : '' }}</td>
                                        <td>{{ $item->user ? $item->user->name : '' }}</td>
                                        <td>{{ $item->user ? $item->user->email : '' }}</td>
                                        {{-- <td>
                                            <form action="{{ route('bandara.datamaskapai.destroy', $item->id) }}"
                                                method="post">
                                                @csrf
                                                @method('delete')
                                                <a href="{{ route('bandara.datamaskapai.edit', $item->id) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <button type="submit" onclick="return confirm('Yakin ingin menghapus?')"
                                                    class="btn btn-danger text-white btn-sm">Hapus</button>
                                            </form>
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
@endsection
