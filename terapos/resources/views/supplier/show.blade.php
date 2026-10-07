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

        .detail-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
            margin-bottom: 24px;
        }

        .detail-card .card-header {
            padding: 18px 24px;
            font-weight: 700;
            font-size: 1rem;
            border: none;
        }

        .detail-card .card-body {
            padding: 28px;
        }

        .info-label {
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
            margin: 0;
        }

        .info-group {
            margin-bottom: 24px;
        }

        .supplier-code-badge {
            background: rgba(37, 99, 235, .1);
            color: #2563eb;
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            display: inline-block;
        }

        .btn-back {
            background: #f1f5f9;
            border: none;
            color: #475569;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: .25s;
        }

        .btn-back:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .btn-edit-detail {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: .25s;
        }

        .btn-edit-detail:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, .3);
        }

        .divider {
            border-color: #f1f5f9;
            margin: 8px 0 24px;
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

        .product-table thead th {
            background: #f8fafc;
            color: #475569;
            border: none;
            padding: 16px 18px;
            font-weight: 700;
            font-size: .85rem;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .product-table tbody td {
            padding: 16px 18px;
            vertical-align: middle;
            border-color: #f1f5f9;
        }

        .product-table tbody tr:hover {
            background: #f8fbff;
        }

        .empty-state {
            padding: 50px 0;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 2.5rem;
            display: block;
            margin-bottom: 10px;
        }

        .product-count-badge {
            background: rgba(255, 255, 255, .25);
            color: white;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 700;
            margin-left: 8px;
        }
    </style>

    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="page-header">
            <div>
                <h2 class="page-title">Detail Supplier</h2>
                <p class="page-subtitle">Informasi supplier dan produk yang disuplai</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('supplier.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn-edit-detail">
                    <i class="bi bi-pencil-fill me-1"></i> Edit Supplier
                </a>
            </div>
        </div>

        {{-- INFORMASI SUPPLIER --}}
        <div class="card detail-card">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-building me-2"></i>
                Informasi Supplier
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 info-group">
                        <p class="info-label">Kode Supplier</p>
                        <span class="supplier-code-badge">{{ $supplier->kode_supplier }}</span>
                    </div>

                    <div class="col-md-6 info-group">
                        <p class="info-label">Nama Supplier</p>
                        <p class="info-value">{{ $supplier->nama_supplier }}</p>
                    </div>

                    <div class="col-md-6 info-group">
                        <p class="info-label">Nama Kontak</p>
                        <p class="info-value">{{ $supplier->nama_kontak }}</p>
                    </div>

                    <div class="col-md-6 info-group">
                        <p class="info-label">Telepon</p>
                        <p class="info-value">
                            <i class="bi bi-telephone-fill me-1 text-success"></i>
                            {{ $supplier->telepon }}
                        </p>
                    </div>

                    <div class="col-md-6 info-group mb-0">
                        <p class="info-label">Email</p>
                        <p class="info-value">
                            <i class="bi bi-envelope-fill me-1 text-primary"></i>
                            {{ $supplier->email ?? '-' }}
                        </p>
                    </div>

                    <div class="col-md-6 info-group mb-0">
                        <p class="info-label">Alamat</p>
                        <p class="info-value" style="font-weight: 400; color: #475569; line-height: 1.6;">
                            {{ $supplier->alamat ?? '-' }}
                        </p>
                    </div>

                </div>
            </div>
        </div>

        {{-- PRODUK YANG DISUPLAI --}}
        <div class="card detail-card">
            <div class="card-header bg-success text-white">
                <i class="bi bi-box-seam-fill me-2"></i>
                Produk Yang Disuplai
                <span class="product-count-badge">
                    {{ $supplier->products->count() }} Produk
                </span>
            </div>
            <div class="card-body p-0">

                @if ($supplier->products->count())
                    <div class="table-responsive">
                        <table class="table product-table mb-0">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Produk</th>
                                    <th>Merk</th>
                                    <th>Stok</th>
                                    <th>Harga Jual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($supplier->products as $product)
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
                                                <span class="stock-critical">
                                                    {{ $product->stok }}
                                                </span>
                                            @else
                                                <span class="stock-good">
                                                    {{ $product->stok }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="price-text">
                                                Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state text-center">
                        <i class="bi bi-box-seam"></i>
                        Supplier ini belum memiliki produk yang terhubung.
                    </div>
                @endif

            </div>
        </div>

    </div>

@endsection
