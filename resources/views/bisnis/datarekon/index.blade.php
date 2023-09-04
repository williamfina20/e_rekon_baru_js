@extends('bisnis.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Rekon Bisnis Support</h1>
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
                                    <th>Nama Maskapai Pusat</th>
                                    <th>Jumlah Berita Acara</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($maskapai_pusat as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            @if ($item->maskapai_pusat_to_maskapai)
                                                {{ $item->maskapai_pusat_to_maskapai->rekon->count() }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->maskapai_pusat_to_maskapai)
                                                <a href="{{ route('bisnis.datarekon.maskapai', $item->id) }}"
                                                    class="btn btn-info btn-sm text-white">Lihat</a>
                                            @endif
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
