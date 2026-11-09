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
            border-color: #198754;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.15);
        }

        /* Tombol Tambah Penjualan Hijau */
        .btn-add-sale {
            background: linear-gradient(135deg, #198754, #20c997);
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
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.2);
        }

        .btn-add-sale:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(25, 135, 84, 0.3);
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
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid #dcfce7;
        }

        .badge-trx:hover {
            background-color: #dcfce7;
            color: #166534;
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
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .btn-view:hover {
            background-color: rgba(25, 135, 84, 0.2);
            color: #198754;
        }

        .btn-delete {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .btn-delete:hover {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        /* Modal Styling TERAPOS */
        .modal-terapos .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }

        .icon-box-danger {
            width: 60px;
            height: 60px;
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin: 0 auto 16px;
        }
    </style>

    <div class="container-fluid px-0">

        <!-- Header Halaman & Tombol Aksi -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Riwayat Penjualan</h3>
                <p class="text-muted small mb-0">Kelola dan lihat seluruh daftar transaksi penjualan TERAPOS.</p>
            </div>
            <a href="{{ route('penjualan.create') }}" class="btn-add-sale">
                <i class="bi bi-plus-lg"></i> Buat Penjualan Baru
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
            <div class="card-header-green">
                <i class="bi bi-funnel-fill"></i> Filter & Pencarian Data
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('penjualan.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-5 col-md-6">
                            <label class="label-title">Kata Kunci Search</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"
                                    style="border-radius: 10px 0 0 10px;">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="search"
                                    class="form-control form-control-terapos border-start-0"
                                    placeholder="Nomor TRX, produk, kasir..." value="{{ $filters['search'] ?? '' }}">
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="start_date" class="label-title">Tanggal Mulai</label>
                            <input type="date" id="start_date" name="start_date"
                                class="form-control form-control-terapos" value="{{ $filters['start_date'] ?? '' }}">
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="end_date" class="label-title">Tanggal Akhir</label>
                            <input type="date" id="end_date" name="end_date" class="form-control form-control-terapos"
                                value="{{ $filters['end_date'] ?? '' }}">
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="user_id" class="label-title">Kasir / Operator</label>
                            <select id="user_id" name="user_id" class="form-select form-select-terapos">
                                <option value="">Semua User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected(($filters['user_id'] ?? '') == $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-1 col-md-12">
                            <button type="submit"
                                class="btn btn-success w-100 fw-semibold rounded-3 py-2 d-flex align-items-center justify-content-center gap-1"
                                title="Terapkan Filter">
                                <i class="bi bi-filter"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card Tabel Data Penjualan -->
        <div class="card card-terapos">
            <div class="card-header-green d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-receipt-cutoff"></i>
                    Daftar Penjualan
                </div>
                <span class="badge bg-white text-success rounded-pill px-3 py-1 font-monospace fw-bold"
                    style="font-size: 12px;">
                    Total: {{ $penjualans->total() }} Transaksi
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-terapos mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Nomor Transaksi</th>
                                <th>Tanggal</th>
                                <th class="text-center">Jenis Item</th>
                                <th class="text-center">Total Qty</th>
                                <th class="text-end">Total Nominal</th>
                                <th>Kasir</th>
                                <th class="text-center pe-4" width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penjualans as $penjualan)
                                <tr>
                                    <td class="ps-4">
                                        <a href="{{ route('penjualan.show', $penjualan) }}" class="badge-trx">
                                            {{ $penjualan->nomor_penjualan }}
                                        </a>
                                    </td>
                                    <td class="fw-semibold text-dark">
                                        {{ \Carbon\Carbon::parse($penjualan->tanggal)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="text-center text-muted fw-semibold">
                                        {{ $penjualan->detail_penjualans_count }} Jenis
                                    </td>
                                    <td class="text-center text-muted fw-semibold">
                                        {{ number_format($penjualan->detail_penjualans_sum_qty ?? 0, 0, ',', '.') }} pcs
                                    </td>
                                    <td class="text-end">
                                        <span class="amount-badge">
                                            Rp {{ number_format($penjualan->total, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="small text-muted">{{ $penjualan->user->name ?? '-' }}</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('penjualan.show', $penjualan) }}" class="action-btn btn-view"
                                                title="Lihat Detail">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>

                                            @if (auth()->user()->role === 'owner')
                                                <button type="button" class="action-btn btn-delete"
                                                    title="Batalkan Penjualan"
                                                    onclick="confirmDelete('{{ route('penjualan.destroy', $penjualan) }}', '{{ $penjualan->nomor_penjualan }}')">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-receipt fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                        <div class="fw-bold fs-6">Belum ada data transaksi</div>
                                        <p class="small text-muted mb-0">Tidak ada transaksi yang cocok dengan kriteria
                                            pencarian Anda.</p>
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

    <!-- MODAL KONFIRMASI PEMBATALAN/HAPUS -->
    <div class="modal fade modal-terapos" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="icon-box-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Batalkan Transaksi Ini?</h4>
                    <p class="text-muted small mb-3">
                        Anda yakin ingin membatalkan transaksi <strong id="modal-trx-number"
                            class="text-success"></strong>?
                    </p>
                    <div class="alert alert-warning border-0 rounded-3 text-start small p-3 mb-4"
                        style="background-color: #fff3cd; color: #664d03;">
                        <i class="bi bi-info-circle-fill me-1"></i> <strong>Catatan:</strong> Membatalkan transaksi ini
                        akan mengembalikan jumlah stok barang yang telah terjual ke stok gudang.
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
                                Ya, Batalkan Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDelete(deleteUrl, trxNumber) {
                document.getElementById('delete-form-modal').action = deleteUrl;
                document.getElementById('modal-trx-number').textContent = trxNumber;

                var modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                modal.show();
            }
        </script>
    @endpush
@endsection
