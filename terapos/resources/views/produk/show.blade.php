@extends('app.master')

@section('title', $product->nama_produk . ' — TERAPOS')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h1 class="h3 fw-bold mb-0">{{ $product->nama_produk }}</h1>
                <span class="badge {{ $product->aktif ? 'text-bg-success' : 'text-bg-secondary' }}">
                    {{ $product->aktif ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>
            <p class="text-muted mb-0">Kode Barang: <span class="font-monospace">{{ $product->kode_produk }}</span></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary">Kembali</a>
            <a href="{{ route('produk.edit', $product) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Edit Barang</a>
        </div>
    </div>

    @if (session('success')) <div class="alert alert-success" role="status">{{ session('success') }}</div> @endif
    @if ($product->harga_jual < $product->harga_beli)
        <div class="alert alert-warning" role="status">
            Harga jual berada di bawah harga beli terakhir. Pastikan ini merupakan keputusan Owner/subsidi silang.
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <section class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">Informasi Barang</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Kode Barang</dt><dd class="col-sm-8 font-monospace">{{ $product->kode_produk }}</dd>
                        <dt class="col-sm-4">Nama Barang</dt><dd class="col-sm-8">{{ $product->nama_produk }}</dd>
                        <dt class="col-sm-4">Kategori</dt><dd class="col-sm-8">{{ $product->category?->nama_kategori ?? '-' }}</dd>
                        <dt class="col-sm-4">Merk</dt><dd class="col-sm-8">{{ $product->merk ?: '-' }}</dd>
                        <dt class="col-sm-4">Satuan</dt><dd class="col-sm-8">{{ $product->satuan }}</dd>
                        <dt class="col-sm-4">Stok</dt><dd class="col-sm-8">{{ number_format($product->stok, 0, ',', '.') }} {{ $product->satuan }}</dd>
                        <dt class="col-sm-4">Stok Minimum</dt><dd class="col-sm-8">{{ number_format($product->stok_minimum, 0, ',', '.') }} {{ $product->satuan }}</dd>
                        <dt class="col-sm-4">Harga Beli Terakhir</dt><dd class="col-sm-8">Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</dd>
                        <dt class="col-sm-4">Harga Jual</dt><dd class="col-sm-8">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</dd>
                        <dt class="col-sm-4">Deskripsi</dt><dd class="col-sm-8">{{ $product->deskripsi ?: '-' }}</dd>
                    </dl>
                </div>
            </section>
        </div>
        <div class="col-lg-5">
            <section class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">Riwayat Stok Terbaru</div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead><tr><th>Tanggal</th><th>Jenis</th><th>Qty</th><th>Keterangan</th></tr></thead>
                        <tbody>
                            @forelse ($product->stockMovements as $movement)
                                <tr>
                                    <td>{{ $movement->created_at?->format('d-m-Y H:i') }}</td>
                                    <td>{{ ucfirst($movement->jenis) }}</td>
                                    <td>{{ number_format($movement->qty, 0, ',', '.') }}</td>
                                    <td>{{ $movement->keterangan }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada riwayat stok.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    @foreach (['beli' => 'Riwayat Harga Beli', 'jual' => 'Riwayat Harga Jual'] as $type => $heading)
        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">{{ $heading }}</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead><tr><th>Tanggal</th><th>Harga Lama</th><th>Harga Baru</th><th>User</th><th>Keterangan</th></tr></thead>
                    <tbody>
                        @forelse ($product->priceHistories->where('jenis_harga', $type) as $history)
                            <tr>
                                <td>{{ $history->created_at?->format('d-m-Y H:i') }}</td>
                                <td>Rp {{ number_format($history->harga_lama, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($history->harga_baru, 0, ',', '.') }}</td>
                                <td>{{ $history->user?->name ?? '-' }}</td>
                                <td>{{ $history->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat harga.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endforeach
@endsection
