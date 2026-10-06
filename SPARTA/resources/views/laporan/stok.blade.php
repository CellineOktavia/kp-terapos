@extends('app.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Laporan Stok</h2>
                <p class="text-muted mb-0">Kondisi persediaan produk saat ini.</p>
            </div>
            <a href="{{ route('laporan.stok.pdf', request()->query()) }}" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i> Cetak / Download PDF
            </a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100"><div class="card-body">
                    <div class="text-muted">Total Produk</div>
                    <div class="h4 fw-bold mb-0">{{ number_format($summary['products'], 0, ',', '.') }}</div>
                </div></div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100"><div class="card-body">
                    <div class="text-muted">Stok Kritis</div>
                    <div class="h4 fw-bold text-danger mb-0">{{ number_format($summary['critical'], 0, ',', '.') }}</div>
                </div></div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100"><div class="card-body">
                    <div class="text-muted">Nilai Persediaan (Harga Beli)</div>
                    <div class="h4 fw-bold mb-0">Rp {{ number_format($summary['inventoryValue'], 0, ',', '.') }}</div>
                    <small class="text-muted">Estimasi berdasarkan harga beli produk saat ini.</small>
                </div></div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('laporan.stok') }}" class="row g-3 align-items-end">
                    <div class="col-lg-5 col-md-6">
                        <label for="stock-search" class="form-label">Cari produk</label>
                        <input id="stock-search" type="search" name="search" class="form-control"
                            value="{{ $filters['search'] ?? '' }}" placeholder="Kode, barcode, atau nama">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select id="category_id" name="category_id" class="form-select">
                            <option value="">Semua kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    @selected(($filters['category_id'] ?? '') == $category->id)>
                                    {{ $category->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select">
                            @foreach (['all' => 'Semua', 'safe' => 'Aman', 'low' => 'Menipis', 'out' => 'Habis'] as $key => $label)
                                <option value="{{ $key }}" @selected(($filters['status'] ?? 'all') === $key)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <button type="submit" class="btn btn-outline-primary w-100">Terapkan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr>
                        <th>Kode</th><th>Barcode</th><th>Nama Produk</th><th>Kategori</th>
                        <th>Satuan</th><th>Stok</th><th>Minimum</th><th>Harga Beli</th>
                        <th>Harga Jual</th><th>Status</th>
                    </tr></thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $product->kode_produk }}</td>
                                <td>{{ $product->barcode ?? '-' }}</td>
                                <td>{{ $product->nama_produk }}</td>
                                <td>{{ $product->category?->nama_kategori ?? '-' }}</td>
                                <td>{{ $product->satuan }}</td>
                                <td>{{ $product->stok }}</td>
                                <td>{{ $product->stok_minimum }}</td>
                                <td>Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                                <td>
                                    @if ((int) $product->stok === 0)
                                        <span class="badge text-bg-danger">HABIS</span>
                                    @elseif ($product->stok <= $product->stok_minimum)
                                        <span class="badge text-bg-warning">MENIPIS</span>
                                    @else
                                        <span class="badge text-bg-success">AMAN</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center text-muted py-4">
                                Tidak ada produk untuk filter ini.
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $products->links() }}</div>
    </div>
@endsection
