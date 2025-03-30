@extends('adminlte::page')

@section('title', 'Repositori Skripsi Universitas Suryakancana')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-primary">
                <i class="fas fa-book-open mr-2"></i>Repositori Skripsi
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
            <!-- Search Panel -->
            <div class="row">
                <div class="col-md-12">
                    @if(request()->has('mirip'))
                        <button class="btn btn-secondary btn-sm" onclick="window.history.back();">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                    @else
                        <!-- In your blade template, replace the search form section with this: -->
                        <form id="searchForm" action="{{ route('findSkripsi') }}" method="GET" class="search-panel">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                                <input type="text" name="judul" class="form-control" placeholder="Cari judul skripsi..." value="{{ request('judul') }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Filter <i class="fas fa-filter"></i>
                                    </button>
                                    <div class="dropdown-menu p-3" style="width: 300px;" onclick="event.stopPropagation();">
                                        <div class="form-group">
                                            <label>Penulis</label>
                                            <input type="text" name="penulis" class="form-control" placeholder="Nama penulis" value="{{ request('penulis') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Tahun</label>
                                            <input type="text" name="rilis" class="form-control" placeholder="Tahun publikasi" value="{{ request('rilis') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Program Studi</label>
                                            <select name="prodi" class="form-control">
                                                <option value="">Semua Program Studi</option>
                                                <option value="Agribisnis" {{ request('prodi') == 'Agribisnis' ? 'selected' : '' }}>Agribisnis</option>
                                                <option value="Agroteknologi" {{ request('prodi') == 'Agroteknologi' ? 'selected' : '' }}>Agroteknologi</option>
                                                <option value="Administrasi Bisnis Internasional" {{ request('prodi') == 'Administrasi Bisnis Internasional' ? 'selected' : '' }}>Administrasi Bisnis Internasional</option>
                                                <option value="Pemanfaatan Sumberdaya Perikanan" {{ request('prodi') == 'Pemanfaatan Sumberdaya Perikanan' ? 'selected' : '' }}>Pemanfaatan Sumberdaya Perikanan</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Terapkan Filter
                                        </button>
                                    </div>
                                </div>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="notification" id="notification"></div>
        
        <div class="card-body">
            @if(request()->has('judul') || request()->has('penulis') || request()->has('rilis'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Hasil pencarian untuk: 
                    @if(request('judul')) <span class="badge badge-primary">Judul: {{ request('judul') }}</span> @endif
                    @if(request('penulis')) <span class="badge badge-primary">Penulis: {{ request('penulis') }}</span> @endif
                    @if(request('rilis')) <span class="badge badge-primary">Tahun: {{ request('rilis') }}</span> @endif
                    @if(request('prodi')) <span class="badge badge-primary">Prodi: {{ request('prodi') }}</span> @endif
                    <a href="{{ route('searchSkripsi') }}" class="float-right text-decoration-none">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            @endif
            
            @if($skripsi->isEmpty())
                <div id="no-skripsi-message" class="alert alert-info text-center py-5" role="alert">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h4>Belum ada skripsi yang tersedia</h4>
                    <p>Saat ini tidak ada skripsi yang memenuhi kriteria pencarian Anda</p>
                </div>
            @else
                <!-- Skripsi List -->
                <div class="skripsi-list">
                    @foreach($skripsi as $skripsis)
                        <div class="skripsi-item">
                            <div class="row mb-4">
                                <div class="col-md-1 text-center">
                                    <div class="skripsi-icon">
                                        <i class="fas fa-book fa-3x text-primary"></i>
                                        <div class="year-badge">{{ $skripsis->rilis }}</div>
                                    </div>
                                </div>
                                <div class="col-md-11">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h5 class="author-name">
                                                        <i class="fas fa-user-graduate text-muted mr-1"></i>
                                                        {{$skripsis->penulis}} 
                                                        <span class="badge badge-pill badge-light">{{$skripsis->prodi}}</span>
                                                    </h5>
                                                </div>
                                                <div>
                                                    <span class="views-badge">
                                                        <i class="fas fa-eye"></i> {{ $skripsis->views }} views
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <h4 class="skripsi-title mt-2">
                                                <a href="/home/skripsi/detail/{{$skripsis->id}}">{{$skripsis->judul}}</a>
                                            </h4>
                                            
                                            <div class="skripsi-abstract">
                                                {{ Str::limit($skripsis->abstrak, 180) }}
                                                @if(strlen($skripsis->abstrak) > 180)
                                                    <a href="#" data-toggle="modal" data-target="#abstrakModal{{ $skripsis->id }}" class="text-primary">
                                                        Baca Selengkapnya
                                                    </a>
                                                @endif
                                            </div>
                                            
                                            <div class="skripsi-info mt-2">
                                                <span class="badge badge-light">
                                                    <i class="fas fa-university"></i> Universitas Suryakancana
                                                </span>
                                                <span class="badge badge-light">
                                                    <i class="fas fa-calendar-alt"></i> {{$skripsis->rilis}}
                                                </span>
                                            </div>
                                            
                                            <div class="skripsi-actions mt-3">
                                                <!-- Action Buttons -->
                                                @if(auth()->user()->favorites()->where('id_skripsi', $skripsis->id)->exists())
                                                    <form action="{{ route('removeFavorite1', $skripsis->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                    @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                                            <i class="fas fa-star"></i> Hapus dari Favorite
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('addFavorite', $skripsis->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-warning btn-sm">
                                                            <i class="fas fa-star"></i> Tambah ke Favorite
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <button class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#metadataModal{{ $skripsis->id }}">
                                                    <i class="fas fa-info-circle"></i> Metadata
                                                </button>
                                                
                                                <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#abstrakModal{{ $skripsis->id }}">
                                                    <i class="fas fa-file-alt"></i> Abstrak
                                                </button>
                                                
                                                <button class="btn btn-outline-info btn-sm" onclick="location.href='{{ route('searchSkripsi') }}?judul={{ $skripsis->judul }}&penulis=&rilis=&mirip=1'">
                                                    <i class="fas fa-clone"></i> Dokumen Yang Mirip
                                                </button>
                                                
                                                <a href="/home/skripsi/detail/{{$skripsis->id}}" class="btn btn-primary btn-sm">
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
                
                <!-- Pagination -->
                {{-- <div class="d-flex justify-content-center mt-4">
                    {{ $skripsi->appends(request()->query())->links() }}
                </div> --}}
            @endif
        </div>
    </div>
</div>

<!-- Modal untuk Tampilan PDF Abstrak - Mengikuti struktur asli -->
@foreach($skripsi as $skripsis)
<div class="modal fade" id="abstrakModal{{ $skripsis->id }}" tabindex="-1" role="dialog" aria-labelledby="abstrakModalLabel{{ $skripsis->id }}" aria-hidden="true">
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
                    <h5 class="modal-title" id="abstrakModalLabel{{ $skripsis->id }}">{{ $skripsis->judul }}</h5>
                    <h6 class="modal-title penulis">{{ $skripsis->penulis }}, author</h6>
                    <hr class="dashed-line">
                </div>
                <p>Abstrak</p>
                <p class="abstrak-text">{{ $skripsis->abstrak }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Tampilan Metadata - Mengikuti struktur asli -->
<div class="modal fade" id="metadataModal{{ $skripsis->id }}" tabindex="-1" role="dialog" aria-labelledby="metadataLabel{{ $skripsis->id }}" aria-hidden="true">
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
                            <li><a href="/home/skripsi/detail/{{$skripsis->id}}">Deskripsi Bibliografi</a></li>
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
    
    /* Modal Styles - Keeping original structure but improving appearance */
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
        background-color: #ffc107;
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
    // Add this to your JS section
$(document).ready(function() {
    // Prevent dropdown from closing when clicking inside
    $('.dropdown-menu').on('click', function(e) {
        e.stopPropagation();
    });
    
    // Ensure dropdown remains open when selecting from the prodi dropdown
    $('select[name="prodi"]').on('change', function(e) {
        e.stopPropagation();
    });
    
    // Prevent form submission when clicking inside dropdown
    $('.dropdown-menu input, .dropdown-menu select').on('click', function(e) {
        e.stopPropagation();
    });
    
    // Keep the remaining notification and animation code
    // Fade out no-skripsi message
    setTimeout(function() {
        $('#no-skripsi-message').fadeOut('slow');
    }, 10000);
    
    // Notification handling
    let notificationMessage = "{{ session('favorite') }}";
    let notificationType = "{{ session('favorite_type') }}";

    if (notificationMessage) {
        let notificationClass = notificationType === 'add' ? 'notification-success' : 'notification-danger';

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