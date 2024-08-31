@extends('adminlte::page')

@section('title', 'Kelola Mahasiswa')

@section('content_header')
    <h1 class="m-0 text-dark">Kelola Mahasiswa</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-default">
    <div class="card-header">{{__('Pengelolaan Mahasiswa')}}</div>
        <div class="card-body">
            <div class="w-100 d-flex justify-content-between" style="margin-right: 10px">
                <div class="tombol">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Tambah Data Mahasiswa
                    </button>
                </div>
                <div class="tombol">
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#import">
                        Import Data Mahasiswa
                    </button>
                </div>
            </div>
            <hr>
            <table id="table-data" class="table table-stripped text-center">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>NPM</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>Tanggal Lahir</th>
                        <th>Angkatan</th>
                        <th>Prodi</th>
                        <th>Alamat</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no=1; @endphp
                    @foreach($mahasiswa as $mahasiswas)
                    <tr>
                        <td>{{$no++}}</td>
                        <td>{{$mahasiswas->npm}}</td>
                        <td>{{$mahasiswas->name}}</td>
                        <td>
                            @if ($mahasiswas->status == 0)
                                <a class="btn btn-sm btn-warning">Belum Diverifikasi</a>
                            @elseif ($mahasiswas->status == 1)
                                <a class="btn btn-sm btn-success">Sudah Diverifikasi</a>
                            @endif
                        </td>
                        <td>{{$mahasiswas->tgl_lahir}}</td>
                        <td>{{$mahasiswas->angkatan}}</td>
                        <td>{{$mahasiswas->prodi}}</td>
                        <td>{{$mahasiswas->alamat}}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" id="btn-edit-mahasiswa" class="btn btn-sm btn-success" data-toggle="modal" data-target="#edit" data-id="{{ $mahasiswas->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                    </svg>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation('{{$mahasiswas->id}}' , '{{$mahasiswas->name}}' )">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                                    </svg>
                                </button>
                                <button class="btn {{ $mahasiswas->status == 0 ? 'btn-success' : 'btn-danger' }}"
                                    onclick="toggleVerifikasiConfirmation({{ $mahasiswas->id }}, '{{ $mahasiswas->name }}')"
                                    title="{{ $mahasiswas->status == 0 ? 'Verifikasi' : 'Batalkan Verifikasi' }}">
                                    <i class="fa {{ $mahasiswas->status == 0 ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
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
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('tambah.mahasiswa')}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" id="name" required placeholder="Masukan Nama Mahasiswa">
                    </div>
                    <div class="d-flex">
                        <div class="form-group col-md-6">
                            <label for="npm">NPM</label>
                            <input type="text" class="form-control" name="npm" id="npm" required placeholder="Harus 10 Nomor">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tgl_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir" required>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="form-group col-md-6">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" name="password" id="password" required placeholder="Minimal 8 Karakter">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="angkatan">Angkatan</label>
                            <input type="text" class="form-control" name="angkatan" id="angkatan" required placeholder="Harus 4 Angka">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="form-group col-md-12">
                            <label for="prodi">Program Studi</label>
                            <select name="prodi" class="form-control" id="prodi" >
                                <option disabled selected >Pilih</option>
                                <option value="Agribisnis">Agribisnis</option>
                                <option value="Agroteknologi">Agroteknologi</option>
                                <option value="Pemanfaatan Sumberdaya Perikanan">Pemanfaatan Sumberdaya Perikanan</option>
                            </select>
                        </div>
                    </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control" name="alamat" id="alamat" required placeholder="Maksimal 255 Karakter">
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
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form method="post" action="{{ route('ubah.mahasiswa')}}" enctype="multipart/form-data">
                @csrf
                @method ('PATCH')
                <div class="modal-body">
                    <input type="text" class="form-control" name="id" id="edit-id" hidden required>
                    <div class="form-group col-md-12">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" id="edit-name" required placeholder="Masukan Nama Mahasiswa"readonly>
                    </div>
                    <div class="d-flex">
                        <div class="form-group col-md-6">
                            <label for="npm">NPM</label>
                            <input type="text" class="form-control" name="npm" id="edit-npm" required placeholder="Harus 10 Nomor">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tgl_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tgl_lahir" id="edit-tgl_lahir" required>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="form-group col-md-6">
                            <label for="angkatan">Angkatan</label>
                            <input type="text" class="form-control" name="angkatan" id="edit-angkatan" required placeholder="Harus 4 Angka">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="prodi">Program Studi</label>
                            <select name="prodi" class="form-control" id="prodi" >
                                <option disabled >Pilih</option>
                                <option value="Agribisnis">Agribisnis</option>
                                <option value="Agroteknologi">Agroteknologi</option>
                                <option value="Pemanfaatan Sumberdaya Perikanan">Pemanfaatan Sumberdaya Perikanan</option>
                            </select>
                        </div>
                    </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control" name="alamat" id="edit-alamat" required placeholder="Maksimal 255 Karakter">
                        </div>
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
                <h5 class="modal-title" id="importLabel">Import Data Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('mahasiswa.import') }}" enctype="multipart/form-data">
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
            $(document).on('click','#btn-edit-mahasiswa', function(){
                let id = $(this).data('id');
                $('#image-area').empty();

                $.ajax({
                    type: "get",
                    url: "{{url('/admin/ajaxadmin/dataMahasiswa')}}/"+id,
                    dataType: 'json',
                    success: function(res){
                        $('#edit-name').val(res.name);
                        $('#edit-email').val(res.email);
                        $('#edit-alamat').val(res.alamat);
                        $('#edit-id').val(res.id);
                        $('#edit-npm').val(res.npm);
                        $('#edit-angkatan').val(res.angkatan);
                        $('#edit-tgl_lahir').val(res.tgl_lahir);
                        $('#edit-jurusan').val(res.jurusan);
                        $('#edit-prodi').val(res.prodi);
                    },
                });
            });
        });

        function deleteConfirmation(id,name) {
            swal.fire({
                title: "Hapus?",
                type: 'warning',
                text: "Apakah anda yakin akan menghapus data dengan Nama " +name+"?!",
                showCancelButton: !0,
                confirmButtonText: "Ya, lakukan!",
                cancelButtonText: "Tidak, batalkan!",

            }).then (function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'GET',
                        url: "{{url('/admin/mahasiswa/hapus')}}/"+id,
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
        function toggleVerifikasiConfirmation(id, name) {
    swal.fire({
        title: "Tindakan verifikasi akun mahasiswa?",
        type: 'warning',
        text: "Apakah anda ingin " + (name.status == 0 ? 'memverifikasi' : 'membatalkan verifikasi') + " akun mahasiswa atas nama " + name + "?",
        showCancelButton: true,
        confirmButtonText: name.status == 0 ? "Ya, verifikasi!" : "Ya, batalkan!",
        cancelButtonText: "Tidak, kembali!",
    }).then(function (e) {
        if (e.value === true) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'GET',
                url: "{{url('/admin/mahasiswa/toggle-verifikasi')}}/" + id,
                data: { _token: CSRF_TOKEN },
                dataType: 'JSON',
                success: function (results) {
                    if (results.success === true) {
                        swal.fire("Berhasil!", results.message, "success");
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    } else {
                        swal.fire("Gagal!", results.message, "error");
                    }
                }
            });
        } else {
            e.dismiss;
        }
    }, function (dismiss) {
        return false;
    });
}

</script>
@stop
