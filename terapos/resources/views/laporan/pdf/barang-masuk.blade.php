<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #172033; }
        h1 { font-size: 17px; margin: 0 0 4px; }
        p { margin: 0 0 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cbd5e1; padding: 5px; }
        th { background: #eaf2ff; text-align: left; }
        .right { text-align: right; }
        .summary { margin-top: 12px; width: 55%; }
    </style>
</head>
<body>
    <h1>TERAPOS — Laporan Barang Masuk</h1>
    <p>Toko Terang Abadi | Periode: {{ $period['label'] }}</p>
    <table>
        <thead><tr><th>No. Penerimaan</th><th>Tanggal</th><th>User</th><th>Nama Barang</th><th class="right">Qty</th><th class="right">Harga Beli Saat Itu</th><th class="right">Nilai Barang Masuk</th></tr></thead>
        <tbody>
            @foreach ($details as $detail)
                <tr>
                    <td>{{ $detail->penerimaanBarang->nomor_penerimaan }}</td>
                    <td>{{ $detail->penerimaanBarang->tanggal?->format('d-m-Y') }}</td>
                    <td>{{ $detail->penerimaanBarang->user?->name ?? '-' }}</td>
                    <td>{{ $detail->product?->nama_produk ?? 'Barang tidak tersedia' }}</td>
                    <td class="right">{{ number_format($detail->qty, 0, ',', '.') }}</td>
                    <td class="right">{{ $detail->harga_beli === null ? 'Belum tercatat' : 'Rp ' . number_format($detail->harga_beli, 0, ',', '.') }}</td>
                    <td class="right">{{ $detail->harga_beli === null ? 'Belum tercatat' : 'Rp ' . number_format($detail->qty * $detail->harga_beli, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table class="summary">
        <tr><th>Jumlah Penerimaan</th><td>{{ number_format($summary['receipts'], 0, ',', '.') }}</td></tr>
        <tr><th>Total Qty Masuk</th><td>{{ number_format($summary['qty'], 0, ',', '.') }}</td></tr>
        <tr><th>Total Nilai Barang Masuk</th><td>Rp {{ number_format($summary['value'], 0, ',', '.') }}</td></tr>
    </table>
    @if(($summary['unknown_value_rows'] ?? 0) > 0)
        <p>Sebanyak {{ $summary['unknown_value_rows'] }} baris belum memiliki snapshot harga beli. Total hanya mencakup nilai yang diketahui.</p>
    @endif
</body>
</html>
