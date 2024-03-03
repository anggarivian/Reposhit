@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Kelola Skripsi</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-default">
    <div class="card-header">{{__('Pengelolaan Skripsi')}}</div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <div class="tombol">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Tambah Data Skripsi
                    </button>
                </div>
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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </button>
                                </a>
                                <button type="button" id="btn-edit-skripsi" class="btn btn-sm btn-success" data-toggle="modal" data-target="#edit" data-id="{{ $skripsis->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                    </svg>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation('{{$skripsis->id}}' , '{{$skripsis->judul}}' )">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                                    </svg>
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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Skripsi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form method="post" action="{{ route('tambah.skripsi')}}" enctype="multipart/form-data">
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
                                <label for="daftarisi">Pilih Daftarisi (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="daftarisi" id="daftarisi" required>
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-6">
                                <label for="daftargambar">Pilih Daftargambar (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="daftargambar" id="daftargambar" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="daftarlampiran">Pilih Daftarlampiran (Maks. 2 MB) :</label>
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
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-6">
                                <label for="bab5">Pilih BAB 5 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab5" id="bab5" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="lampiran">Pilih Pengesahan (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="lampiran" id="lampiran" required>
                            </div>
                        </div>
                            <div class="form-group">
                            <label for="dapus">Pilih Daftar Pustaka (Maks. 2 MB) :</label>
                            <input type="file" class="form-control" style="padding-bottom: 37px" name="dapus" id="dapus" required>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                <form method="post" action="{{ route('ubah.skripsi')}}" enctype="multipart/form-data">
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
                                <label for="bab1">Pilih BAB 1 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab1" id="edit-bab1">
                                <div class="form-group" id="bab1-area"></div>
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-6">
                                <label for="bab2">Pilih BAB 2 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab2" id="edit-bab2">
                                <div class="form-group" id="bab2-area"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="bab3">Pilih BAB 3 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab3" id="edit-bab3">
                                <div class="form-group" id="bab3-area"></div>
                            </div>
                        </div>
                        <div class="d-flex" style="margin: -7px">
                            <div class="form-group col-md-6">
                                <label for="bab4">Pilih BAB 4 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab4" id="edit-bab4">
                                <div class="form-group" id="bab4-area"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="bab5">Pilih BAB 5 (Maks. 2 MB) :</label>
                                <input type="file" class="form-control" style="padding-bottom: 37px" name="bab5" id="edit-bab5">
                                <div class="form-group" id="bab5-area"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dapus">Pilih Daftar Pustaka (Maks. 2 MB) :</label>
                            <input type="file" class="form-control" style="padding-bottom: 37px" name="dapus" id="edit-dapus">
                            <div class="form-group" id="dapus-area"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="text" name="old_cover" id="edit-old-cover" hidden/>
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