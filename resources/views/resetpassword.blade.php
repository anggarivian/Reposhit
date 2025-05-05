@extends('adminlte::page')

@section('title', 'Ubah Password - Universitas Suryakancana')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-primary">
                <i class="fas fa-key mr-2"></i>Ubah Password
            </h1>
            <p class="text-muted">Repositori Skripsi Universitas Suryakancana</p>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-header bg-gradient-light">
            <h3 class="card-title">
                <i class="fas fa-lock mr-1"></i>
                Form Ubah Password
            </h3>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        <div class="card-body">
            <form action="{{ route('mahasiswa.updatePassword') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="current_password">Password Saat Ini</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required minlength="8">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="form-text text-muted">Password minimal 8 karakter</small>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required minlength="8">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Password
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .bg-gradient-light {
        background: linear-gradient(to right, #f8f9fa, #ffffff);
    }
    
    .card {
        transition: box-shadow 0.3s;
    }
    
    .card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Toggle password visibility
        $('.toggle-password').click(function() {
            const targetId = $(this).data('target');
            const inputField = $(`#${targetId}`);
            const icon = $(this).find('i');
            
            if (inputField.attr('type') === 'password') {
                inputField.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                inputField.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
        
        // Notifikasi Toastr jika ada
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        
        // Cek notifikasi dari session
        @if(Session::has('message'))
            Toast.fire({
                icon: "{{ Session::get('alert-type') }}",
                title: "{{ Session::get('message') }}"
            });
        @endif
    });
</script>
@stop