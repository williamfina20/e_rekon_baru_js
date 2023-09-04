@extends('bandara.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Profil</h1>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="mb-3">
                            <tr>
                                <td>Nama Bandara</td>
                                <td>: {{ Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <td>Kode Bandara</td>
                                <td>: {{ Auth::user()->bandara->kode_bandara }}</td>
                            </tr>
                            <tr>
                                <td>Wilayah</td>
                                <td>: {{ Auth::user()->bandara->wilayah }}</td>
                            </tr>
                            <tr>
                                <td>Nama Pimpinan</td>
                                <td>: {{ Auth::user()->bandara->nama_pimpinan }}</td>
                            </tr>
                            <tr>
                                <td>Jabatan Pimpinan</td>
                                <td>: {{ Auth::user()->bandara->jabatan_pimpinan }}</td>
                            </tr>
                            <tr>
                                <td>Kode Jabatan</td>
                                <td>: {{ Auth::user()->bandara->kode_jabatan }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>: {{ Auth::user()->bandara->alamat }}</td>
                            </tr>
                            <tr>
                                <td>No Telepon</td>
                                <td>: {{ Auth::user()->bandara->no_telepon }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>: {{ Auth::user()->bandara->email }}</td>
                            </tr>
                        </table>
                        <form action="{{ route('bandara.profil_update') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" value="{{ Auth::user()->email }}"
                                    class="form-control">
                                @error('username')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control">
                                <span class="text-muted">* Kosongkan jika tidak ingin diganti</span>
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
