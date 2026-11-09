@extends('app.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Laporan</h1>
                <p class="text-muted mb-0">Laporan penjualan dan stok Toko Terang Abadi.</p>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <a href="{{ route('laporan.penjualan') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="text-primary fs-3 mb-3">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <h2 class="h5 fw-bold text-dark">Laporan Penjualan</h2>
                            <p class="text-muted mb-0">Ringkasan transaksi dan omzet penjualan.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-6">
                <a href="{{ route('laporan.stok') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="text-primary fs-3 mb-3">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <h2 class="h5 fw-bold text-dark">Laporan Stok</h2>
                            <p class="text-muted mb-0">Kondisi stok dan nilai persediaan produk.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
