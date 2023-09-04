@extends('admin.layouts.app')
@section('content')
    <div class="mx-4">
        <div class="d-flex justify-content-between">
            <h1 class="mb-0 fw-bold">Laporan Bandara</h1>
            <div>
                <a href="{{ route('admin.laporan') }}" class="btn btn-secondary btn-sm">Kembali</a>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.laporan_bandara.cetak') }}" method="get">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Maskapai Pusat</label>
                                    <select name="maskapai_pusat" class="form-control">
                                        <option value="semua">semua</option>
                                        @foreach ($maskapai_pusat as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Periode</label>
                                    <select name="periode" class="form-control">
                                        <option value="semua">semua</option>
                                        @foreach ($periode as $item)
                                            <option value="{{ $item->bulan }}">{{ date('F Y', strtotime($item->bulan)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm">Cetak</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
    </div>
@endsection
