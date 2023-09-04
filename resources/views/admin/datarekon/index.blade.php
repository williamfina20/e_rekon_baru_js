@extends('admin.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Data Rekon</h1>
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
                                    <th>Nama Bandara</th>
                                    <th>Wilayah</th>
                                    <th>Jumlah Maskapai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_bandara as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->user ? $item->user->name : '' }}</td>
                                        <td>{{ $item->wilayah }}</td>
                                        <td>{{ $item->maskapai ? $item->maskapai->count() : '' }}</td>
                                        <td>
                                            <a href="{{ route('admin.datarekon.maskapai', $item->id) }}"
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
