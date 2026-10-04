@extends('app.master')

@section('content')
    <style>
        /* ==========================
           LAPORAN PEMBELIAN
        ========================== */

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .btn-download {
            background: linear-gradient(135deg,
                    #dc2626,
                    #ef4444);
            border: none;
            color: white;
            padding: 12px 22px;
            border-radius: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: .3s;
        }

        .btn-download:hover {
            color: white;
            transform: translateY(-2px);

            box-shadow:
                0 10px 25px rgba(220, 38, 38, .25);
        }

        .report-stat {
            background: white;
            border-radius: 18px;
            padding: 22px;

            box-shadow:
                0 10px 30px rgba(15, 23, 42, .06);

            border-top: 4px solid #2563eb;
        }

        .report-stat h3 {
            margin: 0;
            font-weight: 800;
            color: #0f172a;
        }

        .report-stat p {
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

        .report-table thead th {
            background: #f8fafc;
            color: #475569;
            border: none;
            padding: 18px;
            font-weight: 700;
        }

        .report-table tbody td {
            padding: 18px;
            vertical-align: middle;
            border-color: #f1f5f9;
        }

        .report-table tbody tr:hover {
            background: #f8fbff;
        }

        .invoice-number {
            color: #2563eb;
            font-weight: 700;
        }

        .supplier-name {
            font-weight: 600;
            color: #0f172a;
        }

        .amount-badge {
            background: rgba(16, 185, 129, .12);
            color: #059669;
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
        }
    </style>

    <div class="container-fluid">

        
        {{-- HEADER --}}
        <div class="page-header">

            <div>

                <h2 class="page-title">
                    Laporan Pembelian
                </h2>

                <p class="page-subtitle">
                    Ringkasan seluruh transaksi pembelian dari supplier
                </p>

            </div>

            <a href="{{ route('laporan.pembelian.pdf') }}" class="btn-download">

                <i class="bi bi-file-earmark-pdf-fill me-2"></i>
                Download PDF

            </a>

        </div>

        {{-- STATISTIK --}}
        <div class="row mb-4">

            <div class="col-md-3">

                <div class="report-stat">

                    <h3>
                        {{ $fakturs->count() }}
                    </h3>

                    <p>
                        Total Faktur
                    </p>

                </div>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="card data-card">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table report-table mb-0">

                        <thead>

                            <tr>

                                <th>No Faktur</th>
                                <th>Supplier</th>
                                <th>Tanggal</th>
                                <th>Total</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($fakturs as $faktur)
                                <tr>

                                    <td>

                                        <span class="invoice-number">

                                            {{ $faktur->nomor_faktur }}

                                        </span>

                                    </td>

                                    <td>

                                        <span class="supplier-name">

                                            {{ $faktur->supplier->nama_supplier ?? '-' }}

                                        </span>

                                    </td>

                                    <td>

                                        {{ \Carbon\Carbon::parse($faktur->tanggal)->format('d M Y') }}

                                    </td>

                                    <td>

                                        <span class="amount-badge">

                                            Rp {{ number_format($faktur->total, 0, ',', '.') }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4">

                                        <div class="empty-state text-center">

                                            <i class="bi bi-cart-check"></i>

                                            Tidak ada data pembelian

                                        </div>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
        

    </div>
@endsection
