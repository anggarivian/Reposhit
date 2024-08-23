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
        <style>
            .page-section-heading {
                font-size: 2.5rem;
                margin-bottom: 2rem;
                color: #2c3e50;
            }

            .divider-custom {
                margin: 1.5rem 0;
            }

            .divider-custom-line {
                width: 100%;
                height: 1px;
                background-color: #2c3e50;
            }

            .divider-custom-icon {
                color: #2c3e50;
                font-size: 2rem;
            }

            .table-borderless td {
                padding: 10px 0;
            }

            .table-borderless td:first-child {
                font-weight: bold;
            }

            .showPdfButton {
                background-color: #3498db;
                border: none;
                color: white;
                padding: 10px 20px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer;
                transition-duration: 0.4s;
                border-radius: 5px;
            }

            .showPdfButton:hover {
                background-color: white;
                color: black;
                border: 2px solid #3498db;
            }

            .custom-table {
                width: 100%;
                max-width: 600px;
                margin: auto;
                border-collapse: collapse;
                background: #ffffff;
                box-shadow: 0px 8px 24px rgba(0, 0, 0, 0.1);
                border-radius: 12px;
                overflow: hidden;
            }

            .custom-table tr {
                border-bottom: 1px solid #f2f2f2;
            }

            .custom-table td {
                padding: 15px 20px;
                vertical-align: top;
                color: #555555;
                font-family: 'Lato', sans-serif;
            }

            .custom-table td:first-child {
                font-weight: bold;
                width: 200px;
                color: #2c3e50;
            }

            .detail-container {
                margin-top: 3rem;
                padding: 2rem;
                background-color: #f9f9f9;
                border-radius: 12px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
                font-family: 'Lato', sans-serif;
            }

            .detail-title {
                font-size: 1.8rem;
                margin-bottom: 1rem;
                text-align: center;
                color: #3498db;
                font-weight: 700;
            }

            .button-container {
                text-align: center;
                margin-top: 2rem;
            }

            .pdf-viewer {
                display: none;
                margin-top: 1.5rem;
                border: 1px solid #ccc;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            .pdf-title {
                text-align: center;
                font-size: 1.4rem;
                color: #2c3e50;
                margin-bottom: 0.5rem;
                font-weight: 600;
            }

            .pdf-frame {
                width: 100%;
                height: 600px;
                border: none;
            }
        </style>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand" href="/welcome">Fakultas Sains Terapan (FASTER)</a>
                <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="/welcome#portfolio">PENCARIAN</a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="/welcome#about">About</a></li>
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

                <div class="detail-container">
                    <h3 class="detail-title">Detail Skripsi</h3>
                    <table class="custom-table">
                        <tr>
                            <td>Judul Skripsi</td>
                            <td>:</td>
                            <td>{{$skripsi->judul}}</td>
                        </tr>
                        <tr>
                            <td>Penulis</td>
                            <td>:</td>
                            <td>{{$skripsi->penulis}}</td>
                        </tr>
                        <tr>
                            <td>Abstrak</td>
                            <td>:</td>
                            <td>{{$skripsi->abstrak}}</td>
                        </tr>
                        <tr>
                            <td>Dosen Pembimbing</td>
                            <td>:</td>
                            <td>{{$skripsi->dospem}}</td>
                        </tr>
                        <tr>
                            <td>Rilis Tahun</td>
                            <td>:</td>
                            <td>{{$skripsi->rilis}}</td>
                        </tr>
                        <tr>
                            <td>Halaman</td>
                            <td>:</td>
                            <td>{{$skripsi->halaman}}</td>
                        </tr>
                    </table>

                    <div class="button-container">
                        @if (Route::has('login'))
                            @auth
                            @foreach ($skripsi->pdfs as $attribute => $pdf)
                            <button class="showPdfButton" data-target="{{ $attribute }}">Lihat {{ ucfirst($label) }}</button>
                            <div id="{{ $attribute }}-pdf" class="pdf-viewer">
                                <h4 class="pdf-title">{{ ucfirst($label) }}</h4>
                                <iframe src="{{ asset('storage/' . $pdf) }}" class="pdf-frame"></iframe>
                            </div>
                            @endforeach
                            @else
                            <p>Silakan <a href="{{ route('login') }}">login</a> untuk melihat detail skripsi.</p>
                            @endif
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
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <h4 class="text-uppercase mb-4">Location</h4>
                        <p class="lead mb-0">
                            Universitas Suryakancana
                            <br />
                            Fakultas Sains Terapan
                        </p>
                    </div>
                    <!-- Footer Social Icons-->
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <h4 class="text-uppercase mb-4">Around the Web</h4>
                        <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-linkedin-in"></i></a>
                        <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-dribbble"></i></a>
                    </div>
                    <!-- Footer About Text-->
                    <div class="col-lg-4">
                        <h4 class="text-uppercase mb-4">About Faster</h4>
                        <p class="lead mb-0">
                            FASTER adalah sebuah repositori skripsi yang membantu mahasiswa mengakses karya ilmiah
                        </p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Copyright Section-->
        <section class="copyright py-4 text-center text-white">
            <div class="container"><small>Copyright &copy; FASTER 2023</small></div>
        </section>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="{{ asset('js/scripts.js') }}"></script>
        <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
        <script>
            document.querySelectorAll('.showPdfButton').forEach(button => {
                button.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');
                    const pdfDiv = document.getElementById(target + '-pdf');
                    if (pdfDiv.style.display === 'none') {
                        pdfDiv.style.display = 'block';
                    } else {
                        pdfDiv.style.display = 'none';
                    }
                });
            });
        </script>
    </body>
</html>
