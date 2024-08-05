@extends('adminlte::page')

@section('title', 'Detail Skripsi')

@section('content_header')
    <h1 class="m-0 text-dark"> Skripsi Mahasiswa </h1>
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
                    <form action="{{ route('postkomentar1') }}" id="komentar-utama-form" method="POST" style="display: none; margin-top: 8px;">
                        @csrf
                        <div class="form-group">
                            <label for="komentar_utama">Tulis komentar Anda</label>
                            <textarea name="content" class="form-control" id="komentar_utama" rows="4" placeholder="Tulis komentar Anda di sini..." required></textarea>
                            <input type="hidden" name="id_skripsi" value="{{ $skripsi->id }}">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Kirim" style="margin-top: 8px;">
                            <button type="button" class="btn btn-secondary" id="btn-batal-komentar">Batal</button>
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
                                            <button type="submit" class="btn btn-danger btn-sm mt-2" onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">Hapus</button>
                                        </form>
                                        <!-- Check if the logged-in user is the owner of the comment -->
                                        @if(auth()->user() && auth()->user()->id == $item['comment']->id_user)
                                            <!-- Edit Button -->
                                            <button class="btn btn-warning btn-sm mt-2" data-toggle="collapse" data-target="#editCommentForm{{ $item['comment']->id }}">Edit</button>
                                            <div id="editCommentForm{{ $item['comment']->id }}" class="collapse mt-3">
                                                <form action="{{ route('updatekomentar1', $item['comment']->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3" placeholder="Edit komentar Anda di sini..." required>{{ $item['comment']->content }}</textarea>
                                                        @error('content')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="submit" class="btn btn-primary btn-sm" value="Update">
                                                    </div>
                                                </form>
                                            </div>
                                        @endif

                                        <!-- Form untuk balasan komentar -->
                                        <button class="btn btn-info btn-sm mt-2" data-toggle="collapse" data-target="#replyForm{{ $item['comment']->id }}">Balas</button>
                                        <div id="replyForm{{ $item['comment']->id }}" class="collapse mt-3">
                                            <form action="{{ route('postBalasan') }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <textarea name="content" class="form-control" rows="3" placeholder="Tulis balasan di sini..." required></textarea>
                                                    <input type="hidden" name="id_skripsi" value="{{ $skripsi->id }}">
                                                    <input type="hidden" name="parent_id" value="{{ $item['comment']->id }}">
                                                </div>
                                                <div class="form-group">
                                                    <input type="submit" class="btn btn-primary btn-sm" value="Kirim">
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Tampilkan balasan komentar -->
                                            @if(isset($item['replies']))
                                                @foreach($item['replies'] as $reply)
                                                    <div class="media mt-3 p-3 border rounded bg-white ml-4">
                                                        <div class="mr-3">
                                                            <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
                                                        </div>
                                                        <div class="media-body">
                                                            <!-- Tanda bahwa ini adalah balasan -->
                                                            <small class="text-muted">Membalas komentar dari {{ $item['comment']->user_name }}</small>
                                                            <h6 class="mt-2 font-weight-bold">{{ $reply->user_name }}</h6>
                                                            <p>{{ $reply->content }}</p>
                                                            <small class="text-muted">Diposting pada {{ \Carbon\Carbon::parse($reply->created_at)->locale('id_ID')->isoFormat('D MMMM YYYY HH:mm') }}</small>

                                                            @if(auth()->user()->id == $reply->id_user)
                                                                <!-- Tombol Edit Balasan -->
                                                                <button class="btn btn-warning btn-sm ml-2" onclick="toggleEditForm({{ $reply->id }})">Edit</button>

                                                                <!-- Form Edit Balasan -->
                                                                <div id="editForm{{ $reply->id }}" class="mt-3" style="display:none;">
                                                                    <form action="{{ route('updatekomentar1', $reply->id) }}" method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="form-group">
                                                                            <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3" required>{{ old('content', $reply->content) }}</textarea>
                                                                            @error('content')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <input type="submit" class="btn btn-primary btn-sm" value="Update">
                                                                            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleEditForm({{ $reply->id }})">Batal</button>
                                                                        </div>
                                                                    </form>
                                                                        <form action="{{ route('deletekomentar', $reply->id) }}" method="POST" style="display:inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger btn-sm mt-2" onclick="return confirm('Apakah Anda yakin ingin menghapus balasan ini?')">Hapus</button>
                                                                    </form>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                </div>
                        @endforeach
                    </div>
                    @else
                        <p class="text-muted">Belum ada komentar.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.getElementById('selectPdf').addEventListener('change', function() {
            const selectedValue = this.value;
            document.querySelectorAll('iframe').forEach(frame => frame.style.display = 'none');
            document.querySelectorAll('h2').forEach(header => header.style.display = 'none');
            document.getElementById('closePdfButton').style.display = 'none';

            if (selectedValue) {
                document.getElementById(`${selectedValue}Frame`).style.display = 'block';
                document.getElementById(`${selectedValue}Header`).style.display = 'block';
                document.getElementById('closePdfButton').style.display = 'block';
            }
        });

        document.getElementById('closePdfButton').addEventListener('click', function() {
            document.querySelectorAll('iframe').forEach(frame => frame.style.display = 'none');
            document.querySelectorAll('h2').forEach(header => header.style.display = 'none');
            this.style.display = 'none';
            document.getElementById('selectPdf').value = '';
        });

        // Manage the comment and reply forms
        document.getElementById('btn-komentar-utama').addEventListener('click', function() {
            document.getElementById('komentar-utama-form').style.display = 'block';
            this.style.display = 'none';
        });

        document.querySelectorAll('.btn-warning').forEach(button => {
            button.addEventListener('click', function() {
                const target = document.querySelector(this.dataset.target);
                target.classList.toggle('collapse');
            });
            document.getElementById('btn-komentar-utama').addEventListener('click', function() {
    const form = document.getElementById('komentar-utama-form');
    const isFormVisible = form.style.display === 'block';

    // Toggle form visibility
    form.style.display = isFormVisible ? 'none' : 'block';
    this.style.display = isFormVisible ? 'block' : 'none'; // Hide button when form is visible
});

document.getElementById('btn-batal-komentar').addEventListener('click', function() {
    document.getElementById('komentar-utama-form').style.display = 'none';
    document.getElementById('btn-komentar-utama').style.display = 'block'; // Show button again
});
    });

        // Optional: Auto-hide notification after 5 seconds
        document.querySelectorAll('.notification').forEach(notification => {
            setTimeout(() => notification.remove(), 5000);
        });
        function toggleEditForm(replyId) {
    const editForm = document.getElementById('editForm' + replyId);
    editForm.style.display = editForm.style.display === 'none' ? 'block' : 'none';}

    function hideCommentForm() {
    document.getElementById('komentar-utama-form').style.display = 'none';
    document.getElementById('btn-komentar-utama').style.display = 'block';
}
    </script>
@stop
