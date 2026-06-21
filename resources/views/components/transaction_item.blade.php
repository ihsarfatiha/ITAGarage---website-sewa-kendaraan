@php
    $motor = $transaction->motor ?? $transaction->car;
    $image = $motor ? ($transaction->motor ? $motor->image : $motor->image) : 'default.jpg';
    // Fix logic: check type directly if relationships work polymorphically or just check null
    // Assuming motor relation exists for now based on controller code
    // Actually controller uses ->with(['motor']) only? Need to check if it supports Car. 
    // Transaction model has `getVehicleAttribute`.
    $vehicle = $transaction->vehicle;
    $image = $vehicle ? $vehicle->image : '';
    $name = $vehicle ? "{$vehicle->brand} {$vehicle->model}" : 'Kendaraan dihapus';
@endphp
<div class="card mb-3 border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="row g-0 align-items-center">
            <div class="col-md-3">
                <div class="h-100 position-relative">
                   <img src="{{ asset('storage/' . $image) }}" 
                         class="img-fluid w-100 h-100 object-fit-cover" 
                         alt="{{ $name }}"
                         style="min-height: 160px; max-height: 180px;">
                    <div class="position-absolute top-0 start-0 p-2">
                        <span class="badge bg-white text-dark shadow-sm">
                            {{ $transaction->transaction_type == 'rent' ? 'Peminjaman' : 'Pembelian' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 p-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="text-xs fw-bold text-uppercase text-muted letter-spacing-1">
                        #{{ $transaction->id }} 
                    </div>
                    <span class="badge rounded-pill bg-{{ 
                        $transaction->status == 'completed' ? 'success' : 
                        ($transaction->status == 'ongoing' ? 'primary' : 
                        ($transaction->status == 'cancelled' ? 'danger' : 'warning')) 
                    }} px-3">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
                
                <h5 class="card-title fw-bold mb-3">{{ $name }}</h5>
                
                <div class="d-flex flex-column gap-2 text-muted small">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-calendar text-primary" style="width: 20px"></i> 
                        {{ date('d M Y', strtotime($transaction->start_date)) }}
                        @if($transaction->transaction_type == 'rent')
                            <i class="fas fa-arrow-right mx-1" style="font-size: 0.8em"></i>
                            {{ date('d M Y', strtotime($transaction->end_date)) }}
                            <span class="fw-bold text-dark ms-1">({{ $transaction->duration_days }} hari)</span>
                        @endif
                    </div>
                    @if($transaction->notes)
                    <div class="d-flex align-items-start gap-2">
                        <i class="fas fa-sticky-note text-warning" style="width: 20px"></i> 
                        <span class="text-truncate">{{ $transaction->notes }}</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-3 p-4 bg-light h-100 d-flex flex-column justify-content-center border-start">
                <div class="mb-3">
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem">Total Harga</small>
                    <h5 class="text-primary fw-bold mb-0">
                        Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                    </h5>
                    @if($transaction->payment)
                         <small class="text-{{ $transaction->payment->payment_status == 'paid' ? 'success' : 'danger' }}">
                            {{ ucfirst($transaction->payment->payment_status) }}
                        </small>
                    @endif
                </div>

                <div class="d-grid gap-2">
                    @if($transaction->status == 'pending')
                    <div class="d-flex gap-2">
                        <form action="{{ url('/transaction/'.$transaction->id.'/pay') }}" method="POST" class="w-100">
                            @csrf
                            <button class="btn btn-success btn-sm w-100 rounded-pill">Bayar</button>
                        </form>
                        <form action="{{ url('/transaction/'.$transaction->id.'/cancel') }}" method="POST" class="w-100">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm w-100 rounded-pill">Batal</button>
                        </form>
                    </div>
                    @elseif($transaction->status == 'ongoing' && $transaction->transaction_type == 'rent')
                        <form action="{{ url('/transaction/'.$transaction->id.'/complete') }}" method="POST">
                            @csrf
                            <button class="btn btn-primary btn-sm w-100 rounded-pill">Selesaikan</button>
                        </form>
                    @endif
                    
                    <button class="btn btn-sm btn-outline-secondary w-100 rounded-pill" data-bs-toggle="modal" data-bs-target="#detailModal{{ $transaction->id }}">
                        Lihat Detail
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal{{ $transaction->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Detail Transaksi #{{ $transaction->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4">
                <div class="row g-4">
                    <div class="col-12 text-center">
                         <img src="{{ asset('storage/' . $image) }}" 
                             class="rounded-4 shadow-sm mb-3" 
                             alt="{{ $name }}"
                             style="width: 100%; height: 200px; object-fit: cover;">
                        <h4 class="fw-bold mb-1">{{ $name }}</h4>
                        <span class="badge bg-{{ 
                            $transaction->status == 'completed' ? 'success' : 
                            ($transaction->status == 'ongoing' ? 'primary' : 
                            ($transaction->status == 'cancelled' ? 'danger' : 'warning')) 
                        }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                    
                    <div class="col-12">
                        <div class="p-3 bg-light rounded-3">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="text-muted w-50">Tipe Transaksi</td>
                                    <td class="fw-bold">{{ $transaction->transaction_type == 'rent' ? 'Peminjaman' : 'Pembelian' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal Mulai</td>
                                    <td class="fw-bold">{{ date('d M Y', strtotime($transaction->start_date)) }}</td>
                                </tr>
                                @if($transaction->transaction_type == 'rent')
                                <tr>
                                    <td class="text-muted">Tanggal Selesai</td>
                                    <td class="fw-bold">{{ date('d M Y', strtotime($transaction->end_date)) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Durasi</td>
                                    <td class="fw-bold">{{ $transaction->duration_days }} hari</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="text-muted">Total Harga</td>
                                    <td class="fw-bold text-primary">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($transaction->payment)
                    <div class="col-12">
                         <h6 class="fw-bold mb-3">Informasi Pembayaran</h6>
                         <div class="p-3 border rounded-3">
                             <div class="d-flex justify-content-between align-items-center mb-2">
                                 <span class="text-muted">Metode</span>
                                 <span class="fw-bold">{{ ucfirst(str_replace('_', ' ', $transaction->payment->payment_method)) }}</span>
                             </div>
                             <div class="d-flex justify-content-between align-items-center">
                                 <span class="text-muted">Status</span>
                                 <span class="badge bg-{{ $transaction->payment->payment_status == 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($transaction->payment->payment_status) }}
                                </span>
                             </div>
                         </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                
                 @if($transaction->status == 'pending')
                    <form action="{{ url('/transaction/'.$transaction->id.'/cancel') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-danger rounded-pill px-4">Batal</button>
                    </form>
                    <form action="{{ url('/transaction/'.$transaction->id.'/pay') }}" method="POST">
                        @csrf
                        <button class="btn btn-success rounded-pill px-4">Bayar Sekarang</button>
                    </form>
                @elseif($transaction->status == 'ongoing' && $transaction->transaction_type == 'rent')
                    <form action="{{ url('/transaction/'.$transaction->id.'/complete') }}" method="POST">
                        @csrf
                        <button class="btn btn-primary rounded-pill px-4">Selesaikan Peminjaman</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
