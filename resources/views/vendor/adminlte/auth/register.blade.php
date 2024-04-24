@extends('adminlte::auth.auth-page', ['auth_type' => 'register'])

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
@endif

@section('auth_header', __('Silahkan untuk daftar terlebih dahulu'))

@section('auth_body')
    <form action="{{ $register_url }}" method="post">
        @csrf

        {{-- Name field --}}
        <div class="input-group mb-3">
            <input type="text" name="npm" class="form-control @error('npm') is-invalid @enderror"
                   value="{{ old('npm') }}" placeholder="{{ __('NPM atau NIM') }}" autofocus required maxlength="255">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-home {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

                @error('npm')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" placeholder="{{ __('adminlte::adminlte.full_name') }}" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

            <div class="input-group mb-3">
                <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                       value="{{ old('alamat') }}" placeholder="{{ __('Alamat') }}" autofocus required maxlength="255">

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-home {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>

                    @error('alamat')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="input-group mb-3">
                <input type="text" name="angkatan" class="form-control @error('angkatan') is-invalid @enderror"
                       value="{{ old('angkatan') }}" placeholder="{{ __('Angkatan') }}" autofocus required maxlength="4">

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-graduation-cap {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>

                @error('angkatan')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="input-group mb-3">
                <input type="date" name="tgl_lahir" class="form-control @error('tgl_lahir') is-invalid @enderror"
                       value="{{ old('tgl_lahir') }}" placeholder="{{ __('Tanggal Lahir') }}" autofocus required>

                {{-- <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-calendar {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div> --}}

                @error('tgl_lahir')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="input-group mb-3">
                <select name="prodi" class="form-control @error('prodi') is-invalid @enderror">
                    <option value="" disabled selected>{{ __('Pilih Prodi') }}</option>
                    <option value="agribisnis" {{ old('prodi') == 'agribisnis' ? 'selected' : '' }}>Agribisnis</option>
                    <option value="agroteknologi" {{ old('prodi') == 'agroteknologi' ? 'selected' : '' }}>Agroteknologi</option>
                </select>

                @error('prodi')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   placeholder="{{ __('adminlte::adminlte.password') }}">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Confirm password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   placeholder="{{ __('adminlte::adminlte.retype_password') }}">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Register button --}}
        <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
            <span class="fas fa-user-plus"></span>
            {{ __('Daftar') }}
        </button>
    </form>
@stop

@section('auth_footer')
    <p class="my-0">
        <a href="{{ $login_url }}">
            {{ __('Sudah memiliki akun') }}
        </a>
    </p>
@stop

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
    })

    @if(Session::has('message'))
        var type = "{{Session::get('alert-type')}}";
        switch (type){
            case 'info':
                Toast.fire({
                    icon: 'info',
                    title: "{{ Session::get('message') }}"
                })
                break;
                case 'success':
                Toast.fire({
                    icon: 'success',
                    title: "{{ Session::get('message') }}"
                })
                break;
                case 'error':
                Toast.fire({
                    icon: 'error',
                    title: "{{ Session::get('message') }}"
                })
                break;
                case 'info':
                Toast.fire({
                    icon: 'info',
                    title: "Ooops",
                    text: "{{ Session::get('message') }}"
                })
                break;
        }
    @endif

    @if ($errors->any())
    @foreach($errors->all() as $errors)
        Swal.fire({
            type: 'error',
            title: "Ooops",
            text: "{{ $errors }}",
        })
        @endforeach
    @endif

    $('#table-data').DataTable();

    let baseurl = "<?=url("/")?>";
    let fullURL = "<?=url()->full()?>";
</script>
