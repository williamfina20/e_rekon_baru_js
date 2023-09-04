@extends('admin.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Laporan</h1>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12 text-start text-md-center">
                <a href="{{ route('admin.laporan_bandara') }}" class="py-2 btn btn-outline-primary m-1">
                    <i class="mdi mdi-airplane-landing"></i> Laporan Bandara
                </a>
                <a href="{{ route('admin.laporan_maskapai') }}" class="py-2 btn btn-outline-primary m-1">
                    <i class="mdi mdi-airplane"></i> Laporan Maskapai
                </a>
                <a href="{{ route('admin.laporan_periode') }}" class="py-2 btn btn-outline-primary m-1">
                    <i class="mdi mdi-timelapse"></i> Laporan Periode
                </a>
            </div>
            <!-- Column -->
        </div>
    </div>
@endsection
