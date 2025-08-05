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

            <!-- Tabel Data Dosen -->
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>NIDN</th>
                        <th>Prodi</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dosen as $index => $dosens)
                        <tr>
                            <td>{{ $dosen->firstItem() + $index }}</td>
                            <td>{{ $dosens->nama }}</td>
                            <td>{{ $dosens->nip }}</td>
                            <td>{{ $dosens->jurusan->nama_jurusan ?? '-' }}</td>
                            <td>{{ $dosens->kontak }}</td>
                            <td>
                                @if($dosens->status)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" id="btn-edit-dosen" class="btn btn-sm btn-success" data-toggle="modal" data-target="#edit" data-id="{{ $dosens->id }}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation('{{ $dosens->id }}' , '{{ $dosens->nama }}')">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $dosen->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('tambah.dosen')}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Dosen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" id="nama" required>
                    </div>
                    <div class="d-flex">
                        <div class="form-group col-md-6">
                            <label for="nip">NIDN</label>
                            <input type="text" class="form-control" name="nip" id="nip" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="program_studi">Program Studi</label>
                            <select name="jurusan_id" class="form-control" id="program_studi" required>
                                <option disabled selected>Pilih</option>
                                @foreach($jurusan as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_jurusan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="kontak">Kontak</label>
                        <input type="text" class="form-control" name="kontak" id="kontak" required>
                    </div>
                    {{-- Status default Aktif, tidak perlu field di form --}}
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
<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('ubah.dosen')}}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Dosen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-group col-md-12">
                        <label for="edit-nama">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" id="edit-nama" required>
                    </div>
                    <div class="d-flex">
                        <div class="form-group col-md-6">
                            <label for="edit-nip">NIDN</label>
                            <input type="text" class="form-control" name="nip" id="edit-nip" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit-program_studi">Program Studi</label>
                            <select name="jurusan_id" class="form-control" id="edit-program_studi" required>
                                <option disabled selected>Pilih</option>
                                @foreach($jurusan as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_jurusan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="edit-kontak">Kontak</label>
                        <input type="text" class="form-control" name="kontak" id="edit-kontak" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="edit-status">Status Dosen</label>
                        <select name="status" id="edit-status" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
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
            <form method="post" action="{{ route('dosen.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importLabel">Import Data Dosen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <label for="file">File</label>
                        <input type="file" class="form-control p-1" name="file" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
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

            $.ajax({
                type: "get",
                url: "{{url('/admin/ajaxadmin/dataDosen')}}/"+id,
                dataType: 'json',
                success: function(res){
                    $('#edit-nama').val(res.nama);
                    $('#edit-kontak').val(res.kontak);
                    $('#edit-id').val(res.id);
                    $('#edit-nip').val(res.nip);
                    $('#edit-program_studi').val(res.jurusan_id);
                    $('#edit-status').val(res.status); // isi status
                },
            });
        });
    });

    function deleteConfirmation(id,nama) {
        swal.fire({
            title: "Hapus?",
            text: "Apakah anda yakin akan menghapus data dengan Nama " +nama+"?!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Ya, lakukan!",
            cancelButtonText: "Tidak, batalkan!",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'GET',
                    url: "{{url('/admin/dosen/hapus')}}/"+id,
                    dataType: 'JSON',
                    success: function (results) {
                        if (results.success) {
                            swal.fire("Berhasil!", results.message, "success");
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            swal.fire("Gagal!", results.message, "error");
                        }
                    }
                });
            }
        });
    }
</script>
@stop
