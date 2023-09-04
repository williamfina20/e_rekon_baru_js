@extends('maskapai_staf.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Beranda Staf Maskapai</h1>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center">
                        DATA REKONSILIASI
                        <br />
                        {{ Auth::user()->name }}
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
    </div>
@endsection
