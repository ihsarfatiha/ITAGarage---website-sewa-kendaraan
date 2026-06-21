@extends('layout.app')

@section('title', 'Katalog Mobil - ITA Garage')

@section('content')
<section class="py-5 bg-light position-relative overflow-hidden">
    <!-- Decorator -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 90% 10%, rgba(239, 71, 111, 0.05) 0%, transparent 50%); pointer-events: none;"></div>

    <div class="container position-relative">
        <div class="text-center mb-5">
            <h1 class="fw-bold display-5">Katalog <span class="text-gradient" style="background: linear-gradient(135deg, #0d6efd, #0d6efd);">Mobil</span></h1>
            <p class="text-muted lead">Kenyamanan perjalanan keluarga dan bisnis Anda</p>
        </div>
        
        <!-- Filter Section -->
        <div class="glass p-4 rounded-4 shadow-sm mb-5">
            <form action="{{ url('/cars') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold text-uppercase">Brand</label>
                        <select name="brand" class="form-select border-0 bg-white shadow-sm">
                            <option value="">Semua Brand</option>
                            <option value="Toyota">Toyota</option>
                            <option value="Honda">Honda</option>
                            <option value="Mitsubishi">Mitsubishi</option>
                            <option value="Daihatsu">Daihatsu</option>
                            <option value="Suzuki">Suzuki</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold text-uppercase">Transmisi</label>
                        <select name="transmission" class="form-select border-0 bg-white shadow-sm">
                            <option value="">Semua</option>
                            <option value="automatic">Automatic</option>
                            <option value="manual">Manual</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold text-uppercase">Kapasitas</label>
                        <select name="seats" class="form-select border-0 bg-white shadow-sm">
                            <option value="">Semua</option>
                            <option value="2">2 Penumpang</option>
                            <option value="4">4-5 Penumpang</option>
                            <option value="7">7+ Penumpang</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 h-100 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #0d6efd, #0d6efd);">
                            <i class="fas fa-filter"></i> Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Car Grid -->
        <div class="row g-4">
            @forelse($cars ?? [] as $car)
            <div class="col-md-4 col-lg-3">
                <div class="card card-product h-100 border-0 shadow-sm">
                    <div class="position-relative overflow-hidden rounded-top">
                        <img src="{{ asset('storage/' . $car->image) }}" class="card-img-top" alt="{{ $car->model }}">
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge badge-custom {{ $car->status == 'available' ? 'badge-available' : 'badge-rented' }}">
                                {{ ucfirst($car->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column p-4">
                        <div class="mb-2">
                            <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">{{ $car->brand }}</small>
                            <h5 class="card-title fw-bold mb-0">{{ $car->model }}</h5>
                        </div>
                        
                        <div class="d-flex gap-3 mb-4 text-muted small">
                            <span class="d-flex align-items-center gap-1" title="Transmisi"><i class="fas fa-cog text-danger"></i> {{ ucfirst($car->transmission) }}</span>
                            <span class="d-flex align-items-center gap-1" title="Kapasitas"><i class="fas fa-users text-danger"></i> {{ $car->seats }}</span>
                            <span class="d-flex align-items-center gap-1" title="Tahun"><i class="fas fa-calendar text-danger"></i> {{ $car->year }}</span>
                        </div>
                        
                        <div class="mt-auto border-top pt-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <small class="text-muted d-block" style="font-size: 0.7rem">Sewa/Hari</small>
                                    <span class="fw-bold text-dark">Rp {{ number_format($car->daily_rent_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block" style="font-size: 0.7rem">Body</small>
                                    <span class="fw-bold text-danger">{{ ucfirst($car->body_type) }}</span>
                                </div>
                            </div>
                            <a href="{{ url('/cars/' . $car->id) }}" class="btn btn-outline-primary w-100 rounded-pill" style="border-color: #0d6efd; color: #0d6efd;">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 py-5">
                <div class="text-center">
                    <div class="mb-4">
                        <i class="fas fa-car fa-3x text-muted opacity-25"></i>
                    </div>
                    <h4 class="fw-bold text-muted">Tidak ada mobil ditemukan</h4>
                    <p class="text-muted">Coba ubah filter pencarian Anda untuk menemukan hasil.</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(isset($cars) && $cars->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $cars->links() }}
        </div>
        @endif
    </div>
</section>
@endsection
