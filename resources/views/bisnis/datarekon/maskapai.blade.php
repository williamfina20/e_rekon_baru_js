@extends('bisnis.layouts.app')
@section('content')
    <div class="mx-4 d-flex justify-content-between">
        <div>
            <h1 class="mb-0 fw-bold">Data Rekon </h1>
            <h3>{{ $maskapai_pusat->name }}</h3>
        </div>
        <div>
            <a href="{{ route('bisnis.datarekon') }}" class="btn btn-secondary btn-sm">Kembali</a>
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
                                    <th>Periode</th>
                                    <th>Jumlah Berita Acara</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    
                                @endphp
                                @foreach ($periode_jumlah as $items => $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('F Y', strtotime($items)) }}</td>
                                        <td>{{ $item }}</td>
                                        <td>
                                            <form action="{{ route('bisnis.datarekon.show', $maskapai_pusat->id) }}"
                                                method="get">
                                                <input type="hidden" name="bulan" value="{{ $items }}" />
                                                <button class="btn btn-info btn-sm text-white" type="submit">Lihat</button>
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
