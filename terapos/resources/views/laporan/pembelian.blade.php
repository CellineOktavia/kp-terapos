@extends('app.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Laporan Pembelian</h2>
                <p class="text-muted mb-0">Periode: {{ $period['label'] }}</p>
            </div>
            <a href="{{ route('laporan.pembelian.pdf', request()->query()) }}" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i> Cetak / Download PDF
            </a>
        </div>

        @include('laporan.partials.period-filter', [
            'reportRoute' => 'laporan.pembelian',
            'period' => $period,
        ])

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100"><div class="card-body">
                    <div class="text-muted">Total Pembelian</div>
                    <div class="h4 fw-bold mb-0">Rp {{ number_format($summary['total'], 0, ',', '.') }}</div>
                </div></div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100"><div class="card-body">
                    <div class="text-muted">Transaksi Pembelian</div>
                    <div class="h4 fw-bold mb-0">{{ number_format($summary['transactions'], 0, ',', '.') }}</div>
                </div></div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100"><div class="card-body">
                    <div class="text-muted">Total Qty Dibeli</div>
                    <div class="h4 fw-bold mb-0">{{ number_format($summary['qty'], 0, ',', '.') }} pcs</div>
                </div></div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr>
                        <th>No. Pembelian</th><th>Tanggal</th><th>Jenis Item</th>
                        <th>Total Qty</th><th>Total Pembelian</th><th>User</th><th>Detail</th>
                    </tr></thead>
                    <tbody>
                        @forelse ($fakturs as $faktur)
                            <tr>
                                <td>{{ $faktur->nomor_faktur }}</td>
                                <td>{{ $faktur->tanggal ? \Carbon\Carbon::parse($faktur->tanggal)->format('d/m/Y') : '-' }}</td>
                                <td>{{ number_format($faktur->detail_fakturs_count, 0, ',', '.') }}</td>
                                <td>{{ number_format($faktur->detail_fakturs_sum_qty ?? 0, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($faktur->total, 0, ',', '.') }}</td>
                                <td>{{ $faktur->user?->name ?? '-' }}</td>
                                <td>
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('faktur.show', $faktur->id) }}">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">
                                Tidak ada transaksi pada periode ini.
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $fakturs->links() }}</div>
    </div>
@endsection
