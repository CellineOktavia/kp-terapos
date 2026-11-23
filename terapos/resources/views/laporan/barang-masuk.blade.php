@extends('app.master')

@section('title', 'Laporan Barang Masuk — TERAPOS')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Laporan Barang Masuk</h1>
            <p class="text-muted mb-0">{{ $period['label'] }}</p>
        </div>
        <a class="btn btn-outline-danger" href="{{ route('laporan.barang-masuk.pdf', request()->query()) }}">
            <i class="bi bi-file-earmark-pdf me-1"></i>Unduh PDF
        </a>
    </div>
    @include('laporan.partials.period-filter', ['reportRoute' => 'laporan.barang-masuk', 'period' => $period])

    <div class="row g-3 mb-4">
        <div class="col-md-4"><div class="card border-0 shadow-sm h-100"><div class="card-body">
            <div class="small text-muted">Jumlah Penerimaan</div><div class="h4 fw-bold mb-0">{{ number_format($summary['receipts'], 0, ',', '.') }}</div>
        </div></div></div>
        <div class="col-md-4"><div class="card border-0 shadow-sm h-100"><div class="card-body">
            <div class="small text-muted">Total Qty Masuk</div><div class="h4 fw-bold mb-0">{{ number_format($summary['qty'], 0, ',', '.') }}</div>
        </div></div></div>
        <div class="col-md-4"><div class="card border-0 shadow-sm h-100"><div class="card-body">
            <div class="small text-muted">Total Nilai Barang Masuk</div><div class="h4 fw-bold mb-0">Rp {{ number_format($summary['value'], 0, ',', '.') }}</div>
        </div></div></div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>No. Penerimaan</th><th>Tanggal</th><th>User</th><th>Nama Barang</th><th class="text-end">Qty Masuk</th><th class="text-end">Harga Beli Saat Itu</th><th class="text-end">Nilai Barang Masuk</th></tr></thead>
                <tbody>
                    @forelse ($details as $detail)
                        <tr>
                            <td><a href="{{ route('penerimaan.show', $detail->penerimaan_barang_id) }}">{{ $detail->penerimaanBarang->nomor_penerimaan }}</a></td>
                            <td>{{ $detail->penerimaanBarang->tanggal?->format('d-m-Y') }}</td>
                            <td>{{ $detail->penerimaanBarang->user?->name ?? '-' }}</td>
                            <td>{{ $detail->product?->nama_produk ?? 'Barang tidak tersedia' }}</td>
                            <td class="text-end">{{ number_format($detail->qty, 0, ',', '.') }}</td>
                            <td class="text-end">{{ $detail->harga_beli === null ? '-' : 'Rp ' . number_format($detail->harga_beli, 0, ',', '.') }}</td>
                            <td class="text-end">{{ $detail->harga_beli === null ? '-' : 'Rp ' . number_format($detail->qty * $detail->harga_beli, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-muted py-4" colspan="7">Tidak ada barang masuk pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">{{ $details->links() }}</div>
    </div>
@endsection
