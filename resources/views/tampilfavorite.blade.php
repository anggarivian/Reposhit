@extends('adminlte::page')

@section('title', 'Skripsi Favorite')

@section('content_header')
    <h1 class="m-0 text-dark">Skripsi Favorite</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header">{{ __('Skripsi Favorite') }}</div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($skripsifavorite->isEmpty())
                <p>Belum ada skripsi yang ditambahkan ke favorite.</p>
            @else
                @foreach($skripsifavorite as $skripsi)
                    <div class="row mb-3 p-3 border rounded" style="border-color: #e3e3e3;">
                        <div class="col-md-11">
                            <h4><a href="/home/skripsi/detail/{{ $skripsi->id }}">{{ $skripsi->judul }}</a></h4>
                            <h5>Pengarang : {{ $skripsi->penulis }}, <span class="font-weight-light">author</span></h5>
                            <p class="text-muted">
                               Tahun : {{ $skripsi->rilis }}, {{ $skripsi->prodi }}, {{ 'Universitas Suryakancana' }}<br>
                            </p>
                            <div class="d-flex justify-content-start">
                                <!-- Button untuk Hapus dari Favorite -->
                                <form action="{{ route('removeFavorite', $skripsi->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="confirmRemoveFavorite(event, '{{ $skripsi->id }}', '{{ $skripsi->judul }}')">
                                        <i class="fas fa-trash-alt"></i> Hapus dari Favorite
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    function confirmRemoveFavorite(event, skripsiId, skripsiTitle) {
    event.preventDefault(); // Mencegah form submit default
    Swal.fire({
        title: "Hapus dari Favorit?",
        text: "Apakah Anda yakin ingin menghapus skripsi berjudul \"" + skripsiTitle + "\" dari daftar favorit?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Tidak, kembali!",
    }).then(function (result) {
        if (result.isConfirmed) {
            event.target.closest('form').submit(); // Lanjutkan form submit jika dikonfirmasi
        }
    });
}
</script>
@stop
