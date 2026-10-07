<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Stok TERAPOS</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #1f2937; }
        h1, h2, p { margin: 0 0 6px; text-align: center; }
        h1 { color: #1d4ed8; font-size: 20px; }
        h2 { font-size: 14px; }
        .summary { width: 100%; margin: 12px 0; border-collapse: collapse; }
        .summary td { border: 1px solid #cbd5e1; padding: 6px; }
        table.report { width: 100%; border-collapse: collapse; }
        .report th, .report td { border: 1px solid #cbd5e1; padding: 5px; }
        .report th { background: #dbeafe; text-align: left; }
        .right { text-align: right; }
        .center { text-align: center; }
        .out { color: #b91c1c; font-weight: bold; }
        .low { color: #b45309; font-weight: bold; }
        .safe { color: #15803d; font-weight: bold; }
        .empty { text-align: center; padding: 18px; }
    </style>
</head>
<body>
    <h1>TERAPOS</h1>
    <h2>Laporan Stok</h2>
    <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    <table class="summary">
        <tr>
            <td><strong>Total Produk:</strong> {{ number_format($summary['products'], 0, ',', '.') }}</td>
            <td><strong>Stok Kritis:</strong> {{ number_format($summary['critical'], 0, ',', '.') }}</td>
            <td><strong>Nilai Persediaan (Harga Beli):</strong> Rp {{ number_format($summary['inventoryValue'], 0, ',', '.') }}</td>
        </tr>
    </table>
    <table class="report">
        <thead><tr>
            <th>Kode</th><th>Barcode</th><th>Nama Produk</th><th>Kategori</th><th>Satuan</th>
            <th>Stok</th><th>Minimum</th><th>Harga Beli</th><th>Harga Jual</th><th>Status</th>
        </tr></thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->kode_produk }}</td>
                    <td>{{ $product->barcode ?? '-' }}</td>
                    <td>{{ $product->nama_produk }}</td>
                    <td>{{ $product->category?->nama_kategori ?? '-' }}</td>
                    <td>{{ $product->satuan }}</td>
                    <td class="center">{{ $product->stok }}</td>
                    <td class="center">{{ $product->stok_minimum }}</td>
                    <td class="right">Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                    <td class="center">
                        @if ((int) $product->stok === 0)
                            <span class="out">HABIS</span>
                        @elseif ($product->stok <= $product->stok_minimum)
                            <span class="low">MENIPIS</span>
                        @else
                            <span class="safe">AMAN</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="10" class="empty">Tidak ada produk untuk filter ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
