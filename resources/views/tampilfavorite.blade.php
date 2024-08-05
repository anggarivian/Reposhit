@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Skripsi Favorit</h2>

    @if($favoriteComments->count() > 0)
        <ul class="list-group">
            @foreach($favoriteComments as $comment)
                <li class="list-group-item">
                    <h5>{{ $comment->skripsi->title }}</h5>
                    <p>{{ $comment->content }}</p>
                    <a href="{{ route('skripsi.show', $comment->skripsi_id) }}" class="btn btn-primary">Lihat Skripsi</a>
                </li>
            @endforeach
        </ul>
    @else
        <p>Belum ada skripsi yang ditandai sebagai favorit.</p>
    @endif
</div>
@endsection
