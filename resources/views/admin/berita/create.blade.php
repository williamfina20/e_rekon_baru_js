@extends('admin.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Tambah Format Berita</h1>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.berita') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}">
                                @error('nama')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Isi</label>
                                <textarea class="form-control" name="isi" id="editor1" rows="10"></textarea>
                                @error('isi')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <a href="{{ route('admin.berita') }}" class="btn btn-secondary btn-sm">Kembali</a>
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
