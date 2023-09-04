@extends('admin.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Tambah Rekon</h1>
        <h3>
            {{ $data_maskapai->bandara ? $data_maskapai->bandara->user->name : '' }} -
            {{ $data_maskapai->user ? $data_maskapai->user->name : '' }}
        </h3>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.datarekon.store', $data_maskapai->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label>Bulan</label>
                                <input type="month" name="bulan" class="form-control" value="{{ old('bulan') }}">
                                @error('bulan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Rekon Admin</label>
                                <input type="file" name="rekon_admin" class="form-control"
                                    value="{{ old('rekon_admin') }}">
                                @error('rekon_admin')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Rekon Maskapai</label>
                                <input type="file" name="rekon_maskapai" class="form-control"
                                    value="{{ old('rekon_maskapai') }}">
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
            <!-- Column -->
        </div>
    </div>
@endsection
