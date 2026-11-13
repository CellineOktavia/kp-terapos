@extends('app.master')

@section('content')
    <style>
        /* ==========================================
               TERAPOS SYSTEM UNIFIED DESIGN SYSTEM (GREEN THEME)
               ========================================== */
        .card-terapos {
            border: 1px solid #f0f0f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03) !important;
            background-color: #ffffff;
            overflow: hidden;
        }

        /* Card Header Hijau Utama TERAPOS */
        .card-terapos .card-header-green {
            background-color: #198754 !important;
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

        /* Badge Nomor Transaksi Hijau */
        .badge-trx {
            background-color: #f0fdf4;
            color: #15803d;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-block;
            font-size: 13px;
            border: 1px solid #dcfce7;
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
            background-color: #f4fbf7;
        }

        .btn-action-outline {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            color: #4a5568;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 10px 18px;
            border-radius: 12px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action-outline:hover {
            background-color: #f8fafc;
            color: #1a202c;
            border-color: #cbd5e0;
            transform: translateY(-1px);
        }

        .btn-print-green {
            background: linear-gradient(135deg, #198754, #20c997);
            border: none;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 10px 18px;
            border-radius: 12px;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.2);
        }

        .btn-print-green:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(25, 135, 84, 0.3);
        }

        .total-amount-highlight {
            font-size: 1.75rem;
            font-weight: 800;
            color: #198754;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>

    <div class="container-fluid px-0">

        <!-- Header Halaman & Tombol Aksi -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Detail Penjualan</h3>
                <p class="text-muted small mb-0">
                    Informasi lengkap transaksi <strong class="text-success">{{ $penjualan->nomor_penjualan }}</strong>
                </p>
            </div>
            <div class="d-flex gap-2 no-print">
                <button type="button" onclick="window.print()" class="btn-print-green">
                    <i class="bi bi-printer-fill"></i> Cetak Struk
                </button>
                <a href="{{ route('penjualan.index') }}" class="btn-action-outline">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Grid 2 Kolom -->
        <div class="row g-4">

            <!-- Kolom Kiri: Informasi Transaksi -->
            <div class="col-lg-4">
                <div class="card card-terapos">
                    <div class="card-header-green">
                        <i class="bi bi-info-circle-fill"></i>
                        Informasi Transaksi
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <span class="label-title">No. Penjualan</span>
                            <div class="badge-trx">
                                {{ $penjualan->nomor_penjualan }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <span class="label-title">Tanggal Transaksi</span>
                            <div class="fw-semibold text-dark">
                                <i class="bi bi-calendar3 text-muted me-1"></i>
                                {{ \Carbon\Carbon::parse($penjualan->tanggal)->translatedFormat('d F Y') }}
                            </div>
                        </div>

                        <div>
                            <span class="label-title">User / Operator</span>
                            <div class="fw-semibold text-dark">
                                <i class="bi bi-person-circle text-muted me-1"></i>
                                {{ $penjualan->user?->name ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Detail Barang & Ringkasan Pembayaran -->
            <div class="col-lg-8">
                <div class="d-flex flex-column gap-4">

                    <!-- Card Detail Barang -->
                    <div class="card card-terapos">
                        <div class="card-header-green d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-box-seam-fill"></i>
                                Detail Barang
                            </div>
                            <span class="badge bg-white text-success rounded-pill px-3 py-1 font-monospace fw-bold"
                                style="font-size: 12px;">
                                Total: {{ $penjualan->detailPenjualans->count() }} Item
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-borderless table-terapos align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center ps-4">Kode</th>
                                            <th>Nama Barang</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Harga</th>
                                            <th class="text-end pe-4">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($penjualan->detailPenjualans as $detail)
                                            <tr>
                                                <td class="text-center ps-4 text-muted font-monospace small">
                                                    {{ $detail->product->kode_produk ?? '-' }}
                                                </td>
                                                <td class="fw-bold text-dark">
                                                    {{ $detail->product->nama_produk ?? 'Barang tidak tersedia' }}
                                                </td>
                                                <td class="text-center text-muted fw-semibold">
                                                    {{ $detail->qty }} {{ $detail->product->satuan ?? 'pcs' }}
                                                </td>
                                                <td class="text-end text-muted">
                                                    Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end pe-4 fw-bold text-dark">
                                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Card Ringkasan Pembayaran -->
                    <div class="card card-terapos">
                        <div class="card-header-green">
                            <i class="bi bi-wallet2"></i>
                            Ringkasan Pembayaran
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Subtotal</span>
                                <span class="fw-semibold text-dark">
                                    Rp {{ number_format($penjualan->detailPenjualans->sum('subtotal'), 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">
                                    Diskon{{ (float) $penjualan->diskon_persen > 0 ? ' ' . rtrim(rtrim(number_format($penjualan->diskon_persen, 2, '.', ''), '0'), '.') . '%' : '' }}
                                </span>
                                <span class="fw-semibold text-success">
                                    Rp {{ number_format($penjualan->diskon_nominal, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom">
                                <span class="text-muted fw-semibold">Total Bayar</span>
                                <span class="total-amount-highlight">
                                    Rp {{ number_format($penjualan->total, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Uang Bayar</span>
                                <span class="fw-bold text-dark">
                                    Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Kembalian</span>
                                <span class="fw-bold text-dark">
                                    Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
