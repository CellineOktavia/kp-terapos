@extends('app.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Laporan Penjualan</h2>
                <p class="text-muted mb-0">Periode: {{ $period['label'] }}</p>
            </div>
            <a href="{{ route('laporan.penjualan.pdf', request()->query()) }}" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i> Cetak / Download PDF
            </a>
        </div>

        @include('laporan.partials.period-filter', [
            'reportRoute' => 'laporan.penjualan',
            'period' => $period,
        ])

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100"><div class="card-body">
                    <div class="text-muted">Total Omzet</div>
                    <div class="h4 fw-bold mb-0">Rp {{ number_format($summary['total'], 0, ',', '.') }}</div>
                </div></div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100"><div class="card-body">
                    <div class="text-muted">Total Transaksi</div>
                    <div class="h4 fw-bold mb-0">{{ number_format($summary['transactions'], 0, ',', '.') }}</div>
                </div></div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100"><div class="card-body">
                    <div class="text-muted">Total Qty Terjual</div>
                    <div class="h4 fw-bold mb-0">{{ number_format($summary['qty'], 0, ',', '.') }} pcs</div>
                </div></div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr>
                        <th>No. Transaksi</th><th>Tanggal</th><th>Pelanggan</th>
                        <th>Jenis Item</th><th>Qty Terjual</th><th>Total</th>
                        <th>Bayar</th><th>Kembalian</th><th>Kasir/User</th><th>Aksi</th>
                    </tr></thead>
                    <tbody>
                        @forelse ($penjualans as $penjualan)
                            <tr>
                                <td>{{ $penjualan->nomor_penjualan }}</td>
                                <td>{{ $penjualan->tanggal?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $penjualan->customer?->nama_customer ?? 'Pelanggan Umum' }}</td>
                                <td>{{ number_format($penjualan->detail_penjualans_count, 0, ',', '.') }}</td>
                                <td>{{ number_format($penjualan->detail_penjualans_sum_qty ?? 0, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($penjualan->bayar ?? 0, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($penjualan->kembalian ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $penjualan->user?->name ?? '-' }}</td>
                                <td>
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('penjualan.show', $penjualan->id) }}">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center text-muted py-4">
                                Tidak ada transaksi pada periode ini.
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $penjualans->links() }}</div>
    </div>
@endsection
