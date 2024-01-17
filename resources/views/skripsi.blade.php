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
                        <th>Abstrak</th>
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
                        <td>{{ Str::limit($skripsis->abstrak, 20) }}</td>
                        <td>{{$skripsis->dospem}}</td>
                        <td>{{$skripsis->rilis}}</td>
                        <td>{{$skripsis->halaman}}</td>
                        <td>
                            <div class="form-group" role="group" aria-label="Basic example">
                                <a href="{{ route('pdf.show', ['id' => $skripsis->id]) }}"  target="_blank">
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
                            <input type="text" class="form-control" name="judul" id="judul" required>
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
                            <label for="abstrak">Abstrak</label>
                            <input type="text" class="form-control" name="abstrak" id="abstrak">
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
                                <label for="rilis">Rilis</label>
                                <input type="text" class="form-control" name="rilis" id="rilis" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="halaman">Halaman</label>
                                <input type="text" class="form-control" name="halaman" id="halaman" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="file">Pilih File</label>
                            <input type="file" class="form-control" style="padding-bottom: 37px" name="file" id="file" required>
                        </div>
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
                            <input type="text" class="form-control" name="judul" id="edit-judul" required>
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
                            <label for="abstrak">Abstrak</label>
                            <input type="text" class="form-control" name="abstrak" id="edit-abstrak">
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
                                <label for="rilis">Rilis</label>
                                <input type="text" class="form-control" name="rilis" id="edit-rilis" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="halaman">Halaman</label>
                                <input type="text" class="form-control" name="halaman" id="edit-halaman" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="file">Pilih File</label>
                            <input type="file" class="form-control" style="padding-bottom: 37px" name="file" id="edit-file">
                            <div class="form-group" id="image-area"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="text" name="old_file" id="edit-old-file"/>
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
                        $('#edit-abstrak').val(res.abstrak);
                        $('#edit-id').val(res.id);
                        $('#edit-dospem').val(res.dospem);
                        $('#edit-rilis').val(res.rilis);
                        $('#edit-volume').val(res.volume);
                        $('#edit-halaman').val(res.halaman);
                        $('#edit-old-file').val(res.file);

                        if (res.file !== null) {
                            $('#image-area').append('[File tersedia]');
                        } else {
                            $('#image-area').append('[File tidak tersedia]');
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