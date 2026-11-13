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

        .btn-back-terapos {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            color: #4a5568;
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-back-terapos:hover {
            background-color: #f8fafc;
            color: #1a202c;
            border-color: #cbd5e0;
            transform: translateY(-1px);
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

        .note-box-terapos {
            background-color: #f8fafc;
            border: 1px solid #edf2f7;
            border-radius: 12px;
            padding: 12px 16px;
            color: #2d3748;
            font-size: 14px;
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
    </style>

    <div class="container-fluid px-0">

        <!-- Header Halaman & Tombol Kembali -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Detail Penerimaan Barang</h3>
                <p class="text-muted small mb-0">Rincian transaksi barang masuk <strong
                        class="text-primary">{{ $penerimaan->nomor_penerimaan }}</strong></p>
            </div>
            <a href="{{ route('penerimaan.index') }}" class="btn-back-terapos">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Card Informasi Utama Penerimaan -->
        <div class="card card-terapos mb-4">
            <div class="card-header-blue">
                <i class="bi bi-file-earmark-text-fill"></i> Informasi Penerimaan Barang
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <span class="label-title">No. Penerimaan</span>
                        <span class="product-code-badge">{{ $penerimaan->nomor_penerimaan }}</span>
                    </div>
                    <div class="col-md-4">
                        <span class="label-title">Tanggal Penerimaan</span>
                        <div class="fw-bold text-dark fs-6">
                            <i class="bi bi-calendar3 text-muted me-1"></i>
                            {{ $penerimaan->tanggal?->translatedFormat('d F Y') ?? '-' }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <span class="label-title">Dicatat Oleh</span>
                        <div class="fw-bold text-dark fs-6">
                            <i class="bi bi-person-circle text-muted me-1"></i>
                            {{ $penerimaan->user?->name ?? '-' }}
                        </div>
                    </div>
                    <div class="col-12">
                        <span class="label-title">Keterangan</span>
                        <div class="note-box-terapos">
                            {{ $penerimaan->keterangan ?: 'Tidak ada keterangan tambahan.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Tabel Barang yang Diterima -->
        <div class="card card-terapos">
            <div class="card-header-blue d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam-fill"></i>
                    Barang yang Diterima
                </div>
                <span class="badge bg-white text-primary rounded-pill px-3 py-1 font-monospace fw-bold"
                    style="font-size: 12px;">
                    Total: {{ $penerimaan->detailPenerimaanBarangs->count() }} Item
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-terapos mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4" width="180">Kode Barang</th>
                                <th>Nama Barang</th>
                                <th class="text-end pe-4" width="200">Qty Diterima</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penerimaan->detailPenerimaanBarangs as $detail)
                                <tr>
                                    <td class="ps-4">
                                        @if ($detail->product?->kode_produk)
                                            <span class="product-code-badge">
                                                {{ $detail->product->kode_produk }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-dark">
                                        {{ $detail->product?->nama_produk ?? 'Barang tidak tersedia' }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="qty-badge-in">
                                            <i class="bi bi-plus-circle-fill me-1"></i>
                                            {{ number_format($detail->qty, 0, ',', '.') }}
                                            {{ $detail->product?->satuan ?? '' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="bi bi-box-seam fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                        <div class="fw-bold fs-6 text-dark">Tidak ada rincian barang</div>
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
