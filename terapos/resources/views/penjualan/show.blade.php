@extends('app.master')

@section('content')
    <style>
        /* Custom Styling TERAPOS Bootstrap Presisi */
        .card-terapos {
            border: 1px solid #f0f0f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03) !important;
            background-color: #ffffff;
            overflow: hidden;
        }

        .card-terapos .card-header-blue {
            background-color: #0d6efd !important;
            /* Warna Biru Utama TERAPOS */
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
            margin-bottom: 4px;
            display: block;
        }

        .badge-trx {
            background-color: #ebf8ff;
            color: #3182ce;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-block;
            font-size: 13px;
        }

        .table-terapos th {
            font-size: 11px;
            font-weight: 700;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #edf2f7;
            padding-bottom: 12px;
        }

        .table-terapos td {
            padding: 16px 12px;
            border-bottom: 1px solid #f7fafc;
            color: #2d3748;
        }

        .btn-action-outline {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            color: #4a5568;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 8px 16px;
            border-radius: 10px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action-outline:hover {
            background-color: #f7fafc;
            color: #1a202c;
            border-color: #cbd5e0;
        }

        .total-amount-highlight {
            font-size: 1.75rem;
            font-weight: 800;
            color: #3182ce;
        }
    </style>

    <div class="container-fluid px-0">

        <!-- Header Halaman & Tombol Aksi -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Detail Penjualan</h3>
                <p class="text-muted small mb-0">Informasi lengkap transaksi {{ $penjualan->nomor_penjualan }}</p>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn-action-outline">
                    <i class="bi bi-printer"></i> Cetak Struk
                </button>
                <a href="{{ route('penjualan.index') }}" class="btn-action-outline">
                    Kembali
                </a>
            </div>
        </div>

        <!-- Grid 2 Kolom -->
        <div class="row g-4">

            <!-- Kolom Kiri: Informasi Transaksi -->
            <div class="col-lg-4">
                <div class="card card-terapos">
                    <div class="card-header-blue">
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
                                {{ \Carbon\Carbon::parse($penjualan->tanggal)->translatedFormat('d F Y') }}
                            </div>
                        </div>

                        <div>
                            <span class="label-title">User</span>
                            <div class="fw-semibold text-dark">
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
                        <div class="card-header-blue">
                            <i class="bi bi-box-seam-fill"></i>
                            Detail Barang
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-borderless table-terapos align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode</th>
                                            <th>Nama Barang</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Harga</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($penjualan->detailPenjualans as $detail)
                                            <tr>
                                                <td class="text-center text-muted font-monospace small">
                                                    {{ $detail->product->kode_produk ?? '-' }}
                                                </td>
                                                <td class="fw-bold text-dark">
                                                    {{ $detail->product->nama_produk ?? 'Barang tidak tersedia' }}
                                                </td>
                                                <td class="text-center text-muted">
                                                    {{ $detail->qty }} {{ $detail->product->satuan ?? 'pcs' }}
                                                </td>
                                                <td class="text-end text-muted">
                                                    Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end fw-bold text-dark">
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
                        <div class="card-header-blue">
                            <i class="bi bi-wallet2"></i>
                            Ringkasan Pembayaran
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Subtotal</span>
                                <span class="fw-semibold">
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
                                <span class="text-muted font-medium">Total Bayar</span>
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
