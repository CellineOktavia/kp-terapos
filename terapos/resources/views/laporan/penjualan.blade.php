@extends('app.master')

@section('content')
    <style>
        /* ==========================================
               TERAPOS UNIFIED DESIGN SYSTEM (FRESH GREEN v3)
               ========================================== */
        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }

        /* Header Halaman */
        .terapos-page-header {
            background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
            border: 1px solid #dcfce7;
            border-radius: 18px;
            padding: 20px 24px;
            box-shadow: 0 4px 20px rgba(25, 135, 84, 0.04);
        }

        .header-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, #198754, #10b981);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.22);
            flex-shrink: 0;
        }

        .period-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: #ffffff;
            border: 1px solid #bbf7d0;
            color: #15803d;
            font-size: 12.5px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 999px;
            margin-top: 4px;
        }

        .period-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #10b981;
            display: inline-block;
        }

        /* Wrapper & Styling Otomatis untuk Partial Filter */
        .filter-wrapper-terapos .card {
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03) !important;
            background-color: #ffffff;
        }

        .filter-wrapper-terapos .card-body {
            padding: 18px 22px !important;
        }

        .filter-wrapper-terapos label,
        .filter-wrapper-terapos .form-label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .filter-wrapper-terapos .form-control,
        .filter-wrapper-terapos .form-select {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #1e293b;
            min-height: 40px;
            transition: all 0.2s ease;
        }

        .filter-wrapper-terapos .form-control:focus,
        .filter-wrapper-terapos .form-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.14);
        }

        .filter-wrapper-terapos .btn-primary,
        .filter-wrapper-terapos .btn-success {
            background-color: #198754 !important;
            border-color: #198754 !important;
            border-radius: 10px !important;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 8px 18px;
            min-height: 40px;
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.18);
            transition: all 0.2s ease;
        }

        .filter-wrapper-terapos .btn-primary:hover,
        .filter-wrapper-terapos .btn-success:hover {
            background-color: #157347 !important;
            border-color: #146c43 !important;
            transform: translateY(-1px);
        }

        .filter-wrapper-terapos .btn-outline-secondary,
        .filter-wrapper-terapos .btn-light,
        .filter-wrapper-terapos .btn-secondary {
            border-radius: 10px !important;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 8px 16px;
            min-height: 40px;
        }

        .filter-wrapper-terapos .btn-outline-success {
            border-radius: 999px !important;
            font-size: 12.5px;
            font-weight: 600;
            padding: 5px 14px;
        }

        /* Card Utama */
        .card-terapos {
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03) !important;
            background-color: #ffffff;
            overflow: hidden;
        }

        .card-terapos .card-header-green {
            background: linear-gradient(135deg, #198754 0%, #059669 100%) !important;
            color: #ffffff;
            font-weight: 600;
            padding: 15px 22px;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .label-title {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: block;
        }

        /* Card Statistik Ringkasan Laporan */
        .stat-card-report {
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03) !important;
            background: #ffffff;
            padding: 20px 22px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            transition: all 0.2s ease;
        }

        .stat-card-report:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06) !important;
        }

        .stat-card-emerald {
            background: linear-gradient(145deg, #ffffff 40%, #ecfdf5 100%);
            border-color: #bbf7d0 !important;
        }

        .stat-icon-box {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .stat-value {
            font-size: 1.45rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.4px;
            line-height: 1.2;
        }

        /* Badge Nomor Transaksi & Total Hijau */
        .badge-trx {
            background-color: #f0fdf4;
            color: #15803d;
            font-weight: 700;
            padding: 5px 11px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid #bbf7d0;
            white-space: nowrap;
        }

        .badge-trx:hover {
            background-color: #198754;
            color: #ffffff;
            border-color: #198754;
        }

        .amount-badge {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-block;
            white-space: nowrap;
        }

        .discount-pill {
            background-color: #fef3c7;
            color: #b45309;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 2px;
        }

        .user-avatar-mini {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background-color: #dcfce7;
            color: #15803d;
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Tombol Download PDF */
        .btn-pdf-download {
            background: linear-gradient(135deg, #dc3545, #ef4444);
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
            gap: 8px;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
        }

        .btn-pdf-download:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(220, 53, 69, 0.3);
        }

        /* Tabel */
        .table-terapos th {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            padding: 15px 14px;
            background-color: #f8fafc;
            white-space: nowrap;
        }

        .table-terapos td {
            padding: 15px 14px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .table-terapos tbody tr {
            transition: background-color 0.15s ease;
        }

        .table-terapos tbody tr:hover {
            background-color: #f0fdf4;
        }

        /* Tombol Aksi Mata Detail Hijau */
        .action-btn {
            width: 34px;
            height: 34px;
            border: 1px solid #bbf7d0;
            border-radius: 9px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }

        .btn-view {
            background-color: #f0fdf4;
            color: #198754;
        }

        .btn-view:hover {
            background-color: #198754;
            color: #ffffff;
            border-color: #198754;
        }
    </style>

    <div class="container-fluid px-0">

        <!-- Header Halaman & Tombol Download -->
        <div class="terapos-page-header d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon-wrapper">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0" style="letter-spacing: -0.3px;">Laporan Penjualan</h3>
                    <div class="period-pill">
                        <span class="period-dot"></span>
                        <span>Periode Laporan: {{ $period['label'] }}</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('laporan.penjualan.pdf', request()->query()) }}" class="btn-pdf-download">
                <i class="bi bi-file-earmark-pdf-fill"></i>
                <span>Cetak / Download PDF</span>
            </a>
        </div>

        <!-- Partial Filter Periode (Variabel & Route Tetap Sama) -->
        <div class="filter-wrapper-terapos mb-4">
            @include('laporan.partials.period-filter', [
                'reportRoute' => 'laporan.penjualan',
                'period' => $period,
            ])
        </div>

        <!-- Ringkasan Statistik Laporan (3 Kolom) -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card-report stat-card-emerald border-start border-success border-4">
                    <div>
                        <span class="label-title text-success">Total Omzet Penjualan</span>
                        <div class="stat-value text-success tabular-nums">
                            Rp {{ number_format($summary['total'], 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="stat-icon-box" style="background-color: #dcfce7; color: #15803d;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-report border-start border-primary border-4">
                    <div>
                        <span class="label-title text-primary">Total Transaksi</span>
                        <div class="stat-value text-dark tabular-nums">
                            {{ number_format($summary['transactions'], 0, ',', '.') }}
                            <span class="fs-6 fw-normal text-muted">Transaksi</span>
                        </div>
                    </div>
                    <div class="stat-icon-box" style="background-color: #dbeafe; color: #2563eb;">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-report border-start border-info border-4">
                    <div>
                        <span class="label-title" style="color: #0891b2;">Total Item Terjual</span>
                        <div class="stat-value text-dark tabular-nums">
                            {{ number_format($summary['qty'], 0, ',', '.') }}
                            <span class="fs-6 fw-normal text-muted">pcs</span>
                        </div>
                    </div>
                    <div class="stat-icon-box" style="background-color: #cffafe; color: #0891b2;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Tabel Data Laporan Penjualan -->
        <div class="card card-terapos">
            <div class="card-header-green d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    <span>Rincian Transaksi Penjualan</span>
                </div>
                <span class="badge bg-white text-success rounded-pill px-3 py-1 font-monospace fw-bold shadow-sm"
                    style="font-size: 12px;">
                    Total: {{ $penjualans->total() }} Transaksi
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-terapos mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">No. Penjualan</th>
                                <th>Tanggal</th>
                                <th class="text-center">Jenis Item</th>
                                <th class="text-center">Qty Terjual</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-end">Diskon</th>
                                <th class="text-end">Total Penjualan</th>
                                <th class="text-end">Bayar</th>
                                <th class="text-end">Kembalian</th>
                                <th>User / Operator</th>
                                <th class="text-center pe-4" width="80">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penjualans as $penjualan)
                                <tr>
                                    <td class="ps-4">
                                        <a href="{{ route('penjualan.show', $penjualan->id) }}" class="badge-trx">
                                            {{ $penjualan->nomor_penjualan }}
                                        </a>
                                    </td>
                                    <td class="fw-semibold text-dark text-nowrap">
                                        {{ $penjualan->tanggal?->translatedFormat('d M Y') ?? '-' }}
                                    </td>
                                    <td class="text-center text-muted fw-semibold text-nowrap tabular-nums">
                                        {{ number_format($penjualan->detail_penjualans_count, 0, ',', '.') }} Jenis
                                    </td>
                                    <td class="text-center text-muted fw-semibold text-nowrap tabular-nums">
                                        {{ number_format($penjualan->detail_penjualans_sum_qty ?? 0, 0, ',', '.') }} pcs
                                    </td>
                                    <td class="text-end text-dark text-nowrap tabular-nums">
                                        Rp {{ number_format($penjualan->detailPenjualans->sum('subtotal'), 0, ',', '.') }}
                                    </td>
                                    <td class="text-end text-nowrap tabular-nums">
                                        @if ((float) $penjualan->diskon_persen > 0)
                                            <span class="discount-pill">
                                                -{{ rtrim(rtrim(number_format($penjualan->diskon_persen, 2, '.', ''), '0'), '.') }}%
                                            </span><br>
                                        @endif
                                        <span class="text-muted fw-medium small">
                                            Rp {{ number_format($penjualan->diskon_nominal, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-end tabular-nums">
                                        <span class="amount-badge">
                                            Rp {{ number_format($penjualan->total, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-end text-muted fw-semibold text-nowrap tabular-nums">
                                        Rp {{ number_format($penjualan->bayar ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end text-muted fw-semibold text-nowrap tabular-nums">
                                        Rp {{ number_format($penjualan->kembalian ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="d-inline-flex align-items-center gap-2">
                                            <span class="user-avatar-mini">
                                                {{ strtoupper(substr($penjualan->user?->name ?? 'U', 0, 1)) }}
                                            </span>
                                            <span
                                                class="small text-dark fw-medium">{{ $penjualan->user?->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center pe-4">
                                        <a href="{{ route('penjualan.show', $penjualan->id) }}" class="action-btn btn-view"
                                            title="Lihat Detail Transaksi">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5 text-muted">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                                            style="width: 64px; height: 64px; background-color: #f1f5f9;">
                                            <i class="bi bi-file-earmark-bar-graph fs-2 text-secondary"></i>
                                        </div>
                                        <div class="fw-bold fs-6 text-dark">Tidak ada data transaksi</div>
                                        <p class="small text-muted mb-0">Tidak terdapat transaksi penjualan pada periode
                                            yang Anda pilih.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($penjualans->hasPages())
                <div class="card-footer bg-white border-top p-4">
                    {{ $penjualans->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
