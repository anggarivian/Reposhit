@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Data Skripsi Mahasiswa</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-default">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
            </div>
            <table id="table-data" class="table table-striped text-center">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Abstrak</th>
                        <th>Dosen Pembimbing</th>
                        <th>Rilis</th>
                        <th>Status</th>
                        <th>Halaman</th>
                        <th>Tanggal Dibuat</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no=1; @endphp
                    @foreach($skripsi as $skripsis)
                    <tr>
                        <td>{{$no++}}</td>
                        <td>{{ Str::limit($skripsis->judul, 20) }}</td>
                        <td>{{$skripsis->penulis}}</td>
                        <td>{{ Str::limit($skripsis->abstrak, 20) }}</td>
                        <td>{{$skripsis->dospem}}</td>
                        <td>{{$skripsis->rilis}}</td>
                        <td>
                            @if ($skripsis->status == 0)
                                <span class="badge badge-warning">Belum Diverifikasi</span>
                            @else
                                <span class="badge badge-success">Sudah Diverifikasi</span>
                            @endif
                        </td>
                        <td>{{$skripsis->halaman}}</td>
                        <td>{{ $skripsis->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="form-group" role="group" aria-label="Basic example">
                                <a href="/admin/skripsi/detail/{{$skripsis->id}}">
                                    <button class="btn btn-sm btn-info">
                                          <i class="fas fa-eye"> Lihat Skripsi</i> 
                                    </button>
                                </a>
                                <button id="btn-edit-skripsi" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit" data-id="{{ $skripsis->id }}">
                                    <i class="fas fa-edit"> Edit</i>
                                </button>  
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation('{{$skripsis->id}}' , '{{$skripsis->judul}}' )">
                                    <i class="fas fa-trash-alt"> Hapus </i>
                                </button>
                                <button type="button" class="btn btn-sm btn-info" onclick="verifikasiConfirmation('{{$skripsis->id}}' , '{{$skripsis->judul}}' )">
                                    <i class="fas fa-check-circle"> Verifikasi</i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- Modal Edit -->
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Skripsi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('edit.skripsi') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <input type="hidden" class="form-control" name="id" id="edit-id">
                        <div class="form-group">
                            <label for="judul">Judul</label>
                            <input type="text" class="form-control" name="judul" id="edit-judul" required placeholder="Masukkan Judul Skripsi">
                        </div>
                        <div class="form-group">
                            <label for="abstrak">Abstrak</label>
                            <textarea class="form-control" name="abstrak" id="edit-abstrak" required placeholder="Masukkan Abstrak"></textarea>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-3">
                                <label for="penulis">Penulis</label>
                                <input type="text" class="form-control" name="penulis" id="penulis" value="{{ Auth::user()->name }}" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="dospem">Dosen Pembimbing</label>
                                <select name="dospem" class="form-control" id="edit-dospem">
                                    <option selected>Pilih</option>
                                    @foreach ($namaDospem as $nama)
                                        <option value="{{$nama->nama}}">{{$nama->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="rilis">Rilis Pada Tahun</label>
                                <input type="text" class="form-control" name="rilis" id="edit-rilis" required placeholder="Harus 4 Angka">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="halaman">Halaman</label>
                                <input type="text" class="form-control" name="halaman" id="edit-halaman" required placeholder="Masukkan Jumlah Halaman">
                            </div>
                        </div>
                        <!-- Form untuk Mengganti File Skripsi -->
                        <div id="file-upload-area" class="mb-3" style="display:none;">
                            <label for="edit-file_skripsi" class="form-label">Pilih File Skripsi Baru</label>
                            <input type="file" class="form-control" id="edit-file_skripsi" name="file_skripsi">
                        </div>
                        <!-- Status File Skripsi -->
                        <div id="file_skripsi-area" class="mb-3">
                            <!-- Teks ini akan diubah oleh JavaScript berdasarkan status file -->
                        </div>
                        <div class="modal-footer">
                            <input type="text" name="old_file_skripsi" id="edit-old-file_skripsi" hidden />
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

            $.ajax({
                type: "GET",
                url: "{{ url('admin/ajaxskripsi/dataSkripsi') }}/" + id,
                dataType: 'json',
                success: function (res) {
                    $('#edit-id').val(res.id);
                    $('#edit-judul').val(res.judul);
                    $('#edit-abstrak').val(res.abstrak);
                    $('#edit-dospem').val(res.dospem);
                    $('#edit-rilis').val(res.rilis);
                    $('#edit-halaman').val(res.halaman);
                    $('#edit-old-file_skripsi').val(res.file_skripsi);

                    // Menampilkan status file skripsi
                    if (res.file_skripsi) {
                        $('#file_skripsi-area').html('<span class="text-success">File Tersedia: ' + res.file_skripsi + '</span>');
                        $('#file-upload-area').show(); // Menampilkan form upload file jika file tersedia
                    } else {
                        $('#file_skripsi-area').html('<span class="text-danger">File Tidak Tersedia</span>');
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
                    url: "{{url('/admin/skripsi/hapus')}}/"+id,
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

    function verifikasiConfirmation(id,name) {
        swal.fire({
            title: "Verifikasi akun Skripsi?",
            type: 'warning',
            text: "Apakah anda ingin memverifikasi skripsi tersebut " +name+"?",
            showCancelButton: !0,
            confirmButtonText: "Ya, lakukan!",
            cancelButtonText: "Tidak, batalkan!",
        }).then (function (e) {
            if (e.value === true) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'GET',
                    url: "{{url('/admin/skripsi/verifikasi')}}/"+id,
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
