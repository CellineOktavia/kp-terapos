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

        /* Tombol Penyesuaian Stok Ungu Gradient */
        .btn-adjustment {
            background: linear-gradient(135deg, #6f42c1, #8b5cf6);
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
            box-shadow: 0 4px 12px rgba(111, 66, 193, 0.2);
        }

        .btn-adjustment:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(111, 66, 193, 0.3);
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

        .btn-view-purple {
            background-color: rgba(111, 66, 193, 0.1);
            color: #6f42c1;
        }

        .btn-view-purple:hover {
            background-color: rgba(111, 66, 193, 0.2);
            color: #6f42c1;
        }
    </style>

    <div class="container-fluid px-0">

        <!-- Header Halaman & Tombol Aksi -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Manajemen Stok</h3>
                <p class="text-muted small mb-0">Pantau jumlah dan status stok seluruh produk TERAPOS.</p>
            </div>
            @if (auth()->user()->role === 'owner')
                <a href="{{ route('stok.adjustment.create') }}" class="btn-adjustment">
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
            <div class="card-header-purple">
                <i class="bi bi-funnel-fill"></i> Filter & Pencarian Stok
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('stok.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-7 col-md-6">
                            <label for="stock-search" class="label-title">Cari Produk</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"
                                    style="border-radius: 10px 0 0 10px;">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input id="stock-search" type="search" name="search"
                                    class="form-control form-control-terapos border-start-0" value="{{ request('search') }}"
                                    placeholder="Kode, barcode, atau nama produk...">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="status" class="label-title">Status Stok</label>
                            <select id="status" name="status" class="form-select form-select-terapos">
                                @foreach (['semua' => 'Semua Status', 'aman' => 'Aman', 'menipis' => 'Stok Menipis', 'habis' => 'Habis'] as $value => $label)
                                    <option value="{{ $value }}" @selected(request('status', 'semua') === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-12">
                            <button type="submit"
                                class="btn btn-purple w-100 fw-semibold rounded-3 py-2 d-flex align-items-center justify-content-center gap-2 text-white"
                                style="background-color: #6f42c1;">
                                <i class="bi bi-filter"></i> Cari Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card Tabel Data Stok -->
        <div class="card card-terapos">
            <div class="card-header-purple d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam-fill"></i>
                    Daftar Stok Produk
                </div>
                <span class="badge bg-white text-purple rounded-pill px-3 py-1 font-monospace fw-bold"
                    style="color: #6f42c1; font-size: 12px;">
                    Total: {{ $products->total() }} Produk
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-terapos mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Kode Produk</th>
                                <th>Nama Produk</th>
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
                                        <span class="badge-code">
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
                                    <td class="text-center fw-bold fs-6 text-dark">
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
                                        @elseif ($product->stok <= $product->stok_minimum)
                                            <span class="badge-status-menipis">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> STOK MENIPIS
                                            </span>
                                        @else
                                            <span class="badge-status-aman">
                                                <i class="bi bi-check-circle-fill me-1"></i> AMAN
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-4">
                                        <a href="{{ route('produk.show', $product) }}" class="action-btn btn-view-purple"
                                            title="Lihat Detail Produk">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-archive fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                        <div class="fw-bold fs-6">Belum ada data stok produk</div>
                                        <p class="small text-muted mb-0">Tidak ada produk yang cocok dengan pencarian Anda.
                                        </p>
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
