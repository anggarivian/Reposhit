@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Kelola Jurusan</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header">{{ __('Pengelolaan Jurusan') }}</div>
        <div class="card-body">
            <div class="mb-3">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahModal">
                    Tambah Jurusan
                </button>
            </div>
            {{-- @if (session('message'))
                <div class="alert alert-{{ session('alert-type', 'info') }}">
                    {{ session('message') }}
                </div>
            @endif --}}
            <table id="table-data" class="table table-striped text-center">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Jurusan</th>
                        <th>Kode Jurusan</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($jurusans as $jurusan)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $jurusan->nama_jurusan }}</td>
                        <td>{{ $jurusan->kode_jurusan }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#editModal" data-id="{{ $jurusan->id }}">
                                <i class="fas fa-edit"> Edit</i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation('{{ $jurusan->id }}', '{{ $jurusan->nama_jurusan }}')">
                                <i class="fas fa-trash-alt"> Hapus</i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('jurusan.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jurusan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Jurusan</label>
                        <input type="text" name="nama_jurusan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Kode Jurusan</label>
                        <input type="text" name="kode_jurusan" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('jurusan.update') }}">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jurusan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-group">
                        <label>Nama Jurusan</label>
                        <input type="text" name="nama_jurusan" id="edit-nama_jurusan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Kode Jurusan</label>
                        <input type="text" name="kode_jurusan" id="edit-kode_jurusan" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).on('click', '[data-target="#editModal"]', function () {
        var id = $(this).data('id');
        $.ajax({
            url: '/admin/ajaxadmin/dataJurusan/' + id,
            type: 'GET',
            success: function (res) {
                $('#edit-id').val(res.id);
                $('#edit-nama_jurusan').val(res.nama_jurusan);
                $('#edit-kode_jurusan').val(res.kode_jurusan);
            }
        });
    });

    function deleteConfirmation(id, nama) {
        Swal.fire({
            title: 'Hapus?',
            text: "Yakin ingin menghapus jurusan '" + nama + "'?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/jurusan/hapus/' + id,
                    type: 'GET',
                    success: function (res) {
                        if (res.success) {
                            Swal.fire('Berhasil!', res.message, 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            Swal.fire('Gagal!', res.message, 'error');
                        }
                    }
                });
            }
        });
    }
</script>
@stop