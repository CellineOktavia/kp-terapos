<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan TERAPOS</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #1f2937; }
        h1, h2, p { margin: 0 0 6px; text-align: center; }
        h1 { color: #1d4ed8; font-size: 20px; }
        h2 { font-size: 14px; }
        .period { margin-bottom: 14px; text-align: center; }
        .summary { width: 100%; margin-bottom: 12px; border-collapse: collapse; }
        .summary td { border: 1px solid #cbd5e1; padding: 6px; }
        table.report { width: 100%; border-collapse: collapse; }
        .report th, .report td { border: 1px solid #cbd5e1; padding: 5px; vertical-align: top; }
        .report th { background: #dbeafe; text-align: left; }
        .transaction { background: #f1f5f9; font-weight: bold; }
        .right { text-align: right; }
        .center { text-align: center; }
        .empty { text-align: center; padding: 18px; }
    </style>
</head>
<body>
    <h1>TERAPOS</h1>
    <h2>Laporan Penjualan</h2>
    <p class="period">Periode: {{ $period['label'] }}</p>
    <table class="summary">
        <tr>
            <td><strong>Total Omzet:</strong> Rp {{ number_format($summary['total'], 0, ',', '.') }}</td>
            <td><strong>Transaksi:</strong> {{ number_format($summary['transactions'], 0, ',', '.') }}</td>
            <td><strong>Qty Terjual:</strong> {{ number_format($summary['qty'], 0, ',', '.') }} pcs</td>
        </tr>
    </table>
    <table class="report">
        <thead><tr>
            <th>Tanggal</th><th>No. Transaksi</th><th>Pelanggan / Kasir</th>
            <th>Kode / Produk</th><th>Qty</th><th>Harga Saat Transaksi</th><th>Subtotal</th><th>Total / Bayar / Kembali</th>
        </tr></thead>
        <tbody>
            @forelse ($penjualans as $sale)
                @forelse ($sale->detailPenjualans as $detail)
                    <tr>
                        <td>{{ $loop->first ? $sale->tanggal?->format('d/m/Y') : '' }}</td>
                        <td>{{ $loop->first ? $sale->nomor_penjualan : '' }}</td>
                        <td>{{ $loop->first ? ($sale->customer?->nama_customer ?? 'Pelanggan Umum') . ' / ' . ($sale->user?->name ?? '-') : '' }}</td>
                        <td>{{ $detail->product?->kode_produk ?? '-' }} / {{ $detail->product?->nama_produk ?? 'Produk tidak tersedia' }}</td>
                        <td class="center">{{ $detail->qty }}</td>
                        <td class="right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        <td class="right">
                            @if ($loop->first)
                                Total Rp {{ number_format($sale->total, 0, ',', '.') }}<br>
                                Bayar Rp {{ number_format($sale->bayar ?? 0, 0, ',', '.') }}<br>
                                Kembali Rp {{ number_format($sale->kembalian ?? 0, 0, ',', '.') }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>{{ $sale->tanggal?->format('d/m/Y') }}</td>
                        <td>{{ $sale->nomor_penjualan }}</td>
                        <td>{{ $sale->customer?->nama_customer ?? 'Pelanggan Umum' }} / {{ $sale->user?->name ?? '-' }}</td>
                        <td colspan="4">Tidak ada detail item.</td>
                        <td class="right">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                    </tr>
                @endforelse
            @empty
                <tr><td colspan="8" class="empty">Tidak ada transaksi pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
