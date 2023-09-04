@extends('admin.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Tambah Maskapai</h1>
        <h3>{{ $maskapai_pusat->name }}</h3>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.datamaskapai.store', $maskapai_pusat->id) }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label>Bandara</label>
                                <select name="bandara" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach ($data_bandara as $item)
                                        <option value="{{ $item->id }}">{{ $item->user->name }}</option>
                                    @endforeach
                                </select>
                                @error('bandara')
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
                                <label>Kode Jabatan</label>
                                <input type="text" name="kode_jabatan" class="form-control"
                                    value="{{ old('kode_jabatan') }}">
                                @error('kode_jabatan')
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
                                <a href="{{ route('admin.datamaskapai', $maskapai_pusat->id) }}"
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
