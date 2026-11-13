@extends('app.master')

@section('content')
    <style>
        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }

        .terapos-page-header {
            background: linear-gradient(135deg, #ffffff 0%, #fef2f2 100%);
            border: 1px solid #fecaca;
            border-radius: 18px;
            padding: 20px 24px;
            box-shadow: 0 4px 20px rgba(220, 38, 38, 0.05);
        }

        .header-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.25);
            flex-shrink: 0;
        }

        .status-pill-header {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: #ffffff;
            border: 1px solid #fca5a5;
            color: #b91c1c;
            font-size: 12.5px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 999px;
            margin-top: 4px;
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #dc2626;
            display: inline-block;
        }

        .card-terapos {
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03) !important;
            background-color: #ffffff;
            overflow: hidden;
        }

        .card-terapos .card-header-red {
            background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%) !important;
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

        .form-control-terapos,
        .form-select-terapos {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 0.875rem;
            height: 40px;
        }

        .form-control-terapos:focus,
        .form-select-terapos:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.14);
        }

        .stat-card-report {
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03) !important;
            background: #ffffff;
            padding: 18px 20px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            transition: all 0.2s ease;
        }

        .stat-card-report:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06) !important;
        }

        .stat-icon-box {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .stat-value {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.4px;
            line-height: 1.2;
        }

        .badge-code {
            background-color: #f8fafc;
            color: #0f172a;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-weight: 700;
            padding: 4px 9px;
            border-radius: 7px;
            font-size: 12.5px;
            border: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .badge-status-out {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .badge-status-low {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fcd34d;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .btn-pdf-download {
            background: linear-gradient(135deg, #b91c1c, #dc2626);
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
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.22);
        }

        .btn-pdf-download:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(220, 38, 38, 0.32);
        }

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
            padding: 14px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .table-terapos tbody tr:hover {
            background-color: #fef2f2;
        }
    </style>

    <div class="container-fluid px-0">

        <!-- Header Halaman -->
        <div class="terapos-page-header d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon-wrapper">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0" style="letter-spacing: -0.3px;">Laporan Stok Kritis</h3>
                    <div class="status-pill-header">
                        <span class="pulse-dot"></span>
                        <span>Barang Habis & Mendekati Batas Minimum</span>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('laporan.stok') }}"
                    class="btn btn-outline-secondary rounded-3 fw-semibold px-3 d-inline-flex align-items-center gap-2">
                    <i class="bi bi-arrow-left"></i>
                    <span>Semua Stok</span>
                </a>
                <a href="{{ route('laporan.stok-kritis.pdf', request()->query()) }}" class="btn-pdf-download">
                    <i class="bi bi-file-earmark-pdf-fill"></i>
                    <span>Cetak / Download PDF</span>
                </a>
            </div>
        </div>

        <!-- Card Filter -->
        <div class="card card-terapos mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('laporan.stok-kritis') }}" class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label for="search" class="label-title">Cari Barang</label>
                        <input type="text" id="search" name="search" class="form-control form-control-terapos"
                            placeholder="Kode, barcode, atau nama barang..." value="{{ $filters['search'] ?? '' }}">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="category_id" class="label-title">Kategori</label>
                        <select id="category_id" name="category_id" class="form-select form-select-terapos">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories ?? \App\Models\Category::orderBy('nama_kategori')->get() as $category)
                                <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>
                                    {{ $category->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="type" class="label-title">Kondisi Stok</label>
                        <select id="type" name="type" class="form-select form-select-terapos">
                            <option value="all" @selected(($filters['type'] ?? 'all') === 'all')>Semua Kritis (Habis & Menipis)</option>
                            <option value="out" @selected(($filters['type'] ?? '') === 'out')>Stok Habis (0)</option>
                            <option value="low" @selected(($filters['type'] ?? '') === 'low')>Stok Menipis (≤ Minimum)</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 d-flex gap-2">
                        <button type="submit" class="btn btn-danger fw-semibold rounded-3 flex-grow-1"
                            style="height: 40px;">
                            <i class="bi bi-funnel-fill me-1"></i> Filter
                        </button>
                        <a href="{{ route('laporan.stok-kritis') }}"
                            class="btn btn-outline-secondary rounded-3 d-inline-flex align-items-center justify-content-center"
                            style="height: 40px; width: 42px;" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ringkasan Statistik (4 Kolom) -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-report border-start border-danger border-4">
                    <div>
                        <span class="label-title text-danger">Total Stok Kritis</span>
                        <div class="stat-value text-danger tabular-nums">
                            {{ number_format($summary['critical'], 0, ',', '.') }}
                            <span class="fs-6 fw-normal text-muted">Item</span>
                        </div>
                    </div>
                    <div class="stat-icon-box" style="background-color: #fee2e2; color: #b91c1c;">
                        <i class="bi bi-exclamation-circle-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-report border-start border-danger border-4">
                    <div>
                        <span class="label-title text-danger">Stok Habis (Kosong)</span>
                        <div class="stat-value text-danger tabular-nums">
                            {{ number_format($summary['out'], 0, ',', '.') }}
                            <span class="fs-6 fw-normal text-muted">Item</span>
                        </div>
                    </div>
                    <div class="stat-icon-box" style="background-color: #fecaca; color: #991b1b;">
                        <i class="bi bi-x-octagon-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-report border-start border-warning border-4">
                    <div>
                        <span class="label-title text-warning">Stok Menipis</span>
                        <div class="stat-value tabular-nums">
                            {{ number_format($summary['low'], 0, ',', '.') }}
                            <span class="fs-6 fw-normal text-muted">Item</span>
                        </div>
                    </div>
                    <div class="stat-icon-box" style="background-color: #fef3c7; color: #b45309;">
                        <i class="bi bi-graph-down-arrow"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card-report border-start border-danger border-4">
                    <div>
                        <span class="label-title text-danger">Est. Biaya Restock Min.</span>
                        <div class="stat-value text-danger tabular-nums">
                            Rp {{ number_format($summary['restockEstimate'], 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="stat-icon-box" style="background-color: #fee2e2; color: #dc2626;">
                        <i class="bi bi-cart-plus-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data Stok Kritis -->
        <div class="card card-terapos">
            <div class="card-header-red d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clipboard2-pulse-fill"></i>
                    <span>Daftar Barang Perlu Restock</span>
                </div>
                <span class="badge bg-white text-danger rounded-pill px-3 py-1 font-monospace fw-bold shadow-sm"
                    style="font-size: 12px;">
                    {{ $products->total() }} Barang
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
                                <th class="text-center">Stok Saat Ini</th>
                                <th class="text-center">Stok Min.</th>
                                <th class="text-center">Saran Order Min.</th>
                                <th class="text-end">Harga Beli</th>
                                <th class="text-end">Est. Biaya Order</th>
                                <th class="text-center pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                @php
                                    $saranOrder = max($product->stok_minimum - $product->stok, 1);
                                    $estimasiBiaya = $saranOrder * $product->harga_beli;
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge-code">{{ $product->kode_produk }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $product->nama_produk }}</div>
                                        @if ($product->barcode)
                                            <div class="small text-muted font-monospace">{{ $product->barcode }}</div>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $product->category?->nama_kategori ?? '-' }}</td>
                                    <td
                                        class="text-center fw-bold tabular-nums {{ (int) $product->stok === 0 ? 'text-danger' : 'text-warning' }}">
                                        {{ number_format($product->stok, 0, ',', '.') }} {{ $product->satuan }}
                                    </td>
                                    <td class="text-center text-muted tabular-nums">
                                        {{ number_format($product->stok_minimum, 0, ',', '.') }} {{ $product->satuan }}
                                    </td>
                                    <td class="text-center fw-semibold text-danger tabular-nums">
                                        +{{ number_format($saranOrder, 0, ',', '.') }} {{ $product->satuan }}
                                    </td>
                                    <td class="text-end text-muted tabular-nums text-nowrap">
                                        Rp {{ number_format($product->harga_beli, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end fw-bold text-dark tabular-nums text-nowrap">
                                        Rp {{ number_format($estimasiBiaya, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-4">
                                        @if ((int) $product->stok === 0)
                                            <span class="badge-status-out">HABIS</span>
                                        @else
                                            <span class="badge-status-low">MENIPIS</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                                            style="width: 64px; height: 64px; background-color: #fee2e2;">
                                            <i class="bi bi-check-circle-fill fs-2 text-danger"></i>
                                        </div>
                                        <div class="fw-bold fs-6 text-dark">Semua Stok Aman!</div>
                                        <p class="small text-muted mb-0">Tidak ada barang dengan stok habis maupun menipis
                                            saat ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($products->hasPages())
                <div class="card-footer bg-white border-top p-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
