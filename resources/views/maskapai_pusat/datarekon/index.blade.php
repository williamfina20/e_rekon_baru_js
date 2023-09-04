@extends('maskapai_pusat.layouts.app')
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
                        </div>
                        <table class="table" id="example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Maskapai</th>
                                    <th>Bandara</th>
                                    <th>Jumlah Rekon</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_maskapai as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->user ? $item->user->name : '' }}</td>
                                        <td>{{ $item->bandara ? $item->bandara->user->name : '' }}</td>
                                        <td>{{ $item->rekon ? $item->rekon->count() : '' }}</td>
                                        <td>
                                            <a href="{{ route('maskapai_pusat.datarekon.show', $item->id) }}"
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
