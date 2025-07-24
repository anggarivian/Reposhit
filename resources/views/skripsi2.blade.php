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

@section('css')
    <style>

    img {
        -webkit-user-select: none;
        -webkit-touch-callout: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        pointer-events: none;
    }
    .pdf-viewer {
        max-width: 800px; margin: auto;
        padding: 10px;
        overflow-y: auto;
        border: 1px solid #ccc;
        max-height: 600px;
    }
    .pdf-page {
        margin-bottom: 20px;
        position: relative;
    }
    .pdf-page img {
        width: 100%; height: auto;
        user-select: none; pointer-events: none;
    }
    .watermark {
        position: absolute;
        bottom: 5px; right: 10px;
        font-size: 12px; color: rgba(255,0,0,0.5);
    }
    </style>
@endsection

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
                        <!-- Enhanced search form dengan metadata support -->
                        <form id="searchForm" action="{{ route('findSkripsi') }}" method="GET" class="search-panel">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                                <input type="text" name="judul" class="form-control" placeholder="Cari judul, subjek, kata kunci, atau deskripsi..." value="{{ request('judul') }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Filter <i class="fas fa-filter"></i>
                                    </button>
                                    <div class="dropdown-menu p-3" style="width: 350px;" onclick="event.stopPropagation();">
                                        <div class="form-group">
                                            <label>Penulis / Kontributor</label>
                                            <input type="text" name="penulis" class="form-control" placeholder="Nama penulis atau kontributor" value="{{ request('penulis') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Tahun</label>
                                            <input type="text" name="rilis" class="form-control" placeholder="Tahun publikasi" value="{{ request('rilis') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Program Studi</label>
                                            <select name="prodi" class="form-control">
                                                <option value="">Semua Program Studi</option>
                                                @foreach($jurusans as $jurusan)
                                                    <option value="{{ $jurusan->nama_jurusan }}"
                                                        {{ request('prodi') == $jurusan->nama_jurusan ? 'selected' : '' }}>
                                                        {{ $jurusan->nama_jurusan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Tambahan filter metadata -->
                                        <div class="form-group">
                                            <label>Subjek</label>
                                            <input type="text" name="subject" class="form-control" placeholder="Subjek atau bidang keilmuan" value="{{ request('subject') }}">
                                        </div>
                                        {{-- <div class="form-group">
                                            <label>Tipe Dokumen</label>
                                            <select name="type" class="form-control">
                                                <option value="">Semua Tipe</option>
                                                <option value="Skripsi" {{ request('type') == 'Skripsi' ? 'selected' : '' }}>Skripsi</option>
                                                <option value="Tesis" {{ request('type') == 'Tesis' ? 'selected' : '' }}>Tesis</option>
                                                <option value="Laporan" {{ request('type') == 'Laporan' ? 'selected' : '' }}>Laporan</option>
                                            </select>
                                        </div> --}}
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
            @if(request()->has('judul') || request()->has('penulis') || request()->has('rilis') || request()->has('subject') || request()->has('type'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Hasil pencarian untuk:
                    @if(request('judul')) <span class="badge badge-primary">Pencarian: {{ request('judul') }}</span> @endif
                    @if(request('penulis')) <span class="badge badge-primary">Penulis: {{ request('penulis') }}</span> @endif
                    @if(request('rilis')) <span class="badge badge-primary">Tahun: {{ request('rilis') }}</span> @endif
                    @if(request('prodi')) <span class="badge badge-primary">Prodi: {{ request('prodi') }}</span> @endif
                    @if(request('subject')) <span class="badge badge-primary">Subjek: {{ request('subject') }}</span> @endif
                    @if(request('type')) <span class="badge badge-primary">Tipe: {{ request('type') }}</span> @endif
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
                {{-- Nav Tabs --}}
                <ul class="nav nav-tabs mb-4" id="prodiTabs" role="tablist">
                    @php
                        $tabs = collect(['Semua' => 'semua'])
                            ->merge($jurusans->pluck('id', 'nama_jurusan')->mapWithKeys(function($id, $nama) {
                                return [$nama => Str::slug($nama, '_')];
                            }));
                    @endphp

                @foreach($tabs as $label => $id)
                    <li class="nav-item">
                    <a
                        class="nav-link {{ $loop->first ? 'active' : '' }}"
                        id="{{ $id }}-tab"
                        data-toggle="tab"
                        href="#{{ $id }}"
                        role="tab"
                        aria-controls="{{ $id }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                    >{{ $label }}</a>
                    </li>
                @endforeach
                </ul>

                {{-- Tab Panes --}}
                <div class="tab-content" id="prodiTabsContent">
                    @foreach($tabs as $label => $id)
                        <div
                        class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                        id="{{ $id }}"
                        role="tabpanel"
                        aria-labelledby="{{ $id }}-tab"
                        >
                        @php
                            // Untuk tab "Semua", tidak di-filter; untuk yang lain, filter berdasarkan prodi
                            $filtered = $id === 'semua'
                                ? $skripsi
                                : $skripsi->filter(function ($item) use ($label) {
                                    return optional($item->mahasiswa->jurusan)->nama_jurusan === $label;
                                });
                        @endphp

                        @if($filtered->isEmpty())
                            <p class="text-muted">Belum ada skripsi untuk {{ strtolower($label) }}.</p>
                        @else
                            <div class="skripsi-list">
                            @foreach($filtered as $skripsis)
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
                                                    {{ $skripsis->penulis }}
                                                    @if($skripsis->metadata && $skripsis->metadata->creator && $skripsis->metadata->creator !== $skripsis->penulis)
                                                        <small class="text-muted">({{ $skripsis->metadata->creator }})</small>
                                                    @endif
                                                    <span class="badge badge-pill badge-light">
                                                        {{ $skripsis->mahasiswa->jurusan->nama_jurusan ?? '-' }}
                                                    </span>
                                                </h5>
                                            </div>
                                        <div>
                                            <span class="views-badge">
                                                <i class="fas fa-eye"></i> {{ $skripsis->views }} views
                                            </span>
                                            </div>
                                        </div>

                                        <h4 class="skripsi-title mt-2">
                                            <a href="/home/skripsi/detail/{{ $skripsis->id }}">
                                                {{ $skripsis->judul }}
                                            </a>
                                        </h4>

                                        <!-- Tampilkan subjek dari metadata jika ada -->
                                        @if($skripsis->metadata && $skripsis->metadata->subject)
                                            <div class="mb-2">
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-bookmark"></i> {{ $skripsis->metadata->subject }}
                                                </span>
                                            </div>
                                        @endif

                                        <div class="skripsi-abstract">
                                            <!-- Prioritas: description dari metadata, kemudian abstrak dari skripsi -->
                                            @php
                                                $abstractText = $skripsis->metadata && $skripsis->metadata->description
                                                    ? $skripsis->metadata->description
                                                    : $skripsis->abstrak;
                                            @endphp
                                            {{ Str::limit($abstractText, 180) }}
                                            @if(strlen($abstractText) > 180)
                                            <a href="#" data-toggle="modal" data-target="#abstrakModal{{ $skripsis->id }}" class="text-primary">
                                                Baca Selengkapnya
                                            </a>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            <span class="badge badge-light">
                                                <i class="fas fa-university"></i>
                                                {{ $skripsis->metadata && $skripsis->metadata->publisher ? $skripsis->metadata->publisher : 'Universitas Suryakancana' }}
                                            </span>
                                            <span class="badge badge-light mr-2">
                                                <i class="fas fa-calendar-alt"></i>
                                                {{ $skripsis->metadata && $skripsis->metadata->date_issued ? $skripsis->metadata->date_issued : $skripsis->rilis }}
                                            </span>
                                            @if($skripsis->metadata && $skripsis->metadata->type)
                                                <span class="badge badge-info mr-2">
                                                    <i class="fas fa-file-alt"></i> {{ $skripsis->metadata->type }}
                                                </span>
                                            @endif
                                            @if($skripsis->metadata && $skripsis->metadata->language)
                                                <span class="badge badge-success mr-2">
                                                    <i class="fas fa-language"></i> {{ strtoupper($skripsis->metadata->language) }}
                                                </span>
                                            @endif
                                            <!-- Tampilkan kata kunci dari metadata atau skripsi -->
                                            @php
                                                $keywords = $skripsis->metadata && $skripsis->metadata->keywords
                                                    ? $skripsis->metadata->keywords
                                                    : $skripsis->katakunci;
                                            @endphp
                                            @if($keywords)
                                                @foreach(explode(',', $keywords) as $tag)
                                                    <a href="{{ route('searchSkripsi', ['judul' => trim($tag)]) }}" class="badge badge-info ml-1" style="text-decoration:none;">
                                                        <i class="fas fa-tag"></i> {{ trim($tag) }}
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="skripsi-actions mt-3">
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

                                            <button class="btn btn-outline-info btn-sm" onclick="location.href='{{ route('searchSkripsi') }}?judul={{ urlencode($skripsis->judul) }}&penulis=&rilis=&mirip=1'">
                                            <i class="fas fa-clone"></i> Dokumen Yang Mirip
                                            </button>
                                            <button class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target="#dapusModal{{ $skripsis->id }}">
                                                <i class="fas fa-book"></i> Daftar Pustaka
                                            </button>
                                            <button class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#abstrakPdfModal{{ $skripsis->id }}">
                                                <i class="fas fa-file-pdf"></i> Lihat PDF Abstrak
                                            </button>
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

<!-- Modal untuk Tampilan Abstrak - Enhanced dengan metadata -->
@foreach($skripsi as $skripsis)
    <div class="modal fade" id="abstrakModal{{ $skripsis->id }}" tabindex="-1" role="dialog" aria-labelledby="abstrakModalLabel{{ $skripsis->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6>{{ $skripsis->metadata && $skripsis->metadata->publisher ? $skripsis->metadata->publisher : 'Universitas Suryakancana' }}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="header">
                        <h5 class="modal-title" id="abstrakModalLabel{{ $skripsis->id }}">
                            {{ $skripsis->metadata && $skripsis->metadata->title ? $skripsis->metadata->title : $skripsis->judul }}
                        </h5>
                        <h6 class="modal-title penulis">
                            {{ $skripsis->metadata && $skripsis->metadata->creator ? $skripsis->metadata->creator : $skripsis->penulis }}, author
                            @if($skripsis->metadata && $skripsis->metadata->contributor)
                                <br><small class="text-muted">Kontributor: {{ $skripsis->metadata->contributor }}</small>
                            @endif
                        </h6>
                        <hr class="dashed-line">
                    </div>
                    <p>Abstrak</p>
                    <p class="abstrak-text">
                        {{ $skripsis->metadata && $skripsis->metadata->description ? $skripsis->metadata->description : $skripsis->abstrak }}
                    </p>
                    @if($skripsis->metadata && $skripsis->metadata->coverage)
                        <p><strong>Cakupan:</strong> {{ $skripsis->metadata->coverage }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Tampilan Metadata - Enhanced dengan semua field metadata -->
    <div class="modal fade" id="metadataModal{{ $skripsis->id }}" tabindex="-1" role="dialog" aria-labelledby="metadataLabel{{ $skripsis->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6>{{ $skripsis->metadata && $skripsis->metadata->publisher ? $skripsis->metadata->publisher : 'Universitas Suryakancana' }}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h1>{{ $skripsis->metadata && $skripsis->metadata->publisher ? $skripsis->metadata->publisher : 'Universitas Suryakancana' }} Library</h1>

                    <div class="utama">
                        <div class="row">
                            <div class="col-md-3"><strong>Judul:</strong></div>
                            <div class="col-md-9">
                                <p>{{ $skripsis->metadata && $skripsis->metadata->title ? $skripsis->metadata->title : $skripsis->judul }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3"><strong>Pengarang:</strong></div>
                            <div class="col-md-9">
                                <p>{{ $skripsis->metadata && $skripsis->metadata->creator ? $skripsis->metadata->creator : $skripsis->penulis }}</p>
                            </div>
                        </div>

                        @if($skripsis->metadata && $skripsis->metadata->contributor)
                        <div class="row">
                            <div class="col-md-3"><strong>Kontributor:</strong></div>
                            <div class="col-md-9">
                                <p>{{ $skripsis->metadata->contributor }}</p>
                            </div>
                        </div>
                        @endif

                        @if($skripsis->metadata && $skripsis->metadata->subject)
                        <div class="row">
                            <div class="col-md-3"><strong>Subjek:</strong></div>
                            <div class="col-md-9">
                                <p>{{ $skripsis->metadata->subject }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-3"><strong>Penerbitan:</strong></div>
                            <div class="col-md-9">
                                <p>{{ $skripsis->mahasiswa->jurusan->nama_jurusan ?? '-' }} {{ $skripsis->metadata && $skripsis->metadata->publisher ? $skripsis->metadata->publisher : 'Universitas Suryakancana' }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3"><strong>Tahun Terbit:</strong></div>
                            <div class="col-md-9">
                                <p>{{ $skripsis->metadata && $skripsis->metadata->date_issued ? $skripsis->metadata->date_issued : $skripsis->rilis }}</p>
                            </div>
                        </div>

                        @if($skripsis->metadata && $skripsis->metadata->type)
                        <div class="row">
                            <div class="col-md-3"><strong>Tipe Dokumen:</strong></div>
                            <div class="col-md-9">
                                <p>{{ $skripsis->metadata->type }}</p>
                            </div>
                        </div>
                        @endif

                        @if($skripsis->metadata && $skripsis->metadata->format)
                        <div class="row">
                            <div class="col-md-3"><strong>Format:</strong></div>
                            <div class="col-md-9">
                                <p>{{ $skripsis->metadata->format }}</p>
                            </div>
                        </div>
                        @endif

                        @if($skripsis->metadata && $skripsis->metadata->language)
                        <div class="row">
                            <div class="col-md-3"><strong>Bahasa:</strong></div>
                            <div class="col-md-9">
                                <p>{{ strtoupper($skripsis->metadata->language) }}</p>
                            </div>
                        </div>
                        @endif

                        @if($skripsis->metadata && $skripsis->metadata->identifier)
                        <div class="row">
                            <div class="col-md-3"><strong>Identifier:</strong></div>
                            <div class="col-md-9">
                                <p>{{ $skripsis->metadata->identifier }}</p>
                            </div>
                        </div>
                        @endif

                        @if($skripsis->metadata && $skripsis->metadata->rights)
                        <div class="row">
                            <div class="col-md-3"><strong>Hak Cipta:</strong></div>
                            <div class="col-md-9">
                                <p>{{ $skripsis->metadata->rights }}</p>
                            </div>
                        </div>
                        @endif

                        @if($skripsis->metadata && $skripsis->metadata->coverage)
                        <div class="row">
                            <div class="col-md-3"><strong>Cakupan:</strong></div>
                            <div class="col-md-9">
                                <p>{{ $skripsis->metadata->coverage }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-3"><strong>Kata Kunci:</strong></div>
                            <div class="col-md-9">
                                <p>
                                    @php
                                        $keywords = $skripsis->metadata && $skripsis->metadata->keywords
                                            ? $skripsis->metadata->keywords
                                            : $skripsis->katakunci;
                                    @endphp
                                    {{ $keywords }}
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3"><strong>Link Terkait:</strong></div>
                            <div class="col-md-9">
                                <ul>
                                    <li><a href="/home/skripsi/detail/{{$skripsis->id}}">Deskripsi Bibliografi</a></li>
                                    <li>
                                        <a href="{{ route('searchSkripsi') }}?judul={{ $skripsis->judul }}&penulis=&rilis=">
                                            Dokumen Yang Mirip
                                        </a>
                                    </li>
                                    <li><a href="/home/skripsi">{{ $skripsis->metadata && $skripsis->metadata->publisher ? $skripsis->metadata->publisher : 'Universitas Suryakancana' }} Library</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="footer mt-4">
                        <p>&copy; {{ date('Y') }} {{ $skripsis->metadata && $skripsis->metadata->publisher ? $skripsis->metadata->publisher : 'Universitas Suryakancana' }} Library. All Rights Reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Daftar Pustaka dengan Tampilan PDF Non-Download -->
<div class="modal fade" id="dapusModal{{ $skripsis->id }}" tabindex="-1" role="dialog" aria-labelledby="dapusModalLabel{{ $skripsis->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="dapusModalLabel{{ $skripsis->id }}">Daftar Pustaka - {{ $skripsis->metadata && $skripsis->metadata->title ? $skripsis->metadata->title : $skripsis->judul }}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                @if($skripsis->file_dapus)
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-file-pdf text-danger mr-2"></i>Dokumen Daftar Pustaka
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div id="pdf-container" style="position: relative; height: 600px; width: 100%;">
                                    <iframe
                                        id="pdfFrame"
                                        src="{{ asset('pdfjs/web/viewer.html') }}?file={{ urlencode( route('dapus.streamPdf', $skripsis->id )) }}#toolbar=0&navpanes=0&scrollbar=0"
                                        style="width:100%; height:100%; border:none;"
                                        sandbox="allow-scripts allow-same-origin"
                                    ></iframe>
                                <div class="pdf-controls" style="position: absolute; top: 10px; right: 10px;">
                                    <button id="fullscreenButton" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-expand"></i> Layar Penuh
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-danger">File daftar pustaka tidak tersedia.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Abstrak PDF dengan Tampilan Non-Download -->
<div class="modal fade" id="abstrakPdfModal{{ $skripsis->id }}" tabindex="-1" role="dialog" aria-labelledby="abstrakPdfModalLabel{{ $skripsis->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="abstrakPdfModalLabel{{ $skripsis->id }}">Abstrak PDF - {{ $skripsis->metadata && $skripsis->metadata->title ? $skripsis->metadata->title : $skripsis->judul }}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                @if($skripsis->file_abstrak)
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-file-pdf text-danger mr-2"></i>Dokumen Abstrak PDF
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div id="pdf-container" style="position: relative; height: 600px; width: 100%;">
                                    <iframe
                                        id="pdfFrame"
                                        src="{{ asset('pdfjs/web/viewer.html') }}?file={{ urlencode( route('abstrak.streamPdf', $skripsis->id )) }}#toolbar=0&navpanes=0&scrollbar=0"
                                        style="width:100%; height:100%; border:none;"
                                        sandbox="allow-scripts allow-same-origin"
                                    ></iframe>
                                <div class="pdf-controls" style="position: absolute; top: 10px; right: 10px;">
                                    <button id="fullscreenButton" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-expand"></i> Layar Penuh
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-danger">File abstrak tidak tersedia.</p>
                @endif
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
    const iframe = document.getElementById('pdfFrame');

    iframe.addEventListener('load', () => {
        const doc = iframe.contentDocument || iframe.contentWindow.document;

        // cegah klik kanan
        doc.addEventListener('contextmenu', e => e.preventDefault());

        // cegah seleksi & shortcut umum
        doc.body.style.userSelect = 'none';
        doc.addEventListener('keydown', function(e) {
            if (e.ctrlKey && ['p','s','u'].includes(e.key.toLowerCase()) || e.key === 'F12') {
            e.preventDefault();
            }
        });
    });
</script>
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
