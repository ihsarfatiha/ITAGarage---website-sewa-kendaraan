@extends('layout.app')

@section('title', 'Beranda - ITA Garage')

@section('content')
<!-- Hero Section -->
<section class="hero-section d-flex align-items-center">
    <div class="hero-bg-shape"></div>
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <span class="badge badge-custom text-primary bg-primary-subtle mb-3">
                    <i class="fas fa-star me-2"></i>Platform Sewa Kendaraan #1
                </span>
                <h1 class="display-3 fw-bold mb-4 text-dark">
                    Jelajahi Kota dengan <br>
                    <span class="text-gradient">Kendaraan Impian</span>
                </h1>
                <p class="lead mb-5 text-muted">
                    Temukan dan sewa kendaraan berkualitas dengan harga terbaik. 
                    Proses cepat, aman, dan terpercaya untuk perjalanan Anda.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ url('/catalog') }}" class="btn btn-primary btn-lg px-4 py-3 align-items-center d-flex gap-2">
                        <i class="fas fa-search"></i> Lihat Katalog
                    </a>
                    <a href="#features" class="btn btn-outline-primary btn-lg px-4 py-3">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
                <div class="mt-5 d-flex gap-5 text-center text-lg-start">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">500+</h4>
                        <small class="text-muted">Unit Motor</small>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">10k+</h4>
                        <small class="text-muted">Pelanggan Puas</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 position-relative">
                <div class="glass p-4 rounded-4 shadow-lg position-relative" style="transform: rotate(2deg);">
                   <img src="https://images.unsplash.com/photo-1558981403-c5f9899a28bc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80" alt="Motor Hero" class="img-fluid rounded-3 shadow-sm">
                </div>
                <!-- Floating Card -->
                <div class="glass p-3 rounded-3 shadow position-absolute bottom-0 start-0 translate-middle-x mb-4 ms-5 d-none d-md-block animate-float">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Verified Unit</h6>
                            <small class="text-success">100% Quality Check</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="text-primary fw-bold text-uppercase tracking-wider">Keunggulan</span>
            <h2 class="fw-bold mt-2">Mengapa Memilih Kami?</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 p-4 border-0 shadow-sm hover-scale text-center">
                    <div class="mb-4 d-inline-block p-3 rounded-circle bg-primary-subtle text-primary mx-auto">
                        <i class="fas fa-shield-alt fa-2x"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Aman & Terpercaya</h4>
                    <p class="text-muted">Setiap unit motor melewati proses inspeksi ketat untuk memastikan keselamatan dan kenyamanan Anda selama berkendara.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4 border-0 shadow-sm hover-scale text-center">
                    <div class="mb-4 d-inline-block p-3 rounded-circle bg-success-subtle text-success mx-auto">
                        <i class="fas fa-wallet fa-2x"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Harga Transparan</h4>
                    <p class="text-muted">Tidak ada biaya tersembunyi. Harga yang Anda lihat adalah harga yang Anda bayar, kompetitif dan sesuai budget.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4 border-0 shadow-sm hover-scale text-center">
                    <div class="mb-4 d-inline-block p-3 rounded-circle bg-info-subtle text-info mx-auto">
                        <i class="fas fa-headset fa-2x"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Layanan 24/7</h4>
                    <p class="text-muted">Tim support kami siap membantu Anda kapanpun dan dimanapun. Perjalanan Anda adalah prioritas kami.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Vehicles Section -->
<section class="py-5 bg-white position-relative">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Armada <span class="text-primary">Terpopuler</span></h2>
            <p class="text-muted">Pilihan kendaraan favorit pelanggan kami</p>
            
            <!-- Category Tabs -->
            <ul class="nav nav-pills justify-content-center gap-3 mt-4" id="vehicle-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-4 shadow-sm" id="pills-motor-tab" data-bs-toggle="pill" data-bs-target="#pills-motor" type="button" role="tab">
                        <i class="fas fa-motorcycle me-2"></i>Motor
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4 shadow-sm" id="pills-car-tab" data-bs-toggle="pill" data-bs-target="#pills-car" type="button" role="tab">
                        <i class="fas fa-car me-2"></i>Mobil
                    </button>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="pills-tabContent">
            <!-- Motors Tab -->
            <div class="tab-pane fade show active" id="pills-motor" role="tabpanel">
                <div class="row g-4">
                    @forelse($popularMotors as $motor)
                    <div class="col-md-4 col-lg-4">
                        <div class="card card-product h-100 border-0 shadow-sm">
                            <div class="position-relative overflow-hidden rounded-top">
                                <img src="{{ asset('storage/' . $motor->image) }}" class="card-img-top" alt="{{ $motor->model }}">
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge badge-custom {{ $motor->status == 'available' ? 'badge-available' : 'badge-rented' }}">
                                        {{ ucfirst($motor->status) }}
                                    </span>
                                </div>
                                <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark text-blue">
                                    <h5 class="mb-0 fw-bold">{{ $motor->model }}</h5>
                                    <small>{{ $motor->brand }}</small>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <small class="text-muted d-block">Sewa per Hari</small>
                                        <span class="fw-bold text-primary h5 mb-0">Rp {{ number_format($motor->daily_rent_price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block">Tahun</small>
                                        <span class="fw-bold">{{ $motor->year }}</span>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <a href="{{ url('/detail/' . $motor->id) }}" class="btn btn-outline-primary rounded-pill">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center text-muted">
                        <p>Belum ada data motor.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Cars Tab -->
            <div class="tab-pane fade" id="pills-car" role="tabpanel">
                <div class="row g-4">
                    @forelse($popularCars ?? [] as $car)
                    <div class="col-md-4 col-lg-4">
                        <div class="card card-product h-100 border-0 shadow-sm">
                            <div class="position-relative overflow-hidden rounded-top">
                                <img src="{{ asset('storage/' . $car->image) }}" class="card-img-top" alt="{{ $car->model }}">
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge badge-custom {{ $car->status == 'available' ? 'badge-available' : 'badge-rented' }}">
                                        {{ ucfirst($car->status) }}
                                    </span>
                                </div>
                                <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark text-blue">
                                    <h5 class="mb-0 fw-bold">{{ $car->model }}</h5>
                                    <small>{{ $car->brand }}</small>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <small class="text-muted d-block">Sewa per Hari</small>
                                        <span class="fw-bold text-danger h5 mb-0">Rp {{ number_format($car->daily_rent_price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block">Kapasitas</small>
                                        <span class="fw-bold text-dark"><i class="fas fa-users text-danger me-1"></i>{{ $car->seats }}</span>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <a href="{{ url('/cars/' . $car->id) }}" class="btn btn-outline-danger rounded-pill">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center text-muted py-5">
                        <i class="fas fa-car fa-3x mb-3 opacity-25"></i>
                        <p>Belum ada data mobil yang tersedia saat ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
@endsection
