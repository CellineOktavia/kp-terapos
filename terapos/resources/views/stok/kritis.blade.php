@extends('app.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Stok Kritis</h2>
                <p class="text-muted mb-0">Produk dengan stok sama dengan atau di bawah batas minimum.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('stok.kritis') }}" class="row g-3 mb-4">
            <div class="col-md-9">
                <label for="critical-search" class="form-label">Cari produk</label>
                <input id="critical-search" type="search" name="search" class="form-control"
                    value="{{ request('search') }}" placeholder="Kode, barcode, atau nama produk">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary w-100">Cari</button>
            </div>
        </form>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                {{ $products->total() }} produk stok kritis
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Minimum</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $product->kode_produk }}</td>
                                <td>{{ $product->nama_produk }}</td>
                                <td>{{ $product->category?->nama_kategori ?? '-' }}</td>
                                <td>{{ $product->satuan }}</td>
                                <td>{{ $product->stok }}</td>
                                <td>{{ $product->stok_minimum }}</td>
                                <td>
                                    @if ((int) $product->stok === 0)
                                        <span class="badge text-bg-danger">HABIS</span>
                                    @else
                                        <span class="badge text-bg-warning">STOK MENIPIS</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Tidak ada stok kritis.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $products->links() }}</div>
    </div>
@endsection
