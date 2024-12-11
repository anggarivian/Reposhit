@extends('adminlte::page')

@section ('title', 'Riwayat Skripsi')

@section('content')
<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">Riwayat Skripsi Anda</h3>
        </div>
        <div class="card-body">
            @if ($history->isEmpty())
                <div id="no-history-message" class="alert alert-info" role="alert">
                    Belum ada riwayat skripsi yang tersedia.
                </div>
            @else
                @foreach ($history as $item)
                    <div class="row mb-3 p-3 border rounded" style="border-color: #e3e3e3;">
                        <div class="col-md-12">
                            <h5>{{ $item->penulis }}, <span class="font-weight-light">author</span></h5>
                            <h4><a href="/home/skripsi/detail/{{$item->id}}">{{$item->judul}}</a></h4>
                            <p>{{ Str::limit($item->abstrak, 150) }}</p>
                            <p class="text-muted">
                                <span><strong>Jumlah views: </strong>{{ $item->views }}</span><br>
                                <span>{{ $item->prodi }}, Universitas Suryakancana, {{ $item->rilis }}</span>
                            </p>
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
    $(document).ready(function() {
        setTimeout(function() {
            $('#no-history-message').fadeOut('slow');
        }, 10000); 
    });
</script>
@stop
