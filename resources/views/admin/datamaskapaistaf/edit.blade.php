@extends('admin.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Edit Staf @if ($maskapai->user)
                {{ $maskapai->user->name }}
            @endif
        </h1>
        <h3>{{ $maskapai->bandara->user->name }}</h3>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.datamaskapaistaf.update', $maskapai_staf->id) }}" method="post">
                            @csrf
                            @method('put')
                            <div class="mb-3">
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control"
                                    value="{{ $maskapai_staf->user->name }}">
                                @error('nama')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Jabatan Staf</label>
                                <input type="text" name="jabatan_staf" class="form-control"
                                    value="{{ $maskapai_staf->jabatan_staf }}">
                                @error('jabatan_staf')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Kode Jabatan</label>
                                <input type="text" name="kode_jabatan" class="form-control"
                                    value="{{ $maskapai_staf->kode_jabatan }}">
                                @error('kode_jabatan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control"
                                    value="{{ $maskapai_staf->user->email }}">
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
                                <a href="{{ route('admin.datamaskapaistaf', $maskapai->id) }}"
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
