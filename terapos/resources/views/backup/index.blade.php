@extends('app.master')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <h2 class="fw-bold mb-1">Backup Database</h2>
            <p class="text-muted mb-0">Buat dan unduh salinan database SQLite secara aman.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center g-4">
                    <div class="col-md">
                        <div class="text-muted small">Database</div>
                        <div class="h5 fw-bold mb-0">SQLite</div>
                    </div>
                    <div class="col-md">
                        <div class="text-muted small">Backup Terakhir</div>
                        <div class="fw-semibold">
                            {{ $latestBackup ? $latestBackup['modified_at']->format('d/m/Y H:i:s') : 'Belum ada backup' }}
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <form action="{{ route('backup.store') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-database-down me-1"></i>
                                Buat Backup Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h3 class="h5 fw-bold mb-0">Daftar Backup</h3>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nama File</th>
                            <th>Tanggal</th>
                            <th>Ukuran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($backups as $backup)
                            <tr>
                                <td>{{ $backup['filename'] }}</td>
                                <td>{{ $backup['modified_at']->format('d/m/Y H:i:s') }}</td>
                                <td>{{ number_format($backup['size'] / 1024, 1, ',', '.') }} KB</td>
                                <td>
                                    <a href="{{ route('backup.download', ['filename' => $backup['filename']]) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download me-1"></i> Download
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Belum ada file backup.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
