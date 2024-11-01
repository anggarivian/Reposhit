@extends('adminlte::page')

@section('title', 'Daftar Skripsi')

@section('content_header')
    <h1 class="m-0 text-dark">Daftar Skripsi</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header">
            <!-- Form Pencarian -->
            <form action="{{ route('searchSkripsi') }}" method="GET" class="form-inline float-right">
                <input type="text" name="judul" class="form-control form-control-sm mr-2" placeholder="Judul" value="{{ request('judul') }}">
                <input type="text" name="penulis" class="form-control form-control-sm mr-2" placeholder="Penulis" value="{{ request('penulis') }}">
                <input type="text" name="rilis" class="form-control form-control-sm mr-2" placeholder="Rilis" value="{{ request('rilis') }}">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-search"></i> Cari
                </button>
            </form>
        </div>
        <div class="card-body">
            @foreach($skripsi as $skripsis)
                <div class="row mb-3 p-3 border rounded" style="border-color: #e3e3e3;">
                    <div class="col-md-11">
                        <h5>{{$skripsis->penulis}}, <span class="font-weight-light">author</span></h5>
                        <h4><a href="/home/skripsi/detail/{{$skripsis->id}}">{{$skripsis->judul}}</a></h4>
                        <p>{{ Str::limit($skripsis->abstrak, 150) }}</p>
                        <p class="text-muted">
                            <p>Jumlah views: {{ $skripsis->views }}</p>
                            {{$skripsis->prodi}}, {{'Universitas Suryakancana'}}, {{$skripsis->rilis}}<br>
                        </p>

                        <!-- Form pencarian tanpa input, hanya dengan judul yang ada -->
                        <form action="{{ route('cariYangMirip') }}" method="GET" class="d-inline">
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
                        <!-- Modified Button to trigger modal -->
                        <button class="btn btn-outline-primary btn-sm mr-2" data-toggle="modal" data-target="#abstrakModal-{{ $skripsis->id }}">
                            <i class="fas fa-file-pdf"></i> Abstrak
                        </button>

                        <!-- Modal for Abstract PDF Display -->
                        <div class="modal fade" id="abstrakModal-{{ $skripsis->id }}" tabindex="-1" role="dialog" aria-labelledby="abstrakModalLabel-{{ $skripsis->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6>Universitas Suryakancana</h6> <!-- Teks universitas di atas -->
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="header">
                                            <h5 class="modal-title" id="abstrakModalLabel-{{ $skripsis->id }}"> {{ $skripsis->judul }}</h5>
                                            <h6 class="modal-title penulis" id="abstrakModalLabel-{{ $skripsis->id }}"> {{ $skripsis->penulis }}, author</h6>
                                            <hr class="dashed-line"> <!-- Garis putus-putus di bawah penulis -->
                                        </div>
                                        <p>Abstrak</p>
                                        <p class="abstrak-text">{{ $skripsis->abstrak }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tambahkan CSS untuk gaya tampilan -->
                        <style>
                            .dashed-line {
                                border: 0;
                                border-top: 2px dashed #000; /* Garis putus-putus */
                                height: 1px; /* Mengatur tinggi garis */
                                margin: 10px 0; /* Jarak atas dan bawah garis */
                            }

                            .modal-header h6 {
                                margin: 0; /* Jarak bawah judul universitas */
                                font-weight: bold;
                                font-size: 1.25rem; /* Ukuran font untuk teks universitas */
                            }

                            .header h5 {
                                margin: 0 0 10px 0; /* Jarak bawah judul ke penulis */
                            }
                            .header h6.penulis {
                                margin-top: 10px; /* Jarak atas untuk penulis */
                                }
                            .abstrak-text {
                                margin-top: 20px; /* Jarak atas teks abstrak */
                                line-height: 1.6; /* Jarak antar baris teks */
                            }
                        </style>
                        <!-- End of Modal -->
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@stop

@section('js')
    <!-- Include Bootstrap's JavaScript if not already included -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
@stop
