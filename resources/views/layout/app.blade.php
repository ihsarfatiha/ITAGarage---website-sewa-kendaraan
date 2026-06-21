<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ITA Garage - Sewa & Beli Kendaraan')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS (CDN for grid/utilities) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-car-side text-primary me-2"></i>ITA <span class="text-primary">Garage</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-lg-3 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('catalog*') || request()->is('cars*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                            Katalog
                        </a>
                        <ul class="dropdown-menu shadow-sm border-0">
                            <li><a class="dropdown-item" href="{{ url('/catalog') }}"><i class="fas fa-motorcycle me-2 text-muted"></i>Motor</a></li>
                            <li><a class="dropdown-item" href="{{ url('/cars') }}"><i class="fas fa-car me-2 text-muted"></i>Mobil</a></li>
                        </ul>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('history*') ? 'active' : '' }}"
                                href="{{ url('/history') }}">Riwayat</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button"
                                data-bs-toggle="dropdown">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2 text-muted"></i>Profil</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ url('/logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-primary px-4" href="{{ url('/login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary px-4 text-white" href="{{ url('/register') }}">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main style="min-height: 80vh; padding-top: 50px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-modern">
        <div class="footer-wave">
            <svg viewBox="0 0 1440 100" fill="none" class="w-100" preserveAspectRatio="none">
                <path
                    d="M0 50L48 56.7C96 63.3 192 76.7 288 80C384 83.3 480 76.7 576 70C672 63.3 768 56.7 864 53.3C960 50 1056 50 1152 56.7C1248 63.3 1344 76.7 1392 83.3L1440 90V0H1392C1344 0 1248 0 1152 0C1056 0 960 0 864 0C768 0 672 0 576 0C480 0 384 0 288 0C192 0 96 0 48 0H0V50Z"
                    fill="#f8f9fa" />
            </svg>
        </div>
        <div class="container position-relative">
            <div class="row g-4">
                <div class="col-lg-4 mb-3">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-motorcycle text-primary fa-2x me-2"></i>
                        <span class="h4 fw-bold mb-0">ITA <span class="text-primary">Garage</span></span>
                    </div>
                    <p class="text-muted">Platform penyewaan dan jual beli kendaraan terpercaya dengan layanan premium dan
                        harga bersaing.</p>
                    <div class="mt-4">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6 mb-3">
                    <h5 class="mb-3">Navigasi</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/') }}"
                                class="text-decoration-none text-muted hover-primary">Beranda</a></li>
                        <li class="mb-2"><a href="{{ url('/catalog') }}"
                                class="text-decoration-none text-muted hover-primary">Katalog</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted hover-primary">Tentang
                                Kami</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted hover-primary">Kontak</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-2 col-6 mb-3">
                    <h5 class="mb-3">Layanan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted hover-primary">Sewa
                                Harian</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted hover-primary">Sewa
                                Mingguan</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted hover-primary">Beli
                                Motor</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted hover-primary">Syarat &
                                Ketentuan</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-3">
                    <h5 class="mb-3">Hubungi Kami</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center text-muted">
                            <i class="fas fa-map-marker-alt text-primary me-3"></i>
                            Jl. Raya Motor No. 123, Jakarta Selatan
                        </li>
                        <li class="mb-3 d-flex align-items-center text-muted">
                            <i class="fas fa-phone text-primary me-3"></i>
                            (021) 1234-5678
                        </li>
                        <li class="mb-3 d-flex align-items-center text-muted">
                            <i class="fas fa-envelope text-primary me-3"></i>
                            hello@ITAGarage.com
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 text-muted opacity-25">
            <div class="text-center text-muted small">
                &copy; {{ date('Y') }} ITA Garage. All rights reserved. Made with <i class="fas fa-heart text-danger"></i>
                for Riders.
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>