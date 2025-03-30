@extends('adminlte::page')

@section('title', 'Riwayat Skripsi Universitas Suryakancana')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-primary">
                <i class="fas fa-history mr-2"></i>Riwayat Skripsi
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
            <div class="row">
                <div class="col-md-6">
                    <h3 class="card-title"><i class="fas fa-book-reader mr-2"></i>Daftar Riwayat Skripsi Anda</h3>
                </div>
                <div class="col-md-6 text-right">
                    @if (!$history->isEmpty())
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteAllModal">
                            <i class="fas fa-trash-alt"></i> Hapus Semua Riwayat
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="notification" id="notification"></div>
        
        <div class="card-body">
            @if ($history->isEmpty())
                <div id="no-history-message" class="alert alert-info text-center py-5" role="alert">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h4>Belum ada riwayat skripsi</h4>
                    <p>Anda belum memiliki riwayat membaca skripsi</p>
                </div>
            @else
                <!-- Skripsi History List -->
                <div class="skripsi-list">
                    @foreach($history as $item)
                        <div class="skripsi-item">
                            <div class="row mb-4">
                                <div class="col-md-1 text-center">
                                    <div class="skripsi-icon">
                                        <i class="fas fa-book fa-3x text-primary"></i>
                                        <div class="year-badge">{{ $item->rilis }}</div>
                                    </div>
                                </div>
                                <div class="col-md-11">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h5 class="author-name">
                                                        <i class="fas fa-user-graduate text-muted mr-1"></i>
                                                        {{$item->penulis}} 
                                                        <span class="badge badge-pill badge-light">{{$item->prodi}}</span>
                                                    </h5>
                                                </div>
                                                <div>
                                                    <span class="views-badge">
                                                        <i class="fas fa-eye"></i> {{ $item->views }} views
                                                    </span>
                                                    <form action="{{ route('deleteHistory', $item->id) }}" method="POST" style="display:inline;" class="ml-2">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus skripsi ini dari riwayat?')">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            
                                            <h4 class="skripsi-title mt-2">
                                                <a href="/home/skripsi/detail/{{$item->id}}">{{$item->judul}}</a>
                                            </h4>
                                            
                                            <div class="skripsi-abstract">
                                                {{ Str::limit($item->abstrak, 180) }}
                                                @if(strlen($item->abstrak) > 180)
                                                    <a href="#" data-toggle="modal" data-target="#abstrakModal{{ $item->id }}" class="text-primary">
                                                        Baca Selengkapnya
                                                    </a>
                                                @endif
                                            </div>
                                            
                                            <div class="skripsi-info mt-2">
                                                <span class="badge badge-light">
                                                    <i class="fas fa-university"></i> Universitas Suryakancana
                                                </span>
                                                <span class="badge badge-light">
                                                    <i class="fas fa-calendar-alt"></i> {{$item->rilis}}
                                                </span>
                                            </div>
                                            
                                            <div class="skripsi-actions mt-3">
                                                <!-- Action Buttons -->
                                                @if(auth()->user()->favorites()->where('id_skripsi', $item->id)->exists())
                                                    <form action="{{ route('removeFavorite1', $item->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                                            <i class="fas fa-star"></i> Hapus dari Favorite
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('addFavorite', $item->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-warning btn-sm">
                                                            <i class="fas fa-star"></i> Tambah ke Favorite
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <button class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#metadataModal{{ $item->id }}">
                                                    <i class="fas fa-info-circle"></i> Metadata
                                                </button>
                                                
                                                <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#abstrakModal{{ $item->id }}">
                                                    <i class="fas fa-file-alt"></i> Abstrak
                                                </button>
                                                
                                                <a href="/home/skripsi/detail/{{$item->id}}" class="btn btn-primary btn-sm">
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

<!-- Delete All Modal -->
<div class="modal fade" id="deleteAllModal" tabindex="-1" role="dialog" aria-labelledby="deleteAllModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAllModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus semua riwayat skripsi?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('deleteAllHistory') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus Semua</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Tampilan PDF Abstrak -->
@foreach($history as $item)
<div class="modal fade" id="abstrakModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="abstrakModalLabel{{ $item->id }}" aria-hidden="true">
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
                    <h5 class="modal-title" id="abstrakModalLabel{{ $item->id }}">{{ $item->judul }}</h5>
                    <h6 class="modal-title penulis">{{ $item->penulis }}, author</h6>
                    <hr class="dashed-line">
                </div>
                <p>Abstrak</p>
                <p class="abstrak-text">{{ $item->abstrak }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Tampilan Metadata -->
<div class="modal fade" id="metadataModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="metadataLabel{{ $item->id }}" aria-hidden="true">
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
                        <p>{{ $item->judul }}</p>
                    </div>
                    <div class="judul">
                        <strong>Pengarang:</strong>
                        
                    </div>
                    <div class="isi">
                        <p>{{ $item->penulis }}</p>
                    </div>
                    <div class="judul">
                        <strong>Penerbitan:</strong>
                    </div>
                    <div class="isi">
                        <p>{{ $item->prodi }} Universitas Suryakancana</p>
                    </div>

                    <div class="judul">
                        <strong>Link Terkait:</strong>
                        <ul>
                            <li><a href="/home/skripsi/detail/{{$item->id}}">Deskripsi Bibliografi</a></li>
                            <li>
                                <a href="{{ route('searchSkripsi') }}?judul={{ $item->judul }}&penulis=&rilis=">
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
@endforeach
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
    
    .skripsi-abstract {
        color: #555;
        line-height: 1.6;
        margin-top: 10px;
        font-size: 0.95rem;
    }
    
    .views-badge {
        background-color: #f8f9fa;
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 0.8rem;
        color: #666;
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
        background-color: #3490dc;
        color: white;
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 10px;
    }
    
    .skripsi-actions .btn {
        margin-right: 5px;
        margin-bottom: 5px;
    }
    
    /* Search Panel */
    .search-panel {
        padding: 10px 0;
    }
    
    /* Modal Styles */
    .modal-header h6 {
        margin: 0;
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .header h5 {
        margin: 10px 0;
        font-weight: bold;
        color: #333;
    }
    
    .header h6.penulis {
        color: #666;
    }
    
    .dashed-line {
        border: 0;
        border-top: 2px dashed #3490dc;
        height: 1px;
        margin: 15px 0;
    }
    
    .abstrak-text {
        text-align: justify;
        line-height: 1.8;
        color: #333;
        margin-top: 10px;
    }
    
    /* Original modal structure styles */
    .utama {
        margin: 20px 0;
    }
    
    .utama .judul {
        margin-bottom: 5px;
        color: #3490dc;
    }
    
    .utama .isi {
        margin-bottom: 15px;
        padding-left: 10px;
    }
    
    .utama ul {
        padding-left: 25px;
    }
    
    .utama ul li {
        margin-bottom: 5px;
    }
    
    .footer {
        margin-top: 30px;
        text-align: center;
        color: #666;
        font-size: 0.9rem;
    }
    
    /* Notification */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 5px;
        font-size: 14px;
        color: #fff;
        display: none;
        z-index: 9999;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .notification-success {
        background-color: #28a745;
    }

    .notification-danger {
        background-color: #dc3545;
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
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Fade out no-history message
    setTimeout(function() {
        $('#no-history-message').fadeOut('slow');
    }, 10000);
    
    // Notification handling
    let notificationMessage = "{{ session('history') }}";
    let notificationType = "{{ session('history_type') }}";

    if (notificationMessage) {
        let notificationClass = notificationType === 'success' ? 'notification-success' : 'notification-danger';

        $('#notification')
            .removeClass('notification-success notification-danger')
            .addClass(notificationClass)
            .text(notificationMessage)
            .fadeIn(500);

        setTimeout(() => {
            $('#notification').fadeOut(500);
        }, 5000);
    }
    
    // Add animation to cards on page load
    $('.skripsi-item').each(function(index) {
        $(this).css({
            'animation': 'fadeInUp 0.5s ease forwards',
            'animation-delay': index * 0.1 + 's',
            'opacity': '0'
        });
    });
    
    // Ensure modals work correctly
    $('.modal').on('shown.bs.modal', function () {
        $(this).find('[autofocus]').focus();
    });
});
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