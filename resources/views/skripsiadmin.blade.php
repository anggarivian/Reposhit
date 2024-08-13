@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Upload Skripsi</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-default">
    <div class="card-header">{{__(' Data Skripsi')}}</div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                {{-- <div class="tombol">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Tambah Data Skripsi
                    </button>
                </div> --}}
                {{-- <div class="tombol">
                    <button type="button" class="btn btn-info" >
                        Export
                    </button>
                </div> --}}
            </div>
            <table id="table-data" class="table table-stripped text-center">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Dosen Pembimbing</th>
                        <th>Rilis</th>
                        <th>Halaman</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no=1; @endphp
                    @foreach($skripsi as $skripsis)
                    <tr>
                        <td>{{$no++}}</td>
                        <td>{{$skripsis->judul}}</td>
                        <td>{{$skripsis->penulis}}</td>
                        <td>{{$skripsis->dospem}}</td>
                        <td>{{$skripsis->rilis}}</td>
                        <td>{{$skripsis->halaman}}</td>
                        <td>
                            <div class="form-group" role="group" aria-label="Basic example">
                                <a href="/admin/skripsi/detail/{{$skripsis->id}}">
                                    <button class="btn btn-sm btn-info">
                                          <i class="fas fa-eye"></i> <!-- Ikon untuk Lihat Detail Skripsi -->
                                    </button>
                                </a>
                                <button type="button" id="btn-edit-skripsi" class="btn btn-sm btn-success" data-toggle="modal" data-target="#edit" data-id="{{ $skripsis->id }}">
                                    <i class="fas fa-edit"></i> <!-- Ikon untuk Edit -->
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation('{{$skripsis->id}}' , '{{$skripsis->judul}}' )">
                                    <i class="fas fa-trash-alt"></i> <!-- Ikon untuk Hapus Favorite -->
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

    <!-- Modal -->
    {{-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Skripsi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form method="post" action="{{ route('tambah.skripsi1')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="judul">Judul</label>
                            <input type="text" class="form-control" name="judul" id="judul" required placeholder="Masukkan Judul Skripsi">
                        </div>
                        <div class="form-group">
                            <label for="penulis">Penulis</label>
                            <select name="penulis" class="form-control" id="penulis">
                                <option selected >Pilih</option>
                                @foreach ($namaPenulis as $nama)
                                    <option value="{{$nama->name}}" >{{$nama->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dospem">Dosen Pembimbing</label>
                            <select name="dospem" class="form-control" id="dospem">
                                <option selected >Pilih</option>
                                @foreach ($namaDospem as $nama)
                                    <option value="{{$nama->nama}}" >{{$nama->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-6">
                                <label for="rilis">Rilis Pada Tahun</label>
                                <input type="text" class="form-control" name="rilis" id="rilis" required placeholder="Harus 4 Angka">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="halaman">Halaman</label>
                                <input type="text" class="form-control" name="halaman" id="halaman" required placeholder="Masukkan Jumlah Halaman">
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-6">
                                <label for="cover">Pilih Cover (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="cover" id="cover" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pengesahan">Pilih Pengesahan (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="pengesahan" id="pengesahan" required>
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-6">
                                <label for="abstrak">Pilih Abstrak (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="abstrak" id="abstrak" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="daftarisi">Pilih Daftar Isi (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="daftarisi" id="daftarisi" required>
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-6">
                                <label for="daftargambar">Pilih Daftar Gambar (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="daftargambar" id="daftargambar" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="daftarlampiran">Pilih Daftar Lampiran (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="daftarlampiran" id="daftarlampiran" required>
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-6">
                                <label for="bab1">Pilih BAB 1 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab1" id="bab1" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="bab2">Pilih BAB 2 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab2" id="bab2" required>
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-6">
                                <label for="bab3">Pilih BAB 3 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab3" id="bab3" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="bab4">Pilih BAB 4 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab4" id="bab4" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bab5">Pilih Bab 5 (Maks. 2 MB) :</label>
                            <input type="file" class="form-control" style="padding-bottom: 37px" name="bab5" id="bab5" required>
                        </div>
                        <div class="form-group">
                            <label for="dapus">Pilih Daftar Pustaka (Maks. 2 MB) :</label>
                            <input type="file" class="form-control" style="padding-bottom: 37px" name="dapus" id="dapus" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    <!-- Modal Edit -->
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Skripsi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form method="post" action="{{ route('ubah.skripsi1')}}" enctype="multipart/form-data">
                    @csrf
                    @method ('PATCH')
                    <div class="modal-body">
                        <input type="text" class="form-control" name="id" id="edit-id" hidden>
                        <div class="form-group">
                            <label for="judul">Judul</label>
                            <input type="text" class="form-control" name="judul" id="edit-judul" required placeholder="Masukkan Judul Skripsi">
                        </div>
                        <div class="form-group">
                            <label for="penulis">Penulis</label>
                            <select name="penulis" class="form-control" id="edit-penulis">
                                <option selected >Pilih</option>
                                @foreach ($namaPenulis as $nama)
                                    <option value="{{$nama->name}}" >{{$nama->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dospem">Dosen Pembimbing</label>
                            <select name="dospem" class="form-control" id="edit-dospem">
                                <option selected >Pilih</option>
                                @foreach ($namaDospem as $nama)
                                    <option value="{{$nama->nama}}" >{{$nama->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-6">
                                <label for="rilis">Rilis Pada Tahun</label>
                                <input type="text" class="form-control" name="rilis" id="edit-rilis" required placeholder="Harus 4 Angka">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="halaman">Halaman</label>
                                <input type="text" class="form-control" name="halaman" id="edit-halaman" required placeholder="Masukkan Jumlah Halaman">
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-6">
                                <label for="cover">Pilih Cover (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="cover" id="edit-cover">
                                <div class="form-group" id="cover-area"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pengesahan">Pilih Pengesahan (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="pengesahan" id="edit-pengesahan">
                                <div class="form-group" id="pengesahan-area"></div>
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-6">
                                <label for="abstrak">Pilih Abstrak (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="abstrak" id="edit-abstrak">
                                <div class="form-group" id="abstrak-area"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="daftarisi">Pilih Daftar Isi (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="daftarisi" id="edit-daftarisi">
                                <div class="form-group" id="daftarisi-area"></div>
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-6">
                                <label for="daftargambar">Pilih Daftar Gambar (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="daftargambar" id="edit-daftargambar">
                                <div class="form-group" id="daftargambar-area"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="daftarlampiran">Pilih Lampiran (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="daftarlampiran" id="edit-daftarlampiran">
                                <div class="form-group" id="daftarlampiran-area"></div>
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-6">
                                <label for="bab1">Pilih BAB 1 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab1" id="edit-bab1">
                                <div class="form-group" id="bab1-area"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="bab2">Pilih BAB 2 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab2" id="edit-bab2">
                                <div class="form-group" id="bab2-area"></div>
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-6">
                                <label for="bab3">Pilih Bab 3 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab3" id="edit-bab3">
                                <div class="form-group" id="bab3-area"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="bab4">Pilih bab 4 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab4" id="edit-bab4">
                                <div class="form-group" id="bab4-area"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bab5">Pilih Bab5 (Maks. 2 MB) :</label>
                            <input type="file" class="form-control" style="padding-bottom: 37px" name="bab5" id="edit-bab5">
                            <div class="form-group" id="bab5-area"></div>
                        </div>
                        <div class="form-group">
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
</script>
@stop
