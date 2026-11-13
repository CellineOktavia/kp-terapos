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

        /* Card Header Biru Utama TERAPOS */
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

        /* Card Statistik Ringkasan Laporan */
        .stat-card-report {
            border: 1px solid #f0f0f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03) !important;
            background: #ffffff;
            padding: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: transform 0.2s ease;
        }

        .stat-card-report:hover {
            transform: translateY(-2px);
        }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }

        /* Badge Nomor Transaksi & Total */
        .badge-trx {
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

        .badge-trx:hover {
            background-color: #bee3f8;
            color: #2b6cb0;
        }

        .amount-badge {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.875rem;
            font-weight: 700;
            display: inline-block;
        }

        /* Tombol Download PDF Gradient Merah Soft */
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
            gap: 6px;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
        }

        .btn-pdf-download:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(220, 53, 69, 0.3);
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

        /* Tombol Aksi Mata Detail */
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

        <!-- Header Halaman & Tombol Download -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Laporan Penjualan</h3>
                <p class="text-muted small mb-0">Periode: <strong class="text-primary">{{ $period['label'] }}</strong></p>
            </div>
            <a href="{{ route('laporan.penjualan.pdf', request()->query()) }}" class="btn-pdf-download">
                <i class="bi bi-file-earmark-pdf-fill"></i> Cetak / Download PDF
            </a>
        </div>

        <!-- Partial Filter Periode -->
        <div class="mb-4">
            @include('laporan.partials.period-filter', [
                'reportRoute' => 'laporan.penjualan',
                'period' => $period,
            ])
        </div>

        <!-- Ringkasan Statistik Laporan (3 Kolom) -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card-report border-start border-primary border-4">
                    <span class="label-title text-primary">Total Omzet Penjualan</span>
                    <div class="stat-value text-primary">
                        Rp {{ number_format($summary['total'], 0, ',', '.') }}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-report border-start border-success border-4">
                    <span class="label-title text-success">Total Transaksi</span>
                    <div class="stat-value text-dark">
                        {{ number_format($summary['transactions'], 0, ',', '.') }} <span
                            class="fs-6 fw-normal text-muted">Transaksi</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-report border-start border-info border-4">
                    <span class="label-title text-info">Total Item Terjual</span>
                    <div class="stat-value text-dark">
                        {{ number_format($summary['qty'], 0, ',', '.') }} <span class="fs-6 fw-normal text-muted">pcs</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Tabel Data Laporan Penjualan -->
        <div class="card card-terapos">
            <div class="card-header-blue d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    Rincian Transaksi Penjualan
                </div>
                <span class="badge bg-white text-primary rounded-pill px-3 py-1 font-monospace fw-bold"
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
                                    <td class="fw-semibold text-dark">
                                        {{ $penjualan->tanggal?->translatedFormat('d M Y') ?? '-' }}
                                    </td>
                                    <td class="text-center text-muted fw-semibold">
                                        {{ number_format($penjualan->detail_penjualans_count, 0, ',', '.') }} Jenis
                                    </td>
                                    <td class="text-center text-muted fw-semibold">
                                        {{ number_format($penjualan->detail_penjualans_sum_qty ?? 0, 0, ',', '.') }} pcs
                                    </td>
                                    <td class="text-end">
                                        Rp {{ number_format($penjualan->detailPenjualans->sum('subtotal'), 0, ',', '.') }}
                                    </td>
                                    <td class="text-end text-success">
                                        @if ((float) $penjualan->diskon_persen > 0)
                                            {{ rtrim(rtrim(number_format($penjualan->diskon_persen, 2, '.', ''), '0'), '.') }}%<br>
                                        @endif
                                        Rp {{ number_format($penjualan->diskon_nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end">
                                        <span class="amount-badge">
                                            Rp {{ number_format($penjualan->total, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-end text-muted fw-semibold">
                                        Rp {{ number_format($penjualan->bayar ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end text-muted fw-semibold">
                                        Rp {{ number_format($penjualan->kembalian ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="small text-muted">{{ $penjualan->user?->name ?? '-' }}</span>
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
                                        <i
                                            class="bi bi-file-earmark-bar-graph fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                        <div class="fw-bold fs-6">Tidak ada data transaksi</div>
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
                <div class="card-footer bg-white border-top-0 p-4">
                    {{ $penjualans->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
