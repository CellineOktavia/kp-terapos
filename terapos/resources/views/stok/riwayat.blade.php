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

        /* Badge Kode Barang Ungu Soft */
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

        /* Badge Pergerakan Stok */
        .badge-jenis-masuk {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-jenis-keluar {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
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
    </style>

    <div class="container-fluid px-0">

        <!-- Header Halaman -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Riwayat Stok</h3>
                <p class="text-muted small mb-0">Riwayat seluruh pergerakan stok masuk dan keluar barang.</p>
            </div>
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
                <i class="bi bi-funnel-fill"></i> Filter & Pencarian Riwayat Stok
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('stok.riwayat') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-4 col-md-6">
                            <label for="movement-search" class="label-title">Kata Kunci Search</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"
                                    style="border-radius: 10px 0 0 10px;">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input id="movement-search" type="search" name="search"
                                    class="form-control form-control-terapos border-start-0" value="{{ request('search') }}"
                                    placeholder="Kode barang, nama barang, atau keterangan...">
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="jenis" class="label-title">Jenis Pergerakan</label>
                            <select id="jenis" name="jenis" class="form-select form-select-terapos">
                                <option value="">Semua Jenis</option>
                                <option value="masuk" @selected(request('jenis') === 'masuk')>Masuk (Stok +)</option>
                                <option value="keluar" @selected(request('jenis') === 'keluar')>Keluar (Stok -)</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="mulai" class="label-title">Tanggal Mulai</label>
                            <input id="mulai" type="date" name="mulai" class="form-control form-control-terapos"
                                value="{{ request('mulai') }}">
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="sampai" class="label-title">Tanggal Akhir</label>
                            <input id="sampai" type="date" name="sampai" class="form-control form-control-terapos"
                                value="{{ request('sampai') }}">
                        </div>

                        <div class="col-lg-2 col-md-12">
                            <button type="submit"
                                class="btn btn-purple w-100 fw-semibold rounded-3 py-2 d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-filter"></i> Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card Tabel Data Riwayat Stok -->
        <div class="card card-terapos">
            <div class="card-header-purple d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history"></i>
                    Daftar Aktivitas Stok
                </div>
                <span class="badge bg-white rounded-pill px-3 py-1 font-monospace fw-bold"
                    style="color: #6f42c1; font-size: 12px;">
                    Total: {{ $movements->total() }} Aktivitas
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-terapos mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Tanggal & Waktu</th>
                                <th class="text-center">Kode Barang</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Jenis</th>
                                <th class="text-center">Qty</th>
                                <th class="pe-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($movements as $movement)
                                <tr>
                                    <td class="ps-4 fw-semibold text-dark">
                                        <i class="bi bi-calendar3 me-1 text-muted"></i>
                                        {{ $movement->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-code">
                                            {{ $movement->product?->kode_produk ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-dark">
                                        {{ $movement->product?->nama_produk ?? 'Barang tidak tersedia' }}
                                    </td>
                                    <td class="text-center">
                                        @if ($movement->jenis === 'masuk')
                                            <span class="badge-jenis-masuk">
                                                <i class="bi bi-arrow-down-left-circle-fill"></i> MASUK
                                            </span>
                                        @else
                                            <span class="badge-jenis-keluar">
                                                <i class="bi bi-arrow-up-right-circle-fill"></i> KELUAR
                                            </span>
                                        @endif
                                    </td>
                                    <td
                                        class="text-center fw-bold fs-6 {{ $movement->jenis === 'masuk' ? 'text-success' : 'text-danger' }}">
                                        {{ $movement->jenis === 'masuk' ? '+' : '-' }}{{ number_format($movement->qty, 0, ',', '.') }}
                                    </td>
                                    <td class="pe-4 text-muted small">
                                        {{ $movement->keterangan ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-clock-history fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                        <div class="fw-bold fs-6 text-dark">Belum ada riwayat pergerakan stok</div>
                                        <p class="small text-muted mb-0">Tidak ada riwayat aktivitas stok yang cocok dengan
                                            filter Anda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($movements->hasPages())
                <div class="card-footer bg-white border-top-0 p-4">
                    {{ $movements->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
