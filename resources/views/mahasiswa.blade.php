@extends('adminlte::page')

@section('title', 'Kelola Mahasiswa')

@section('content_header')
    <h1 class="m-0 text-dark">Kelola Mahasiswa</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header">{{ __('Pengelolaan Mahasiswa') }}</div>
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

            <!-- Tabel Data Mahasiswa -->
            <table class="table table-striped text-center">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>NPM</th>
                        <th>Nama</th>
                        <th>Tanggal Lahir</th>
                        <th>Angkatan</th>
                        <th>Jurusan</th>
                        <th>Alamat</th>
                        <th>Password</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mahasiswa as $index => $mahasiswas)
                        <tr>
                            <td>{{ $mahasiswa->firstItem() + $index }}</td>
                            <td>{{ $mahasiswas->npm }}</td>
                            <td>{{ $mahasiswas->name }}</td>
                            <td>{{ $mahasiswas->tgl_lahir }}</td>
                            <td>{{ $mahasiswas->angkatan }}</td>
                            <td>{{ $mahasiswas->jurusan->nama_jurusan ?? '-' }}</td>
                            <td>{{ $mahasiswas->alamat }}</td>
                            <td>
                                <!-- Password dengan toggle visibility -->
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="password-text" id="password-{{ $mahasiswas->id }}">
                                        •••••••••
                                    </span>
                                    <button class="btn btn-sm btn-outline-secondary ml-2 toggle-password" 
                                            data-password="{{ $mahasiswas->password_text ?? 'Tidak tersedia' }}" 
                                            data-target="password-{{ $mahasiswas->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="form-group" role="group" aria-label="Basic example">
                                    <button type="button" id="btn-edit-mahasiswa" class="btn btn-sm btn-success" data-toggle="modal" data-target="#edit" data-id="{{ $mahasiswas->id }}">
                                        <i class="fas fa-edit"> Edit </i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation('{{ $mahasiswas->id }}' , '{{ $mahasiswas->name }}')">
                                        <i class="fas fa-trash-alt"> Hapus </i>
                                    </button>
                                    <!-- Tombol reset password bisa ditambahkan kembali -->
                                    {{-- <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#resetPassword" onclick="setResetUser('{{ $mahasiswas->id }}', '{{ $mahasiswas->name }}')">
                                        <i class="fas fa-key"> Reset</i>
                                    </button> --}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $mahasiswa->links('pagination::bootstrap-4') }}
            </div>
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
                            <select name="jurusan_id" class="form-control" id="prodi" required>
                                <option disabled selected>Pilih Jurusan</option>
                                @foreach($jurusans as $jur)
                                    <option value="{{ $jur->id }}">{{ $jur->nama_jurusan }}</option>
                                @endforeach
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
                        <input type="text" class="form-control" name="name" id="edit-name" required placeholder="Masukan Nama Mahasiswa" readonly>
                    </div>
                    <div class="d-flex">
                        <div class="form-group col-md-6">
                            <label for="npm">NPM</label>
                            <input type="text" class="form-control" name="npm" id="edit-npm" required placeholder="Harus 10 Nomor" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tgl_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tgl_lahir" id="edit-tgl_lahir">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="form-group col-md-6">
                            <label for="angkatan">Angkatan</label>
                            <input type="text" class="form-control" name="angkatan" id="edit-angkatan" required placeholder="Harus 4 Angka">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="prodi">Program Studi</label>
                            <select name="jurusan_id" id="edit-jurusan" class="form-control" required>
                                <option disabled selected>Pilih Jurusan</option>
                                @foreach($jurusans as $jur)
                                    <option value="{{ $jur->id }}">{{ $jur->nama_jurusan }}</option>
                                @endforeach
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

<!-- Modal Import -->
<div class="modal fade" id="import" tabindex="-1" aria-labelledby="importLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importLabel">File yang harus di import harus excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('mahasiswa.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <label for="file">File</label>
                        <input type="file" class="form-control p-1" name="file" id="edit-file" required/>
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

<!-- CSS untuk Password Toggle -->
<style>
    .password-text {
        display: inline-block;
        min-width: 80px;
    }
    .toggle-password {
        cursor: pointer;
    }
    .toggle-password:focus {
        outline: none;
        box-shadow: none;
    }
</style>
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
                    $('#edit-jurusan').val(res.jurusan_id);
                },
            });
        });
    });

    function deleteConfirmation(id, name) {
        swal.fire({
            title: "Hapus?",
            type: 'warning',
            text: "Apakah anda yakin akan menghapus data dengan Nama " + name + "?!",
            showCancelButton: true,
            confirmButtonText: "Ya, lakukan!",
            cancelButtonText: "Tidak, batalkan!",
        }).then(function(e) {
            if (e.value === true) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                
                $.ajax({
                    type: 'GET',
                    url: "{{url('/admin/mahasiswa/hapus')}}/" + id,
                    dataType: 'JSON',
                    success: function(results) {
                        if (results.success === true) {
                            swal.fire("Done!", results.message, "success");
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            swal.fire("Error!", results.message, "error");
                        }
                    },
                    error: function(xhr, status, error) {
                        swal.fire("Error!", "Terjadi kesalahan saat menghapus data", "error");
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    }

    // Script untuk password toggle
    $(document).ready(function() {
        $('.toggle-password').on('click', function() {
            const targetId = $(this).data('target');
            const passwordText = $(this).data('password');
            const $passwordElement = $('#' + targetId);
            const $icon = $(this).find('i');
            
            if ($passwordElement.text().includes('•')) {
                $passwordElement.text(passwordText);
                $icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                $passwordElement.text('•••••••••');
                $icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
</script>
@stop
