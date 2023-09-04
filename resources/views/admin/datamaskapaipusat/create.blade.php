@extends('admin.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Tambah Maskapai Pusat</h1>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.datamaskapaipusat.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}">
                                @error('nama')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Nama Pimpinan</label>
                                <input type="text" name="nama_pimpinan" class="form-control"
                                    value="{{ old('nama_pimpinan') }}">
                                @error('nama_pimpinan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Jabatan Pimpinan</label>
                                <input type="text" name="jabatan_pimpinan" class="form-control"
                                    value="{{ old('jabatan_pimpinan') }}">
                                @error('jabatan_pimpinan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Dasar Hukum</label>
                                <input type="text" name="dasar_hukum" class="form-control"
                                    value="{{ old('dasar_hukum') }}">
                                @error('dasar_hukum')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Harga</label>
                                <input type="number" name="harga" class="form-control" value="{{ old('harga') }}">
                                @error('harga')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" value="{{ old('username') }}">
                                @error('username')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" value="{{ old('password') }}">
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <a href="{{ route('admin.datamaskapaipusat') }}"
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
