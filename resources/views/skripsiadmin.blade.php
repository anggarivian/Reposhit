@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Data Skripsi Mahasiswa</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-default">
    {{-- <div class="card-header">{{__(' ')}}</div> --}}
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
            </div>
            <table id="table-data" class="table table-stripped text-center">
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
                        <th>tanggal dibuat</th>
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
                                <a class="btn btn-sm btn-warning">Belum Diverifikasi</a>
                            @elseif ($skripsis->status == 1)
                                <a class="btn btn-sm btn-success">Sudah Diverifikasi</a>
                            @endif
                        </td>
                        <td>{{$skripsis->halaman}}</td>
                        <td>{{ $skripsis->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="form-group" role="group" aria-label="Basic example">
                                <a href="/admin/skripsi/detail/{{$skripsis->id}}">
                                    <button class="btn btn-sm btn-info">
                                          <i class="fas fa-eye"> Lihat Skripsi</i> <!-- Ikon untuk Lihat Detail Skripsi -->
                                    </button>
                                </a>
                                <button type="button" id="btn-edit-skripsi" class="btn btn-sm btn-success" data-toggle="modal" data-target="#edit" data-id="{{ $skripsis->id }}">
                                    <i class="fas fa-edit"> Edit </i> <!-- Ikon untuk Edit -->
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation('{{$skripsis->id}}' , '{{$skripsis->judul}}' )">
                                    <i class="fas fa-trash-alt"> Hapus  </i> <!-- Ikon untuk Hapus Favorite -->
                                </button>
                                <button type="button" class="btn btn-sm btn-info" onclick="verifikasiConfirmation('{{$skripsis->id}}' , '{{$skripsis->name}}' )">
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
                <form method="post" action="{{ route('edit.skripsi')}}" enctype="multipart/form-data">
                    @csrf
                    @method ('PATCH')
                    <div class="modal-body">
                        <input type="text" class="form-control" name="id" id="edit-id" hidden>
                        <div class="form-group">
                            <label for="judul">Judul</label>
                            <input type="text" class="form-control" name="judul" id="edit-judul" required placeholder="Masukkan Judul Skripsi">
                        </div>
                        <div class="form-group">
                            <label for="abstrak">Abstrak</label>
                            <input type="text" class="form-control" name="abstrak" id="old-abstrak" required placeholder="Masukkan Abstrak">
                        </div>
                        <div class="form-group">
                            <label for="penulis">penulis</label>
                            <input type="text" class="form-control" name="penulis" id="edit-penulis" required placeholder="Masukkan penulis Skripsi"readonly>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-4">
                            <label for="dospem">Dosen Pembimbing</label>
                            <select name="dospem" class="form-control" id="edit-dospem">
                                <option selected >Pilih</option>
                                @foreach ($namaDospem as $nama)
                                    <option value="{{$nama->nama}}" >{{$nama->nama}}</option>
                                @endforeach
                            </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="rilis">Rilis Pada Tahun</label>
                                <input type="text" class="form-control" name="rilis" id="edit-rilis" required placeholder="Harus 4 Angka">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="halaman">Halaman</label>
                                <input type="text" class="form-control" name="halaman" id="edit-halaman" required placeholder="Masukkan Jumlah Halaman">
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-4">
                                <label for="cover">Pilih Cover (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="cover" id="edit-cover">
                                <div class="form-group" id="cover-area"></div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="pengesahan">Pilih Pengesahan (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="pengesahan" id="edit-pengesahan">
                                <div class="form-group" id="pengesahan-area"></div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="abstrak">Pilih Abstrak (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="abstrak" id="edit-abstrak">
                                <div class="form-group" id="abstrak-area"></div>
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-4">
                                <label for="daftarisi">Pilih Daftar Isi (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="daftarisi" id="edit-daftarisi">
                                <div class="form-group" id="daftarisi-area"></div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="daftargambar">Pilih Daftar Gambar (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="daftargambar" id="edit-daftargambar">
                                <div class="form-group" id="daftargambar-area"></div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="daftarlampiran">Pilih Lampiran (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="daftarlampiran" id="edit-daftarlampiran">
                                <div class="form-group" id="daftarlampiran-area"></div>
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-4">
                                <label for="bab1">Pilih BAB 1 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab1" id="edit-bab1">
                                <div class="form-group" id="bab1-area"></div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="bab2">Pilih BAB 2 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab2" id="edit-bab2">
                                <div class="form-group" id="bab2-area"></div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="bab3">Pilih Bab 3 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab3" id="edit-bab3">
                                <div class="form-group" id="bab3-area"></div>
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-4">
                                <label for="bab4">Pilih bab 4 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab4" id="edit-bab4">
                                <div class="form-group" id="bab4-area"></div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="bab5">Pilih Bab5 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab5" id="edit-bab5">
                                <div class="form-group" id="bab5-area"></div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="dapus">Pilih Daftar Pustaka (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="dapus" id="edit-dapus">
                                <div class="form-group" id="dapus-area"></div>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <input type="text" name="old_cover" id="edit-old-cover" hidden/>
                        <input type="text" name="old_pengesahan" id="edit-old-pengesahan" hidden/>
                        <input type="text" name="old_abstrak" id="edit-old-abstrak" hidden/>
                        <input type="text" name="old_daftarisi" id="edit-old-daftarisi" hidden/>
                        <input type="text" name="old_daftargambar" id="edit-old-daftargambar" hidden/>
                        <input type="text" name="old_daftarlampiran" id="edit-old-daftarlampiran" hidden/>
                        <input type="text" name="old_bab1" id="edit-old-bab1" hidden/>
                        <input type="text" name="old_bab2" id="edit-old-bab2" hidden/>
                        <input type="text" name="old_bab3" id="edit-old-bab3" hidden/>
                        <input type="text" name="old_bab4" id="edit-old-bab4" hidden/>
                        <input type="text" name="old_bab5" id="edit-old-bab5" hidden/>
                        <input type="text" name="old_dapus" id="edit-old-dapus" hidden/>
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
            $(document).on('click','#btn-edit-skripsi', function(){
                let id = $(this).data('id');
                $('#image-area').empty();

                $.ajax({
                    type: "get",
                    url: "{{url('/admin/ajaxadmin/dataSkripsi')}}/"+id,
                    dataType: 'json',
                    success: function(res){
                        $('#edit-judul').val(res.judul);
                        $('#edit-penulis').val(res.penulis);
                        $('#edit-id').val(res.id);
                        $('#edit-dospem').val(res.dospem);
                        $('#edit-rilis').val(res.rilis);
                        $('#edit-volume').val(res.volume);
                        $('#edit-halaman').val(res.halaman);
                        $('#old-cover').val(res.cover);
                        $('#old-pengesahan').val(res.pengesahan);
                        $('#old-abstrak').val(res.abstrak);
                        $('#old-daftarisi').val(res.daftarisi);
                        $('#old-daftargambar').val(res.daftargambar);
                        $('#old-daftarlampiran').val(res.daftarlampiran);
                        $('#old-bab1').val(res.bab1);
                        $('#old-bab2').val(res.bab2);
                        $('#old-bab3').val(res.bab3);
                        $('#old-bab4').val(res.bab4);
                        $('#old-bab5').val(res.bab5);
                        $('#old-dapus').val(res.dapus);

                        if (res.cover !== null) {
                            $('#cover-area').append('[Cover tersedia]');
                        } else {
                            $('#cover-area').append('[Cover tidak tersedia]');
                        }
                        if (res.pengesahan !== null) {
                            $('#pengesahan-area').append('[Pengesahan tersedia]');
                        } else {
                            $('#pengesahan-area').append('[Pengesahan tidak tersedia]');
                        }
                        if (res.abstrak !== null) {
                            $('#abstrak-area').append('[Abstrak tersedia]');
                        } else {
                            $('#abstrak-area').append('[Abstrak tidak tersedia]');
                        }
                        if (res.daftarisi !== null) {
                            $('#daftarisi-area').append('[Daftarisi tersedia]');
                        } else {
                            $('#daftarisi-area').append('[Daftarisi tidak tersedia]');
                        }
                        if (res.daftargambar !== null) {
                            $('#daftargambar-area').append('[Daftargambar tersedia]');
                        } else {
                            $('#daftargambar-area').append('[Daftargambar tidak tersedia]');
                        }
                        if (res.daftarlampiran !== null) {
                            $('#daftarlampiran-area').append('[Daftarlampiran tersedia]');
                        } else {
                            $('#daftarlampiran-area').append('[Daftarlampiran tidak tersedia]');
                        }
                        if (res.bab1 !== null) {
                            $('#bab1-area').append('[Bab 1 tersedia]');
                        } else {
                            $('#bab1-area').append('[Bab 1 tidak tersedia]');
                        }
                        if (res.bab2 !== null) {
                            $('#bab2-area').append('[Bab 2 tersedia]');
                        } else {
                            $('#bab2-area').append('[Bab 2 tidak tersedia]');
                        }
                        if (res.bab3 !== null) {
                            $('#bab3-area').append('[Bab 3 tersedia]');
                        } else {
                            $('#bab3-area').append('[Bab 3 tidak tersedia]');
                        }
                        if (res.bab4 !== null) {
                            $('#bab4-area').append('[Bab 4 tersedia]');
                        } else {
                            $('#bab4-area').append('[Bab 4 tidak tersedia]');
                        }
                        if (res.bab5 !== null) {
                            $('#bab5-area').append('[Bab 5 tersedia]');
                        } else {
                            $('#bab5-area').append('[Bab 5 tidak tersedia]');
                        }
                        if (res.dapus !== null) {
                            $('#dapus-area').append('[Daftar Pustaka tersedia]');
                        } else {
                            $('#dapus-area').append('[Daftar Pustaka tidak tersedia]');
                        }
                    },
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
                            console.log(results);

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
