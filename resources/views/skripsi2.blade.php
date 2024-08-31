<!-- resources/views/skripsi2.blade.php -->

@extends('adminlte::page')

@section('title', 'Daftar Skripsi')

@section('content_header')
    <h1 class="m-0 text-dark">Daftar Skripsi</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header">{{__('Data Skripsi')}}</div>
        <div class="card-body">
            @foreach($skripsi as $skripsis)
                <div class="row mb-3 p-3 border rounded" style="border-color: #e3e3e3;">
                    <div class="col-md-11">
                        <h5>{{$skripsis->penulis}}, <span class="font-weight-light">author</span></h5>
                        <h4><a href="/home/skripsi/detail/{{$skripsis->id}}">{{$skripsis->judul}}</a></h4>
                        <p>{{ Str::limit($skripsis->abstrak, 150) }}</p>
                        <p class="text-muted">
                            {{$skripsis->prodi}}, {{'Universitas Suryakancana'}}, {{$skripsis->rilis}}<br>
                        </p>

                        <!-- Form pencarian tanpa input, hanya dengan judul yang ada -->
                        <form action="{{ route('cariYangMirip') }}" method="GET" class="d-inline">
                            <input type="hidden" name="judul" value="{{$skripsis->judul}}">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-search"></i> Cari yang mirip
                            </button>
                        </form>

                        <!-- Bagian untuk Tambah/Hapus Favorite -->
                        @if(auth()->user()->favorites()->where('id_skripsi', $skripsis->id)->exists())
                            <form action="{{ route('removeFavorite1', $skripsis->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm mr-2">
                                    <i class="bi bi-star-fill"></i> Hapus dari Favorite
                                </button>
                            </form>
                        @else
                            <form action="{{ route('addFavorite', $skripsis->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning btn-sm mr-2">
                                    <i class="bi bi-star"></i> Tambah ke Favorite
                                </button>
                            </form>
                        @endif

                        <button class="btn btn-outline-primary btn-sm mr-2"><i class="fas fa-file-pdf"></i> Metadata PDF</button>
                        <button class="btn btn-outline-primary btn-sm mr-2"><i class="fas fa-file-pdf"></i> Abstrak PDF</button>
                        <button class="btn btn-outline-primary btn-sm"><i class="fas fa-file-alt"></i> Abstrak</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@stop
