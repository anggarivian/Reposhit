@extends('adminlte::page')

@section('title', 'Detail Skripsi')

@section('content_header')
<h1 class="m-0 text-dark">Detail Repositori Skripsi - Admin</h1>
<p class="text-muted">Sistem Informasi Repositori Karya Ilmiah Mahasiswa</p>
@stop

@section('content')

    <style>
        .repo-table {
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            border-collapse: separate;
            border-spacing: 0;
            background-color: #ffffff;
        }

        .repo-table th {
            width: 22%;
            background-color: #f0f4f8;
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
            font-weight: 600;
            color: #2d3748;
        }

        .repo-table td {
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
            line-height: 1.7;
            color: #4a5568;
        }

        .meta-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 6px;
            margin-right: 14px;
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        .abstrak-content {
            max-height: 200px;
            overflow-y: auto;
            padding-right: 15px;
            line-height: 1.8;
            text-align: justify;
        }

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

<div class="row">
    <div class="col-12">
        <div class="card-body">
            {{-- Notifikasi Sukses atau Error --}}
            @if(session('success'))
                <div id="success-alert" class="alert alert-success alert-dismissible fade show small-alert" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show small-alert" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('favorite'))
                <div id="favorite-alert" class="alert alert-{{ session('favorite_type') == 'add' ? 'success' : 'danger' }} alert-dismissible fade show small-alert" role="alert">
                    {{ session('favorite') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <table class="repo-table">
                        <tbody>
                            <tr>
                                <th>
                                    <div class="meta-label">
                                        <div class="meta-icon bg-primary">
                                            <i class="fas fa-book-open"></i>
                                        </div>
                                        Judul Skripsi
                                    </div>
                                </th>
                                <td><strong>{{ $skripsi->judul }}</strong></td>
                            </tr>
                            <tr>
                                <th>
                                    <div class="meta-label">
                                        <div class="meta-icon bg-success">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        Penulis
                                    </div>
                                </th>
                                <td>{{ $skripsi->penulis }}</td>
                            </tr>
                            <tr>
                                <th>
                                    <div class="meta-label">
                                        <div class="meta-icon bg-info">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </div>
                                        Dosen Pembimbing
                                    </div>
                                </th>
                                <td>{{ $skripsi->dospem }}</td>
                            </tr>
                            <tr>
                                <th>
                                    <div class="meta-label">
                                        <div class="meta-icon bg-warning">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        Abstrak
                                    </div>
                                </th>
                                <td>
                                    <div class="abstrak-content">
                                        {{ $skripsi->abstrak }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <div class="meta-label">
                                        <div class="meta-icon bg-danger">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        Tahun
                                    </div>
                                </th>
                                <td>{{ $skripsi->rilis }}</td>
                            </tr>
                            <tr>
                                <th>
                                    <div class="meta-label">
                                        <div class="meta-icon bg-secondary">
                                            <i class="fas fa-file-contract"></i>
                                        </div>
                                        Halaman
                                    </div>
                                </th>
                                <td>{{ $skripsi->halaman }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Menampilkan file skripsi dalam iframe --}}
            <hr>
            <button class="btn btn-secondary btn-sm" onclick="window.history.back();">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
            <hr>
            @if($skripsi->file_skripsi)
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-file-pdf text-danger mr-2"></i>Dokumen Skripsi</h5>
                </div>
                <div class="card-body p-0">
                    <div id="pdf-container" style="position: relative; height: 600px; width: 100%;">
                            <iframe
                                id="pdfFrame"
                                src="{{ asset('pdfjs/web/viewer.html') }}?file={{ urlencode( route('skripsi.streamPdf', $skripsi->id )) }}#toolbar=0&navpanes=0&scrollbar=0"
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
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle mr-2"></i> File skripsi tidak tersedia untuk diakses.
            </div>
        @endif
            <hr>
            <div class="btn-group mt-4 mb-4">
                <button class="btn btn-info" id="btn-komentar-utama">
                    <i class="fas fa-comment-dots"></i> <span>Tulis Komentar</span>
                </button>
            </div>

            {{-- Form komentar utama --}}
            <form action="{{ route('postkomentar1') }}" id="komentar-utama-form" method="POST" style="display: none; margin-top: 10px;">
                @csrf
                <div class="form-group">
                    <label for="komentar_utama">Tulis Komentar Anda</label>
                    <textarea name="content" class="form-control" id="komentar_utama" rows="4" placeholder="Tulis komentar Anda di sini..." required></textarea>
                    <input type="hidden" name="id_skripsi" value="{{ $skripsi->id }}">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Kirim">
                    <button type="button" class="btn btn-secondary" id="btn-batal-komentar">Batal</button>
                </div>
            </form>

            <hr>

{{-- Filter Komentar --}}
<div class="comment-section-header mb-3">
    <h4><i class="fas fa-comments"></i> Diskusi Akademik (Admin Panel)</h4>
    <div class="btn-group filter-comments mt-2">
        <a href="{{ request()->fullUrlWithQuery(['order' => 'terbaru']) }}" class="btn btn-primary btn-sm mr-2 {{ request('order') == 'terbaru' || !request('order') ? 'active' : '' }}">
            Komentar Terlama
        </a>
        <a href="{{ request()->fullUrlWithQuery(['order' => 'terlama']) }}" class="btn btn-primary btn-sm {{ request('order') == 'terlama' ? 'active' : '' }}">
            Komentar Terbaru
        </a>
    </div>
</div>
{{-- Loop untuk menampilkan komentar dan balasannya --}}
@if(isset($comments) && $comments->count() > 0)
    <div class="comment-section mt-3">
        @if(request('order') == 'terlama')
            @foreach($comments as $item)
                <div class="media mb-3 p-3 border rounded bg-light shadow-sm">
                    <i class="bi bi-person-circle mr-3" style="font-size: 2rem;"></i>
                    <div class="media-body">
                        <h5 class="mt-0 font-weight-bold">
                            {{ $item['comment']->user_name }}
                            @if(isset($item['comment']->user) && $item['comment']->user->role == 'admin')
                                <span class="badge badge-danger">Admin</span>
                            @endif
                        </h5>
                        <p>{{ $item['comment']->content }}</p>
                        <small class="text-muted">
                            Diposting pada {{ \Carbon\Carbon::parse($item['comment']->created_at)->locale('id_ID')->isoFormat('D MMMM YYYY HH:mm') }}
                        </small>

                        {{-- Admin dapat mengedit semua komentar --}}
                        <button class="btn btn-warning btn-sm mt-2" data-toggle="collapse" data-target="#editCommentForm{{ $item['comment']->id }}">Edit</button>
                        <div id="editCommentForm{{ $item['comment']->id }}" class="collapse mt-3">
                            <form action="{{ route('updatekomentar1', $item['comment']->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3" required>{{ $item['comment']->content }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary btn-sm" value="Update">
                                </div>
                            </form>
                        </div>

                        {{-- Admin dapat menghapus semua komentar --}}
                        <form action="{{ route('deletekomentar1', $item['comment']->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mt-2" onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">Hapus</button>
                        </form>

                        <button class="btn btn-info btn-sm mt-2" data-toggle="collapse" data-target="#replyForm{{ $item['comment']->id }}">
                            Balas
                        </button>
                        <div id="replyForm{{ $item['comment']->id }}" class="collapse mt-3">
                            <form action="{{ route('postBalasan1') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="replyContent{{ $item['comment']->id }}">Tulis Balasan</label>
                                    <textarea id="replyContent{{ $item['comment']->id }}" name="content" class="form-control" rows="3" placeholder="Tulis balasan Anda di sini..." required></textarea>
                                    <input type="hidden" name="parent_id" value="{{ $item['comment']->id }}">
                                    <input type="hidden" name="id_skripsi" value="{{ $skripsi->id }}">
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary btn-sm" value="Kirim Balasan">
                                </div>
                            </form>
                        </div>

                        @if(isset($item['replies']) && $item['replies']->count() > 0)
                            @foreach($item['replies'] as $reply)
                                <div class="media mt-3 p-3 border rounded bg-white ml-4 shadow-sm">
                                    <i class="bi bi-person-circle mr-3" style="font-size: 2rem;"></i>
                                    <div class="media-body">
                                        <small class="text-muted">Membalas komentar dari {{ $item['comment']->user_name }}</small>
                                        <h6 class="mt-0 font-weight-bold">
                                            {{ $reply->user_name }}
                                            @if(isset($reply->user) && $reply->user->role == 'admin')
                                                <span class="badge badge-danger">Admin</span>
                                            @endif
                                        </h6>
                                        <p>{{ $reply->content }}</p>
                                        <small class="text-muted">
                                            Diposting pada {{ \Carbon\Carbon::parse($reply->created_at)->locale('id_ID')->isoFormat('D MMMM YYYY HH:mm') }}
                                        </small>

                                        {{-- Admin dapat mengedit semua balasan --}}
                                        <button class="btn btn-warning btn-sm mt-2" data-toggle="collapse" data-target="#editReplyForm{{ $reply->id }}">Edit</button>
                                        <div id="editReplyForm{{ $reply->id }}" class="collapse mt-3">
                                            <form action="{{ route('updatekomentar1', $reply->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="2" required>{{ $reply->content }}</textarea>
                                                    @error('content')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <input type="submit" class="btn btn-primary btn-sm" value="Update">
                                                </div>
                                            </form>
                                        </div>

                                        {{-- Admin dapat menghapus semua balasan --}}
                                        <form action="{{ route('deletekomentar1', $reply->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm mt-2" onclick="return confirm('Apakah Anda yakin ingin menghapus balasan ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            @foreach($comments->reverse() as $item)
                <div class="media mb-3 p-3 border rounded bg-light shadow-sm">
                    <i class="bi bi-person-circle mr-3" style="font-size: 2rem;"></i>
                    <div class="media-body">
                        <h5 class="mt-0 font-weight-bold">
                            {{ $item['comment']->user_name }}
                            @if(isset($item['comment']->user) && $item['comment']->user->role == 'admin')
                                <span class="badge badge-danger">Admin</span>
                            @endif
                        </h5>
                        <p>{{ $item['comment']->content }}</p>
                        <small class="text-muted">
                            Diposting pada {{ \Carbon\Carbon::parse($item['comment']->created_at)->locale('id_ID')->isoFormat('D MMMM YYYY HH:mm') }}
                        </small>

                        {{-- Admin dapat mengedit semua komentar --}}
                        <button class="btn btn-warning btn-sm mt-2" data-toggle="collapse" data-target="#editCommentForm{{ $item['comment']->id }}">Edit</button>
                        <div id="editCommentForm{{ $item['comment']->id }}" class="collapse mt-3">
                            <form action="{{ route('updatekomentar1', $item['comment']->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3" required>{{ $item['comment']->content }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary btn-sm" value="Update">
                                </div>
                            </form>
                        </div>

                        {{-- Admin dapat menghapus semua komentar --}}
                        <form action="{{ route('deletekomentar1', $item['comment']->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mt-2" onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">Hapus</button>
                        </form>

                        <button class="btn btn-info btn-sm mt-2" data-toggle="collapse" data-target="#replyForm{{ $item['comment']->id }}">
                            Balas
                        </button>
                        <div id="replyForm{{ $item['comment']->id }}" class="collapse mt-3">
                            <form action="{{ route('postBalasan1') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="replyContent{{ $item['comment']->id }}">Tulis Balasan</label>
                                    <textarea id="replyContent{{ $item['comment']->id }}" name="content" class="form-control" rows="3" placeholder="Tulis balasan Anda di sini..." required></textarea>
                                    <input type="hidden" name="parent_id" value="{{ $item['comment']->id }}">
                                    <input type="hidden" name="id_skripsi" value="{{ $skripsi->id }}">
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary btn-sm" value="Kirim Balasan">
                                </div>
                            </form>
                        </div>

                        @if(isset($item['replies']) && $item['replies']->count() > 0)
                            @foreach($item['replies'] as $reply)
                                <div class="media mt-3 p-3 border rounded bg-white ml-4 shadow-sm">
                                    <i class="bi bi-person-circle mr-3" style="font-size: 2rem;"></i>
                                    <div class="media-body">
                                        <small class="text-muted">Membalas komentar dari {{ $item['comment']->user_name }}</small>
                                        <h6 class="mt-0 font-weight-bold">
                                            {{ $reply->user_name }}
                                            @if(isset($reply->user) && $reply->user->role == 'admin')
                                                <span class="badge badge-danger">Admin</span>
                                            @endif
                                        </h6>
                                        <p>{{ $reply->content }}</p>
                                        <small class="text-muted">
                                            Diposting pada {{ \Carbon\Carbon::parse($reply->created_at)->locale('id_ID')->isoFormat('D MMMM YYYY HH:mm') }}
                                        </small>

                                        {{-- Admin dapat mengedit semua balasan --}}
                                        <button class="btn btn-warning btn-sm mt-2" data-toggle="collapse" data-target="#editReplyForm{{ $reply->id }}">Edit</button>
                                        <div id="editReplyForm{{ $reply->id }}" class="collapse mt-3">
                                            <form action="{{ route('updatekomentar1', $reply->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="2" required>{{ $reply->content }}</textarea>
                                                    @error('content')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <input type="submit" class="btn btn-primary btn-sm" value="Update">
                                                </div>
                                            </form>
                                        </div>

                                        {{-- Admin dapat menghapus semua balasan --}}
                                        <form action="{{ route('deletekomentar1', $reply->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm mt-2" onclick="return confirm('Apakah Anda yakin ingin menghapus balasan ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@else
    <p>Tidak ada komentar yang ditampilkan.</p>
@endif
        </div>
    </div>
</div>
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
    document.addEventListener('DOMContentLoaded', function () {
        // Notifikasi otomatis hilang setelah 5 detik
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.remove(); // Menghapus elemen dari DOM
            }, 5000);
        });

        // Fullscreen untuk PDF
        const fullscreenButton = document.getElementById('fullscreenButton');
        const pdfFrame = document.getElementById('pdfFrame');
        if (fullscreenButton && pdfFrame) {
            fullscreenButton.addEventListener('click', function () {
                if (pdfFrame.requestFullscreen) {
                    pdfFrame.requestFullscreen();
                } else if (pdfFrame.mozRequestFullScreen) { // Firefox
                    pdfFrame.mozRequestFullScreen();
                } else if (pdfFrame.webkitRequestFullscreen) { // Chrome, Safari, Opera
                    pdfFrame.webkitRequestFullscreen();
                } else if (pdfFrame.msRequestFullscreen) { // IE/Edge
                    pdfFrame.msRequestFullscreen();
                } else {
                    alert('Fullscreen tidak didukung pada browser ini.');
                }
            });
        }

        // Toggle form komentar utama
        const btnKomentarUtama = document.getElementById('btn-komentar-utama');
        const komentarUtamaForm = document.getElementById('komentar-utama-form');
        const btnBatalKomentar = document.getElementById('btn-batal-komentar');
        if (btnKomentarUtama && komentarUtamaForm) {
            btnKomentarUtama.addEventListener('click', function () {
                komentarUtamaForm.style.display = 'block';
                btnKomentarUtama.style.display = 'none';
            });
            btnBatalKomentar.addEventListener('click', function () {
                komentarUtamaForm.style.display = 'none';
                btnKomentarUtama.style.display = 'inline-block';
            });
        }
    });
</script>
@stop
