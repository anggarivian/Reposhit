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
                <iframe src="data:application/pdf;base64,{{ $pdf }}#toolbar=0&navpanes=0&view=fitH" width="100%" height="600px"></iframe>
                {{-- <a href="{{ route('pdf.show', ['id' => $skripsi->id]) }}"  target="_blank">
                    <button class="btn btn-info m-3">
                        Lihat Skripsi
                    </button>
                </a> --}}
            </div>
        </div>
    </div>
</div>
@stop