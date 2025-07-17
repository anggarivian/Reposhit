<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="E-Repository Fakultas Sains Terapan - Universitas Suryakancana" />
    <meta name="author" content="" />
    <title>FASTER - Detail Skripsi</title>
    <!-- Font Awesome icons -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
            <!-- favicon -->
        <link rel="icon" type="image/png" href="{{asset('vendor/adminlte/dist/img/unsur.png')}}">
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
            padding: 4rem 0;
        }

        .masthead-avatar {
            width: 120px;
            height: 120px;
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

        /* Detail Section */
        .detail-section {
            background: var(--light-bg);
            padding: 5rem 0;
        }

        .detail-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }

        .detail-title {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .detail-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .detail-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            vertical-align: top;
        }

        .detail-table td:first-child {
            font-weight: 600;
            color: var(--primary-color);
            width: 200px;
            background: #f8f9fa;
        }

        .detail-table td:nth-child(2) {
            width: 20px;
            text-align: center;
            color: var(--secondary-color);
            background: #f8f9fa;
        }

        .detail-table td:last-child {
            color: #495057;
            line-height: 1.6;
        }

        .detail-table tr:first-child td:first-child {
            border-top-left-radius: 10px;
        }

        .detail-table tr:first-child td:last-child {
            border-top-right-radius: 10px;
        }

        .detail-table tr:last-child td {
            border-bottom: none;
        }

        .detail-table tr:last-child td:first-child {
            border-bottom-left-radius: 10px;
        }

        .detail-table tr:last-child td:last-child {
            border-bottom-right-radius: 10px;
        }

        /* PDF Buttons */
        .pdf-buttons {
            text-align: center;
            margin-top: 2rem;
        }

        .btn-pdf {
            background: linear-gradient(135deg, var(--success-color) 0%, #16a19c 100%);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            color: white;
            font-weight: 600;
            margin: 0.5rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-pdf:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            color: white;
        }

        .btn-back {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #5a6268 100%);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            color: white;
        }

        /* PDF Viewer */
        .pdf-viewer {
            display: none;
            margin-top: 2rem;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .pdf-header {
            background: var(--primary-color);
            color: white;
            padding: 1rem 2rem;
            text-align: center;
            font-weight: 600;
        }

        .pdf-frame {
            width: 100%;
            height: 600px;
            border: none;
        }

        /* Login Message */
        .login-message {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .login-icon {
            font-size: 3rem;
            color: var(--success-color);
            margin-bottom: 1rem;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--success-color) 100%);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            color: white;
            font-weight: 600;
            margin-top: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            color: white;
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

        /* Responsive */
        @media (max-width: 768px) {
            .masthead {
                padding: 3rem 0;
            }

            .detail-card {
                padding: 2rem;
            }

            .detail-table td:first-child {
                width: 150px;
                font-size: 0.9rem;
            }

            .detail-table td {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .btn-pdf, .btn-back, .btn-login {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
            }
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
                        <a class="nav-link py-3 px-0 px-lg-3 rounded" href="/welcome#search">
                            <i class="fas fa-search me-1"></i>Pencarian
                        </a>
                    </li>
                    <li class="nav-item mx-0 mx-lg-1">
                        <a class="nav-link py-3 px-0 px-lg-3 rounded" href="/welcome#about">
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
<img class="masthead-avatar mb-4 animate-fadeInUp" src="/vendor/adminlte/dist/img/unsur.png" alt="UNSUR Logo" />
            <!-- Masthead Heading -->
            <h1 class="masthead-heading text-uppercase mb-0 animate-fadeInUp">Detail Skripsi</h1>
            <!-- Icon Divider -->
            <div class="divider-custom divider-light animate-fadeInUp">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-file-alt"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- Masthead Subheading -->
            <p class="masthead-subheading font-weight-light mb-0 animate-fadeInUp">
                E-Repository Universitas Suryakancana
            </p>
        </div>
    </header>

    <!-- Detail Section -->
    <section class="detail-section">
        <div class="container">
            <!-- Back Button -->
            <div class="text-center mb-4">
                <a href="/welcome" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Pencarian
                </a>
            </div>

            <!-- Detail Card -->
            <div class="detail-card animate-fadeInUp">
                <h2 class="detail-title">
                    <i class="fas fa-file-alt me-2"></i>
                    Informasi Skripsi
                </h2>
                
                <table class="detail-table">
                    <tr>
                        <td><i class="fas fa-book me-2"></i>Judul Skripsi</td>
                        <td>:</td>
                        <td>{{$skripsi->judul}}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-user me-2"></i>Penulis</td>
                        <td>:</td>
                        <td>{{$skripsi->penulis}}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-align-left me-2"></i>Abstrak</td>
                        <td>:</td>
                        <td>{{$skripsi->abstrak}}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-chalkboard-teacher me-2"></i>Dosen Pembimbing</td>
                        <td>:</td>
                        <td>{{$skripsi->dospem}}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-calendar-alt me-2"></i>Tahun Rilis</td>
                        <td>:</td>
                        <td>{{$skripsi->rilis}}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-file-pdf me-2"></i>Jumlah Halaman</td>
                        <td>:</td>
                        <td>{{$skripsi->halaman}} halaman</td>
                    </tr>
                </table>

                <!-- PDF Buttons -->
                <div class="pdf-buttons">
                    @if (Route::has('login'))
                        @auth
                            @foreach ($skripsi->pdfs as $attribute => $pdf)
                            <button class="btn-pdf showPdfButton" data-target="{{ $attribute }}">
                                <i class="fas fa-eye"></i>
                                Lihat {{ ucfirst($attribute) }}
                            </button>
                            @endforeach
                        @else
                            <div class="login-message mt-4">
                                <div class="login-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <h4 class="mb-3">Akses Terbatas</h4>
                                <p class="text-muted mb-3">
                                    Untuk melihat data skripsi secara lengkap, silakan login terlebih dahulu.
                                </p>
                                <a href="{{ route('login') }}" class="btn-login">
                                    <i class="fas fa-sign-in-alt"></i>
                                    Login Sekarang
                                </a>
                            </div>
                        @endauth
                    @endif
                </div>
            </div>

            <!-- PDF Viewers -->
            @if (Route::has('login'))
                @auth
                    @foreach ($skripsi->pdfs as $attribute => $pdf)
                    <div id="{{ $attribute }}-pdf" class="pdf-viewer">
                        <div class="pdf-header">
                            <i class="fas fa-file-pdf me-2"></i>
                            {{ ucfirst($attribute) }} - {{ $skripsi->judul }}
                        </div>
                        <iframe src="{{ asset('storage/' . $pdf) }}" class="pdf-frame"></iframe>
                    </div>
                    @endforeach
                @endauth
            @endif
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section text-white mb-0" id="about">
        <div class="container">
            <!-- About Section Heading -->
            <h2 class="text-center text-uppercase text-white fw-bold">About FASTER</h2>
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
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // PDF viewer functionality
        document.querySelectorAll('.showPdfButton').forEach(button => {
            button.addEventListener('click', function() {
                const target = this.getAttribute('data-target');
                const pdfDiv = document.getElementById(target + '-pdf');
                
                // Hide all other PDF viewers first
                document.querySelectorAll('.pdf-viewer').forEach(viewer => {
                    if (viewer.id !== target + '-pdf') {
                        viewer.style.display = 'none';
                    }
                });
                
                // Toggle current PDF viewer
                if (pdfDiv.style.display === 'none' || pdfDiv.style.display === '') {
                    pdfDiv.style.display = 'block';
                    this.innerHTML = '<i class="fas fa-eye-slash"></i> Tutup ' + target.charAt(0).toUpperCase() + target.slice(1);
                    
                    // Smooth scroll to PDF viewer
                    setTimeout(() => {
                        pdfDiv.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }, 100);
                } else {
                    pdfDiv.style.display = 'none';
                    this.innerHTML = '<i class="fas fa-eye"></i> Lihat ' + target.charAt(0).toUpperCase() + target.slice(1);
                }
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

        document.querySelectorAll('.detail-card').forEach(card => {
            observer.observe(card);
        });
    </script>
</body>
</html>