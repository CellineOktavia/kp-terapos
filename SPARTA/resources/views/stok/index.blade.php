@extends('app.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Manajemen Stok</h2>
                <p class="text-muted mb-0">Pantau jumlah dan status stok seluruh produk.</p>
            </div>
            @if (Auth::user()->role === 'owner')
                <a href="{{ route('stok.adjustment.create') }}" class="btn btn-primary">Penyesuaian Stok</a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('stok.index') }}" class="row g-3">
                    <div class="col-md-7">
                        <label for="stock-search" class="form-label">Cari produk</label>
                        <input id="stock-search" type="search" name="search" class="form-control"
                            value="{{ request('search') }}" placeholder="Kode, barcode, atau nama produk">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select">
                            @foreach (['semua' => 'Semua', 'aman' => 'Aman', 'menipis' => 'Menipis', 'habis' => 'Habis'] as $value => $label)
                                <option value="{{ $value }}" @selected(request('status', 'semua') === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-primary w-100" type="submit">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Stok Saat Ini</th>
                            <th>Stok Minimum</th>
                            <th>Status</th>
                            <th>Aksi</th>
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
                                    @elseif ($product->stok <= $product->stok_minimum)
                                        <span class="badge text-bg-warning">STOK MENIPIS</span>
                                    @else
                                        <span class="badge text-bg-success">AMAN</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('produk.show', $product) }}" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Tidak ada data stok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $products->links() }}</div>
    </div>
@endsection
