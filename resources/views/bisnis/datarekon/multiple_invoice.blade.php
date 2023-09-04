@extends('bisnis.layouts.app')
@section('content')
    <div class="mx-4 d-flex justify-content-between">
        <div>
            <h1 class="mb-0 fw-bold">Multiple Invoice</h1>
            <h3>{{ $maskapai_pusat->name }} -
                {{ date('F Y', strtotime($bulan)) }}</h3>
        </div>
        <div>
            <form action="{{ route('bisnis.datarekon.show', $maskapai_pusat->id) }}" method="get">
                <input type="hidden" name="bulan" value="{{ $bulan }}" />
                <button class="btn btn-secondary btn-sm" type="submit">Kembali</button>
            </form>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('bisnis.datarekon.multiple_invoice_simpan', $data_rekon->id) }}"
                            method="post">
                            @csrf
                            <div class="row d-flex">
                                @foreach ($rekon as $item)
                                    @if (!$item->no_invoice)
                                        <h5>{{ $item->bandara ? $item->bandara->user->name : '' }}</h5>
                                        <div class="mb-3 col-6">
                                            <label>No Invoice</label>
                                            <input type="text" name="no_invoice[{{ $item->id }}]"
                                                oninput="myFunction(value,{{ $item->id }})" id="no_invoice"
                                                class="form-control">
                                            @error('no_invoice')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-6">
                                            <label>No Faktur Pajak</label>
                                            <input type="text" name="no_faktur_pajak[{{ $item->id }}]"
                                                id="no_faktur_pajak[{{ $item->id }}]" class="form-control" readonly>
                                            @error('no_faktur_pajak')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <hr />
                                    @endif
                                @endforeach
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
    <script>
        function myFunction(isi, id) {
            if (isi != '') {
                document.getElementById(`no_faktur_pajak[${id}]`).readOnly = false;
                document.getElementById(`no_faktur_pajak[${id}]`).required = true;
            } else {
                document.getElementById(`no_faktur_pajak[${id}]`).readOnly = true;
                document.getElementById(`no_faktur_pajak[${id}]`).required = false;
            }
        }
    </script>
@endsection
{{-- <!DOCTYPE html>
<html>

<body>
    <h2>Enter Some Text</h2>

    <p>Event will be fired when the input element lose focus after value update.</p>
    <input type="text" name="txt" value="" onchange="myFunction(this.value)">
    <p id='show'></p>

    <script>
        function myFunction(val) {
            var x = document.getElementById('show');
            x.innerHTML = "Entered Value is: " + val;
        }
    </script>
</body>

</html> --}}
