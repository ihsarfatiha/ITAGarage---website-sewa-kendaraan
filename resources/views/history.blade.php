@extends('layout.app')

@section('title', 'Riwayat Transaksi - ITA Garage')

@section('content')
<section class="py-5">
    <div class="container">
        <h2 class="mb-4">
            <i class="fas fa-history"></i> Riwayat Transaksi
        </h2>

    <div class="row g-5">
        <div class="col-12">
            <!-- Filter Tabs -->
            <ul class="nav nav-tabs mb-4 px-3" id="transactionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
                        <i class="fas fa-list me-2"></i> Semua
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="rent-tab" data-bs-toggle="tab" data-bs-target="#rent" type="button">
                        <i class="fas fa-calendar-check me-2"></i> Peminjaman
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="purchase-tab" data-bs-toggle="tab" data-bs-target="#purchase" type="button">
                        <i class="fas fa-shopping-cart me-2"></i> Pembelian
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="transactionTabContent">
                
                @php
                    // Helper function to render transaction card
                    $renderCard = function($transaction) {
                        return view('components.transaction_card', ['transaction' => $transaction])->render();
                    };
                @endphp

                <!-- All Transactions -->
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    @forelse($transactions ?? [] as $transaction)
                        @include('components.transaction_item', ['transaction' => $transaction])
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-50"></i>
                            <h5 class="text-muted">Belum ada transaksi</h5>
                        </div>
                    @endforelse
                </div>

                <!-- Rent Transactions -->
                <div class="tab-pane fade" id="rent" role="tabpanel">
                    @forelse($rentTransactions ?? [] as $transaction)
                        @include('components.transaction_item', ['transaction' => $transaction])
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3 opacity-50"></i>
                            <h5 class="text-muted">Belum ada peminjaman</h5>
                        </div>
                    @endforelse
                </div>

                <!-- Purchase Transactions -->
                <div class="tab-pane fade" id="purchase" role="tabpanel">
                    @forelse($purchaseTransactions ?? [] as $transaction)
                        @include('components.transaction_item', ['transaction' => $transaction])
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-basket fa-3x text-muted mb-3 opacity-50"></i>
                            <h5 class="text-muted">Belum ada pembelian</h5>
                        </div>
                    @endforelse
                </div>
            </div>
            
        </div>
    </div>
</section>

{{-- Reusable Transaction Item Logic --}}
{{-- Since we cannot easily make a component file in one go, I will inline the card logic here but ideally it should be a component --}}
{{-- But wait, I can just write the whole logic inline in loops if I don't create a separate file. --}}
{{-- Or simpler: use a loop for each tab but copy paste logic? No, better to make a partial if possible. --}}
{{-- I'll use a loop macro or just repeat code for clarity in this single file edit. --}}
@endsection