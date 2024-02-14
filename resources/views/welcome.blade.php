<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>FASTER - Jurnal</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}">
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand" href="#page-top">Faster</a>
                <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="/welcome#portfolio">Pencarian</a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="#about">About</a></li>
                        @if (Route::has('login'))
                            @auth
                                <li class="nav-item mx-0 mx-lg-1">
                                    <a href="{{ url('/home') }}" class="nav-link py-3 px-0 px-lg-3 rounded">Home</a>
                                </li>
                        @else
                            <li class="nav-item mx-0 mx-lg-1">
                                <a href="{{ route('login') }}" class="nav-link py-3 px-0 px-lg-3 rounded">Log in</a>
                            </li>
                            <li class="nav-item mx-0 mx-lg-1">
                                <a href="{{ route('register') }}" class="nav-link py-3 px-0 px-lg-3 rounded">Register</a>
                            </li>
                            @endauth
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead bg-primary text-white text-center">
            <div class="container d-flex align-items-center flex-column">
                <!-- Masthead Avatar Image-->
                <img class="masthead-avatar mb-5" src="vendor/adminlte/dist/img/unsur.png" alt="..." />
                <!-- Masthead Heading-->
                <h1 class="masthead-heading text-uppercase mb-0">E-Repository</h1>
                <!-- Icon Divider-->
                <div class="divider-custom divider-light">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Masthead Subheading-->
                <p class="masthead-subheading font-weight-light mb-0">Universitas Suryakancana - Fakultas - Sains Terapan</p>
            </div>
        </header>
        <!-- Portfolio Section-->
        <section class="page-section portfolio" id="portfolio">
            <div class="container">
                <!-- Portfolio Section Heading-->
                <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Cari Skripsi</h2>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>

                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-1">
                        <div class="p-6">
                            <form action="/welcome" method="GET">
                                <!-- @csrf -->
                                <div class="search d-flex justify-content-center">
                                    <div class="d-blox justify-content-center m-1">
                                        <div class="form-group">
                                            <input type="text" class="form-control rounded" name="judul" id="judul" value="{{ request('judul') }}" placeholder="Cari Judul">
                                        </div>
                                    </div>
                                    <div class="d-blox justify-content-center m-1">
                                        <div class="form-group">
                                            <input type="text" class="form-control rounded" name="penulis" id="penulis" value="{{ request('penulis') }}" placeholder="Cari Penulis">
                                        </div>
                                    </div>
                                    <div class="d-blox justify-content-center m-1">
                                        <div class="form-group">
                                            <input type="text" class="form-control rounded" name="rilis" id="rilis" value="{{ request('rilis') }}" placeholder="Cari Tahun Terbit">
                                        </div>
                                    </div>
                                    <div class="d-blok justify-content-center m-1">
                                        <button type="submit" class="btn btn-primary btn-rounded">
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <br>
                            <table id="table-data" class="table table-stripped text-center">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Penulis</th>
                                        <th>Abstrak</th>
                                        <th>Dosen Pembimbing</th>
                                        <th>Rilis</th>
                                        <th>Halaman</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no=1; @endphp
                                    @foreach($skripsi as $skripsis)
                                    <tr>
                                        <td>{{$no++}}</td>
                                        <td>{{$skripsis->judul}}</td>
                                        <td>{{$skripsis->penulis}}</td>
                                        <td>{{ Str::limit($skripsis->abstrak, 20) }}</td>
                                        <td>{{$skripsis->dospem}}</td>
                                        <td>{{$skripsis->rilis}}</td>
                                        <td>{{$skripsis->halaman}}</td>
                                        <td>
                                            <a href="/welcome/detail/{{$skripsis->id}}">
                                                <button class="btn btn-sm btn-primary">
                                                    Detail
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </section>
        <!-- About Section-->
        <section class="page-section bg-primary text-white mb-0" id="about">
            <div class="container">
                <!-- About Section Heading-->
                <h2 class="page-section-heading text-center text-uppercase text-white">About</h2>
                <!-- Icon Divider-->
                <div class="divider-custom divider-light">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- About Section Content-->
                <div class="row">
                    <div class="col-lg-12 me-auto"><p class="lead">Faster Unsur adalah Fakultas Sains Terapan di Universitas Suryakancana, Cianjur. Fakultas ini berada di Jalan Pasir Gede Raya, Cianjur.Menjadi Institusi yang Unggul di Tingkat ASEAN, Dalam Penyelenggaraan Pendidikan dan Penelitian untuk Kesejahteraan Masyarakat pada Tahun 2030</p></div>
                </div>
                <!-- About Section Button-->
                <div class="text-center mt-4">
                    <a class="btn btn-xl btn-outline-light" href="https://faster.unsur.ac.id/">
                        
                        Informasi Lanjut
                    </a>
                </div>
            </div>
        </section>
        <!-- Contact Section-->
        <!-- Footer-->
        <footer class="footer text-center">
            <div class="container">
                <div class="row">
                    <!-- Footer Location-->
                    <div class="col-lg-6 mb-5 mb-lg-0">
                        <h4 class="text-uppercase mb-6">Location</h4>
                        <p class="lead mb-0">
                            Jl. Pasirgede Raya, Muka
                            <br />
                            Kec, Cianjur, Kabupaten Cianjur
                        </p>
                    </div>
                    <!-- Footer About Text-->
                    <div class="col-lg-6 mb-5 mb-lg-0">
                        <h4 class="text-uppercase mb-6">About Faster</h4>
                        <p class="lead mb-0">
                            Universitas Suryakancana
                            <br />Fakultas - Sains Terapan
                        </p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Copyright Section-->
        <div class="copyright py-4 text-center text-white">
            <div class="container"><small>Copyright &copy; Your Website 2024</small></div>
        </div>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="{{ asset('js/scripts.js') }}"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
    </body>
</html>