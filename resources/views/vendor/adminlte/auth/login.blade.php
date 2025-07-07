@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <style>
        /* Custom styling for repository theme */
        .login-page {
            background: linear-gradient(135deg, #1a3d2e 0%, #2d5a3d 30%, #4a7c59 70%, #3d6b47 100%);
            min-height: 100vh;
            position: relative;
        }
        
        .card {
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            border: none;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }
        
        .card-header {
            background: rgba(255, 255, 255, 0.95);
            color: #1a3d2e;
            border-radius: 15px 15px 0 0 !important;
            padding: 25px;
            text-align: center;
            border: none;
        }
        
        .card-body {
            padding: 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 0 0 15px 15px;
        }
        
        /* Form header with logo styling */
        .form-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        /* Logo styling */
        .form-logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            border-radius: 50%;
            box-shadow: 0 8px 25px rgba(26, 61, 46, 0.3);
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            padding: 10px;
            transition: all 0.3s ease;
        }
        
        .form-logo:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 35px rgba(26, 61, 46, 0.4);
        }
        
        .form-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }
        
        /* Ensure all other logo elements are hidden */
        .logo-container,
        .login-logo,
        .brand-image,
        .brand-link img {
            display: none !important;
            visibility: hidden !important;
        }
        
        .form-header h2 {
            font-size: 1.8rem;
            margin: 0;
            font-weight: 700;
            color: #1a3d2e;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .form-header .subtitle {
            font-size: 0.95rem;
            color: #666;
            margin-top: 5px;
            font-weight: 400;
            font-style: italic;
        }
        
        /* Form styling */
        .input-group {
            margin-bottom: 25px;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px 20px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: rgba(250, 250, 250, 0.9);
        }
        
        .form-control:focus {
            border-color: #1a3d2e;
            box-shadow: 0 0 0 0.2rem rgba(26, 61, 46, 0.25);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .input-group-text {
            border: 2px solid #e0e0e0;
            border-left: none;
            border-radius: 0 10px 10px 0;
            background: linear-gradient(135deg, #1a3d2e, #2d5a3d);
            color: white;
            width: 50px;
            justify-content: center;
        }
        
        .input-group .form-control {
            border-right: none;
            border-radius: 10px 0 0 10px;
        }
        
        .input-group:focus-within .input-group-text {
            border-color: #1a3d2e;
        }
        
        /* Button styling */
        .btn-primary {
            background: linear-gradient(135deg, #1a3d2e, #2d5a3d);
            border: none;
            border-radius: 10px;
            padding: 15px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(26, 61, 46, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #0f2a1c, #1e4a34);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 61, 46, 0.4);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            border: none;
            border-radius: 10px;
            padding: 15px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
        }
        
        .btn-warning:hover {
            background: linear-gradient(135deg, #e67e22, #d35400);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(243, 156, 18, 0.4);
            color: white;
        }
        
        /* Error styling */
        .invalid-feedback {
            color: #e74c3c;
            font-size: 0.875rem;
            margin-top: 5px;
            font-weight: 500;
        }
        
        .form-control.is-invalid {
            border-color: #e74c3c;
            background: #fdf2f2;
        }
        
        /* Enhanced background for better text visibility */
        .login-page::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.05) 0%, transparent 30%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.03) 0%, transparent 25%);
            pointer-events: none;
            z-index: 0;
        }
        
        /* Background overlay for better contrast */
        .login-page::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.1);
            pointer-events: none;
            z-index: 0;
        }
        
        /* Enhanced styling for text outside form card */
        .login-page .login-box {
            position: relative;
            z-index: 2;
        }
        
        /* Better visibility for outside card text */
        .outside-card-text {
            color: #ffffff;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8);
            font-weight: 700;
            font-size: 1.2rem;
            background: rgba(0, 0, 0, 0.2);
            padding: 5px 10px;
            border-radius: 5px;
            backdrop-filter: blur(5px);
        }
        
        .outside-card-title {
            color: #ffffff;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.8);
            font-weight: 800;
            font-size: 2.2rem;
            margin-bottom: 1rem;
            background: rgba(0, 0, 0, 0.2);
            padding: 10px 20px;
            border-radius: 10px;
            backdrop-filter: blur(5px);
            letter-spacing: 1px;
        }
        
        /* Special styling for "Repositori" text visibility */
        .repositori-title {
            color: #ffffff !important;
            text-shadow: 
                2px 2px 4px rgba(0, 0, 0, 0.9),
                -1px -1px 2px rgba(0, 0, 0, 0.5),
                1px -1px 2px rgba(0, 0, 0, 0.5),
                -1px 1px 2px rgba(0, 0, 0, 0.5);
            font-weight: 900 !important;
            font-size: 2.5rem !important;
            letter-spacing: 2px;
            background: linear-gradient(45deg, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.1));
            padding: 15px 25px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Animation for logo */
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
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .animate-pulse {
            animation: pulse 2s infinite;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 30px 20px;
            }
            
            .form-header h2 {
                font-size: 1.5rem;
            }
            
            .form-logo {
                width: 80px;
                height: 80px;
            }
            
            .repositori-title {
                font-size: 2rem !important;
                padding: 10px 15px;
            }
            
            .form-control {
                padding: 12px 15px;
            }
            
            .btn-primary,
            .btn-warning {
                padding: 12px 20px;
            }
        }
        
        @media (max-width: 480px) {
            .form-header h2 {
                font-size: 1.3rem;
            }
            
            .form-logo {
                width: 70px;
                height: 70px;
            }
            
            .repositori-title {
                font-size: 1.7rem !important;
                padding: 8px 12px;
            }
            
            .card-body {
                padding: 25px 15px;
            }
        }
        
        /* Additional styling for better visual hierarchy */
        .login-form-container {
            position: relative;
            z-index: 1;
        }
        
        .btn-group-custom {
            gap: 10px;
        }
        
        .btn-group-custom .btn {
            flex: 1;
        }
        
        /* Hover effects for input groups */
        .input-group:hover .form-control {
            border-color: #1a3d2e;
        }
        
        .input-group:hover .input-group-text {
            background: linear-gradient(135deg, #2d5a3d, #1a3d2e);
        }
        
        /* Hide any AdminLTE default branding/logos */
        .brand-link,
        .navbar-brand,
        .main-header .brand-image,
        .login-logo img {
            display: none !important;
        }
    </style>
@stop

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )
@php( $password_reset_url = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? route($password_reset_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? url($password_reset_url) : '' )
@endif

@section('auth_header', __('Silahkan Login'))

@section('auth_body')
    <div class="login-form-container">
        <div class="form-header">
            <!-- UNSUR Logo -->
            <div class="form-logo animate-fadeInUp">
                <img src="{{ asset('vendor/adminlte/dist/img/unsur.png') }}" alt="UNSUR Logo" />
            </div>
            
            <h2 class="animate-fadeInUp">Repositori Skripsi</h2>
            <p class="subtitle animate-fadeInUp">Sistem Informasi Skripsi UNSUR</p>
        </div>
        
        <form action="{{ $login_url }}" method="post">
            @csrf

            {{-- Email field --}}
            <div class="input-group mb-4">
                <input type="text" 
                       name="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" 
                       placeholder="{{ __('Masukkan NPM Anda') }}" 
                       autofocus>

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Password field --}}
            <div class="input-group mb-4">
                <input type="password" 
                       name="password" 
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="{{ __('Masukkan Password Anda') }}">

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Action buttons --}}
            <div class="row">
                <div class="col-6">
                    <a href="{{ route('welcome') }}" class="btn btn-block btn-warning">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Kembali</span>
                    </a>
                </div>
                <div class="col-6">
                    <button type="submit" class="btn btn-block btn-primary">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        <span>Masuk</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
@stop

@section('auth_footer')
    {{-- You can uncomment these sections if needed --}}
    {{-- Password reset link --}}
    {{-- @if($password_reset_url)
        <p class="my-0">
            <a href="{{ $password_reset_url }}" style="color: #1a3d2e;">
                {{ __('adminlte::adminlte.i_forgot_my_password') }}
            </a>
        </p>
    @endif --}}

    {{-- Register link --}}
    {{-- @if($register_url)
        <p class="my-0">
            <a href="{{ $register_url }}" style="color: #1a3d2e;">
                {{ __('adminlte::adminlte.register_a_new_membership') }}
            </a>
        </p>
    @endif --}}
@stop