@extends('app.master')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <h2 class="fw-bold">Riwayat Stok</h2>
            <p class="text-muted mb-0">Riwayat seluruh pergerakan stok masuk dan keluar.</p>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('stok.riwayat') }}" class="row g-3">
                    <div class="col-lg-4 col-md-6">
                        <label for="movement-search" class="form-label">Pencarian</label>
                        <input id="movement-search" type="search" name="search" class="form-control"
                            value="{{ request('search') }}" placeholder="Kode, nama produk, atau keterangan">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="jenis" class="form-label">Jenis</label>
                        <select id="jenis" name="jenis" class="form-select">
                            <option value="">Semua</option>
                            <option value="masuk" @selected(request('jenis') === 'masuk')>Masuk</option>
                            <option value="keluar" @selected(request('jenis') === 'keluar')>Keluar</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="mulai" class="form-label">Tanggal mulai</label>
                        <input id="mulai" type="date" name="mulai" class="form-control" value="{{ request('mulai') }}">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="sampai" class="form-label">Tanggal akhir</label>
                        <input id="sampai" type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                    </div>
                    <div class="col-lg-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">{{ $movements->total() }} aktivitas stok</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal/Waktu</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Jenis</th>
                            <th>Qty</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($movements as $movement)
                            <tr>
                                <td>{{ $movement->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>{{ $movement->product?->kode_produk ?? '-' }}</td>
                                <td>{{ $movement->product?->nama_produk ?? 'Produk tidak tersedia' }}</td>
                                <td>
                                    <span class="badge {{ $movement->jenis === 'masuk' ? 'text-bg-success' : 'text-bg-danger' }}">
                                        {{ strtoupper($movement->jenis) }}
                                    </span>
                                </td>
                                <td>{{ $movement->qty }}</td>
                                <td>{{ $movement->keterangan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat stok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $movements->links() }}</div>
    </div>
@endsection
