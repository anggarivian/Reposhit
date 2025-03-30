@extends('adminlte::page')

@section('title', 'Ubah Password Universitas Suryakancana')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-primary">
                <i class="fas fa-key mr-2"></i>Ubah Password
            </h1>
            <p class="text-muted">Perpustakaan Digital Universitas Suryakancana</p>
        </div>
        <div>
            {{-- <img src="{{ asset('images/logo-unsur.png') }}" alt="Logo UNSUR" height="60"> --}}
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-header bg-gradient-light">
            <h3 class="card-title"><i class="fas fa-lock mr-2"></i>Form Ganti Password</h3>
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div id="success-alert" class="alert alert-success alert-dismissible fade show small-alert" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div id="error-alert" class="alert alert-danger alert-dismissible fade show small-alert" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <i class="fas fa-user-shield fa-3x text-primary mb-3"></i>
                                <h4>Ganti Password Akun Anda</h4>
                                <p class="text-muted">Pastikan menggunakan password yang kuat dan mudah diingat</p>
                            </div>

                            <form method="POST" action="{{ route('ubah.password') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="password">
                                        <i class="fas fa-lock text-muted mr-1"></i> Password Baru
                                    </label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="password" class="form-control" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-eye-slash" id="togglePassword"></i></span>
                                        </div>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Password minimal 8 karakter</small>
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">
                                        <i class="fas fa-check-circle text-muted mr-1"></i> Konfirmasi Password Baru
                                    </label>
                                    <div class="input-group">
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-eye-slash" id="toggleConfirmation"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-save mr-1"></i> Simpan Password Baru
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    /* Main Styles */
    .card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    /* Small animation for buttons */
    .btn {
        transition: all 0.3s;
    }
    
    .btn:hover {
        transform: translateY(-2px);
    }
    
    /* Small alert styling */
    .small-alert {
        font-size: 14px;
        padding: 8px 15px;
    }
    
    /* Password toggle icon styles */
    #togglePassword, #toggleConfirmation {
        cursor: pointer;
    }
</style>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Fade out alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.remove(); // Menghapus elemen dari DOM setelah 5 detik
            }, 5000);
        });
        
        // Add animation to cards on page load
        $('.card.shadow-sm').css({
            'animation': 'fadeInUp 0.5s ease forwards',
            'opacity': '0'
        });
        
        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        
        togglePassword.addEventListener('click', function () {
            // Toggle password visibility
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle eye icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
        
        // Toggle password confirmation visibility
        const toggleConfirmation = document.querySelector('#toggleConfirmation');
        const confirmation = document.querySelector('#password_confirmation');
        
        toggleConfirmation.addEventListener('click', function () {
            // Toggle password visibility
            const type = confirmation.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmation.setAttribute('type', type);
            
            // Toggle eye icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });
</script>
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@stop