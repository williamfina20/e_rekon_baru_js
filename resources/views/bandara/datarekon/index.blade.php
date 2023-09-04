@extends('bandara.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Data Rekon
        </h1>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body table-responsive">
                        <div class="d-flex justify-content-between">
                            {{-- <a href="{{ route('admin.datamaskapai.create', $bandara->id) }}"
                                class="btn btn-primary btn-sm mb-2">Tambah</a>
                            <a href="{{ route('admin.datamaskapai') }}" class="btn btn-secondary btn-sm mb-2">Kembali</a> --}}
                        </div>
                        <table class="table" id="example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Maskapai</th>
                                    <th>Username</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_maskapai as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->user ? $item->user->name : '' }}</td>
                                        <td>{{ $item->user ? $item->user->email : '' }}</td>
                                        <td>
                                            <a href="{{ route('bandara.datarekon.show', $item->id) }}"
                                                class="btn btn-info text-white btn-sm">Lihat</a>
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
