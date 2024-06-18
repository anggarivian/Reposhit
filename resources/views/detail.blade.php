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
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 200px;">Judul Skripsi</td>
                            <td style="width: 20px;">:</td>
                            <td>{{$skripsi->judul}}</td>
                        </tr>
                        <tr>
                            <td style="width: 200px;">Penulis</td>
                            <td style="width: 20px;">:</td>
                            <td>{{$skripsi->penulis}}</td>
                        </tr>
                        <tr>
                            <td style="width: 200px;">Dosen Pembimbing</td>
                            <td style="width: 20px;">:</td>
                            <td>{{$skripsi->dospem}}</td>
                        </tr>
                        <tr>
                            <td style="width: 200px;">Rilis Tahun</td>
                            <td style="width: 20px;">:</td>
                            <td>{{$skripsi->rilis}}</td>
                        </tr>
                        <tr>
                            <td style="width: 200px;">Halaman</td>
                            <td style="width: 20px;">:</td>
                            <td>{{$skripsi->halaman}}</td>
                        </tr>
                    </table>
                    <hr>
                    {{-- Tombol untuk menampilkan/menyembunyikan iframe --}}
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
                        <button class="btn btn-info m-3 showPdfButton" data-target="{{ $attribute }}">
                            Lihat {{ ucfirst($label) }}
                        </button>
                    @endforeach

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
                        <!-- Sembunyikan iframe dengan ID sesuai dengan atribut -->
                        <iframe id="{{ $attribute }}Frame" src="data:application/pdf;base64,{{ $pdf }}#toolbar=0&navpanes=0&view=fitH" width="100%" height="600px" style="display: none;"></iframe>
                    @endforeach

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
                        <textarea name="content" class="form-control" id="komentar_utama" rows="4" required></textarea>
                        <input type="hidden" name="id_skripsi" value="{{ $skripsi->id }}">
                        <!-- Tombol Kirim -->
                        <input type="submit" class="btn btn-primary" value="Kirim" style="margin-top: 8px;">
                        <br>
                    </form>
<hr>
                @if(session('success'))
                <div class="alert alert-success" role="alert" id="success-alert">
                    {{ session('success') }}
                </div>
                @endif
                    @if(isset($comment) && $comment->count() > 0)
                    <ul>
                        @foreach($comment as $item)
                            <li>{{ $item->user_name }} : {{ $item->content}}</li>
                        @endforeach
                    </ul>
                @else
                    <p>Tidak ada komentar saat ini.</p>
                @endif
                    {{-- Form untuk menambahkan komentar --}}
                    {{-- <h2>Komentar</h2>
                    <form action="{{ route('tambah.comment', ['skripsi' => $skripsi->id]) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <!-- Kolom input untuk menulis komentar -->
                            <textarea name="content" class="form-control" rows="3" placeholder="Tambahkan komentar"></textarea>
                        </div>
                        <!-- Tombol untuk mengirimkan komentar -->
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </form> --}}

                    {{-- @if($comments->count() > 0)
                    <h2>Komentar</h2>
                    <ul>
                        @foreach($comments as $comment)
                            <li>{{ $comment->content }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>Tidak ada komentar untuk skripsi ini.</p>
                @endif --}}


                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.querySelectorAll('.showPdfButton').forEach(function(button) {
            button.addEventListener('click', function() {
                var targetAttribute = this.getAttribute('data-target');

                // Sembunyikan semua iframe dan header (nama atribut) terlebih dahulu
                document.querySelectorAll('iframe').forEach(function(iframe) {
                    iframe.style.display = 'none';
                });

                document.querySelectorAll('h2').forEach(function(header) {
                    header.style.display = 'none';
                });

                // Tampilkan iframe dan header yang sesuai dengan tombol yang ditekan
                var pdfFrame = document.getElementById(targetAttribute + 'Frame');
                var pdfHeader = document.getElementById(targetAttribute + 'Header');

                pdfFrame.style.display = 'block';
                pdfHeader.style.display = 'block';
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
        var komentarButton = document.getElementById('btn-komentar-utama');
        var komentarForm = document.getElementById('komentar-utama-form');

        komentarButton.addEventListener('click', function() {
            if (komentarForm.style.display === 'none' || komentarForm.style.display === '') {
                komentarForm.style.display = 'block';
            } else {
                komentarForm.style.display = 'none';
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
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
