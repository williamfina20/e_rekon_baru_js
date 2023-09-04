@extends('maskapai_pusat.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Edit Maskapai {{ Auth::user()->name }}
        </h1>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('maskapai_pusat.datamaskapai.update', $data_maskapai->id) }}" method="post">
                            @csrf
                            @method('put')
                            <div class="mb-3">
                                <label>Bandara</label>
                                <select name="bandara" class="form-control" required>
                                    <option value="{{ $data_maskapai->bandara_id }}">
                                        {{ $data_maskapai->bandara->user->name }}
                                    </option>
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
                                    value="{{ $data_maskapai->user->name }}">
                                @error('nama_maskapai')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Nama Pimpinan</label>
                                <input type="text" name="nama_pimpinan" class="form-control"
                                    value="{{ $data_maskapai->nama_pimpinan }}">
                                @error('nama_pimpinan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Jabatan Pimpinan</label>
                                <input type="text" name="jabatan_pimpinan" class="form-control"
                                    value="{{ $data_maskapai->jabatan_pimpinan }}">
                                @error('jabatan_pimpinan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Kode Jabatan</label>
                                <input type="text" name="kode_jabatan" class="form-control"
                                    value="{{ $data_maskapai->kode_jabatan }}">
                                @error('kode_jabatan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Alamat</label>
                                <textarea name="alamat" rows="2" class="form-control">{{ $data_maskapai->alamat }}</textarea>
                                @error('alamat')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>No Telepon</label>
                                <input type="number" name="no_telepon" class="form-control"
                                    value="{{ $data_maskapai->no_telepon }}">
                                @error('no_telepon')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ $data_maskapai->email }}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control"
                                    value="{{ $data_maskapai->user->email }}">
                                @error('username')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" value="{{ old('password') }}">
                                <div class="fs-6 text-muted">*Kosongkan jika tidak ingin diganti</div>
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
