@extends('app.master')

@section('content')
    <style>
        /* ==========================
           STOK KRITIS PAGE
        ========================== */

        .page-header {
            margin-bottom: 24px;
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

        .critical-stat {
            background: #fff;
            border-radius: 18px;
            padding: 22px;
            box-shadow:
                0 10px 30px rgba(15, 23, 42, .06);

            border-top: 4px solid #ef4444;
        }

        .critical-stat h3 {
            margin: 0;
            font-weight: 800;
            color: #dc2626;
        }

        .critical-stat p {
            margin: 6px 0 0;
            color: #64748b;
        }

        .data-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;

            box-shadow:
                0 10px 30px rgba(15, 23, 42, .06);
        }

        .critical-table thead th {
            background: #f8fafc;
            color: #475569;
            border: none;
            padding: 18px;
            font-weight: 700;
        }

        .critical-table tbody td {
            padding: 18px;
            vertical-align: middle;
            border-color: #f1f5f9;
        }

        .critical-table tbody tr:hover {
            background: #fff7f7;
        }

        .product-code {
            font-weight: 700;
            color: #2563eb;
        }

        .product-name {
            font-weight: 600;
            color: #0f172a;
        }

        .stock-danger {
            font-weight: 700;
            color: #dc2626;
        }

        .stock-minimum {
            font-weight: 600;
            color: #64748b;
        }

        .status-badge {
            background: rgba(239, 68, 68, .12);
            color: #dc2626;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 700;
        }

        .empty-state {
            padding: 60px 0;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 3rem;
            display: block;
            margin-bottom: 12px;
            color: #10b981;
        }
    </style>

    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="page-header">

            <h2 class="page-title">
                Stok Kritis
            </h2>

            <p class="page-subtitle">
                Produk yang perlu segera dilakukan restock
            </p>

        </div>

        {{-- STATISTIK --}}
        <div class="row mb-4">

            <div class="col-md-3">

                <div class="critical-stat">

                    <h3>
                        {{ $products->total() }}
                    </h3>

                    <p>
                        Produk Stok Kritis
                    </p>

                </div>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="card data-card">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table critical-table mb-0">

                        <thead>

                            <tr>

                                <th>Kode</th>
                                <th>Produk</th>
                                <th>Supplier</th>
                                <th>Stok Saat Ini</th>
                                <th>Stok Minimum</th>
                                <th>Status</th>

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

                                    <td>

                                        {{ $product->supplier?->nama_supplier ?? '-' }}

                                    </td>

                                    <td>

                                        <span class="stock-danger">

                                            {{ $product->stok }}

                                        </span>

                                    </td>

                                    <td>

                                        <span class="stock-minimum">

                                            {{ $product->stok_minimum }}

                                        </span>

                                    </td>

                                    <td>

                                        <span class="status-badge">

                                            KRITIS

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6">

                                        <div class="empty-state text-center">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Tidak ada stok kritis

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
@endsection
