@extends('app.master')

@section('content')
    <style>
        /* ==========================================
               TERAPOS SYSTEM UNIFIED DESIGN SYSTEM
               ========================================== */
        .card-terapos {
            border: 1px solid #f0f0f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03) !important;
            background-color: #ffffff;
            overflow: hidden;
        }

        .card-terapos .card-header-blue {
            background-color: #0d6efd !important;
            color: #ffffff;
            font-weight: 600;
            padding: 14px 20px;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .label-title {
            font-size: 11px;
            font-weight: 700;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: block;
        }

        .form-control-terapos,
        .form-select-terapos {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 10px 14px;
            font-size: 14px;
            color: #2d3748;
        }

        .form-control-terapos:focus,
        .form-select-terapos:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }

        .btn-add-product {
            background: linear-gradient(135deg, #0d6efd, #3b82f6);
            border: none;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .btn-add-product:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(13, 110, 253, 0.3);
        }

        .product-code-badge {
            background-color: #ebf8ff;
            color: #3182ce;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-block;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .product-code-badge:hover {
            background-color: #bee3f8;
            color: #2b6cb0;
        }

        .qty-badge-in {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-block;
        }

        .table-terapos th {
            font-size: 11px;
            font-weight: 700;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #edf2f7;
            padding: 16px 14px;
            background-color: #f8fafc;
        }

        .table-terapos td {
            padding: 16px 14px;
            border-bottom: 1px solid #f7fafc;
            color: #2d3748;
            vertical-align: middle;
        }

        .table-terapos tbody tr:hover {
            background-color: #f8fbff;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 10px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .btn-view {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .btn-view:hover {
            background-color: rgba(13, 110, 253, 0.2);
            color: #0d6efd;
        }
    </style>

    <div class="container-fluid px-0">

        <!-- Header Halaman & Tombol Tambah -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Penerimaan Barang</h3>
                <p class="text-muted small mb-0">Catat dan lihat riwayat barang fisik yang masuk.</p>
            </div>
            @if (auth()->user()->isOwner())
                <a href="{{ route('penerimaan.create') }}" class="btn-add-product">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Penerimaan
                </a>
            @endif
        </div>

        <!-- Alert Notifikasi Flash Session & Validasi -->
        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4 d-flex align-items-center gap-2"
                style="background-color: #d1e7dd; color: #0f5132;">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4 d-flex align-items-start gap-2"
                style="background-color: #f8d7da; color: #842029;">
                <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Filter & Pencarian -->
        <div class="card card-terapos mb-4">
            <div class="card-header-blue">
                <i class="bi bi-funnel-fill"></i> Filter & Pencarian Penerimaan
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('penerimaan.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-4 col-md-6">
                            <label for="receipt-search" class="label-title">Cari Penerimaan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"
                                    style="border-radius: 10px 0 0 10px;">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input id="receipt-search" type="search" name="search"
                                    class="form-control form-control-terapos border-start-0"
                                    placeholder="Nomor, barang, kode, atau user..." value="{{ $filters['search'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="start_date" class="label-title">Tanggal Mulai</label>
                            <input id="start_date" type="date" name="start_date"
                                class="form-control form-control-terapos" value="{{ $filters['start_date'] ?? '' }}">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="end_date" class="label-title">Tanggal Akhir</label>
                            <input id="end_date" type="date" name="end_date" class="form-control form-control-terapos"
                                value="{{ $filters['end_date'] ?? '' }}">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="user_id" class="label-title">User</label>
                            <select id="user_id" name="user_id" class="form-select form-select-terapos">
                                <option value="">Semua User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected(($filters['user_id'] ?? '') == $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-12">
                            <button type="submit"
                                class="btn btn-primary w-100 fw-semibold rounded-3 py-2 d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-filter"></i> Terapkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card Tabel Riwayat Penerimaan -->
        <div class="card card-terapos">
            <div class="card-header-blue d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-box-arrow-in-down"></i>
                    Riwayat Penerimaan Barang
                </div>
                <span class="badge bg-white text-primary rounded-pill px-3 py-1 font-monospace fw-bold"
                    style="font-size: 12px;">
                    Total: {{ $penerimaans->total() }} Transaksi
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-terapos mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">No. Penerimaan</th>
                                <th>Tanggal</th>
                                <th class="text-center">Jenis Barang</th>
                                <th class="text-center">Total Qty</th>
                                <th>User</th>
                                <th class="text-center pe-4" width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penerimaans as $penerimaan)
                                <tr>
                                    <td class="ps-4">
                                        <a href="{{ route('penerimaan.show', $penerimaan) }}" class="product-code-badge">
                                            {{ $penerimaan->nomor_penerimaan }}
                                        </a>
                                    </td>
                                    <td class="text-muted">
                                        {{ $penerimaan->tanggal?->translatedFormat('d M Y') ?? '-' }}
                                    </td>
                                    <td class="text-center fw-semibold text-dark">
                                        {{ $penerimaan->detail_penerimaan_barangs_count }} Item
                                    </td>
                                    <td class="text-center">
                                        <span class="qty-badge-in">
                                            <i class="bi bi-plus-circle-fill me-1"></i>
                                            {{ number_format($penerimaan->detail_penerimaan_barangs_sum_qty ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="fw-medium text-dark">
                                        {{ $penerimaan->user?->name ?? '-' }}
                                    </td>
                                    <td class="text-center pe-4">
                                        <a href="{{ route('penerimaan.show', $penerimaan) }}" class="action-btn btn-view"
                                            title="Lihat Detail Penerimaan">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                        <div class="fw-bold fs-6 text-dark">Belum ada riwayat penerimaan barang</div>
                                        <p class="small text-muted mb-0">Coba ubah filter pencarian Anda atau tambahkan data
                                            penerimaan baru.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($penerimaans->hasPages())
                <div class="card-footer bg-white border-top-0 p-4">
                    {{ $penerimaans->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
