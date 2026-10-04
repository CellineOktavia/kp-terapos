@extends('app.master')

@section('content')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .page-subtitle {
            color: #64748b;
            margin: 0;
        }

        .btn-add-product {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border: none;
            color: white;
            padding: 12px 22px;
            border-radius: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: .3s;
        }

        .btn-add-product:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, .25);
        }

        .product-stat {
            background: white;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
            border-top: 4px solid #2563eb;
        }

        .product-stat h3 {
            margin: 0;
            font-weight: 800;
            color: #0f172a;
        }

        .product-stat p {
            margin: 6px 0 0;
            color: #64748b;
        }

        .search-card,
        .data-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .search-input {
            height: 52px;
            padding-left: 45px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
        }

        .search-input:focus {
            border-color: #2563eb;
            box-shadow: none;
        }

        .btn-search {
            height: 52px;
            border-radius: 14px;
            font-weight: 600;
        }

        .product-table thead th {
            background: #f8fafc;
            color: #475569;
            border: none;
            padding: 18px;
            font-weight: 700;
        }

        .product-table tbody td {
            padding: 18px;
            vertical-align: middle;
            border-color: #f1f5f9;
        }

        .product-table tbody tr:hover {
            background: #f8fbff;
        }

        .product-code {
            color: #2563eb;
            font-weight: 700;
        }

        .product-name {
            font-weight: 600;
            color: #0f172a;
        }

        .stock-good {
            background: rgba(16, 185, 129, .12);
            color: #059669;
            padding: 7px 14px;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 600;
        }

        .stock-critical {
            background: rgba(239, 68, 68, .12);
            color: #dc2626;
            padding: 7px 14px;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 600;
        }

        .price-text {
            font-weight: 700;
            color: #0f172a;
        }

        .action-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: none;
            transition: .25s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .btn-view {
            background: rgba(14, 165, 233, .12);
            color: #0284c7;
        }

        .btn-view:hover {
            background: rgba(14, 165, 233, .2);
            color: #0284c7;
        }

        .btn-edit {
            background: rgba(245, 158, 11, .12);
            color: #d97706;
        }

        .btn-edit:hover {
            background: rgba(245, 158, 11, .2);
            color: #d97706;
        }

        .btn-delete {
            background: rgba(239, 68, 68, .12);
            color: #dc2626;
        }

        .btn-delete:hover {
            background: rgba(239, 68, 68, .2);
            color: #dc2626;
        }

        .empty-state {
            padding: 60px 0;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 3rem;
            display: block;
            margin-bottom: 12px;
        }

        .barcode-cell {
            text-align: center;
        }

        .barcode-cell svg {
            display: block;
            margin: 0 auto;
        }
    </style>

    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="page-header">
            <div>
                <h2 class="page-title">Data Produk</h2>
                <p class="page-subtitle">Kelola seluruh produk Richie Motor</p>
            </div>

            <a href="{{ route('produk.create') }}" class="btn-add-product">
                <i class="bi bi-plus-circle-fill me-2"></i>
                Tambah Produk
            </a>
        </div>

        {{-- STAT --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="product-stat">
                    <h3>{{ $products->total() }}</h3>
                    <p>Total Produk</p>
                </div>
            </div>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- SEARCH --}}
        <div class="card search-card mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('produk.index') }}">
                    <div class="row g-3">
                        <div class="col-md-10">
                            <div class="search-box">
                                <i class="bi bi-search"></i>
                                <input type="text" name="search" class="form-control search-input"
                                    placeholder="Cari kode atau nama produk..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-search w-100">
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card data-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table product-table mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Merk</th>
                                <th>Stok</th>
                                <th>Harga Jual</th>
                                <th>Barcode</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>
                                        <span class="product-code">
                                            {{ $product->kode_produk }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="product-name">
                                            {{ $product->nama_produk }}
                                        </span>
                                    </td>
                                    <td>{{ $product->merk }}</td>
                                    <td>
                                        @if ($product->stok <= $product->stok_minimum)
                                            <span class="stock-critical">{{ $product->stok }}</span>
                                        @else
                                            <span class="stock-good">{{ $product->stok }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="price-text">
                                            Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="barcode-cell">
                                        <svg class="barcode" data-value="{{ $product->kode_produk }}">
                                        </svg>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('produk.show', $product->id) }}"
                                                class="btn action-btn btn-view" title="Detail">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="{{ route('produk.edit', $product->id) }}"
                                                class="btn action-btn btn-edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <form action="{{ route('produk.destroy', $product->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn action-btn btn-delete"
                                                    onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state text-center">
                                            <i class="bi bi-box-seam"></i>
                                            Tidak ada data produk
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $products->links() }}
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.barcode').forEach(function(el) {
                JsBarcode(el, el.getAttribute('data-value'), {
                    format: 'CODE128',
                    width: 1.5,
                    height: 40,
                    displayValue: true,
                    margin: 4,
                });
            });
        });
    </script>
@endsection
