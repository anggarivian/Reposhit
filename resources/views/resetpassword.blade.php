@extends('adminlte::page')

@section('title', 'Ubah Password')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Ganti Password</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('ubah.password') }}">
            @csrf

            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password" class="form-control" required>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
