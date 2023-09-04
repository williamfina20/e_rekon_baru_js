@extends('admin.layouts.app')
@section('content')
    <div class="mx-4 d-flex justify-content-between">
        <div>
            <h1 class="mb-0 fw-bold">Data Rekon </h1>
            <h3>{{ $bandara->user ? $bandara->user->name : '' }}</h3>
        </div>
        <div>
            <a href="{{ route('admin.datarekon') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body table-responsive">
                        <table class="table table-hover" id="example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Maskapai</th>
                                    <th>Jumlah Rekon</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($maskapai as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->user ? $item->user->name : '' }}</td>
                                        <td>{{ $item->rekon ? $item->rekon->count() : '' }}</td>
                                        <td>
                                            <a href="{{ route('admin.datarekon.show', $item->id) }}"
                                                class="btn btn-info btn-sm text-white">Lihat</a>
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
