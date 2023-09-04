@extends('bandara.layouts.app')
@section('content')
    <div class="mx-4">
        <h1 class="mb-0 fw-bold">Edit Rekon - {{ $data_maskapai->user ? $data_maskapai->user->name : '' }}</h1>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            @if ($data_rekon->status != 1)
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div id="bandara_rekon_edit"></div>
                            @php
                                $data_rekon_2 = json_encode($data_rekon);
                                $data_maskapai_2 = json_encode($data_maskapai);
                            @endphp
                        </div>
                    </div>
                </div>
            @endif
            <!-- Column -->
        </div>
        <script>
            window.data_rekon = <?= $data_rekon_2 ?>;
            window.data_maskapai = <?= $data_maskapai_2 ?>;
        </script>
    </div>
@endsection
