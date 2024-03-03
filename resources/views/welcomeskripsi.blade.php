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
                <a class="navbar-brand" href="/welcome">Faster</a>
                <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="/welcome#portfolio">PENCARIAN</a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="/welcome">About</a></li>
                        @if (Route::has('login'))
                            @auth
                                <li class="nav-item mx-0 mx-lg-1">
                                    <a href="{{ url('/home') }}" class="nav-link py-3 px-0 px-lg-3 rounded">Home</a>
                                </li>
                        @else
                            <li class="nav-item mx-0 mx-lg-1">
                                <a href="{{ route('login') }}" class="nav-link py-3 px-0 px-lg-3 rounded">Log in</a>
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
                <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Lihat Detail</h2>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>

                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-1">
                        <div class="p-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td style="width: 200px;">Judul Skripsi</td>
                                    <td>:</td>
                                    <td>{{$skripsi->judul}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 200px;">Penulis</td>
                                    <td>:</td>
                                    <td>{{$skripsi->penulis}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 200px;">Abstrak</td>
                                    <td>:</td>
                                    <td>{{$skripsi->abstrak}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 200px;">Dosen Pembimbing</td>
                                    <td>:</td>
                                    <td>{{$skripsi->dospem}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 200px;">Rilis Tahun</td>
                                    <td>:</td>
                                    <td>{{$skripsi->rilis}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 200px;">Halaman</td>
                                    <td>:</td>
                                    <td>{{$skripsi->halaman}}</td>
                                </tr>
                            </table>
                            <hr>
                            @if(auth()->check())
                            {{-- Tombol untuk menampilkan/menyembunyikan iframe --}}
                @foreach($pdfs as $attribute => $pdf)
                @php
                    $label = $attribute == 'dapus'
                        ? 'Daftar Pustaka'
                        : (strpos($attribute, 'bab') === 0
                            ? 'Bab ' . (
                                $attribute == 'bab1' ? 'I' : (
                                    $attribute == 'bab2' ? 'II' : (
                                        $attribute == 'bab3' ? 'III' : (
                                            $attribute == 'bab4' ? 'IV' : (
                                                $attribute == 'bab5' ? 'V' : $attribute
                                            )
                                        )
                                    )
                                )
                            )
                            : $attribute
                        );
                @endphp
                <button class="btn btn-info m-3 showPdfButton" data-target="{{ $attribute }}">
                    Lihat {{ ucfirst($label) }}
                </button>
            @endforeach

            {{-- Menampilkan semua PDF --}}
            @foreach($pdfs as $attribute => $pdf)
                @php
                    $label = $attribute == 'dapus'
                        ? 'Daftar Pustaka'
                        : (strpos($attribute, 'bab') === 0
                            ? 'Bab ' . (
                                $attribute == 'bab1' ? 'I' : (
                                    $attribute == 'bab2' ? 'II' : (
                                        $attribute == 'bab3' ? 'III' : (
                                            $attribute == 'bab4' ? 'IV' : (
                                                $attribute == 'bab5' ? 'V' : $attribute
                                            )
                                        )
                                    )
                                )
                            )
                            : $attribute
                        );
                @endphp
                <h2 id="{{ $attribute }}Header" style="display: none;">{{ ucfirst($label) }}</h2>
                <!-- Sembunyikan iframe dengan ID sesuai dengan atribut -->
                <iframe id="{{ $attribute }}Frame" src="data:application/pdf;base64,{{ $pdf }}#toolbar=0&navpanes=0&view=fitH" width="100%" height="600px" style="display: none;"></iframe>
            @endforeach
                            @else
                                {{-- Pengguna belum login, tampilkan pesan --}}
                                <a href="/login" class="btn btn-info m-3">
                                    Silahkan Login Untuk Melihat
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
        </section>
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
        <script>
            document.querySelectorAll('.showPdfButton').forEach(function(button) {
                button.addEventListener('click', function() {
                    var targetAttribute = this.getAttribute('data-target');
    
                    // Sembunyikan semua iframe dan header (nama atribut) terlebih dahulu
                    document.querySelectorAll('iframe').forEach(function(iframe) {
                        iframe.style.display = 'none';
                    });
    
                    document.querySelectorAll('h2').forEach(function(header) {
                        header.style.display = 'none';
                    });
    
                    // Tampilkan iframe dan header yang sesuai dengan tombol yang ditekan
                    var pdfFrame = document.getElementById(targetAttribute + 'Frame');
                    var pdfHeader = document.getElementById(targetAttribute + 'Header');
    
                    pdfFrame.style.display = 'block';
                    pdfHeader.style.display = 'block';
                });
            });
        </script>
        {{-- <script>
            
        </script> --}}
    </body>
</html>