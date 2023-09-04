@extends('admin.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Edit Rekon</h1>
        <h3>
            {{ $data_maskapai->bandara ? $data_maskapai->bandara->user->name : '' }} -
            {{ $data_maskapai->user ? $data_maskapai->user->name : '' }}
        </h3>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            @if ($data_rekon->status != 1)
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form action="{{ route('admin.datarekon.update', $data_rekon->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="mb-3">
                                    <label>Bulan</label>
                                    <input type="month" name="bulan" class="form-control"
                                        value="{{ $data_rekon->bulan }}" readonly>
                                    @error('bulan')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label>Rekon Admin</label>
                                    <a href="{{ asset('storage/' . $data_rekon->rekon_admin) }}" target="_blank"
                                        class="btn btn-link"><i class="mdi mdi-eye  text-primary"></i>
                                        Lihat
                                    </a>
                                    <input type="file" name="rekon_admin" class="form-control"
                                        value="{{ old('rekon_admin') }}">
                                    <span class="text-muted">* Kosongkan jika tidak ingin diganti</span>
                                    @error('rekon_admin')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label>Rekon Maskapai</label>
                                    <a href="{{ asset('storage/' . $data_rekon->rekon_maskapai) }}" target="_blank"
                                        class="btn btn-link"><i class="mdi mdi-eye  text-primary"></i>
                                        Lihat
                                    </a>
                                    <input type="file" name="rekon_maskapai" class="form-control"
                                        value="{{ old('rekon_maskapai') }}">
                                    <span class="text-muted">* Kosongkan jika tidak ingin diganti</span>
                                    @error('rekon_maskapai')
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
            @endif
            <!-- Column -->
        </div>
    </div>
@endsection
