@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Kelola Dosen</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-default">
    <div class="card-header">{{__('Pengelolaan Dosen')}}</div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <div class="tombol">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Tambah Data Dosen
                    </button>
                </div>
                <div class="tombol">
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#import">
                        Import Data Dosen
                    </button>
                </div>
            </div>
            <table id="table-data" class="table table-stripped text-center">
                <thead>
                    <tr class="text-center">
                        <th>No.</th>
                        <th>Nama</th>
                        <th>NIDN</th>
                        <th>Prodi</th>
                        <th>Kontak</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no=1; @endphp
                    @foreach($dosen as $dosens)
                    <tr>
                        <td>{{$no++}}</td>
                        <td>{{$dosens->nama}}</td>
                        <td>{{$dosens->nip}}</td>
                        <td>{{$dosens->program_studi}}</td>
                        <td>{{$dosens->kontak}}</td>
                        <td>
                            <div class="form-group" role="group" aria-label="Basic example">
                                <button type="button" id="btn-edit-skripsi" class="btn btn-sm btn-success" data-toggle="modal" data-target="#edit" data-id="{{ $dosens->id }}">
                                    <i class="fas fa-edit"> Edit</i> <!-- Ikon untuk Edit -->
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation('{{$dosens->id}}' , '{{$dosens->judul}}' )">
                                    <i class="fas fa-trash-alt"> Hapus </i> <!-- Ikon untuk Hapus Favorite -->
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

    <!-- Modal Tambah -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Dosen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form method="post" action="{{ route('tambah.dosen')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group col-md-12">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" id="nama" required placeholder="Masukan Nama Dosen">
                        </div>
                        <div class="d-flex">
                            <div class="form-group col-md-6">
                                <label for="nip">NIDN</label>
                                <input type="text" class="form-control" name="nip" id="nip" required placeholder="Harus 18 Nomor">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tgl_lahir">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir" required>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="form-group col-md-6">
                                <label for="program_studi">Program Studi</label>
                                <select name="program_studi" class="form-control" id="program_studi" >
                                    <option disabled selected>Pilih</option>
                                    <option value="Agribisnis">Agribisnis</option>
                                    <option value="Agroteknologi">Agroteknologi</option>
                                    <option value="perikanan">Pemanfaatan Sumberdaya Perikanan</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="kontak">Kontak</label>
                                <input type="text" class="form-control" name="kontak" id="kontak" required placeholder="Maksimal 12 Nomor">
                            </div>
                        </div>
                        {{-- <div class="d-flex">
                            <div class="form-group col-md-6">
                                <label for="jabatan">Jabatan</label>
                                <input type="text" class="form-control" name="jabatan" id="jabatan" required placeholder="Masukan Jabatan">
                            </div>
                        </div> --}}
                        {{-- <div class="form-group col-md-12">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control" name="alamat" id="alamat" required placeholder="Maksimal 255 Karakter">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" name="password" id="password" required placeholder="Maksimal 8 Karakter">
                        </div> --}}
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
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Dosen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('ubah.dosen')}}" enctype="multipart/form-data">
                    @csrf
                    @method ('PATCH')
                    <div class="modal-body">
                        <input type="text" class="form-control" name="id" id="edit-id" required hidden>
                        <div class="form-group col-md-12">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" id="edit-nama" required placeholder="Masukan Nama Dosen">
                        </div>
                        <div class="d-flex">
                            <div class="form-group col-md-6">
                                <label for="nip">NIDN </label>
                                <input type="text" class="form-control" name="nip" id="edit-nip" required placeholder="Maksimal 18 Nomor">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tgl_lahir">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tgl_lahir" id="edit-tgl_lahir" required>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="form-group col-md-6">
                                <label for="program_studi">Program Studi</label>
                                <select name="program_studi" class="form-control" id="program_studi" >
                                    <option disabled>Pilih</option>
                                    <option value="Agribisnis">Agribisnis</option>
                                    <option value="Agroteknologi">Agroteknologi</option>
                                    <option value="Pemanfaatan Sumberdaya Perikanan">Pemanfaatan Sumberdaya Perikanan</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="kontak">Kontak</label>
                                <input type="text" class="form-control" name="kontak" id="edit-kontak" required placeholder="Maksimal 12 Nomor">
                            </div>
                        </div>
                        {{-- <div class="d-flex">
                            <div class="form-group col-md-6">
                                <label for="jabatan">Jabatan</label>
                                <input type="text" class="form-control" name="jabatan" id="edit-jabatan" required placeholder="Masukan Jabatan">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control" name="alamat" id="edit-alamat" required placeholder="Maksimal 255 Karakter">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" name="password" id="edit-password" required placeholder="Maksimal 8 Karakter">
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Ubah Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="import" tabindex="-1" aria-labelledby="importLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importLabel">Import Data Dosen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('dosen.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group col-md-12">
                            <label for="file">File</label>
                            <input type="file"class="form-control p-1" name="file" id="edit-file" required/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop


@section('js')

<script>
        $(function(){
            $(document).on('click','#btn-edit-dosen', function(){
                let id = $(this).data('id');
                $('#image-area').empty();

                $.ajax({
                    type: "get",
                    url: "{{url('/admin/ajaxadmin/dataDosen')}}/"+id,
                    dataType: 'json',
                    success: function(res){
                        $('#edit-nama').val(res.nama);
                        $('#edit-kontak').val(res.kontak);
                        $('#edit-id').val(res.id);
                        $('#edit-nip').val(res.nip);
                        $('#edit-jabatan').val(res.jabatan);
                        $('#edit-tgl_lahir').val(res.tgl_lahir);
                        $('#edit-program_studi').val(res.program_studi);
                    },
                });
            });
        });

        function deleteConfirmation(id,nama) {
            swal.fire({
                title: "Hapus?",
                type: 'warning',
                text: "Apakah anda yakin akan menghapus data dengan Nama " +nama+"?!",
                showCancelButton: !0,
                confirmButtonText: "Ya, lakukan!",
                cancelButtonText: "Tidak, batalkan!",

            }).then (function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'GET',
                        url: "{{url('/admin/dosen/hapus')}}/"+id,
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
