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

        .form-control-terapos {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 10px 14px;
            font-size: 14px;
            color: #2d3748;
        }

        .form-control-terapos:focus {
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

        .stock-good {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-block;
        }

        .stock-critical {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
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

        .btn-edit {
            background-color: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .btn-edit:hover {
            background-color: rgba(245, 158, 11, 0.2);
            color: #d97706;
        }

        .btn-delete {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .btn-delete:hover {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        /* Modal Styling Kustom TERAPOS */
        .modal-terapos .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }

        .icon-box-danger {
            width: 65px;
            height: 65px;
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.85rem;
            margin: 0 auto 16px;
        }
    </style>

    <div class="container-fluid px-0">

        <!-- Header Halaman & Tombol Tambah -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Data Produk</h3>
                <p class="text-muted small mb-0">Kelola seluruh daftar barang dan katalog produk TERAPOS.</p>
            </div>
            <a href="{{ route('produk.create') }}" class="btn-add-product">
                <i class="bi bi-plus-circle-fill"></i> Tambah Produk
            </a>
        </div>

        <!-- Alert Notifikasi Flash Session -->
        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4 d-flex align-items-center gap-2"
                style="background-color: #d1e7dd; color: #0f5132;">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4 d-flex align-items-center gap-2"
                style="background-color: #f8d7da; color: #842029;">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <!-- Card Filter & Pencarian -->
        <div class="card card-terapos mb-4">
            <div class="card-header-blue">
                <i class="bi bi-funnel-fill"></i> Filter & Pencarian Data Produk
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('produk.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-10 col-md-9">
                            <label class="label-title">Kata Kunci Search</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"
                                    style="border-radius: 10px 0 0 10px;">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="search"
                                    class="form-control form-control-terapos border-start-0"
                                    placeholder="Cari kode, barcode, nama, merk, atau kategori..."
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <button type="submit"
                                class="btn btn-primary w-100 fw-semibold rounded-3 py-2 d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-filter"></i> Cari Produk
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card Tabel Data Produk -->
        <div class="card card-terapos">
            <div class="card-header-blue d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam-fill"></i>
                    Daftar Produk
                </div>
                <span class="badge bg-white text-primary rounded-pill px-3 py-1 font-monospace fw-bold"
                    style="font-size: 12px;">
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
                                <th class="text-center">Stok</th>
                                <th class="text-end">Harga Jual</th>
                                <th class="text-center pe-4" width="130">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td class="ps-4">
                                        <a href="{{ route('produk.show', $product->id) }}" class="product-code-badge">
                                            {{ $product->kode_produk }}
                                        </a>
                                    </td>
                                    <td class="fw-bold text-dark">
                                        {{ $product->nama_produk }}
                                    </td>
                                    <td>
                                        <span
                                            class="text-muted small">{{ $product->category?->nama_kategori ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($product->stok <= $product->stok_minimum)
                                            <span class="stock-critical">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                                {{ number_format($product->stok, 0, ',', '.') }} {{ $product->satuan }}
                                            </span>
                                        @else
                                            <span class="stock-good">
                                                <i class="bi bi-check-circle-fill me-1"></i>
                                                {{ number_format($product->stok, 0, ',', '.') }} {{ $product->satuan }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('produk.show', $product->id) }}" class="action-btn btn-view"
                                                title="Lihat Detail Produk">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="{{ route('produk.edit', $product->id) }}" class="action-btn btn-edit"
                                                title="Edit Produk">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <button type="button" class="action-btn btn-delete" title="Hapus Produk"
                                                onclick="confirmDelete('{{ route('produk.destroy', $product->id) }}', '{{ $product->nama_produk }}')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-box-seam fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                        <div class="fw-bold fs-6 text-dark">Tidak ada produk yang ditemukan</div>
                                        <p class="small text-muted mb-0">Coba ubah kata kunci pencarian Anda atau tambahkan
                                            produk baru.</p>
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

    <!-- MODAL KONFIRMASI HAPUS PRODUK (TERAPOS MODERN DESIGN) -->
    <div class="modal fade modal-terapos" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="icon-box-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Hapus Produk Ini?</h4>
                    <p class="text-muted small mb-3">
                        Apakah Anda yakin ingin menghapus produk <strong id="modal-product-name"
                            class="text-danger"></strong> dari sistem?
                    </p>
                    <div class="alert alert-warning border-0 rounded-3 text-start small p-3 mb-4"
                        style="background-color: #fff3cd; color: #664d03;">
                        <i class="bi bi-info-circle-fill me-1"></i> <strong>Peringatan:</strong> Tindakan ini tidak dapat
                        dibatalkan. Data produk akan terhapus secara permanen.
                    </div>

                    <form id="delete-form-modal" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-light px-4 py-2 rounded-3 fw-semibold border"
                                data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-danger px-4 py-2 rounded-3 fw-semibold">
                                Ya, Hapus Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDelete(deleteUrl, productName) {
                document.getElementById('delete-form-modal').action = deleteUrl;
                document.getElementById('modal-product-name').textContent = productName;

                var modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                modal.show();
            }
        </script>
    @endpush
@endsection
