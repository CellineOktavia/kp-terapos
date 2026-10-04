@extends('app.master')

@section('content')
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="page-header mb-4">

            <div>

                <h2 class="page-title">
                    Laporan Produk
                </h2>

                <p class="page-subtitle">
                    Laporan data produk dan persediaan sparepart pada sistem SPARTA
                </p>

            </div>

            <a href="{{ route('laporan.produk.pdf') }}" class="btn btn-danger download-btn">

                <i class="bi bi-file-earmark-pdf-fill me-2"></i>

                Download PDF

            </a>

        </div>

        {{-- SUMMARY CARD --}}
        <div class="row g-3 mb-4">

            <div class="col-md-6">

                <div class="summary-card">

                    <div class="summary-icon bg-primary-subtle">
                        <i class="bi bi-box-seam"></i>
                    </div>

                    <div>

                        <h3>
                            {{ $products->count() }}
                        </h3>

                        <span>
                            Total Produk
                        </span>

                    </div>

                </div>

            </div>

            <div class="col-md-6">

                <div class="summary-card">

                    <div class="summary-icon bg-success-subtle">
                        <i class="bi bi-boxes"></i>
                    </div>

                    <div>

                        <h3>
                            {{ $products->sum('stok') }}
                        </h3>

                        <span>
                            Total Stok
                        </span>

                    </div>

                </div>

            </div>

        </div>

        {{-- TABLE CARD --}}
        <div class="card custom-card">

            <div class="card-header bg-white border-0">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-table me-2"></i>

                    Data Produk

                </h5>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle custom-table">

                        <thead>

                            <tr>

                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Merk</th>
                                <th>Stok</th>
                                <th>Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($products as $product)
                                <tr>

                                    <td>

                                        <span class="fw-semibold">
                                            {{ $product->kode_produk }}
                                        </span>

                                    </td>

                                    <td>

                                        {{ $product->nama_produk }}

                                    </td>

                                    <td>

                                        {{ $product->merk }}

                                    </td>

                                    <td>

                                        {{ $product->stok }}

                                    </td>

                                    <td>

                                        @if ($product->stok <= $product->stok_minimum)
                                            <span class="badge bg-danger">

                                                Stok Kritis

                                            </span>
                                        @else
                                            <span class="badge bg-success">

                                                Normal

                                            </span>
                                        @endif

                                    </td>

                                </tr>
                            @empty

                                <tr>

                                    <td colspan="5" class="text-center text-muted py-4">

                                        Tidak ada data produk

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-title {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: #64748b;
            margin: 0;
        }

        .download-btn {
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .summary-card {
            background: white;
            border-radius: 18px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .05);
            height: 100%;
        }

        .summary-card h3 {
            margin: 0;
            font-weight: 700;
            color: #0f172a;
        }

        .summary-card span {
            color: #64748b;
            font-size: .9rem;
        }

        .summary-icon {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .custom-card {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .05);
        }

        .custom-table thead th {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            color: #334155;
            font-weight: 600;
        }

        .custom-table tbody tr:hover {
            background: #f8fbff;
        }

        .badge {
            padding: 7px 12px;
            border-radius: 999px;
            font-weight: 500;
        }
    </style>
@endsection
