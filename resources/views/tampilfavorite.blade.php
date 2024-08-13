@extends('adminlte::page')

@section('title', 'Skripsi Favorite')

@section('content_header')
    <h1 class="m-0 text-dark">Skripsi Favorite</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header">{{__('Skripsi Favorite')}}</div>
        <div class="card-body">
            @if($favoriteSkripsi->isEmpty())
                <p>Belum ada skripsi yang ditambahkan ke favorite.</p>
            @else
                <table id="table-data" class="table table-striped text-center">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Dosen Pembimbing</th>
                            <th>Rilis</th>
                            <th>Halaman</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($favoriteSkripsi as $skripsi)
                        <tr>
                            <td>{{$no++}}</td>
                            <td>{{$skripsi->judul}}</td>
                            <td>{{$skripsi->penulis}}</td>
                            <td>{{$skripsi->dospem}}</td>
                            <td>{{$skripsi->rilis}}</td>
                            <td>{{$skripsi->halaman}}</td>
                            <td>
                                <a type="button" class="btn btn-sm btn-info" href="/home/skripsi/detail/{{$skripsi->id}}">
                                    <i class="fas fa-eye"></i> <!-- Ikon untuk Lihat Detail Skripsi -->
                                </a>
                                <form action="{{ route('removeFavorite', $skripsi->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus skripsi ini dari favorite?')">
                                        <i class="fas fa-trash-alt"></i> <!-- Ikon untuk Hapus Favorite -->
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@stop
