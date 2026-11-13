@extends('app.master')

@section('content')
    <style>
        /* ==========================================
           TERAPOS SYSTEM UNIFIED DESIGN SYSTEM (RED / CRITICAL THEME)
           ========================================== */
        .card-terapos {
            border: 1px solid #f0f0f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03) !important;
            background-color: #ffffff;
            overflow: hidden;
        }

        /* Card Header Merah Utama TERAPOS */
        .card-terapos .card-header-red {
            background-color: #dc3545 !important;
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

        .form-control-terapos {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 10px 14px;
            font-size: 14px;
            color: #2d3748;
        }

        .form-control-terapos:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15);
        }

        /* Tombol Penyesuaian Stok Merah/Danger Gradient */
        .btn-adjustment-danger {
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

        .btn-adjustment-danger:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(220, 53, 69, 0.3);
        }

        /* Badge Kode Barang Merah Soft */
        .badge-code-danger {
            background-color: #fef2f2;
            color: #dc3545;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-block;
            font-size: 13px;
            text-decoration: none;
            border: 1px solid #fee2e2;
        }

        /* Status Stok Badges */
        .badge-status-menipis {
            background-color: rgba(255, 193, 7, 0.15);
            color: #d97706;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            display: inline-block;
        }

        .badge-status-habis {
            background-color: rgba(220, 53, 69, 0.12);
            color: #dc3545;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.78rem;
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
            background-color: #fff5f5;
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

        .btn-view-red {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .btn-view-red:hover {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }
    </style>

    <div class="container-fluid px-0">

        <!-- Header Halaman & Tombol Aksi -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Stok Kritis</h3>
                <p class="text-muted small mb-0">Daftar barang dengan jumlah stok sama dengan atau di bawah batas minimum.
                </p>
            </div>
            @if (auth()->user()->role === 'owner')
                <a href="{{ route('stok.adjustment.create') }}" class="btn-adjustment-danger">
                    <i class="bi bi-sliders"></i> Penyesuaian Stok
                </a>
            @endif
        </div>

        <!-- Alert Notifikasi Flash Session -->
        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4 d-flex align-items-center gap-2"
                style="background-color: #d1e7dd; color: #0f5132;">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4"
                style="background-color: #f8d7da; color: #842029;">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Terjadi kesalahan:</div>
                <ul class="mb-0 ps-3 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Filter & Pencarian -->
        <div class="card card-terapos mb-4">
            <div class="card-header-red">
                <i class="bi bi-funnel-fill"></i> Filter Pencarian Stok Kritis
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('stok.kritis') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-10 col-md-9">
                            <label for="critical-search" class="label-title">Cari Barang Kritis</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"
                                    style="border-radius: 10px 0 0 10px;">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input id="critical-search" type="search" name="search"
                                    class="form-control form-control-terapos border-start-0" value="{{ request('search') }}"
                                    placeholder="Kode barang, barcode, atau nama barang...">
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-3">
                            <button type="submit"
                                class="btn btn-danger w-100 fw-semibold rounded-3 py-2 d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-filter"></i> Cari Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card Tabel Data Stok Kritis -->
        <div class="card card-terapos">
            <div class="card-header-red d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Daftar Barang Stok Kritis
                </div>
                <span class="badge bg-white text-danger rounded-pill px-3 py-1 font-monospace fw-bold"
                    style="font-size: 12px;">
                    Total: {{ $products->total() }} Barang Perlu Perhatian
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-terapos mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Stok Saat Ini</th>
                                <th class="text-center">Stok Minimum</th>
                                <th class="text-center">Status</th>
                                <th class="text-center pe-4" width="90">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge-code-danger">
                                            {{ $product->kode_produk }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-dark">
                                        {{ $product->nama_produk }}
                                    </td>
                                    <td>
                                        <span
                                            class="text-muted small">{{ $product->category?->nama_kategori ?? '-' }}</span>
                                    </td>
                                    <td class="text-center text-muted fw-semibold">
                                        {{ $product->satuan }}
                                    </td>
                                    <td class="text-center fw-bold fs-6 text-danger">
                                        {{ number_format($product->stok, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center text-muted fw-semibold">
                                        {{ number_format($product->stok_minimum, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if ((int) $product->stok === 0)
                                            <span class="badge-status-habis">
                                                <i class="bi bi-x-circle-fill me-1"></i> HABIS
                                            </span>
                                        @else
                                            <span class="badge-status-menipis">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> STOK MENIPIS
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-4">
                                        <a href="{{ route('produk.show', $product) }}" class="action-btn btn-view-red"
                                            title="Lihat Detail Barang">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-shield-check fs-1 d-block mb-3 text-success opacity-75"></i>
                                        <div class="fw-bold fs-6 text-dark">Tidak ada stok kritis!</div>
                                        <p class="small text-muted mb-0">Semua stok barang saat ini dalam kondisi aman di
                                            atas batas minimum.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($products->hasPages())
                <div class="card-footer bg-white border-top-0 p-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
