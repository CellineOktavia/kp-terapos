@extends('app.master')

@section('content')
    <div class="container-fluid px-0">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Detail Penerimaan Barang</h3>
                <p class="text-muted small mb-0">{{ $penerimaan->nomor_penerimaan }}</p>
            </div>
            <a href="{{ route('penerimaan.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-muted small">No. Penerimaan</div>
                        <div class="fw-semibold">{{ $penerimaan->nomor_penerimaan }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Tanggal</div>
                        <div class="fw-semibold">{{ $penerimaan->tanggal?->translatedFormat('d F Y') ?? '-' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">User</div>
                        <div class="fw-semibold">{{ $penerimaan->user?->name ?? '-' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Keterangan</div>
                        <div>{{ $penerimaan->keterangan ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Barang yang Diterima</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th class="text-end">Qty Diterima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penerimaan->detailPenerimaanBarangs as $detail)
                            <tr>
                                <td>{{ $detail->product?->kode_produk ?? '-' }}</td>
                                <td>{{ $detail->product?->nama_produk ?? 'Barang tidak tersedia' }}</td>
                                <td class="text-end">
                                    {{ number_format($detail->qty, 0, ',', '.') }} {{ $detail->product?->satuan ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
