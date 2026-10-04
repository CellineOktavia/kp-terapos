@extends('app.master')

@section('content')
    <style>
        /* ==========================
           PENJUALAN PAGE
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

        .btn-add-sale {
            background: linear-gradient(135deg,
                    #2563eb,
                    #3b82f6);
            border: none;
            color: #fff;
            padding: 12px 22px;
            border-radius: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: .3s;
        }

        .btn-add-sale:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow:
                0 10px 25px rgba(37, 99, 235, .25);
        }

        .sale-stat {
            background: #fff;
            border-radius: 18px;
            padding: 22px;
            box-shadow:
                0 10px 30px rgba(15, 23, 42, .06);
            border-top: 4px solid #2563eb;
        }

        .sale-stat h3 {
            margin: 0;
            font-weight: 800;
            color: #0f172a;
        }

        .sale-stat p {
            margin: 6px 0 0;
            color: #64748b;
        }

        .search-card,
        .data-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow:
                0 10px 30px rgba(15, 23, 42, .06);
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
            padding-left: 46px;
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

        .sales-table thead th {
            background: #f8fafc;
            color: #475569;
            border: none;
            padding: 18px;
            font-weight: 700;
        }

        .sales-table tbody td {
            padding: 18px;
            vertical-align: middle;
            border-color: #f1f5f9;
        }

        .sales-table tbody tr:hover {
            background: #f8fbff;
        }

        .invoice-number {
            color: #2563eb;
            font-weight: 700;
        }

        .customer-name {
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

        .action-btn {
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 10px;
            transition: .25s;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .btn-view {
            background: rgba(37, 99, 235, .12);
            color: #2563eb;
        }

        .btn-view:hover {
            background: rgba(37, 99, 235, .2);
            color: #2563eb;
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
    </style>

    <div class="container-fluid">

        
        {{-- HEADER --}}
        <div class="page-header">

            <div>

                <h2 class="page-title">
                    Data Penjualan
                </h2>

                <p class="page-subtitle">
                    Transaksi penjualan barang
                </p>

            </div>

            <a href="{{ route('penjualan.create') }}" class="btn-add-sale">

                <i class="bi bi-plus-circle-fill me-2"></i>
                Buat Penjualan

            </a>

        </div>

        {{-- STATISTIK --}}
        <div class="row mb-4">

            <div class="col-md-3">

                <div class="sale-stat">

                    <h3>
                        {{ $penjualans->total() }}
                    </h3>

                    <p>
                        Total Transaksi
                    </p>

                </div>

            </div>

        </div>

        {{-- SEARCH --}}
        <div class="card search-card mb-4">

            <div class="card-body p-4">

                <form method="GET" action="{{ route('penjualan.index') }}">

                    <div class="row g-3">

                        <div class="col-md-10">

                            <div class="search-box">

                                <i class="bi bi-search"></i>

                                <input type="text" name="search" class="form-control search-input"
                                    placeholder="Cari nomor penjualan..." value="{{ $search ?? '' }}">

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

                    <table class="table sales-table mb-0">

                        <thead>

                            <tr>

                                <th>No Penjualan</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th width="150">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($penjualans as $penjualan)
                                <tr>

                                    <td>

                                        <span class="invoice-number">

                                            {{ $penjualan->nomor_penjualan }}

                                        </span>

                                    </td>

                                    <td>

                                        <span class="customer-name">

                                            {{ $penjualan->customer->nama_customer }}

                                        </span>

                                    </td>

                                    <td>

                                        {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d M Y') }}

                                    </td>

                                    <td>

                                        <span class="amount-badge">

                                            Rp {{ number_format($penjualan->total, 0, ',', '.') }}

                                        </span>

                                    </td>

                                    <td>

                                        <div class="d-flex gap-2">

                                            <a href="{{ route('penjualan.show', $penjualan) }}"
                                                class="btn action-btn btn-view" title="Detail">

                                                <i class="bi bi-eye-fill"></i>

                                            </a>

                                            @if (Auth::user()->role === 'owner')
                                                <a href="{{ route('penjualan.edit', $penjualan) }}"
                                                    class="btn action-btn btn-edit" title="Edit">

                                                    <i class="bi bi-pencil-fill"></i>

                                                </a>

                                                <form action="{{ route('penjualan.destroy', $penjualan) }}" method="POST">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn action-btn btn-delete" title="Hapus"
                                                        onclick="return confirm('Yakin ingin menghapus transaksi ini?')">

                                                        <i class="bi bi-trash-fill"></i>

                                                    </button>

                                                </form>
                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5">

                                        <div class="empty-state text-center">

                                            <i class="bi bi-receipt"></i>

                                            Tidak ada data penjualan

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

            {{ $penjualans->links() }}

        </div>
        

    </div>
@endsection
