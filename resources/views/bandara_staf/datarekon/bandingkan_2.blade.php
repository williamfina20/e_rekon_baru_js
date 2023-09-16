@extends('bandara_staf.layouts.app')
@section('content')
    <div class="mx-4">
        <div class="row">
            <div class="col-8">
                <h2 class="mb-0 fw-bold">Data Rekon</h2>
                <h4>
                    {{ $data_rekon->bandara->user ? $data_rekon->bandara->user->name : '' }} <i
                        class="mdi mdi-arrow-right"></i>
                    {{ $data_rekon->maskapai ? $data_rekon->maskapai->user->name : '' }}({{ date('F Y', strtotime($data_rekon->bulan)) }})
                </h4>
            </div>
            <div class="col-4 text-end">
                <a href="{{ route('bandara_staf.datarekon.show', $data_rekon->maskapai_id) }}"
                    class="btn btn-secondary btn-sm">Kembali</a>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div id="bandara_bandingkan_js"></div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            @php
                $data_rekon_id = $data_rekon->id;
                $user_id = Auth::user()->id;
            @endphp
        </div>
        <script>
            let rekon_id = <?= $data_rekon->id ?>;
            let user_id = <?= $user_id ?>;
            let user_tipe = 'bandara_staf'
        </script>
    </div>
@endsection
