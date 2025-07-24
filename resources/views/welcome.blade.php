<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="E-Repository Fakultas Sains Terapan - Universitas Suryakancana" />
    <meta name="author" content="" />
    <title>FASTER - Jurnal</title>
    <!-- Font Awesome icons -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- favicon -->
        <link rel="icon" type="image/png" href="{{asset('vendor/adminlte/dist/img/unsur.png')}}">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #6c757d;
            --success-color: #18a19f;
            --danger-color: #e74c3c;
            --light-bg: #f8f9fa;
            --dark-bg: #343a40;
        }

        body {
            font-family: 'Lato', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        /* Navigation */
        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
        }

        .nav-link {
            font-weight: 400;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            transform: translateY(-2px);
        }

        /* Masthead */
        .masthead {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--success-color) 100%);
            padding: 6rem 0;
        }

        .masthead-avatar {
            width: 150px;
            height: 150px;
            border: 5px solid white;
            border-radius: 50%;
        }

        /* Custom divider */
        .divider-custom {
            margin: 1.5rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .divider-custom-line {
            height: 2px;
            background-color: #6c757d;
            width: 100px;
        }

        .divider-custom-icon {
            margin: 0 1rem;
        }

        .divider-light .divider-custom-line {
            background-color: white;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .stats-icon {
            width: 70px;
            height: 70px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stats-icon.success {
            background: var(--success-color);
        }

        .stats-icon.danger {
            background: var(--danger-color);
        }

        /* Search Form */
        .search-section {
            background: var(--light-bg);
            padding: 4rem 0;
        }

        .search-form {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(44,62,80,0.25);
        }

        .btn-search {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--success-color) 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Table */
        .table-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .table thead th {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-detail {
            background: linear-gradient(135deg, var(--success-color) 0%, #16a19c 100%);
            border: none;
            border-radius: 10px;
            padding: 0.5rem 1.5rem;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-detail:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }

        /* About Section */
        .about-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-bg) 100%);
            padding: 5rem 0;
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 3rem 0;
        }

        .copyright {
            background: #1a252f;
            color: white;
            padding: 1rem 0;
        }

        /* No Results Message */
        .no-results {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .no-results-icon {
            font-size: 3rem;
            color: var(--danger-color);
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .masthead {
                padding: 4rem 0;
            }

            .search-form {
                padding: 1.5rem;
            }

            .form-control {
                margin-bottom: 1rem;
            }

            .table-responsive {
                font-size: 0.9rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body id="page-top">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="/welcome">
                <i class="fas fa-university me-2"></i>
                Fakultas Sains Terapan (FASTER)
            </a>
            <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item mx-0 mx-lg-1">
                        <a class="nav-link py-3 px-0 px-lg-3 rounded" href="#search">
                            <i class="fas fa-search me-1"></i>Pencarian
                        </a>
                    </li>
                    <li class="nav-item mx-0 mx-lg-1">
                        <a class="nav-link py-3 px-0 px-lg-3 rounded" href="#about">
                            <i class="fas fa-info-circle me-1"></i>About
                        </a>
                    </li>
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item mx-0 mx-lg-1">
                                <a href="{{ url('/home') }}" class="nav-link py-3 px-0 px-lg-3 rounded">
                                    <i class="fas fa-home me-1"></i>Home
                                </a>
                            </li>
                    @else
                        <li class="nav-item mx-0 mx-lg-1">
                            <a href="{{ route('login') }}" class="nav-link py-3 px-0 px-lg-3 rounded">
                                <i class="fas fa-sign-in-alt me-1"></i>Log in
                            </a>
                        </li>
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Masthead -->
    <header class="masthead text-white text-center">
        <div class="container d-flex align-items-center flex-column">
            <!-- Masthead Avatar Image -->
            <img class="masthead-avatar mb-5 animate-fadeInUp" src="vendor/adminlte/dist/img/unsur.png" alt="UNSUR Logo" />
            <!-- Masthead Heading -->
            <h1 class="masthead-heading text-uppercase mb-0 animate-fadeInUp">E-Repository</h1>
            <!-- Icon Divider -->
            <div class="divider-custom divider-light animate-fadeInUp">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- Masthead Subheading -->
            <p class="masthead-subheading font-weight-light mb-0 animate-fadeInUp">
                Universitas Suryakancana - Fakultas Sains Terapan
            </p>
        </div>
    </header>

    <!-- Statistics Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card text-center animate-fadeInUp">
                        <div class="stats-icon mx-auto mb-3">
                            <i class="fas fa-graduation-cap fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-2">{{ isset($jumlahSkripsi) ? $jumlahSkripsi : $skripsi->count() }}</h4>
                        <p class="text-muted mb-0">Total Skripsi</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card text-center animate-fadeInUp">
                        <div class="stats-icon success mx-auto mb-3">
                            <i class="fas fa-users fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-2">{{ isset($jumlahMahasiswa) ? $jumlahMahasiswa : '0' }}</h4>
                        <p class="text-muted mb-0">Total Mahasiswa</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card text-center animate-fadeInUp">
                        <div class="stats-icon danger mx-auto mb-3">
                            <i class="fas fa-chalkboard-teacher fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-2">{{ isset($jumlahDosen) ? $jumlahDosen : '0' }}</h4>
                        <p class="text-muted mb-0">Total Dosen</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card text-center animate-fadeInUp">
                        <div class="stats-icon mx-auto mb-3">
                            <i class="fas fa-check-circle fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-2">{{ isset($jumlahSkripsi) ? $jumlahSkripsi : $skripsi->count() }}</h4>
                        <p class="text-muted mb-0">Total Skripsi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="search-section" id="search">
        <div class="container">
        <!-- Section Heading -->
            <h2 class="text-center text-uppercase text-secondary mb-0 fw-bold">Cari Skripsi</h2>
            <!-- Icon Divider -->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-search"></i></div>
                <div class="divider-custom-line"></div>
            </div>

        <!-- Search Form -->
        <div class="search-form">
            <form action="/welcome" method="GET" id="searchForm">
                <div class="row g-3 justify-content-center">
                    <div class="col-md-2">
                        <input
                            type="text"
                            class="form-control"
                            name="judul"
                            id="judul"
                            value="{{ old('judul', request('judul')) }}"
                            placeholder="Cari Judul">
                    </div>
                    <div class="col-md-2">
                        <input
                            type="text"
                            class="form-control"
                            name="penulis"
                            id="penulis"
                            value="{{ old('penulis', request('penulis')) }}"
                            placeholder="Cari Penulis">
                    </div>
                    <div class="col-md-2">
                        <input
                            type="text"
                            class="form-control"
                            name="rilis"
                            id="rilis"
                            value="{{ old('tahun', request('tahun')) }}"
                            placeholder="Tahun Terbit">
                    </div>
                    <div class="col-md-2">
                        <input
                            type="text"
                            class="form-control"
                            name="subject"
                            id="subject"
                            value="{{ old('subject', request('subject')) }}"
                            placeholder="Topik (Subject)">
                    </div>
                    <div class="col-md-2">
                        <input
                            type="text"
                            class="form-control"
                            name="keywords"
                            id="keywords"
                            value="{{ old('keywords', request('keywords')) }}"
                            placeholder="Kata Kunci">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-search w-100">
                            Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table / No Results -->
        <div class="table-container mt-5">
            @if(isset($hasFilter) && $hasFilter)
                @if($results && $results->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0 text-center" id="table-data">
                        <thead class="table-secondary">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Judul</th>
                                <th scope="col">Penulis</th>
                                <th scope="col">Tahun Rilis</th>
                                <th scope="col">Topik</th>
                                <th scope="col">Kata Kunci</th>
                                <th scope="col">Halaman</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 + ($results->currentPage() - 1) * $results->perPage() }}</td>
                                <td>{{ $item->judul }}</td>
                                <td>{{ $item->penulis }}</td>
                                <td>{{ $item->rilis }}</td>
                                <td>{{ optional($item->metadata)->subject ?? '-' }}</td>
                                <td>{{ optional($item->metadata)->keywords ?? '-' }}</td>
                                <td>{{ $item->halaman }}</td>
                                <td>
                                    <a href="{{ url('/welcome/detail/'.$item->id) }}">
                                        <button class="btn btn-detail btn-sm">
                                            <i class="fas fa-info-circle me-1"></i>Detail
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- pagination --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $results->links() }}
                    </div>
                </div>
                @else
                <div class="no-results mt-4 text-center">
                    <div class="no-results-icon mb-3">
                        <i class="fas fa-search-minus fa-2x text-secondary"></i>
                    </div>
                    <h4>Tidak Ada Hasil</h4>
                    <p class="text-muted">Maaf, skripsi yang Anda cari tidak ditemukan.</p>
                    <a href="{{ url('/welcome') }}" class="btn btn-search mt-2">
                        <i class="fas fa-redo me-1"></i>Reset Pencarian
                    </a>
                </div>
                @endif
            @endif
        </div>
    </div>

    </section>

    <!-- About Section -->
    <section class="about-section text-white mb-0" id="about">
        <div class="container">
            <!-- About Section Heading -->
            <h2 class="text-center text-uppercase text-white fw-bold">About</h2>
            <!-- Icon Divider -->
            <div class="divider-custom divider-light">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- About Section Content -->
            <div class="row">
                <div class="col-lg-12">
                    <p class="lead text-center">
                        Fakultas Sains Terapan (FASTER) Universitas Suryakancana adalah institusi pendidikan tinggi yang berlokasi di Jalan Pasir Gede Raya, Cianjur.
                        Komitmen kami adalah menjadi institusi yang unggul di tingkat ASEAN dalam penyelenggaraan pendidikan dan penelitian untuk kesejahteraan masyarakat pada tahun 2030.
                    </p>
                </div>
            </div>
            <!-- About Section Button -->
            <div class="text-center mt-4">
                <a class="btn btn-xl btn-outline-light rounded-pill" href="https://faster.unsur.ac.id/">
                    <i class="fas fa-external-link-alt me-2"></i>Informasi Lanjut
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <div class="row">
                <!-- Footer Location -->
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h4 class="text-uppercase mb-4">
                        <i class="fas fa-map-marker-alt me-2"></i>Lokasi
                    </h4>
                    <p class="lead mb-0">
                        Jl. Pasirgede Raya, Muka<br />
                        Kec. Cianjur, Kabupaten Cianjur
                    </p>
                </div>
                <!-- Footer About Text -->
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h4 class="text-uppercase mb-4">
                        <i class="fas fa-university me-2"></i>About FASTER
                    </h4>
                    <p class="lead mb-0">
                        Universitas Suryakancana<br />
                        Fakultas Sains Terapan
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Copyright Section -->
    <div class="copyright py-4 text-center text-white">
        <div class="container">
            <small>Copyright &copy; FASTER UNSUR 2024</small>
        </div>
    </div>

    <!-- Bootstrap core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom JS -->
    <script>
        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fadeInUp');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.stats-card').forEach(card => {
            observer.observe(card);
        });


    </script>
</body>
</html>
