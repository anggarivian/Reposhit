@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="row">
                <!-- Welcome Card -->
                <div class="col-lg-3 grid-margin mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body bg-gradient-light">
                            <div class="col-sm-12">
                                <div class="statistics-details d-flex align-items-center justify-content-between">
                                    <div class="icon-container bg-white p-3 rounded-circle">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                            <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"/>
                                            <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"/>
                                        </svg>
                                    </div>
                                    <div class="title">
                                        @if($user->roles_id == 1)
                                            <h5 class="font-weight-bold">Anda Login Sebagai Admin</h5>
                                        @elseif($user->roles_id == 2)
                                            <p class="mb-0">Selamat Datang</p>
                                            <h4 class="font-weight-bold">{{$user->name}}</h4>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Dosen -->
                <div class="col-lg-3 grid-margin mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body bg-gradient-secondary">
                            <div class="col-sm-12">
                                <div class="statistics-details d-flex align-items-center justify-content-between">
                                    <div class="icon-container bg-white p-3 rounded-circle">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#343a40" class="bi bi-person-gear" viewBox="0 0 16 16">
                                            <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z"/>
                                        </svg>
                                    </div>
                                    <div class="title text-white">
                                        <h5 class="mb-2">Jumlah Dosen</h5>
                                        <h3 class="font-weight-bold text-right">{{$jumlahDosen}}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Mahasiswa -->
                <div class="col-lg-3 grid-margin mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body bg-gradient-success">
                            <div class="col-sm-12">
                                <div class="statistics-details d-flex align-items-center justify-content-between">
                                    <div class="icon-container bg-white p-3 rounded-circle">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#28a745" class="bi bi-people" viewBox="0 0 16 16">
                                            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/>
                                        </svg>
                                    </div>
                                    <div class="title text-white">
                                        <h5 class="mb-2">Jumlah Mahasiswa</h5>
                                        <h3 class="font-weight-bold text-right">{{$jumlahMahasiswa}}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Skripsi -->
                <div class="col-lg-3 grid-margin mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body bg-gradient-info">
                            <div class="col-sm-12">
                                <div class="statistics-details d-flex align-items-center justify-content-between">
                                    <div class="icon-container bg-white p-3 rounded-circle">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="#5a5a5aff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><path d="M14 3v5h5M16 13H8M16 17H8M10 9H8"/></svg>
                                    </div>
                                    <div class="title text-white">
                                        <h5 class="mb-2">Jumlah Skripsi</h5>
                                        <h3 class="font-weight-bold text-right">{{$jumlahSkripsi}}</h3>
                                    </div>
                                </div>
                            </div>
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
    .bg-gradient-light {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    .bg-gradient-dark {
        background: linear-gradient(135deg, #343a40 0%, #212529 100%);
        color: white;
    }
    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    .bg-gradient-danger {
        background: linear-gradient(135deg, #dc3545 0%, #f55a4e 100%);
        color: white;
    }
    .card {
        border-radius: 12px;
        overflow: hidden;
        border: 0;
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .statistics-details {
        padding: 10px 0;
    }
    .icon-container {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .rounded-circle {
        border-radius: 50% !important;
    }
    .shadow-sm {
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important;
    }
    .title h5, .title h3 {
        margin-bottom: 0;
    }
</style>
@stop

@section('js')
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
@stop
