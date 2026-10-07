@extends('app.master')

@section('content')
    <style>
        /* ==========================
           LAPORAN SUPPLIER
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

        .supplier-code {
            color: #2563eb;
            font-weight: 700;
        }

        .supplier-name {
            color: #0f172a;
            font-weight: 600;
        }

        .contact-badge {
            background: rgba(37, 99, 235, .10);
            color: #2563eb;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 600;
        }

        .phone-badge {
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
                    Laporan Supplier
                </h2>

                <p class="page-subtitle">
                    Daftar seluruh supplier yang bekerja sama dengan Richie Motor
                </p>

            </div>

            <a href="{{ route('laporan.supplier.pdf') }}" class="btn-download">

                <i class="bi bi-file-earmark-pdf-fill me-2"></i>
                Download PDF

            </a>

        </div>

        {{-- STATISTIK --}}
        <div class="row mb-4">

            <div class="col-md-3">

                <div class="report-stat">

                    <h3>
                        {{ $suppliers->count() }}
                    </h3>

                    <p>
                        Total Supplier
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

                                <th>Kode</th>
                                <th>Nama Supplier</th>
                                <th>Kontak</th>
                                <th>Telepon</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($suppliers as $supplier)
                                <tr>

                                    <td>

                                        <span class="supplier-code">

                                            {{ $supplier->kode_supplier }}

                                        </span>

                                    </td>

                                    <td>

                                        <span class="supplier-name">

                                            {{ $supplier->nama_supplier }}

                                        </span>

                                    </td>

                                    <td>

                                        <span class="contact-badge">

                                            {{ $supplier->nama_kontak }}

                                        </span>

                                    </td>

                                    <td>

                                        <span class="phone-badge">

                                            {{ $supplier->telepon }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4">

                                        <div class="empty-state text-center">

                                            <i class="bi bi-truck"></i>

                                            Tidak ada data supplier

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
