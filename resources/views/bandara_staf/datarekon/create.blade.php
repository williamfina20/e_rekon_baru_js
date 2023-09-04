@extends('bandara_staf.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Tambah Rekon - {{ $data_maskapai->user ? $data_maskapai->user->name : '' }}</h1>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div id="bandara_staf_rekon_tambah"></div>
                        @php
                            $data_maskapai_2 = json_encode($data_maskapai);
                        @endphp

                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
        <script>
            window.data_maskapai = <?= $data_maskapai_2 ?>;
        </script>
    </div>
@endsection
