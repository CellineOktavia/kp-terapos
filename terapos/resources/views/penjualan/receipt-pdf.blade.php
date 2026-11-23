<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #172033; }
        h1, h2, p { margin: 0; }
        h1 { font-size: 17px; }
        h2 { font-size: 12px; margin-top: 3px; }
        .center { text-align: center; }
        .meta { margin: 14px 0 10px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 5px 2px; border-bottom: 1px solid #d8dee8; }
        th { text-align: left; }
        .right { text-align: right; }
        .totals { margin-top: 8px; }
        .totals td { border: 0; padding: 3px 2px; }
        .grand-total { font-weight: bold; border-top: 1px solid #172033 !important; }
        .footer { margin-top: 18px; text-align: center; }
    </style>
</head>
<body>
    <div class="center">
        <h1>TERAPOS</h1>
        <h2>Toko Terang Abadi</h2>
    </div>
    <div class="meta">
        No. Penjualan: {{ $penjualan->nomor_penjualan }}<br>
        Tanggal/Waktu: {{ $penjualan->created_at?->format('d-m-Y H:i') ?? $penjualan->tanggal?->format('d-m-Y') }}<br>
        User/Operator: {{ $penjualan->user?->name ?? '-' }}
    </div>
    <table>
        <thead><tr><th>Barang</th><th class="right">Qty</th><th class="right">Harga</th><th class="right">Subtotal</th></tr></thead>
        <tbody>
            @foreach ($penjualan->detailPenjualans as $detail)
                <tr>
                    <td>{{ $detail->product?->nama_produk ?? 'Barang tidak tersedia' }}</td>
                    <td class="right">{{ $detail->qty }}</td>
                    <td class="right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @php($subtotal = $penjualan->detailPenjualans->sum('subtotal'))
    <table class="totals">
        <tr><td>Subtotal</td><td class="right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td></tr>
        <tr><td>Diskon</td><td class="right">Rp {{ number_format($penjualan->diskon_nominal, 0, ',', '.') }}</td></tr>
        @if ((float) $penjualan->cross_subsidy_amount > 0)
            <tr><td>Subsidi Silang</td><td class="right">Rp {{ number_format($penjualan->cross_subsidy_amount, 0, ',', '.') }}</td></tr>
        @endif
        <tr class="grand-total"><td>Total</td><td class="right">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td></tr>
        <tr><td>Bayar</td><td class="right">Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</td></tr>
        <tr><td>Kembalian</td><td class="right">Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</td></tr>
    </table>
    <div class="footer">Terima kasih telah berbelanja di Toko Terang Abadi.</div>
</body>
</html>
