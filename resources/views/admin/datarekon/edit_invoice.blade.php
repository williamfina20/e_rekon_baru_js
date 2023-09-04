@extends('admin.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Edit Invoice</h1>
        <h3>
            {{ $data_maskapai->bandara ? $data_maskapai->bandara->user->name : '' }} -
            {{ $data_maskapai->user ? $data_maskapai->user->name : '' }} -
            {{ date('F Y', strtotime($data_rekon->bulan)) }}
        </h3>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.datarekon.update_invoice', $data_rekon->id) }}" method="post">
                            @method('put')
                            @csrf
                            <div class="mb-3">
                                <label>No Invoice</label>
                                <input type="text" name="no_invoice" class="form-control"
                                    value="{{ $data_rekon->no_invoice }}">
                                @error('no_invoice')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>No Faktur Pajak</label>
                                <input type="text" name="no_faktur_pajak" class="form-control"
                                    value="{{ $data_rekon->no_faktur_pajak }}">
                                @error('no_faktur_pajak')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <a href="{{ route('admin.datarekon.show', $data_maskapai->id) }}"
                                    class="btn btn-secondary btn-sm">Kembali</a>
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
