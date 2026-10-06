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
                    Riwayat Penjualan
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

                        <div class="col-lg-4 col-md-6">

                            <div class="search-box">

                                <i class="bi bi-search"></i>

                                <input type="text" name="search" class="form-control search-input"
                                    placeholder="Nomor, pelanggan, produk, kode, atau kasir..."
                                    value="{{ $filters['search'] ?? '' }}">

                            </div>

                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" id="start_date" name="start_date" class="form-control"
                                value="{{ $filters['start_date'] ?? '' }}">
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" id="end_date" name="end_date" class="form-control"
                                value="{{ $filters['end_date'] ?? '' }}">
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="user_id" class="form-label">Kasir/User</label>
                            <select id="user_id" name="user_id" class="form-select">
                                <option value="">Semua user</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        @selected(($filters['user_id'] ?? '') == $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <button type="submit" class="btn btn-primary btn-search w-100">Cari</button>

                        </div>

                    </div>

                </form>

            </div>

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

        {{-- TABLE --}}
        <div class="card data-card">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table sales-table mb-0">

                        <thead>

                            <tr>

                                <th>Nomor</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Jenis Item</th>
                                <th>Total Qty</th>
                                <th>Total</th>
                                <th>Kasir</th>
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
                                        {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d M Y') }}
                                    </td>

                                    <td>{{ $penjualan->customer->nama_customer ?? 'Pelanggan Umum' }}</td>
                                    <td>{{ $penjualan->detail_penjualans_count }}</td>
                                    <td>{{ number_format($penjualan->detail_penjualans_sum_qty ?? 0, 0, ',', '.') }}</td>

                                    <td>
                                        <span class="amount-badge">
                                            Rp {{ number_format($penjualan->total, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <td>{{ $penjualan->user->name ?? '-' }}</td>

                                    <td>

                                        <div class="d-flex gap-2">

                                            <a href="{{ route('penjualan.show', $penjualan) }}"
                                                class="btn action-btn btn-view" title="Detail">

                                                <i class="bi bi-eye-fill"></i>

                                            </a>

                                            @if (Auth::user()->role === 'owner')
                                                <form action="{{ route('penjualan.destroy', $penjualan) }}" method="POST">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn action-btn btn-delete"
                                                        title="Batalkan Penjualan"
                                                        onclick="return confirm('Batalkan penjualan ini dan kembalikan stok?')">

                                                        <i class="bi bi-trash-fill"></i>

                                                    </button>

                                                </form>
                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8">

                                        <div class="empty-state text-center">

                                            <i class="bi bi-receipt"></i>

                                            Tidak ada transaksi yang sesuai dengan pencarian/filter.

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
