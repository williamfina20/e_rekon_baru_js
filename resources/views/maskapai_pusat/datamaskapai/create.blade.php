@extends('maskapai_pusat.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Tambah Maskapai {{ Auth::user()->name }}
        </h1>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('maskapai_pusat.datamaskapai.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label>Bandara</label>
                                <select name="bandara" class="form-control" required>
                                    <option value="">--Pilih--</option>
                                    @foreach ($bandara as $item)
                                        @php
                                            $cek = 0;
                                            if ($item->maskapai) {
                                                foreach ($item->maskapai as $m => $m2) {
                                                    if ($m2->maskapai_pusat_id == Auth::user()->id) {
                                                        $cek = $cek + 1;
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if ($cek < 1)
                                            <option value="{{ $item->id }}">{{ $item->user->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('bandara')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Nama Maskapai</label>
                                <input type="text" name="nama_maskapai" class="form-control"
                                    value="{{ old('nama_maskapai') }}">
                                @error('nama_maskapai')
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
                                <label>Alamat</label>
                                <textarea name="alamat" rows="2" class="form-control">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>No Telepon</label>
                                <input type="number" name="no_telepon" class="form-control"
                                    value="{{ old('no_telepon') }}">
                                @error('no_telepon')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                @error('email')
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
                                <a href="{{ route('maskapai_pusat.datamaskapai') }}"
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
