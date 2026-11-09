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

        .stat-card-backup {
            border: 1px solid #f0f0f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03) !important;
            background: #ffffff;
            padding: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .btn-create-backup {
            background: linear-gradient(135deg, #0d6efd, #3b82f6);
            border: none;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .btn-create-backup:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(13, 110, 253, 0.3);
        }

        .badge-file {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            border: 1px solid #e2e8f0;
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
            padding: 6px 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-download-action {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border: 1px solid rgba(13, 110, 253, 0.2);
        }

        .btn-download-action:hover {
            background-color: #0d6efd;
            color: #ffffff;
            transform: translateY(-2px);
        }
    </style>

    <div class="container-fluid px-0">

        <!-- Header Halaman -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Backup Database</h3>
                <p class="text-muted small mb-0">Amankan data sistem dengan membuat dan mengunduh salinan database SQLite.
                </p>
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
        @if (session('error'))
            <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4 d-flex align-items-center gap-2"
                style="background-color: #f8d7da; color: #842029;">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <!-- Card Informasional & Tombol Buat Backup -->
        <div class="card card-terapos mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center g-4">
                    <div class="col-md-4">
                        <div class="stat-card-backup border-start border-primary border-4">
                            <span class="label-title text-primary">Tipe Database</span>
                            <div class="fs-4 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-database-fill text-primary"></i> SQLite Database
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stat-card-backup border-start border-info border-4">
                            <span class="label-title text-info">Backup Terakhir</span>
                            <div class="fs-6 fw-bold text-dark">
                                @if ($latestBackup)
                                    <i class="bi bi-clock-history text-info me-1"></i>
                                    {{ $latestBackup['modified_at']->translatedFormat('d M Y, H:i:s') }}
                                @else
                                    <span class="text-muted fw-normal">Belum ada backup dibuat</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 d-flex align-items-center justify-content-md-end">
                        <form action="{{ route('backup.store') }}" method="POST" class="w-100 text-md-end">
                            @csrf
                            <button type="submit" class="btn-create-backup w-100 justify-content-center">
                                <i class="bi bi-database-fill-down fs-5"></i>
                                Buat Backup Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Tabel Daftar Backup -->
        <div class="card card-terapos">
            <div class="card-header-blue d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-folder-symlink-fill"></i>
                    Daftar File Backup Database
                </div>
                <span class="badge bg-white text-primary rounded-pill px-3 py-1 font-monospace fw-bold"
                    style="font-size: 12px;">
                    Total: {{ count($backups) }} File
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-terapos mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Nama File Backup</th>
                                <th>Tanggal & Waktu Buat</th>
                                <th class="text-center">Ukuran File</th>
                                <th class="text-center pe-4" width="140">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($backups as $backup)
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge-file">
                                            <i class="bi bi-file-earmark-zip-fill text-primary"></i>
                                            {{ $backup['filename'] }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold text-dark">
                                        <i class="bi bi-calendar3 me-1 text-muted"></i>
                                        {{ $backup['modified_at']->translatedFormat('d M Y, H:i:s') }}
                                    </td>
                                    <td class="text-center text-muted fw-semibold">
                                        {{ number_format($backup['size'] / 1024, 1, ',', '.') }} KB
                                    </td>
                                    <td class="text-center pe-4">
                                        <a href="{{ route('backup.download', ['filename' => $backup['filename']]) }}"
                                            class="action-btn btn-download-action" title="Unduh File Backup">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i
                                            class="bi bi-database-exclamation fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                        <div class="fw-bold fs-6 text-dark">Belum ada file backup</div>
                                        <p class="small text-muted mb-0">Klik tombol "Buat Backup Sekarang" di atas untuk
                                            membuat salinan database pertama Anda.</p>
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
