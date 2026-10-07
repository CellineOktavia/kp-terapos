@extends('app.master')

@section('content')
    <style>
        /* ==========================
           FAKTUR PAGE
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

        .btn-add-faktur {
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

        .btn-add-faktur:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow:
                0 10px 25px rgba(37, 99, 235, .25);
        }

        .faktur-stat {
            background: #fff;
            border-radius: 18px;
            padding: 22px;
            box-shadow:
                0 10px 30px rgba(15, 23, 42, .06);
            border-top: 4px solid #2563eb;
        }

        .faktur-stat h3 {
            margin: 0;
            font-weight: 800;
            color: #0f172a;
        }

        .faktur-stat p {
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

        .faktur-table thead th {
            background: #f8fafc;
            color: #475569;
            border: none;
            padding: 18px;
            font-weight: 700;
        }

        .faktur-table tbody td {
            padding: 18px;
            vertical-align: middle;
            border-color: #f1f5f9;
        }

        .faktur-table tbody tr:hover {
            background: #f8fbff;
        }

        .invoice-number {
            font-weight: 700;
            color: #2563eb;
        }

        .amount-badge {
            background: rgba(16, 185, 129, .12);
            color: #059669;
            padding: 8px 14px;
            border-radius: 999px;
            font-weight: 700;
            font-size: .85rem;
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

        .btn-edit {
            background: rgba(245, 158, 11, .12);
            color: #d97706;
        }

        .btn-delete {
            background: rgba(239, 68, 68, .12);
            color: #dc2626;
        }

        .btn-view:hover {
            background: rgba(37, 99, 235, .2);
            color: #2563eb;
        }

        .btn-edit:hover {
            background: rgba(245, 158, 11, .2);
            color: #d97706;
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
                    Riwayat Pembelian
                </h2>

                <p class="page-subtitle">
                    Riwayat transaksi barang yang dibeli
                </p>

            </div>

            <a href="{{ route('faktur.create') }}" class="btn-add-faktur">

                <i class="bi bi-plus-circle-fill me-2"></i>
                Tambah Pembelian

            </a>

        </div>

        {{-- STAT --}}
        <div class="row mb-4">

            <div class="col-md-3">

                <div class="faktur-stat">

                    <h3>
                        {{ $fakturs->total() }}
                    </h3>

                    <p>
                        Total Pembelian
                    </p>

                </div>

            </div>

        </div>

        {{-- SEARCH --}}
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

        <div class="card search-card mb-4">

            <div class="card-body p-4">

                <form action="{{ route('faktur.index') }}" method="GET">

                    <div class="row g-3">

                        <div class="col-lg-4 col-md-6">

                            <div class="search-box">

                                <i class="bi bi-search"></i>

                                <input type="text" name="search" class="form-control search-input"
                                    placeholder="Nomor, produk, kode, atau user..."
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
                            <label for="user_id" class="form-label">User</label>
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

        {{-- TABLE --}}
        <div class="card data-card">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table faktur-table mb-0">

                        <thead>

                            <tr>

                                <th>Nomor Pembelian</th>
                                <th>Tanggal</th>
                                <th>Jumlah Item</th>
                                <th>Total Qty</th>
                                <th>Total</th>
                                <th>Dibuat Oleh</th>
                                <th width="150">
                                    Aksi
                                </th>

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
                                        {{ \Carbon\Carbon::parse($faktur->tanggal)->format('d M Y') }}
                                    </td>

                                    <td>{{ $faktur->detail_fakturs_count }}</td>
                                    <td>{{ number_format($faktur->detail_fakturs_sum_qty ?? 0, 0, ',', '.') }}</td>

                                    <td>
                                        <span class="amount-badge">
                                            Rp {{ number_format($faktur->total, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <td>{{ $faktur->user->name ?? '-' }}</td>

                                    <td>

                                        <div class="d-flex gap-2">

                                            <a href="{{ route('faktur.show', $faktur) }}" class="btn action-btn btn-view"
                                                title="Detail">

                                                <i class="bi bi-eye-fill"></i>

                                            </a>

                                            @if (Auth::user()->role === 'owner')
                                                <form action="{{ route('faktur.destroy', $faktur) }}" method="POST">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn action-btn btn-delete"
                                                        title="Batalkan Pembelian"
                                                        onclick="return confirm('Batalkan pembelian ini? Stok akan dikurangi dan pembatalan ditolak jika stok sudah digunakan.')">

                                                        <i class="bi bi-trash-fill"></i>

                                                    </button>

                                                </form>
                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7">

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

            {{ $fakturs->links() }}

        </div>
        

    </div>
@endsection
