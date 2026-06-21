@extends('layout.app')

@section('title', 'Detail Motor - ITA Garage')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/catalog') }}" class="text-decoration-none">Katalog</a></li>
                <li class="breadcrumb-item active text-muted">Detail Motor</li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Image Section -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg overflow-hidden rounded-4 sticky-top" style="top: 100px; z-index: 1;">
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $motor->image) }}"
                             class="card-img-top w-100 h-100 object-fit-cover" 
                             alt="{{ $motor->model ?? 'Motor' }}"
                             style="min-height: 400px;">
                        <span class="position-absolute top-0 end-0 m-4 badge {{ $motor->status == 'available' ? 'badge-available' : ($motor->status == 'rented' ? 'badge-rented' : 'badge-sold') }} badge-custom shadow-sm fs-6">
                            {{ ucfirst($motor->status ?? 'available') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="col-lg-6">
                <div class="ps-lg-4">
                    <h5 class="text-primary fw-bold text-uppercase letter-spacing-2 mb-2">{{ $motor->brand ?? 'Brand' }}</h5>
                    <h1 class="fw-bold display-6 mb-3">{{ $motor->model ?? 'Model Name' }}</h1>
                    
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="d-flex gap-1 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-muted small">(4.8/5.0 dari 24 review)</span>
                    </div>

                    <p class="text-muted mb-5 leading-relaxed">
                        {{ $motor->description ?? 'Motor ini adalah pilihan tepat untuk mobilitas Anda. Dengan kondisi prima dan perawatan rutin, kami menjamin kenyamanan berkendara Anda.' }}
                    </p>

                    <!-- Specs Grid -->
                    <div class="row g-3 mb-5">
                        <div class="col-6">
                            <div class="p-3 bg-white rounded-3 shadow-sm border">
                                <small class="text-muted d-block mb-1">Tipe Transmisi</small>
                                <div class="fw-bold d-flex align-items-center gap-2">
                                    <i class="fas fa-cog text-primary"></i> {{ ucfirst($motor->type ?? '-') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-white rounded-3 shadow-sm border">
                                <small class="text-muted d-block mb-1">Tahun Produksi</small>
                                <div class="fw-bold d-flex align-items-center gap-2">
                                    <i class="fas fa-calendar text-primary"></i> {{ $motor->year ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-white rounded-3 shadow-sm border">
                                <small class="text-muted d-block mb-1">Warna</small>
                                <div class="fw-bold d-flex align-items-center gap-2">
                                    <i class="fas fa-palette text-primary"></i> {{ $motor->color ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-white rounded-3 shadow-sm border">
                                <small class="text-muted d-block mb-1">Unit Tersedia</small>
                                <div class="fw-bold d-flex align-items-center gap-2">
                                    <i class="fas fa-boxes text-primary"></i> {{ $motor->stock ?? '0' }} Unit
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Card -->
                    <div class="glass p-4 rounded-4 shadow-md mb-4 border-primary border-opacity-10">
                        <div class="row align-items-center">
                            <div class="col-6 border-end">
                                <small class="text-muted text-uppercase fw-bold">Harga Sewa</small>
                                <h3 class="text-primary fw-bold mt-1 mb-0">
                                    Rp {{ number_format($motor->daily_rent_price ?? 0, 0, ',', '.') }}
                                    <span class="fs-6 text-muted fw-normal">/hari</span>
                                </h3>
                            </div>
                            <div class="col-6 ps-4">
                                <small class="text-muted text-uppercase fw-bold">Harga Beli</small>
                                <h4 class="text-success fw-bold mt-1 mb-0">
                                    Rp {{ number_format($motor->purchase_price ?? 0, 0, ',', '.') }}
                                </h4>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    @auth
                    @if(auth()->user()->role !== 'admin')
                    <div class="d-grid gap-3">
                        <button type="button" class="btn btn-primary btn-lg shadow-lg" data-bs-toggle="modal" data-bs-target="#rentModal">
                            <i class="fas fa-key me-2"></i> Sewa Sekarang
                        </button>
                        <button type="button" class="btn btn-outline-success btn-lg" data-bs-toggle="modal" data-bs-target="#purchaseModal">
                            <i class="fas fa-shopping-bag me-2"></i> Beli Unit Ini
                        </button>
                    </div>
                    @endif
                    @else
                    <div class="alert alert-warning border-0 shadow-sm rounded-3 d-flex align-items-center gap-3" role="alert">
                        <i class="fas fa-lock fa-2x opacity-50"></i>
                        <div>
                            <strong>Ingin menyewa motor ini?</strong>
                            <div class="small">Silakan <a href="{{ url('/login') }}" class="alert-link">Login</a> atau <a href="{{ url('/register') }}" class="alert-link">Daftar</a> terlebih dahulu.</div>
                        </div>
                    </div>
                    @endauth
                    <!-- Reviews Section -->
                    <div class="mt-5 border-top pt-5">
                        <h4 class="fw-bold mb-4">Ulasan Pelanggan</h4>

                        @if(session('success'))
                            <div class="alert alert-success border-0 shadow-sm mb-4">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            </div>
                        @endif

                        @auth
                        @php
                            $hasTransaction = \App\Models\Transaction::where('user_id', auth()->id())
                                ->where('vehicle_type', 'motor')
                                ->where('motor_id', $motor->id)
                                ->where('status', 'completed')
                                ->exists();
                        @endphp

                        @if($hasTransaction)
                        <div class="card border-0 shadow-sm mb-5 bg-light">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3">Bagaimana pengalaman Anda?</h5>
                                <form action="{{ url('/reviews') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="reviewable_type" value="App\Models\Motor">
                                    <input type="hidden" name="reviewable_id" value="{{ $motor->id }}">
                                    
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small fw-bold">Rating</label>
                                            <select name="rating" class="form-select border-0" required>
                                                <option value="5">⭐⭐⭐⭐⭐ (Sempurna)</option>
                                                <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                                                <option value="3">⭐⭐⭐ (Cukup)</option>
                                                <option value="2">⭐⭐ (Kurang)</option>
                                                <option value="1">⭐ (Buruk)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label text-muted small fw-bold">Ulasan Anda</label>
                                            <textarea name="comment" class="form-control border-0" rows="3" placeholder="Ceritakan pengalaman Anda menggunakan motor ini..." required></textarea>
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="submit" class="btn btn-primary px-4 rounded-pill">
                                                Kirim Ulasan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                        @endauth
                        
                        <!-- Review Summary -->
                        <div class="d-flex align-items-center gap-4 mb-5 p-4 bg-white rounded-4 shadow-sm border">
                            <div class="text-center">
                                <h1 class="fw-bold display-4 text-warning mb-0">{{ number_format($motor->average_rating, 1) }}</h1>
                                <div class="text-warning small">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= round($motor->average_rating) ? '' : 'text-muted opacity-25' }}"></i>
                                    @endfor
                                </div>
                                <small class="text-muted">{{ $motor->total_reviews }} Ulasan</small>
                            </div>
                            <div class="flex-grow-1 border-start ps-4">
                                @foreach([5,4,3,2,1] as $star)
                                @php 
                                    $count = $motor->reviews->where('rating', $star)->count();
                                    $percent = $motor->total_reviews > 0 ? ($count / $motor->total_reviews) * 100 : 0;
                                @endphp
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <small class="text-muted fw-bold" style="width: 12px">{{ $star }}</small>
                                    <i class="fas fa-star text-warning fa-xs"></i>
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar bg-warning" style="width:{{ $percent }}%"></div>
                                    </div>
                                    <small class="text-muted" style="width: 30px">{{ $count }}</small>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Review List -->
                        <div class="d-flex flex-column gap-4">
                            @forelse($motor->reviews as $review)
                            <div class="d-flex gap-3">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                                    <span class="fw-bold text-muted">{{ substr($review->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h6 class="fw-bold mb-0">{{ $review->user->name }}</h6>
                                        <small class="text-muted">&bull; {{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="text-warning small mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-muted opacity-25' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="text-muted small mb-0">{{ $review->comment }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-4 text-muted">
                                <p>Belum ada ulasan untuk motor ini.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rent Modal -->
<div class="modal fade" id="rentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Form Peminjaman Motor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('/transaction/rent') }}" method="POST">
                @csrf
                <input type="hidden" name="motor_id" value="{{ $motor->id ?? '' }}">
                <div class="modal-body pt-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small text-muted fw-bold">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted fw-bold">Tanggal Selesai</label>
                            <input type="date" name="end_date" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted fw-bold">Catatan (Opsional)</label>
                            <textarea name="notes" class="form-control bg-light border-0" rows="3" placeholder="Contoh: Helm 2 buah, Jas Hujan"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Konfirmasi Sewa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Purchase Modal -->
<div class="modal fade" id="purchaseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Form Pembelian Motor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('/transaction/purchase') }}" method="POST">
                @csrf
                <input type="hidden" name="motor_id" value="{{ $motor->id ?? '' }}">
                <div class="modal-body pt-4">
                    <div class="alert alert-warning border-0 bg-warning-subtle text-warning-emphasis rounded-3 mb-4">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Pastikan data Anda sudah benar sebelum melakukan pembelian
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small text-muted fw-bold">Metode Pembayaran</label>
                        <select name="payment_method" class="form-select bg-light border-0" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="cash">Tunai (Cash)</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="credit_card">Kartu Kredit / Debit</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small text-muted fw-bold">Catatan (Opsional)</label>
                        <textarea name="notes" class="form-control bg-light border-0" rows="3"></textarea>
                    </div>

                    <div class="p-3 bg-success-subtle rounded-3 border border-success-subtle">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-success-emphasis fw-bold">Total Pembayaran</span>
                            <span class="h4 mb-0 text-success fw-bold">Rp {{ number_format($motor->purchase_price ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4">Beli Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection