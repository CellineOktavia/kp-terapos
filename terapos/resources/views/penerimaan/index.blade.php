@extends('app.master')

@section('content')
    <div class="container-fluid px-0">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Penerimaan Barang</h3>
                <p class="text-muted small mb-0">Catat dan lihat riwayat barang fisik yang masuk.</p>
            </div>
            @if (auth()->user()->isOwner())
                <a href="{{ route('penerimaan.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Penerimaan
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('penerimaan.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-4 col-md-6">
                            <label for="receipt-search" class="form-label">Cari Penerimaan</label>
                            <input id="receipt-search" type="search" name="search" class="form-control"
                                placeholder="Nomor, barang, kode, atau user"
                                value="{{ $filters['search'] ?? '' }}">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input id="start_date" type="date" name="start_date" class="form-control"
                                value="{{ $filters['start_date'] ?? '' }}">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input id="end_date" type="date" name="end_date" class="form-control"
                                value="{{ $filters['end_date'] ?? '' }}">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="user_id" class="form-label">User</label>
                            <select id="user_id" name="user_id" class="form-select">
                                <option value="">Semua User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected(($filters['user_id'] ?? '') == $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-12">
                            <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Penerimaan</th>
                            <th>Tanggal</th>
                            <th class="text-center">Jenis Barang</th>
                            <th class="text-center">Total Qty</th>
                            <th>User</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penerimaans as $penerimaan)
                            <tr>
                                <td class="fw-semibold">
                                    <a href="{{ route('penerimaan.show', $penerimaan) }}">
                                        {{ $penerimaan->nomor_penerimaan }}
                                    </a>
                                </td>
                                <td>{{ $penerimaan->tanggal?->translatedFormat('d M Y') ?? '-' }}</td>
                                <td class="text-center">{{ $penerimaan->detail_penerimaan_barangs_count }}</td>
                                <td class="text-center">
                                    {{ number_format($penerimaan->detail_penerimaan_barangs_sum_qty ?? 0, 0, ',', '.') }}
                                </td>
                                <td>{{ $penerimaan->user?->name ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('penerimaan.show', $penerimaan) }}"
                                        class="btn btn-sm btn-outline-primary" title="Detail Penerimaan">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    Belum ada riwayat penerimaan barang.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($penerimaans->hasPages())
                <div class="card-footer bg-white">
                    {{ $penerimaans->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
