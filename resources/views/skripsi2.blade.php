@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Data Skripsi</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-default">
    <div class="card-header">{{__('Data Skripsi')}}</div>
        <div class="card-body">
            <table id="table-data" class="table table-stripped text-center">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Abstrak</th>
                        <th>Dosen Pembimbing</th>
                        <th>Rilis</th>
                        <th>Halaman</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no=1; @endphp
                    @foreach($skripsi as $skripsis)
                    <tr>
                        <td>{{$no++}}</td>
                        <td>{{$skripsis->judul}}</td>
                        <td>{{$skripsis->penulis}}</td>
                        <td>{{ Str::limit($skripsis->abstrak, 20) }}</td>
                        <td>{{$skripsis->dospem}}</td>
                        <td>{{$skripsis->rilis}}</td>
                        <td>{{$skripsis->halaman}}</td>
                        <td>
                            <a type="button" id="btn-edit-skripsi" class="btn btn-sm btn-success" href="/home/skripsi/detail/{{$skripsis->id}}">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop