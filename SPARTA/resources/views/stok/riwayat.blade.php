@extends('app.master')

@section('content')
    <style>
        /* ==========================
           RIWAYAT STOK PAGE
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

        .movement-stat {
            background: #fff;
            border-radius: 18px;
            padding: 22px;
            box-shadow:
                0 10px 30px rgba(15, 23, 42, .06);

            border-top: 4px solid #2563eb;
        }

        .movement-stat h3 {
            margin: 0;
            font-weight: 800;
            color: #0f172a;
        }

        .movement-stat p {
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

        .movement-table thead th {
            background: #f8fafc;
            color: #475569;
            border: none;
            padding: 18px;
            font-weight: 700;
        }

        .movement-table tbody td {
            padding: 18px;
            vertical-align: middle;
            border-color: #f1f5f9;
        }

        .movement-table tbody tr:hover {
            background: #f8fbff;
        }

        .product-name {
            font-weight: 600;
            color: #0f172a;
        }

        .qty-in {
            color: #059669;
            font-weight: 700;
        }

        .qty-out {
            color: #dc2626;
            font-weight: 700;
        }

        .badge-in {
            background: rgba(16, 185, 129, .12);
            color: #059669;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 700;
        }

        .badge-out {
            background: rgba(239, 68, 68, .12);
            color: #dc2626;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 700;
        }

        .date-text {
            color: #64748b;
            font-weight: 500;
        }

        .description-text {
            color: #334155;
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
    </style>

    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="page-header">

            <h2 class="page-title">
                Riwayat Stok
            </h2>

            <p class="page-subtitle">
                Riwayat seluruh pergerakan stok masuk dan keluar
            </p>

        </div>

        {{-- STAT CARD --}}
        <div class="row mb-4">

            <div class="col-md-3">

                <div class="movement-stat">

                    <h3>
                        {{ $movements->total() }}
                    </h3>

                    <p>
                        Total Aktivitas Stok
                    </p>

                </div>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="card data-card">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table movement-table mb-0">

                        <thead>

                            <tr>

                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Jenis</th>
                                <th>Qty</th>
                                <th>Keterangan</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($movements as $movement)
                                <tr>

                                    <td>

                                        <span class="date-text">

                                            {{ \Carbon\Carbon::parse($movement->created_at)->format('d M Y H:i') }}

                                        </span>

                                    </td>

                                    <td>

                                        <span class="product-name">

                                            {{ $movement->product->nama_produk }}

                                        </span>

                                    </td>

                                    <td>

                                        @if ($movement->jenis == 'masuk')
                                            <span class="badge-in">

                                                Stok Masuk

                                            </span>
                                        @else
                                            <span class="badge-out">

                                                Stok Keluar

                                            </span>
                                        @endif

                                    </td>

                                    <td>

                                        @if ($movement->jenis == 'masuk')
                                            <span class="qty-in">

                                                +{{ $movement->qty }}

                                            </span>
                                        @else
                                            <span class="qty-out">

                                                -{{ $movement->qty }}

                                            </span>
                                        @endif

                                    </td>

                                    <td>

                                        <span class="description-text">

                                            {{ $movement->keterangan }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5">

                                        <div class="empty-state text-center">

                                            <i class="bi bi-clock-history"></i>

                                            Belum ada riwayat stok

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

            {{ $movements->links() }}

        </div>

    </div>
@endsection
