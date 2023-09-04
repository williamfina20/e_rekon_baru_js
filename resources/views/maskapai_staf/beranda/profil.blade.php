@extends('maskapai_staf.layouts.app')
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
                                <td>Nama</td>
                                <td>: {{ Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>: {{ Auth::user()->maskapai_staf->jabatan_staf }}</td>
                            </tr>
                            <tr>
                                <td>Kode Jabatan</td>
                                <td>: {{ Auth::user()->maskapai_staf->kode_jabatan }}</td>
                            </tr>
                            <tr>
                                <td>Maskapai</td>
                                <td>: {{ Auth::user()->maskapai_staf->maskapai->user->name }}</td>
                            </tr>
                            <tr>
                                <td>Bandara</td>
                                <td>: {{ Auth::user()->maskapai_staf->maskapai->bandara->user->name }}</td>
                            </tr>
                        </table>
                        <form action="{{ route('maskapai_staf.profil_update') }}" method="post">
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
