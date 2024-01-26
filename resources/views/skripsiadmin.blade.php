@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
<h1 class="m-0 text-dark">Detail Skripsi</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 200px;">Judul Skripsi</td>
                        <td style="width: 20px;">:</td>
                        <td>{{$skripsi->judul}}</td>
                    </tr>
                    <tr>
                        <td style="width: 200px;">Penulis</td>
                        <td style="width: 20px;">:</td>
                        <td>{{$skripsi->penulis}}</td>
                    </tr>
                    <tr>
                        <td style="width: 200px;">Dosen Pembimbing</td>
                        <td style="width: 20px;">:</td>
                        <td>{{$skripsi->dospem}}</td>
                    </tr>
                    <tr>
                        <td style="width: 200px;">Abstrak</td>
                        <td style="width: 20px;">:</td>
                        <td>{{$skripsi->abstrak}}</td>
                    </tr>
                    <tr>
                        <td style="width: 200px;">Rilis Tahun</td>
                        <td style="width: 20px;">:</td>
                        <td>{{$skripsi->rilis}}</td>
                    </tr>
                    <tr>
                        <td style="width: 200px;">Halaman</td>
                        <td style="width: 20px;">:</td>
                        <td>{{$skripsi->halaman}}</td>
                    </tr>
                </table>
                <hr>
                {{-- Tombol untuk menampilkan/menyembunyikan iframe --}}
                @foreach($pdfs as $attribute => $pdf)
                @php
                    $label = $attribute == 'dapus'
                        ? 'Daftar Pustaka'
                        : (strpos($attribute, 'bab') === 0
                            ? 'Bab ' . (
                                $attribute == 'bab1' ? 'I' : (
                                    $attribute == 'bab2' ? 'II' : (
                                        $attribute == 'bab3' ? 'III' : (
                                            $attribute == 'bab4' ? 'IV' : (
                                                $attribute == 'bab5' ? 'V' : $attribute
                                            )
                                        )
                                    )
                                )
                            )
                            : $attribute
                        );
                @endphp
                <button class="btn btn-info m-3 showPdfButton" data-target="{{ $attribute }}">
                    Lihat {{ ucfirst($label) }}
                </button>
            @endforeach

            {{-- Menampilkan semua PDF --}}
            @foreach($pdfs as $attribute => $pdf)
                @php
                    $label = $attribute == 'dapus'
                        ? 'Daftar Pustaka'
                        : (strpos($attribute, 'bab') === 0
                            ? 'Bab ' . (
                                $attribute == 'bab1' ? 'I' : (
                                    $attribute == 'bab2' ? 'II' : (
                                        $attribute == 'bab3' ? 'III' : (
                                            $attribute == 'bab4' ? 'IV' : (
                                                $attribute == 'bab5' ? 'V' : $attribute
                                            )
                                        )
                                    )
                                )
                            )
                            : $attribute
                        );
                @endphp
                <h2 id="{{ $attribute }}Header" style="display: none;">{{ ucfirst($label) }}</h2>
                <!-- Sembunyikan iframe dengan ID sesuai dengan atribut -->
                <iframe id="{{ $attribute }}Frame" src="data:application/pdf;base64,{{ $pdf }}#toolbar=0&navpanes=0&view=fitH" width="100%" height="600px" style="display: none;"></iframe>
            @endforeach
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
    <script>
        document.querySelectorAll('.showPdfButton').forEach(function(button) {
            button.addEventListener('click', function() {
                var targetAttribute = this.getAttribute('data-target');

                // Sembunyikan semua iframe dan header (nama atribut) terlebih dahulu
                document.querySelectorAll('iframe').forEach(function(iframe) {
                    iframe.style.display = 'none';
                });

                document.querySelectorAll('h2').forEach(function(header) {
                    header.style.display = 'none';
                });

                // Tampilkan iframe dan header yang sesuai dengan tombol yang ditekan
                var pdfFrame = document.getElementById(targetAttribute + 'Frame');
                var pdfHeader = document.getElementById(targetAttribute + 'Header');

                pdfFrame.style.display = 'block';
                pdfHeader.style.display = 'block';
            });
        });
    </script>
@stop