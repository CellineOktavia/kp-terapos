@extends('app.master')

@section('title', 'Laporan Barang Masuk — TERAPOS')

@section('content')
<style>
.receipt-report-header{background:linear-gradient(135deg,#f8fbff 0%,#eff6ff 100%);border:1px solid #bfdbfe;border-radius:18px;padding:20px 24px}.receipt-report-icon{width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#2563eb,#60a5fa);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.35rem}.receipt-period{background:#fff;border:1px solid #bfdbfe;color:#1d4ed8;border-radius:999px;padding:5px 12px;font-size:.78rem;font-weight:700}.receipt-stat{border:1px solid #e2e8f0;border-radius:16px;background:#fff;box-shadow:0 4px 20px rgba(15,23,42,.04);padding:18px 20px;height:100%;display:flex;align-items:center;justify-content:space-between}.receipt-stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:#eff6ff;color:#2563eb}.receipt-table-card{border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;background:#fff;box-shadow:0 4px 20px rgba(15,23,42,.04)}.receipt-table-card thead th{background:#eff6ff;color:#1d4ed8;font-size:.73rem;text-transform:uppercase;letter-spacing:.04em;border-bottom:1px solid #bfdbfe;padding:14px 16px}.receipt-table-card td{padding:14px 16px;vertical-align:middle;border-bottom:1px solid #f1f5f9}.receipt-number{background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;border-radius:8px;padding:6px 9px;font-weight:800;text-decoration:none}.receipt-filter-wrap .filter-card-terapos{border-color:#bfdbfe}.receipt-filter-wrap .btn-preset-terapos.active,.receipt-filter-wrap .btn-terapos-apply{background:#2563eb!important;border-color:#2563eb!important}.receipt-filter-wrap .btn-preset-terapos:hover{color:#1d4ed8;background:#eff6ff}
</style>

<div class="receipt-report-header d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="receipt-report-icon"><i class="bi bi-box-arrow-in-down"></i></div>
        <div>
            <h1 class="h3 fw-bold mb-1">Laporan Barang Masuk</h1>
            <span class="receipt-period"><i class="bi bi-calendar3 me-1"></i>{{ $period['label'] }}</span>
        </div>
    </div>
    <a class="btn btn-primary" href="{{ route('laporan.barang-masuk.pdf', request()->query()) }}">
        <i class="bi bi-file-earmark-arrow-down me-1"></i>Unduh PDF
    </a>
</div>

<div class="receipt-filter-wrap">
    @include('laporan.partials.period-filter', ['reportRoute' => 'laporan.barang-masuk', 'period' => $period])
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="receipt-stat"><div><div class="small text-muted">Jumlah Penerimaan</div><div class="h4 fw-bold mb-0 text-primary">{{ number_format($summary['receipts'],0,',','.') }}</div></div><div class="receipt-stat-icon"><i class="bi bi-clipboard-check fs-5"></i></div></div></div>
    <div class="col-md-4"><div class="receipt-stat"><div><div class="small text-muted">Total Qty Masuk</div><div class="h4 fw-bold mb-0 text-primary">{{ number_format($summary['qty'],0,',','.') }}</div></div><div class="receipt-stat-icon"><i class="bi bi-boxes fs-5"></i></div></div></div>
    <div class="col-md-4"><div class="receipt-stat"><div><div class="small text-muted">Total Nilai Barang Masuk</div><div class="h4 fw-bold mb-0 text-primary">Rp {{ number_format($summary['value'],0,',','.') }}</div><small class="text-muted">{{ ($summary['unknown_value_rows'] ?? 0) > 0 ? 'Nilai diketahui saja; data belum lengkap.' : 'Berdasarkan snapshot penerimaan.' }}</small></div><div class="receipt-stat-icon"><i class="bi bi-cash-stack fs-5"></i></div></div></div>
</div>

@if(($summary['unknown_value_rows'] ?? 0) > 0)
<div class="alert alert-warning border-0 rounded-3">
    Sebanyak {{ $summary['unknown_value_rows'] }} baris belum memiliki snapshot harga beli. Nilai tersebut tidak dihitung sebagai Rp0.
</div>
@endif

<div class="receipt-table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Nomor Penerimaan</th><th>Tanggal</th><th>Nama Barang</th><th class="text-end">Qty Masuk</th><th>Satuan</th><th class="text-end">Harga Beli Saat Itu</th><th class="text-end">Nilai Barang Masuk</th><th>User / Operator</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
                @forelse($details as $detail)
                <tr>
                    <td><a class="receipt-number" href="{{ route('penerimaan.show',$detail->penerimaan_barang_id) }}">{{ $detail->penerimaanBarang->nomor_penerimaan }}</a></td>
                    <td>{{ $detail->penerimaanBarang->tanggal?->format('d-m-Y') }}</td>
                    <td class="fw-bold">{{ $detail->product?->nama_produk ?? 'Barang tidak tersedia' }}</td>
                    <td class="text-end">{{ number_format($detail->qty,0,',','.') }}</td>
                    <td>{{ $detail->product?->satuan ?? '-' }}</td>
                    <td class="text-end">{{ $detail->harga_beli === null ? 'Belum tercatat' : 'Rp '.number_format($detail->harga_beli,0,',','.') }}</td>
                    <td class="text-end">{{ $detail->harga_beli === null ? 'Belum tercatat' : 'Rp '.number_format($detail->qty*$detail->harga_beli,0,',','.') }}</td>
                    <td>{{ $detail->penerimaanBarang->user?->name ?? '-' }}</td>
                    <td class="text-center"><a href="{{ route('penerimaan.show',$detail->penerimaan_barang_id) }}" class="btn btn-sm btn-outline-primary" title="Detail penerimaan" aria-label="Detail penerimaan"><i class="bi bi-eye"></i></a></td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-5"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Tidak ada barang masuk pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3 bg-white">{{ $details->links() }}</div>
</div>
@endsection
