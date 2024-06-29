@extends('adminlte::page')

@section('title', 'Detail Skripsi')

@section('content_header')
    <h1 class="m-0 text-dark">Detail Skripsi</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width: 200px;">Judul Skripsi</th>
                            <td>{{ $skripsi->judul }}</td>
                        </tr>
                        <tr>
                            <th style="width: 200px;">Penulis</th>
                            <td>{{ $skripsi->penulis }}</td>
                        </tr>
                        <tr>
                            <th style="width: 200px;">Dosen Pembimbing</th>
                            <td>{{ $skripsi->dospem }}</td>
                        </tr>
                        <tr>
                            <th style="width: 200px;">Rilis Tahun</th>
                            <td>{{ $skripsi->rilis }}</td>
                        </tr>
                        <tr>
                            <th style="width: 200px;">Halaman</th>
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
                                            ? 'Bab ' . (
                                                $attribute == 'bab1' ? 'I' : (
                                                    $attribute == 'bab2' ? 'II' : (
                                                        $attribute == 'bab3' ? 'III' : (
                                                            $attribute == 'bab4' ? 'IV' : (
                                                                $attribute == 'bab5' ? 'V' : $attribute
                                                            )
                                                        )
                                                    )
                                                )
                                            )
                                            : $attribute
                                        );
                                @endphp
                                <option value="{{ $attribute }}">{{ ucfirst($label) }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Menampilkan semua PDF --}}
                    @foreach($pdfs as $attribute => $pdf)
                        @php
                            $label = $attribute == 'dapus'
                                ? 'Daftar Pustaka'
                                : (strpos($attribute, 'bab') === 0
                                    ? 'Bab ' . (
                                        $attribute == 'bab1' ? 'I' : (
                                            $attribute == 'bab2' ? 'II' : (
                                                $attribute == 'bab3' ? 'III' : (
                                                    $attribute == 'bab4' ? 'IV' : (
                                                        $attribute == 'bab5' ? 'V' : $attribute
                                                    )
                                                )
                                            )
                                        )
                                    )
                                    : $attribute
                                );
                        @endphp
                        <h2 id="{{ $attribute }}Header" style="display: none;">{{ ucfirst($label) }}</h2>
                        <iframe id="{{ $attribute }}Frame" src="data:application/pdf;base64,{{ $pdf }}#toolbar=0&navpanes=0&view=Fit" width="100%" height="700px" style="display: none;"></iframe>
                    @endforeach

                    <button class="btn btn-secondary mt-3" id="closePdfButton" style="display: none;">Tutup PDF</button>

                    <hr>
                    <div class="btn-group">
                        <button class="btn btn-default" id="btn-suka-utama">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0"/>
                            </svg>
                            <span class="ms-1">Suka</span>
                        </button>
                        <button class="btn btn-default" id="btn-komentar-utama">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-square" viewBox="0 0 16 16">
                                <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                            </svg>
                            <span class="ms-1">Komentar</span>
                        </button>
                    </div>

                    <form action="{{ route('postkomentar') }}" style="margin-top: 8px; display: none;" id="komentar-utama-form" method="POST">
                        @csrf
                        <!-- Textarea untuk konten komentar -->
                        <div class="form-group">
                            <label for="komentar_utama">Tulis komentar Anda</label>
                            <textarea name="content" class="form-control" id="komentar_utama" rows="4" placeholder="Tulis komentar Anda di sini..." required></textarea>
                            <input type="hidden" name="id_skripsi" value="{{ $skripsi->id }}">
                        </div>
                        <!-- Tombol Kirim -->
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Kirim" style="margin-top: 8px;">
                        </div>
                    </form>
                    <hr>
                    @if(session('success'))
                    <div class="alert alert-success" role="alert" id="success-alert">
                        {{ session('success') }}
                    </div>
                    @endif
                    @if(isset($comment) && $comment->count() > 0)
                    <div class="comment-section mt-3">
                        @foreach($comment->reverse() as $item) <!-- Menggunakan reverse() untuk membalik urutan -->
                        <div class="media mb-3 p-3 border rounded bg-light">
                            <div class="mr-3">
                                <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
                            </div>
                            <div class="media-body">
                                <h5 class="mt-0 font-weight-bold">{{ $item->user_name }}</h5>
                                <p>{{ $item->content }}</p>
                                <small class="text-muted">Diposting pada {{ \Carbon\Carbon::parse($item->created_at)->locale('id_ID')->isoFormat('D MMMM YYYY HH:mm') }}</small>
                                 <!-- Delete Button -->
                                <form action="{{ route('deletekomentar', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted">Tidak ada komentar saat ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .btn-group button {
            margin-right: 10px;
        }

        .comment-section {
            background-color: #e4e4e4;
            padding: 10px;
            border-radius: 8px;
        }

        .media {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .media-body {
            margin-left: 10px;
        }

        .media .bi-person-circle {
            font-size: 2rem;
        }
        table {
            background-color: #fff;
            border-radius: 8px;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        th, td {
            padding: 15px;
        }

        .card-body {
            padding: 2rem;
        }
        .table th, .table td {
            vertical-align: middle;
        }

    </style>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectPdf = document.getElementById('selectPdf');
            const closePdfButton = document.getElementById('closePdfButton');

            selectPdf.addEventListener('change', function() {
                const targetAttribute = this.value;

                // Sembunyikan semua iframe dan header (nama atribut) terlebih dahulu
                document.querySelectorAll('iframe').forEach(function(iframe) {
                    iframe.style.display = 'none';
                });

                document.querySelectorAll('h2').forEach(function(header) {
                    header.style.display = 'none';
                });

                // Tampilkan iframe dan header yang sesuai dengan pilihan
                if (targetAttribute) {
                    const pdfFrame = document.getElementById(targetAttribute + 'Frame');
                    const pdfHeader = document.getElementById(targetAttribute + 'Header');

                    pdfFrame.style.display = 'block';
                    pdfHeader.style.display = 'block';
                    closePdfButton.style.display = 'inline-block';
                } else {
                    closePdfButton.style.display = 'none';
                }
            });

            closePdfButton.addEventListener('click', function() {
                // Sembunyikan semua iframe dan header (nama atribut)
                document.querySelectorAll('iframe').forEach(function(iframe) {
                    iframe.style.display = 'none';
                });

                document.querySelectorAll('h2').forEach(function(header) {
                    header.style.display = 'none';
                });

                closePdfButton.style.display = 'none';
                selectPdf.value = '';
            });

            var komentarButton = document.getElementById('btn-komentar-utama');
            var komentarForm = document.getElementById('komentar-utama-form');

            komentarButton.addEventListener('click', function() {
                if (komentarForm.style.display === 'none' || komentarForm.style.display === '') {
                    komentarForm.style.display = 'block';
                } else {
                    komentarForm.style.display = 'none';
                }
            });

            // Cek apakah notifikasi sukses ada
            var successAlert = document.getElementById('success-alert');
            if (successAlert) {
                // Hilangkan notifikasi setelah 5 detik
                setTimeout(function() {
                    successAlert.style.display = 'none';
                }, 5000);
            }
        });
    </script>
@stop
