@extends('bisnis.layouts.app')
@section('content')
    <div class="mx-4 d-flex justify-content-between">
        <div>
            <h1 class="mb-0 fw-bold">One Invoice</h1>
            <h3>
                {{ $data_maskapai->maskapai_pusat ? $data_maskapai->maskapai_pusat->name : '' }} -
                {{ date('F Y', strtotime($data_rekon->bulan)) }}
            </h3>
        </div>
        <div>
            <form action="{{ route('bisnis.datarekon.show', $data_maskapai->maskapai_pusat->id) }}" method="get">
                <input type="hidden" name="bulan" value="{{ $data_rekon->bulan }}" />
                <button class="btn btn-secondary btn-sm" type="submit">Kembali</button>
            </form>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('bisnis.datarekon.one_invoice_simpan', $data_rekon->id) }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label>No Invoice</label>
                                <input type="text" name="no_invoice" class="form-control">
                                @error('no_invoice')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>No Faktur Pajak</label>
                                <input type="text" name="no_faktur_pajak" class="form-control">
                                @error('no_faktur_pajak')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
    </div>
@endsection
