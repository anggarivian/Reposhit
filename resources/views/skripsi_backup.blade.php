@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Mahasiswa Skripsi</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-default">
    <div class="card-header">{{__(' Data Skripsi')}}</div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                    <!-- Button trigger modal -->
                    @if ($skripsi == null)
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Tambah Data Skripsi
                    </button>
                    @endif
                {{-- <div class="tombol">
                    <button type="button" class="btn btn-info" >
                        Export
                    </button>
                </div> --}}
            </div>
            <table id="table-data" class="table table-striped text-center">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Judul</th>
                        <th>Abstrak</th>
                        <th>Penulis</th>
                        <th>Dosen Pembimbing</th>
                        <th>Rilis</th>
                        <th>Status</th>
                        <th>Halaman</th>
                        <th>Kata Kunci</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no=1; @endphp
                    @if ($skripsi != null)
                        @foreach($skripsi as $skripsis)
                        <tr>
                            <td>{{$no++}}</td>
                            <td>{{ Str::limit($skripsis->judul, 20) }}</td>
                            <td>{{ Str::limit($skripsis->abstrak, 20) }}</td>
                            <td>{{$skripsis->penulis}}</td>
                            <td>{{$skripsis->dospem}}</td>
                            <td>{{$skripsis->rilis}}</td>
                            <td>
                            @if ($skripsis->status == 0)
                                <span class="badge badge-warning">Belum Diverifikasi</span>
                            @elseif ($skripsis->status == 1)
                                <span class="badge badge-success">Sudah Diverifikasi</span>
                            @elseif ($skripsis->status == 2)
                                <span class="badge badge-danger">Ditolak</span>
                            @else
                                <span class="badge badge-secondary">Status Tidak Diketahui</span>
                            @endif
                            </td>
                            <td>{{$skripsis->halaman}}</td>
                            <td>{{$skripsis->katakunci}}</td>
                            <td>
                                <a href="/mahasiswa/skripsi/detail/{{ $skripsis->id }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                @if ($skripsis->status != 1)
                                <button id="btn-edit-skripsi" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit" data-id="{{ $skripsis->id }}">
                                    <i class="fas fa-edit"> Edit</i>
                                </button>
                                @endif
                                    {{-- <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation('{{$skripsis->id}}' , '{{$skripsis->judul}}' )">
                                        <i class="fas fa-trash-alt"> Hapus</i> <!-- Ikon untuk Hapus Favorite -->
                                    </button> --}}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form method="post" action="{{ route('tambah.skripsi') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="fas fa-graduation-cap mr-2"></i>Tambah Data Skripsi
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-4">
                        <!-- Informasi Utama Skripsi -->
                        <div class="card border-0 mb-4">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0 text-primary">
                                    <i class="fas fa-book mr-2"></i>Informasi Utama Skripsi
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="judul" class="font-weight-bold">Judul <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" name="judul" id="judul" required
                                           placeholder="Masukkan Judul Skripsi">
                                </div>

                                <div class="form-group">
                                    <label for="abstrak" class="font-weight-bold">Abstrak <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="abstrak" id="abstrak" rows="4" required
                                              placeholder="Masukkan abstrak skripsi..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Data Penulis dan Pembimbing -->
                        <div class="card border-0 mb-4">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0 text-primary">
                                    <i class="fas fa-users mr-2"></i>Data Penulis & Pembimbing
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="penulis" class="font-weight-bold">Penulis</label>
                                            <input type="text" class="form-control" name="penulis"
                                                   value="{{ Auth::user()->name }}" readonly>
                                            <small class="form-text text-muted">Data diambil dari akun Anda</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dospem" class="font-weight-bold">Dosen Pembimbing <span class="text-danger">*</span></label>
                                            <select name="dospem" class="form-control" required>
                                                <option value="">-- Pilih Dosen Pembimbing --</option>
                                                @foreach ($namaDospem as $nama)
                                                    <option value="{{ $nama->nama }}">{{ $nama->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Publikasi -->
                        <div class="card border-0 mb-4">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0 text-primary">
                                    <i class="fas fa-calendar-alt mr-2"></i>Detail Publikasi
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="rilis" class="font-weight-bold">Tahun Rilis <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="rilis" required
                                                   placeholder="Contoh: 2025" min="2000" max="2030">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="halaman" class="font-weight-bold">Jumlah Halaman <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="halaman" required
                                                   placeholder="Contoh: 100" min="1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Metadata Tambahan -->
                        <div class="card border-0 mb-4">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0 text-primary">
                                    <i class="fas fa-tags mr-2"></i>Metadata Tambahan
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="katakunci" class="font-weight-bold">Kata Kunci <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="katakunci" id="katakunci" required
                                                   placeholder="machine learning, web development">
                                            <small class="form-text text-muted">Pisahkan dengan koma</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="subject" class="font-weight-bold">Bidang Keilmuan</label>
                                            <input type="text" class="form-control" name="subject" id="subject"
                                                   value="{{ Auth::user()->jurusan->nama_jurusan }}" readonly>
                                            <small class="form-text text-muted">Berdasarkan jurusan Anda</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="coverage" class="font-weight-bold">Cakupan</label>
                                            <input type="text" class="form-control" name="coverage" id="coverage"
                                                   placeholder="Informatika - Pemrograman Web">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="identifier" class="font-weight-bold">Identifier</label>
                                            <input type="text" class="form-control" name="identifier" id="identifier"
                                                   placeholder="Link atau kode unik (opsional)">
                                            <small class="form-text text-muted">URL atau kode identifikasi</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="source" class="font-weight-bold">Sumber Dokumen</label>
                                            <input type="text" class="form-control" name="source" id="source"
                                                   placeholder="Original, Terjemahan, Revisi">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="rights" class="font-weight-bold">Hak Cipta</label>
                                            <input type="text" class="form-control" name="rights" id="rights"
                                                   placeholder="Open Access, Copyright, dll">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload File -->
                        <div class="card border-0 mb-4">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0 text-primary">
                                    <i class="fas fa-upload mr-2"></i>Upload File Dokumen
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Catatan:</strong> Semua file harus dalam format PDF dengan ukuran maksimal 10 MB
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="file_skripsi" class="font-weight-bold">
                                                <i class="fas fa-file-pdf text-danger mr-2"></i>File Skripsi Lengkap <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" class="form-control-file" name="file_skripsi" accept=".pdf" required>
                                            <small class="form-text text-muted">Format: PDF, Maksimal: 10 MB</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="file_dapus" class="font-weight-bold">
                                                <i class="fas fa-file-pdf text-danger mr-2"></i>File Daftar Pustaka <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" class="form-control-file" name="file_dapus" accept=".pdf" required>
                                            <small class="form-text text-muted">Format: PDF, Maksimal: 10 MB</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="file_abstrak" class="font-weight-bold">
                                                <i class="fas fa-file-pdf text-danger mr-2"></i>File Abstrak <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" class="form-control-file" name="file_abstrak" accept=".pdf" required>
                                            <small class="form-text text-muted">Format: PDF, Maksimal: 10 MB</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Fields -->
                        <input type="hidden" name="format" value="PDF">
                        <input type="hidden" name="publisher" value="Universitas Suryakancana Fakultas Sains Terapan">
                        <input type="hidden" name="language" value="id">
                        <input type="hidden" name="type" value="Skripsi">
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Tambah Data Skripsi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Modal Edit -->
<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="post" action="{{ route('ubah.skripsi') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Data Skripsi</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">

                    <div class="form-group">
                        <label for="edit-judul">Judul</label>
                        <input type="text" class="form-control" name="judul" id="edit-judul" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-abstrak">Abstrak</label>
                        <input class="form-control" name="abstrak" id="edit-abstrak" rows="3" required></input>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="edit-penulis">Penulis</label>
                            <input type="text" class="form-control" name="penulis" value="{{ Auth::user()->name }}" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="edit-dospem">Dosen Pembimbing</label>
                            <select name="dospem" class="form-control" id="edit-dospem" required>
                                <option value="">Pilih</option>
                                @foreach ($namaDospem as $nama)
                                    <option value="{{ $nama->nama }}">{{ $nama->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="edit-rilis">Rilis Tahun</label>
                            <input type="number" class="form-control" name="rilis" id="edit-rilis" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="edit-halaman">Jumlah Halaman</label>
                            <input type="number" class="form-control" name="halaman" id="edit-halaman" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="edit-katakunci">Kata Kunci</label>
                            <input type="text" class="form-control" name="katakunci" id="edit-katakunci" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit-file_skripsi">File Skripsi (PDF, maks 10 MB)</label>
                            <input type="file" class="form-control" name="file_skripsi" id="edit-file_skripsi" accept=".pdf">
                            <div id="file_skripsi-area" class="mb-1"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="edit-file_dapus">File Daftar Pustaka (PDF, maks 10 MB)</label>
                            <input type="file" class="form-control" name="file_dapus" id="edit-file_dapus" accept=".pdf">
                            <div id="file_dapus-area1" class="mb-1"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit-file_abstrak">File Abstrak (PDF, maks 10 MB)</label>
                            <input type="file" class="form-control" name="file_abstrak" id="edit-file_abstrak" accept=".pdf">
                            <div id="file_abstrak-area2" class="mb-1"></div>
                        </div>
                    </div>

                    <!-- Hidden fields to retain old file names -->
                    <input type="hidden" name="old_file_skripsi" id="edit-old-file_skripsi">
                    <input type="hidden" name="old_file_dapus" id="edit-old-file_dapus">
                    <input type="hidden" name="old_file_abstrak" id="edit-old-file_abstrak">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Ubah Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('js')

<script>
  $(function(){
        $(document).on('click', '#btn-edit-skripsi', function () {
            let id = $(this).data('id');
            $('#file_skripsi-area').empty(); // Kosongkan elemen sebelum diisi ulang
            $('#file-upload-area').hide(); // Sembunyikan area upload file saat modal dibuka
            $('#file_skripsi-area1').empty();
            $('#file-upload-area1').hide();
            $('#file_skripsi-area2').empty();
            $('#file-upload-area2').hide();

            $.ajax({
                type: "GET",
                url: "{{ url('mahasiswa/ajaxmahasiswa/dataSkripsi') }}/" + id,
                dataType: 'json',
                success: function (res) {
                    $('#edit-id').val(res.id);
                    $('#edit-judul').val(res.judul);
                    $('#edit-abstrak').val(res.abstrak);
                    $('#edit-dospem').val(res.dospem);
                    $('#edit-rilis').val(res.rilis);
                    $('#edit-halaman').val(res.halaman);
                    $('#edit-katakunci').val(res.katakunci);
                    $('#edit-old-file_skripsi').val(res.file_skripsi);
                    $('#edit-old-file_dapus').val(res.file_dapus);
                    $('#edit-old-file_abstrak').val(res.file_abstrak);

                    // Menampilkan status file skripsi
                    if (res.file_skripsi) {
                        $('#file_skripsi-area').html('<span class="text-success">File Tersedia: ' + res.file_skripsi + '</span>');
                        $('#file-upload-area').show(); // Menampilkan form upload file jika file tersedia
                    } else {
                        $('#file_skripsi-area').html('<span class="text-danger">File Tidak Tersedia</span>');
                    }
                    if (res.file_dapus) {
                        $('#file_dapus-area1').html('<span class="text-success">File Tersedia: ' + res.file_dapus + '</span>');
                        $('#file-upload-area1').show(); // Menampilkan form upload file jika file tersedia
                    } else {
                        $('#file_dapus-area1').html('<span class="text-danger">File Tidak Tersedia</span>');
                    }
                    if (res.file_abstrak) {
                        $('#file_abstrak-area2').html('<span class="text-success">File Tersedia: ' + res.file_abstrak + '</span>');
                        $('#file-upload-area2').show(); // Menampilkan form upload file jika file tersedia
                    } else {
                        $('#file_abstrak-area2').html('<span class="text-danger">File Tidak Tersedia</span>');
                    }
                },
                error: function () {
                    alert("Gagal mengambil data!");
                }
            });
        });
    });


        function deleteConfirmation(id,judul) {
            swal.fire({
                title: "Hapus?",
                type: 'warning',
                text: "Apakah anda yakin akan menghapus Skripsi dengan Judul " +judul+"?!",
                showCancelButton: !0,
                confirmButtonText: "Ya, lakukan!",
                cancelButtonText: "Tidak, batalkan!",

            }).then (function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'GET',
                        url: "{{url('/mahasiswa/skripsi/hapus')}}/"+id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            if (results.success === true) {
                                swal.fire("Done!", results.message, "success");
                                setTimeout(function(){
                                    location.reload();
                                },1000);
                            } else {
                                swal.fire("Error!", results.message, "error");
                            }
                        }
                    });
                } else {
                    e.dismiss;
                }
            }, function (dismiss) {
                return false;
            })
        }
</script>
@stop
