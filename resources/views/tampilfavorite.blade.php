@extends('adminlte::page')

@section('title', 'Skripsi Favorite Universitas Suryakancana')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-primary">
                <i class="fas fa-star mr-2"></i>Skripsi Favorite
            </h1>
            <p class="text-muted">Perpustakaan Digital Universitas Suryakancana</p>
        </div>
        <div>
            {{-- <img src="{{ asset('images/logo-unsur.png') }}" alt="Logo UNSUR" height="60"> --}}
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-header bg-gradient-light">
            <h3 class="card-title"><i class="fas fa-bookmark mr-2"></i>Daftar Skripsi Favorite Anda</h3>
        </div>
        
        <div class="card-body">
            @if(session('favorite'))
                <div id="favorite-alert" class="alert alert-{{ session('favorite_type') == 'add' ? 'success' : 'danger' }} alert-dismissible fade show small-alert" role="alert">
                    {{ session('favorite') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($skripsifavorite->isEmpty())
                <div id="no-favorite-message" class="alert alert-info text-center py-5" role="alert">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h4>Belum ada skripsi favorite</h4>
                    <p>Anda belum menambahkan skripsi ke daftar favorite</p>
                </div>
            @else
                <!-- Skripsi Favorite List -->
                <div class="skripsi-list">
                    @foreach($skripsifavorite as $skripsi)
                        <div class="skripsi-item">
                            <div class="row mb-4">
                                <div class="col-md-1 text-center">
                                    <div class="skripsi-icon">
                                        <i class="fas fa-book fa-3x text-warning"></i>
                                        <div class="year-badge">{{ $skripsi->rilis }}</div>
                                    </div>
                                </div>
                                <div class="col-md-11">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h5 class="author-name">
                                                        <i class="fas fa-user-graduate text-muted mr-1"></i>
                                                        {{$skripsi->penulis}} 
                                                        <span class="badge badge-pill badge-light">{{$skripsi->prodi}}</span>
                                                    </h5>
                                                </div>
                                                <div>
                                                    <form action="{{ route('removeFavorite', $skripsi->id) }}" method="POST" style="display:inline;" class="ml-2">
                                                        @csrf
                                                        @method('DELETE')
                                                        {{-- <button type="submit" class="btn btn-sm btn-outline-danger" onclick="confirmRemoveFavorite(event, '{{ $skripsi->id }}', '{{ $skripsi->judul }}')">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button> --}}
                                                    </form>
                                                </div>
                                            </div>
                                            
                                            <h4 class="skripsi-title mt-2">
                                                <a href="/home/skripsi/detail/{{$skripsi->id}}">{{$skripsi->judul}}</a>
                                            </h4>
                                            
                                            <div class="skripsi-info mt-2">
                                                <span class="badge badge-light">
                                                    <i class="fas fa-university"></i> Universitas Suryakancana
                                                </span>
                                                <span class="badge badge-light">
                                                    <i class="fas fa-calendar-alt"></i> {{$skripsi->rilis}}
                                                </span>
                                            </div>
                                            
                                            <div class="skripsi-actions mt-3">
                                                <!-- Action Button -->
                                                <form action="{{ route('removeFavorite', $skripsi->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="confirmRemoveFavorite(event, '{{ $skripsi->id }}', '{{ $skripsi->judul }}')">
                                                        <i class="fas fa-trash-alt"></i> Hapus dari Favorite
                                                    </button>
                                                </form>
                                                
                                                <a href="/home/skripsi/detail/{{$skripsi->id}}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-book-reader"></i> Lihat Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    /* Main Styles */
    .skripsi-item {
        transition: all 0.3s;
    }
    
    .skripsi-title a {
        color: #3490dc;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s;
    }
    
    .skripsi-title a:hover {
        color: #1e70af;
        text-decoration: underline;
    }
    
    .author-name {
        color: #555;
        font-size: 1rem;
    }
    
    .skripsi-info {
        margin-top: 10px;
    }
    
    .skripsi-icon {
        position: relative;
        margin-top: 15px;
    }
    
    .year-badge {
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #ffc107;
        color: white;
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 10px;
    }
    
    .skripsi-actions .btn {
        margin-right: 5px;
        margin-bottom: 5px;
    }
    
    /* Card hover effects */
    .card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    /* Small animation for buttons */
    .btn {
        transition: all 0.3s;
    }
    
    .btn:hover {
        transform: translateY(-2px);
    }
    
    /* Small alert styling */
    .small-alert {
        font-size: 14px;
        padding: 8px 15px;
    }
</style>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Fade out alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.remove(); // Menghapus elemen dari DOM setelah 5 detik
            }, 5000);
        });
        
        // Add animation to cards on page load
        $('.skripsi-item').each(function(index) {
            $(this).css({
                'animation': 'fadeInUp 0.5s ease forwards',
                'animation-delay': index * 0.1 + 's',
                'opacity': '0'
            });
        });
    });

    function confirmRemoveFavorite(event, skripsiId, skripsiTitle) {
        event.preventDefault(); // Mencegah form submit default
        Swal.fire({
            title: "Hapus dari Favorit?",
            text: "Apakah Anda yakin ingin menghapus skripsi berjudul \"" + skripsiTitle + "\" dari daftar favorit?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Tidak, kembali!",
        }).then(function (result) {
            if (result.isConfirmed) {
                event.target.closest('form').submit(); // Lanjutkan form submit jika dikonfirmasi
            }
        });
    }
</script>
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@stop