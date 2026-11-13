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

        .count-badge {
            background-color: #ebf8ff;
            color: #3182ce;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 999px;
            display: inline-block;
            font-size: 0.85rem;
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
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 10px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            flex-shrink: 0;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .btn-save {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .btn-save:hover {
            background-color: rgba(13, 110, 253, 0.2);
            color: #0d6efd;
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
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Data Kategori</h3>
                <p class="text-muted small mb-0">Kelola kategori barang TERAPOS.</p>
            </div>
            <a href="#form-kategori" class="btn-add-product" onclick="document.getElementById('nama_kategori').focus();">
                <i class="bi bi-plus-circle-fill"></i> Tambah Kategori
            </a>
        </div>

        <!-- Alert Notifikasi Flash Session & Validasi -->
        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4 d-flex align-items-center gap-2"
                style="background-color: #d1e7dd; color: #0f5132;">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4 d-flex align-items-start gap-2"
                style="background-color: #f8d7da; color: #842029;">
                <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Tambah Kategori Baru -->
        <div class="card card-terapos mb-4" id="form-kategori">
            <div class="card-header-blue">
                <i class="bi bi-folder-plus"></i> Tambah Kategori Baru
            </div>
            <div class="card-body p-4">
                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-10 col-md-9">
                            <label for="nama_kategori" class="label-title">Nama Kategori</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"
                                    style="border-radius: 10px 0 0 10px;">
                                    <i class="bi bi-tag-fill"></i>
                                </span>
                                <input id="nama_kategori" type="text" name="nama_kategori"
                                    class="form-control form-control-terapos border-start-0"
                                    placeholder="Masukkan nama kategori baru..." value="{{ old('nama_kategori') }}"
                                    maxlength="100" required>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <button type="submit"
                                class="btn btn-primary w-100 fw-semibold rounded-3 py-2 d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-plus-lg"></i> Tambah
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card Tabel Daftar Kategori -->
        <div class="card card-terapos">
            <div class="card-header-blue d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-tags-fill"></i>
                    Daftar Kategori
                </div>
                <span class="badge bg-white text-primary rounded-pill px-3 py-1 font-monospace fw-bold"
                    style="font-size: 12px;">
                    Total: {{ $categories->count() }} Kategori
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-terapos mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Nama Kategori</th>
                                <th class="text-center" width="180">Jumlah Barang</th>
                                <th class="pe-4" style="min-width: 340px">Ubah & Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">
                                        {{ $category->nama_kategori }}
                                    </td>
                                    <td class="text-center">
                                        <span class="count-badge">
                                            <i class="bi bi-box-seam me-1"></i>
                                            {{ number_format($category->products_count, 0, ',', '.') }} Barang
                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <form action="{{ route('kategori.update', $category) }}" method="POST"
                                                class="d-flex align-items-center gap-2 flex-grow-1">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="nama_kategori"
                                                    class="form-control form-control-terapos py-2"
                                                    value="{{ $category->nama_kategori }}" maxlength="100" required
                                                    aria-label="Nama kategori">
                                                <button type="submit" class="action-btn btn-save"
                                                    title="Simpan Perubahan Kategori">
                                                    <i class="bi bi-check-lg fs-5"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="action-btn btn-delete" title="Hapus Kategori"
                                                onclick="confirmDelete('{{ route('kategori.destroy', $category) }}', '{{ addslashes($category->nama_kategori) }}')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="bi bi-tags fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                        <div class="fw-bold fs-6 text-dark">Belum ada kategori</div>
                                        <p class="small text-muted mb-0">Silakan tambahkan kategori baru menggunakan
                                            formulir di atas.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- MODAL KONFIRMASI HAPUS KATEGORI (TERAPOS MODERN DESIGN) -->
    <div class="modal fade modal-terapos" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="icon-box-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Hapus Kategori Ini?</h4>
                    <p class="text-muted small mb-3">
                        Apakah Anda yakin ingin menghapus kategori <strong id="modal-category-name"
                            class="text-danger"></strong> dari sistem?
                    </p>
                    <div class="alert alert-warning border-0 rounded-3 text-start small p-3 mb-4"
                        style="background-color: #fff3cd; color: #664d03;">
                        <i class="bi bi-info-circle-fill me-1"></i> <strong>Peringatan:</strong> Barang yang terhubung
                        dengan kategori ini akan menjadi <strong>tanpa kategori</strong>.
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
                                Ya, Hapus Kategori
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDelete(deleteUrl, categoryName) {
                document.getElementById('delete-form-modal').action = deleteUrl;
                document.getElementById('modal-category-name').textContent = categoryName;

                var modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                modal.show();
            }
        </script>
    @endpush
@endsection
