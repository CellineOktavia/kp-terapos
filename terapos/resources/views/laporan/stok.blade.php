@extends('app.master')

@section('content')
    <style>
        /* ==========================================
           TERAPOS SYSTEM UNIFIED DESIGN SYSTEM (PURPLE THEME)
           ========================================== */
        .card-terapos {
            border: 1px solid #f0f0f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03) !important;
            background-color: #ffffff;
            overflow: hidden;
        }

        /* Card Header Ungu Utama TERAPOS */
        .card-terapos .card-header-purple {
            background-color: #6f42c1 !important;
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
            border-color: #6f42c1;
            box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.15);
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

        /* Badge Kode Produk */
        .badge-code {
            background-color: #f3e8ff;
            color: #6f42c1;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-block;
            font-size: 13px;
            text-decoration: none;
            border: 1px solid #e9d5ff;
        }

        /* Status Stok Badges */
        .badge-status-aman {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            display: inline-block;
        }

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
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            display: inline-block;
        }

        /* Tombol Download PDF Gradient Merah */
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

        .btn-purple {
            background-color: #6f42c1;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            transition: all 0.25s ease;
        }

        .btn-purple:hover {
            background-color: #59339d;
            color: #ffffff;
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
            background-color: #fcfaff;
        }
    </style>

    <div class="container-fluid px-0">

        <!-- Header Halaman & Tombol Download -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Laporan Persediaan Stok</h3>
                <p class="text-muted small mb-0">Pantau kondisi dan estimasi nilai persediaan seluruh produk TERAPOS saat
                    ini.</p>
            </div>
            <a href="{{ route('laporan.stok.pdf', request()->query()) }}" class="btn-pdf-download">
                <i class="bi bi-file-earmark-pdf-fill"></i> Cetak / Download PDF
            </a>
        </div>

        <!-- Ringkasan Statistik Laporan Stok (3 Kolom) -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card-report border-start border-4" style="border-color: #6f42c1 !important;">
                    <span class="label-title" style="color: #6f42c1;">Total Produk Register</span>
                    <div class="stat-value text-dark">
                        {{ number_format($summary['products'], 0, ',', '.') }} <span
                            class="fs-6 fw-normal text-muted">Item</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-report border-start border-danger border-4">
                    <span class="label-title text-danger">Stok Kritis / Perlu Attention</span>
                    <div class="stat-value text-danger">
                        {{ number_format($summary['critical'], 0, ',', '.') }} <span
                            class="fs-6 fw-normal text-muted">Produk</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-report border-start border-success border-4">
                    <span class="label-title text-success">Estimasi Nilai Persediaan (Harga Beli)</span>
                    <div class="stat-value text-success">
                        Rp {{ number_format($summary['inventoryValue'], 0, ',', '.') }}
                    </div>
                    <small class="text-muted" style="font-size: 11px;">*Kalkulasi berdasarkan total stok × harga beli
                        produk.</small>
                </div>
            </div>
        </div>

        <!-- Card Filter & Pencarian -->
        <div class="card card-terapos mb-4">
            <div class="card-header-purple">
                <i class="bi bi-funnel-fill"></i> Filter & Pencarian Laporan Stok
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('laporan.stok') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-5 col-md-6">
                            <label for="stock-search" class="label-title">Cari Produk</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"
                                    style="border-radius: 10px 0 0 10px;">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input id="stock-search" type="search" name="search"
                                    class="form-control form-control-terapos border-start-0"
                                    value="{{ $filters['search'] ?? '' }}" placeholder="Kode, barcode, atau nama produk...">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="category_id" class="label-title">Kategori</label>
                            <select id="category_id" name="category_id" class="form-select form-select-terapos">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>
                                        {{ $category->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="status" class="label-title">Status Stok</label>
                            <select id="status" name="status" class="form-select form-select-terapos">
                                @foreach (['all' => 'Semua Status', 'safe' => 'Aman', 'low' => 'Stok Menipis', 'out' => 'Habis'] as $key => $label)
                                    <option value="{{ $key }}" @selected(($filters['status'] ?? 'all') === $key)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <button type="submit"
                                class="btn btn-purple w-100 fw-semibold rounded-3 py-2 d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-filter"></i> Terapkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card Tabel Data Laporan Stok -->
        <div class="card card-terapos">
            <div class="card-header-purple d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-boxes"></i>
                    Rincian Persediaan Produk
                </div>
                <span class="badge bg-white rounded-pill px-3 py-1 font-monospace fw-bold"
                    style="color: #6f42c1; font-size: 12px;">
                    Total: {{ $products->total() }} Produk
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-terapos mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Kode</th>
                                <th>Barcode</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Min.</th>
                                <th class="text-end">Harga Beli</th>
                                <th class="text-end">Harga Jual</th>
                                <th class="text-center pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge-code">
                                            {{ $product->kode_produk }}
                                        </span>
                                    </td>
                                    <td class="font-monospace small text-muted">
                                        {{ $product->barcode ?? '-' }}
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
                                    <td class="text-center fw-bold fs-6 text-dark">
                                        {{ number_format($product->stok, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center text-muted fw-semibold">
                                        {{ number_format($product->stok_minimum, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end text-muted fw-semibold">
                                        Rp {{ number_format($product->harga_beli, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-4">
                                        @if ((int) $product->stok === 0)
                                            <span class="badge-status-habis">
                                                <i class="bi bi-x-circle-fill me-1"></i> HABIS
                                            </span>
                                        @elseif ($product->stok <= $product->stok_minimum)
                                            <span class="badge-status-menipis">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> MENIPIS
                                            </span>
                                        @else
                                            <span class="badge-status-aman">
                                                <i class="bi bi-check-circle-fill me-1"></i> AMAN
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5 text-muted">
                                        <i class="bi bi-archive fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                        <div class="fw-bold fs-6 text-dark">Tidak ada data persediaan</div>
                                        <p class="small text-muted mb-0">Tidak terdapat produk yang sesuai dengan kriteria
                                            filter Anda.</p>
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
