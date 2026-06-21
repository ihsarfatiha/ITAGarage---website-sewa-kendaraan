@extends('layout.app')

@section('title', 'Katalog Motor - ITA Garage')

@section('content')
<section class="py-5 bg-light position-relative overflow-hidden">
    <!-- Decorator -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 10% 10%, rgba(67, 97, 238, 0.05) 0%, transparent 50%); pointer-events: none;"></div>

    <div class="container position-relative">
        <div class="text-center mb-5">
            <h1 class="fw-bold display-5">Katalog <span class="text-gradient" style="background: linear-gradient(135deg, #0d6efd, #0d6efd);">Motor</span></h1>
            <p class="text-muted lead">Temukan kendaran yang pas untuk gaya Anda</p>
        </div>
        
        <!-- Filter Section -->
        <div class="glass p-4 rounded-4 shadow-sm mb-5">
            <form action="{{ url('/catalog') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold text-uppercase">Brand</label>
                        <select name="brand" class="form-select border-0 bg-white shadow-sm">
                            <option value="">Semua Brand</option>
                            <option value="Honda">Honda</option>
                            <option value="Yamaha">Yamaha</option>
                            <option value="Suzuki">Suzuki</option>
                            <option value="Kawasaki">Kawasaki</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold text-uppercase">Tipe</label>
                        <select name="type" class="form-select border-0 bg-white shadow-sm">
                            <option value="">Semua Tipe</option>
                            <option value="matic">Matic</option>
                            <option value="manual">Manual</option>
                            <option value="sport">Sport</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted fw-bold text-uppercase">Status</label>
                        <select name="status" class="form-select border-0 bg-white shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="available">Tersedia</option>
                            <option value="rented">Disewa</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 h-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="fas fa-filter"></i> Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Motor Grid -->
        <div class="row g-4">
            @forelse($motors ?? [] as $motor)
            <div class="col-md-4 col-lg-3">
                <div class="card card-product h-100 border-0 shadow-sm">
                    <div class="position-relative overflow-hidden rounded-top">
                        <img src="{{ asset('storage/' . $motor->image) }}" class="card-img-top" alt="{{ $motor->model }}">
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge badge-custom {{ $motor->status == 'available' ? 'badge-available' : 'badge-rented' }}">
                                {{ ucfirst($motor->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column p-4">
                        <div class="mb-2">
                            <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">{{ $motor->brand }}</small>
                            <h5 class="card-title fw-bold mb-0">{{ $motor->model }}</h5>
                        </div>
                        
                        <div class="d-flex gap-3 mb-4 text-muted small">
                            <span class="d-flex align-items-center gap-1"><i class="fas fa-cog text-primary"></i> {{ ucfirst($motor->type) }}</span>
                            <span class="d-flex align-items-center gap-1"><i class="fas fa-calendar text-primary"></i> {{ $motor->year }}</span>
                            <span class="d-flex align-items-center gap-1"><i class="fas fa-palette text-primary"></i> {{ $motor->color }}</span>
                        </div>
                        
                        <div class="mt-auto border-top pt-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <small class="text-muted d-block" style="font-size: 0.7rem">Sewa/Hari</small>
                                    <span class="fw-bold text-dark">Rp {{ number_format($motor->daily_rent_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block" style="font-size: 0.7rem">Harga Beli</small>
                                    <span class="fw-bold text-success">Rp {{ number_format($motor->purchase_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <a href="{{ url('/detail/' . $motor->id) }}" class="btn btn-outline-primary w-100 rounded-pill">
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
                        <i class="fas fa-search fa-3x text-muted opacity-25"></i>
                    </div>
                    <h4 class="fw-bold text-muted">Tidak ada motor ditemukan</h4>
                    <p class="text-muted">Coba ubah filter pencarian Anda untuk menemukan hasil.</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(isset($motors) && $motors->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $motors->links() }}
        </div>
        @endif
    </div>
</section>
@endsection