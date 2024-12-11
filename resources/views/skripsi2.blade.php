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
        <div class="notification" id="notification"></div>
        <div class="card-body">
            @if($skripsi->isEmpty())
                <!-- Teks penanda jika tidak ada skripsi yang tersedia -->
                <div id="no-skripsi-message" class="alert alert-info" role="alert">
                    Belum ada skripsi yang tersedia.
                </div>
            @else
                <!-- Jika data skripsi tersedia, maka tampilkan daftar skripsi -->
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

                            <!-- Bagian Tambah/Hapus Favorite -->
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
                            <button class="btn btn-outline-secondary btn-sm mr-2" data-toggle="modal" data-target="#metadataModal-{{ $skripsis->id }}">
                                <i class="fas fa-info-circle"></i> Metadata
                            </button>
                            <button class="btn btn-outline-primary btn-sm mr-2" data-toggle="modal" data-target="#abstrakModal-{{ $skripsis->id }}">
                                <i class="fas fa-file-pdf"></i> Abstrak
                            </button>
                            <!-- Modal untuk Tampilan PDF Abstrak -->
                            <div class="modal fade" id="abstrakModal-{{ $skripsis->id }}" tabindex="-1" role="dialog" aria-labelledby="abstrakModalLabel-{{ $skripsis->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6>Universitas Suryakancana</h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="header">
                                                <h5 class="modal-title" id="abstrakModalLabel-{{ $skripsis->id }}">{{ $skripsis->judul }}</h5>
                                                <h6 class="modal-title penulis">{{ $skripsis->penulis }}, author</h6>
                                                <hr class="dashed-line">
                                            </div>
                                            <p>Abstrak</p>
                                            <p class="abstrak-text">{{ $skripsis->abstrak }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal untuk Tampilan Metadata -->
                            <div class="modal fade" id="metadataModal-{{ $skripsis->id }}" tabindex="-1" role="dialog" aria-labelledby="metadataLabel-{{ $skripsis->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6>Universitas Suryakancana</h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h1>Universitas Suryakancana Library</h1>
                            
                                            <div class="utama">
                                                <div class="judul">
                                                    <strong>Judul:</strong>
                                                </div>
                                                <div class="isi">
                                                    <p>{{ $skripsis->judul }}</p>
                                                </div>
                                                <div class="judul">
                                                    <strong>Pengarang:</strong>
                                                    
                                                </div>
                                                <div class="isi">
                                                    <p>{{ $skripsis->penulis }}</p>
                                                </div>
                                                <div class="judul">
                                                    <strong>Penerbitan:</strong>
                                                </div>
                                                <div class="isi">
                                                    <p>{{ $skripsis->prodi }} Universitas Suryakancana</p>
                                                </div>

                                                <div class="judul">
                                                    <strong>Link Terkait:</strong>
                                                    <ul>
                                                        <li><a href="#">Deskripsi Bibliografi</a></li>
                                                        <li>
                                                            <a href="{{ route('searchSkripsi') }}?judul={{ $skripsis->judul }}&penulis=&rilis=">
                                                                Dokumen Yang Mirip
                                                            </a>
                                                        </li>
                                                        <li><a href="/home/skripsi">Universitas Suryakancana Library</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                            
                                            <div class="footer">
                                                <p>&copy; {{ date('Y') }} Universitas Suryakancana Library. All Rights Reserved.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tambahan CSS untuk gaya tampilan -->
                            <style>
                                .dashed-line {
                                    border: 0;
                                    border-top: 2px dashed #000;
                                    height: 1px;
                                    margin: 10px 0;
                                }
                                .modal-header h6 {
                                    margin: 0;
                                    font-weight: bold;
                                    font-size: 1.25rem;
                                }
                                .header h5 {
                                    margin: 0 0 10px 0;
                                }
                                .header h6.penulis {
                                    margin-top: 10px;
                                }
                                .abstrak-text {
                                    margin-top: 20px;
                                    line-height: 1.6;
                                }
                                .notification {
                                    position: fixed; /* Tetapkan posisi tetap */
                                    top: 20px; /* Jarak dari atas halaman */
                                    right: 20px; /* Jarak dari kanan halaman */
                                    padding: 10px 15px;
                                    border-radius: 5px;
                                    font-size: 14px;
                                    color: #fff;
                                    display: none; /* Default disembunyikan */
                                    z-index: 9999; /* Pastikan berada di atas elemen lainnya */
                                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                }

                                .notification-success {
                                    background-color: #ffc107; /* Warna kuning untuk Tambah Favorite */
                                }

                                .notification-danger {
                                    background-color: #dc3545; /* Warna merah untuk Hapus Favorite */
                                }
                            </style>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@stop

@section('js')


    <!-- JavaScript untuk menghilangkan pesan setelah 4 detik -->
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#no-skripsi-message').fadeOut('slow');
            }, 10000); //
        });
        $(document).ready(function() {
            // Menampilkan notifikasi
            let notificationMessage = "{{ session('favorite') }}";
            let notificationType = "{{ session('favorite_type') }}"; // Menggunakan tipe aksi dari controller

            if (notificationMessage) {
                // Tentukan class notifikasi berdasarkan tipe
                let notificationClass = notificationType === 'add' ? 'notification-success' : 'notification-danger';

                // Update dan tampilkan notifikasi
                $('#notification')
                    .removeClass('notification-success notification-danger')
                    .addClass(notificationClass)
                    .text(notificationMessage)
                    .fadeIn(500); // Animasi fade in

                // Menghilangkan notifikasi setelah 5 detik
                setTimeout(() => {
                    $('#notification').fadeOut(500); // Animasi fade out
                }, 5000);
            }
        });
    </script>
@stop
