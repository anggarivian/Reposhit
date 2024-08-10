@extends('adminlte::page')

@section('title', 'Detail Skripsi')

@section('content_header')
    <h1 class="m-0 text-dark">Detail Skripsi Mahasiswa</h1>
@stop

@section('content')
<style>
    .small-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        font-size: 0.875rem; /* Ukuran font kecil */
        padding: 0.5rem 1rem; /* Padding lebih kecil */
        max-width: 300px; /* Maksimal lebar */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Sedikit bayangan */
    }

    .small-alert .close {
        font-size: 0.875rem; /* Ukuran font tombol close */
        padding: 0.25rem 0.5rem; /* Padding lebih kecil */
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

                <table class="table table-bordered table-hover">
                    <tr>
                        <th style="width: 200px;">Judul Skripsi</th>
                        <td>{{ $skripsi->judul }}</td>
                    </tr>
                    <tr>
                        <th>Penulis</th>
                        <td>{{ $skripsi->penulis }}</td>
                    </tr>
                    <tr>
                        <th>Dosen Pembimbing</th>
                        <td>{{ $skripsi->dospem }}</td>
                    </tr>
                    <tr>
                        <th>Rilis Tahun</th>
                        <td>{{ $skripsi->rilis }}</td>
                    </tr>
                    <tr>
                        <th>Halaman</th>
                        <td>{{ $skripsi->halaman }}</td>
                    </tr>
                </table>
                <hr>

                {{-- Combo box untuk menampilkan/menyembunyikan iframe --}}
                <div class="form-group">
                    <label for="selectPdf">Pilih bagian untuk melihat PDF:</label>
                    <select class="form-control" id="selectPdf">
                        <option value="">-- Pilih bagian --</option>
                        @foreach($pdfs as $attribute => $pdf)
                            @php
                                $label = $attribute == 'dapus'
                                    ? 'Daftar Pustaka'
                                    : (strpos($attribute, 'bab') === 0
                                        ? 'Bab ' . [
                                            'bab1' => 'I',
                                            'bab2' => 'II',
                                            'bab3' => 'III',
                                            'bab4' => 'IV',
                                            'bab5' => 'V',
                                        ][$attribute] ?? $attribute
                                        : ucfirst($attribute));
                            @endphp
                            <option value="{{ $attribute }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Menampilkan semua PDF --}}
                @foreach($pdfs as $attribute => $pdf)
                    <h2 id="{{ $attribute }}Header" style="display: none;" class="text-center">{{ ucfirst($label) }}</h2>
                    <iframe id="{{ $attribute }}Frame" src="data:application/pdf;base64,{{ $pdf }}#toolbar=0&navpanes=0&view=Fit" width="100%" height="700px" style="display: none; border: 1px solid #ccc; border-radius: 4px;"></iframe>
                @endforeach

                <button class="btn btn-secondary mt-3" id="closePdfButton" style="display: none;">Tutup PDF</button>

                <hr>

                <div class="btn-group">
                    <button class="btn btn-primary mr-2 btn-custom" id="btn-suka-utama">
                        <i class="bi bi-hand-thumbs-up"></i> <span>Suka</span>
                    </button>
                    <button class="btn btn-info btn-custom" id="btn-komentar-utama">
                        <i class="bi bi-chat-square-text"></i> <span>Komentar</span>
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

                {{-- Loop untuk menampilkan komentar dan balasannya --}}
                @if(isset($comments) && $comments->count() > 0)
                    <div class="comment-section mt-3">
                        @foreach($comments->reverse() as $item)
                            <div class="media mb-3 p-3 border rounded bg-light shadow-sm">
                                <i class="bi bi-person-circle mr-3" style="font-size: 2rem;"></i>
                                <div class="media-body">
                                    <h5 class="mt-0 font-weight-bold">{{ $item['comment']->user_name }}</h5>
                                    <p>{{ $item['comment']->content }}</p>
                                    <small class="text-muted">Diposting pada {{ \Carbon\Carbon::parse($item['comment']->created_at)->locale('id_ID')->isoFormat('D MMMM YYYY HH:mm') }}</small>

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
                                            <form action="{{ route('deletekomentar1', $item['comment']->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm mt-2" onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">Hapus</button>
                                            </form>

                                    <button class="btn btn-info btn-sm mt-2" data-toggle="collapse" data-target="#replyForm{{ $item['comment']->id }}">Balas</button>
                                    <div id="replyForm{{ $item['comment']->id }}" class="collapse mt-3">
                                        <form action="{{ route('postBalasan') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <textarea name="content" class="form-control" rows="3" placeholder="Tulis balasan Anda di sini..." required></textarea>
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
                                                    <h6 class="mt-0 font-weight-bold">{{ $reply->user_name }}</h6>
                                                    <p>{{ $reply->content }}</p>
                                                    <small class="text-muted">Diposting pada {{ \Carbon\Carbon::parse($reply->created_at)->locale('id_ID')->isoFormat('D MMMM YYYY HH:mm') }}</small>
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
document.addEventListener('DOMContentLoaded', function () {
    // Script notifikasi
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
        }, 5000); // Notifikasi menghilang setelah 5 detik
        setTimeout(() => {
            alert.remove();
        }, 5500); // Menghapus elemen setelah 5.5 detik untuk memberi waktu transisi
    });

    // Script combo box untuk menampilkan/menyembunyikan iframe PDF
    document.getElementById('selectPdf').addEventListener('change', function () {
        const selectedValue = this.value;
        const iframes = document.querySelectorAll('iframe[id$="Frame"]');
        const headers = document.querySelectorAll('h2[id$="Header"]');

        // Sembunyikan semua iframe dan header
        iframes.forEach(iframe => iframe.style.display = 'none');
        headers.forEach(header => header.style.display = 'none');

        // Tampilkan iframe dan header yang sesuai dengan pilihan pengguna
        if (selectedValue) {
            document.getElementById(selectedValue + 'Frame').style.display = 'block';
            document.getElementById(selectedValue + 'Header').style.display = 'block';
        }
    });

    // Script untuk menampilkan/menyembunyikan form komentar utama
    const btnKomentarUtama = document.getElementById('btn-komentar-utama');
    const formKomentarUtama = document.getElementById('komentar-utama-form');
    const btnBatalKomentar = document.getElementById('btn-batal-komentar');

    btnKomentarUtama.addEventListener('click', function() {
        if (formKomentarUtama.style.display === 'none' || formKomentarUtama.style.display === '') {
            formKomentarUtama.style.display = 'block';
        } else {
            formKomentarUtama.style.display = 'none';
        }
    });

    // Script untuk menyembunyikan form komentar utama saat tombol "Batal" diklik
    btnBatalKomentar.addEventListener('click', function() {
        formKomentarUtama.style.display = 'none';
    });
});
    </script>
@stop
