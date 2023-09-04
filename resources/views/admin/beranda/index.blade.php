@extends('admin.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Beranda</h1>
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
                        <div class="row mt-3 align-items-center">
                            <div class="col-md-4 px-1">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="d-md-flex justify-content-around align-items-center">
                                            <div class="text-center">
                                                <i class="mdi mdi-airplane" style="font-size: 40px;"></i>
                                            </div>
                                            <div class="text-center">
                                                <h6>Data Maskapai Pusat</h6>
                                                <h1>{{ DB::table('users')->where('level', 'maskapai_pusat')->count() }}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 px-1">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="d-md-flex justify-content-around align-items-center">
                                            <div class="text-center">
                                                <i class="mdi mdi-airplane-landing" style="font-size: 40px;"></i>
                                            </div>
                                            <div class="text-center">
                                                <h6>Data Bandara</h6>
                                                <h1>{{ DB::table('bandara')->count() }}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 px-1">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="d-md-flex justify-content-around align-items-center">
                                            <div class="text-center">
                                                <i class="mdi mdi-account-circle" style="font-size: 40px;"></i>
                                            </div>
                                            <div class="text-center">
                                                <h6>Data Monitoring</h6>
                                                <h1>{{ DB::table('users')->where('level', 'pimpinan')->count() }}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 px-1">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="d-md-flex justify-content-around align-items-center">
                                            <div class="text-center">
                                                <i class="mdi mdi-account-settings" style="font-size: 40px;"></i>
                                            </div>
                                            <div class="text-center">
                                                <h6>Bisnis Support</h6>
                                                <h1>{{ DB::table('users')->where('level', 'bisnis')->count() }}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 px-1">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="d-md-flex justify-content-around align-items-center">
                                            <div class="text-center">
                                                <i class="mdi mdi-table" style="font-size: 40px;"></i>
                                            </div>
                                            <div class="text-center">
                                                <h6>Data Rekon</h6>
                                                <h1>{{ DB::table('rekons')->count() }}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
    </div>
@endsection
