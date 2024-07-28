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

                    {{-- Form komentar utama --}}
                    <form action="{{ route('postkomentar') }}" style="margin-top: 8px; display: none;" id="komentar-utama-form" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="komentar_utama">Tulis komentar Anda</label>
                            <textarea name="content" class="form-control" id="komentar_utama" rows="4" placeholder="Tulis komentar Anda di sini..." required></textarea>
                            <input type="hidden" name="id_skripsi" value="{{ $skripsi->id }}">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Kirim" style="margin-top: 8px;">
                        </div>
                    </form>

                    {{-- Container untuk notifikasi --}}
                    <div id="notification-container"></div>

                    <hr>

                    {{-- Loop untuk menampilkan komentar dan balasannya --}}
                    @if(isset($comments) && $comments->count() > 0)
                        <div class="comment-section mt-3">
                            @foreach($comments->reverse() as $item)
                                <div class="media mb-3 p-3 border rounded bg-light">
                                    <div class="mr-3">
                                        <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mt-0 font-weight-bold">{{ $item['comment']->user_name }}</h5>
                                        <p>{{ $item['comment']->content }}</p>
                                        <small class="text-muted">Diposting pada {{ \Carbon\Carbon::parse($item['comment']->created_at)->locale('id_ID')->isoFormat('D MMMM YYYY HH:mm') }}</small>
                                        <!-- Delete Button -->
                                        <form action="{{ route('deletekomentar', $item['comment']->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">Hapus</button>
                                        </form>
                                        <!-- Balas Komentar -->
                                        <button class="btn btn-secondary btn-sm ml-2" data-toggle="collapse" data-target="#balasanForm{{ $item['comment']->id }}">Balas</button>
                                        <div id="balasanForm{{ $item['comment']->id }}" class="collapse mt-3">
                                            <form action="{{ route('postBalasan') }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3" placeholder="Tulis balasan Anda di sini..." required>{{ old('content') }}</textarea>
                                                    @error('content')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <input type="hidden" name="id_skripsi" value="{{ $skripsi->id }}">
                                                    <input type="hidden" name="parent_id" value="{{ $item['comment']->id }}">
                                                </div>
                                                <div class="form-group">
                                                    <input type="submit" class="btn btn-primary btn-sm" value="Kirim">
                                                </div>
                                            </form>
                                        </div>
                                        @if(isset($item['replies']))
                                            @foreach($item['replies'] as $reply)
                                                <div class="media mb-3 p-3 border rounded bg-light mt-3 ml-5">
                                                    <div class="mr-3">
                                                        <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <div class="media-body">
                                                        <h5 class="mt-0 font-weight-bold">{{ $reply->user_name }}</h5>
                                                        <p>{{ $reply->content }}</p>
                                                        <small class="text-muted">Diposting pada {{ \Carbon\Carbon::parse($reply->created_at)->locale('id_ID')->isoFormat('D MMMM YYYY HH:mm') }}</small>
                                                        <!-- Delete Button -->
                                                        <form action="{{ route('deletekomentar', $reply->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">Hapus</button>
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
                        <p>Belum ada komentar untuk skripsi ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Function to show alert and hide after a specified time
            function showAlert(message, alertType) {
                var alertBox = $('<div class="alert alert-' + alertType + ' alert-dismissible fade show mt-3" role="alert">' + message +
                                 '</div>');
                $('#notification-container').append(alertBox);
                setTimeout(function() {
                    alertBox.alert('close');
                }, 5000); // 5000 milliseconds = 5 seconds
            }

            // Example usage:
            @if(session('success'))
                showAlert("{{ session('success') }}", "success");
            @endif

            @if(session('error'))
                showAlert("{{ session('error') }}", "danger");
            @endif

            // Tampilkan/hide iframe sesuai dengan pilihan dropdown
            $('#selectPdf').change(function() {
                var selectedPdf = $(this).val();
                $('iframe').hide();
                $('h2').hide();
                $('#' + selectedPdf + 'Frame').show();
                $('#' + selectedPdf + 'Header').show();
                $('#closePdfButton').show();
            });

            // Tombol tutup PDF
            $('#closePdfButton').click(function() {
                $('iframe').hide();
                $('h2').hide();
                $(this).hide();
                $('#selectPdf').val('');
            });

            // Toggle form komentar utama
            $('#btn-komentar-utama').click(function() {
                $('#komentar-utama-form').toggle();
            });
        });
    </script>
@stop
