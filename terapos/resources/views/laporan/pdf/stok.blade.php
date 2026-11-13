<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Stok TERAPOS</title>
    <style>
        @page {
            margin: 12mm 12mm 15mm 12mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8.5px;
            color: #1e293b;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #059669;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .brand-title {
            font-size: 17px;
            font-weight: bold;
            color: #059669;
            margin: 0 0 2px 0;
        }

        .brand-subtitle {
            font-size: 8px;
            color: #64748b;
            margin: 0;
        }

        .report-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            text-align: right;
            margin: 0 0 2px 0;
            text-transform: uppercase;
        }

        .report-meta {
            font-size: 8px;
            color: #475569;
            text-align: right;
            margin: 0;
        }

        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 0;
            margin: 0 -6px 12px -6px;
        }

        .summary-box {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            border-radius: 4px;
            padding: 7px 10px;
            width: 24%;
        }

        .summary-box-critical {
            border: 1px solid #fecaca;
            background-color: #fef2f2;
            width: 24%;
        }

        .summary-box-highlight {
            border: 1px solid #6ee7b7;
            background-color: #ecfdf5;
            width: 52%;
        }

        .summary-label {
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 2px;
        }

        .summary-box-critical .summary-label {
            color: #b91c1c;
        }

        .summary-box-highlight .summary-label {
            color: #047857;
        }

        .summary-value {
            font-size: 11.5px;
            font-weight: bold;
            color: #0f172a;
        }

        .summary-box-critical .summary-value {
            color: #b91c1c;
        }

        .summary-box-highlight .summary-value {
            color: #065f46;
            font-size: 12.5px;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report thead {
            display: table-header-group;
        }

        table.report tr {
            page-break-inside: avoid;
        }

        .report th {
            background-color: #059669;
            color: #ffffff;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 6px 5px;
            border: 1px solid #047857;
            text-align: left;
            white-space: nowrap;
        }

        .report th.right {
            text-align: right;
        }

        .report th.center {
            text-align: center;
        }

        .report td {
            padding: 5px 5px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .report tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .code-text {
            font-family: DejaVu Sans Mono, monospace;
            font-size: 7.5px;
            font-weight: bold;
            color: #0f172a;
        }

        .barcode-sub {
            font-family: DejaVu Sans Mono, monospace;
            font-size: 6.5px;
            color: #64748b;
            display: block;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .nowrap {
            white-space: nowrap;
        }

        .badge-status {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .out {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }

        .low {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fcd34d;
        }

        .safe {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
        }

        .grand-total-row td {
            background-color: #ecfdf5 !important;
            border-top: 2px solid #059669;
            border-bottom: 2px solid #059669;
            padding: 7px 6px;
            font-weight: bold;
            font-size: 8.5px;
            color: #065f46;
        }

        .empty {
            text-align: center;
            padding: 20px;
            color: #64748b;
        }

        .page-footer {
            position: fixed;
            bottom: -10mm;
            left: 0;
            right: 0;
            font-size: 7.5px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 3px;
        }

        .page-number:after {
            content: counter(page);
        }
    </style>
</head>

<body>

    <div class="page-footer">
        <table style="width: 100%;">
            <tr>
                <td>TERAPOS — Laporan Persediaan & Valuasi Stok</td>
                <td style="text-align: right;">Halaman <span class="page-number"></span></td>
            </tr>
        </table>
    </div>

    <table class="header-table">
        <tr>
            <td style="vertical-align: bottom;">
                <div class="brand-title">TERAPOS</div>
                <div class="brand-subtitle">Sistem Informasi Point of Sale & Inventori</div>
            </td>
            <td style="vertical-align: bottom;">
                <div class="report-title">
                    {{ ($filters['status'] ?? '') === 'critical' ? 'Laporan Stok Kritis (Perlu Restock)' : 'Laporan Stok' }}
                </div>
                <div class="report-meta">
                    Dicetak: <strong>{{ now()->format('d/m/Y H:i') }}</strong>
                    @if (!empty($filters['status']) && $filters['status'] !== 'all')
                        |  Status: <strong>{{ strtoupper($filters['status']) }}</strong>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <table class="summary-table">
        <tr>
            <td class="summary-box">
                <div class="summary-label">Total Barang</div>
                <div class="summary-value nowrap">{{ number_format($summary['products'], 0, ',', '.') }} Item</div>
            </td>
            <td class="summary-box summary-box-critical">
                <div class="summary-label">Stok Kritis</div>
                <div class="summary-value nowrap">{{ number_format($summary['critical'], 0, ',', '.') }} Item</div>
            </td>
            <td class="summary-box summary-box-highlight">
                <div class="summary-label">Nilai Persediaan (Harga Beli)</div>
                <div class="summary-value nowrap">Rp {{ number_format($summary['inventoryValue'], 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="report">
        <thead>
            <tr>
                <th class="center" style="width: 4%;">No</th>
                <th style="width: 14%;">Kode / Barcode</th>
                <th style="width: 22%;">Nama Barang</th>
                <th style="width: 13%;">Kategori</th>
                <th class="right" style="width: 8%;">Stok</th>
                <th class="center" style="width: 5%;">Min</th>
                <th class="right" style="width: 10%;">Harga Beli</th>
                <th class="right" style="width: 10%;">Harga Jual</th>
                <th class="right" style="width: 11%;">Nilai Stok</th>
                <th class="center" style="width: 7%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                @php
                    $nilaiStok = $product->stok * $product->harga_beli;
                @endphp
                <tr>
                    <td class="center nowrap">{{ $loop->iteration }}</td>
                    <td class="nowrap">
                        <span class="code-text">{{ $product->kode_produk }}</span>
                        <span class="barcode-sub">{{ $product->barcode ?? '-' }}</span>
                    </td>
                    <td><strong>{{ $product->nama_produk }}</strong></td>
                    <td>{{ $product->category?->nama_kategori ?? '-' }}</td>
                    <td class="right nowrap">
                        <strong>{{ number_format($product->stok, 0, ',', '.') }}</strong>
                        <span style="color: #64748b; font-size: 7px;">{{ $product->satuan }}</span>
                    </td>
                    <td class="center nowrap" style="color: #64748b;">
                        {{ number_format($product->stok_minimum, 0, ',', '.') }}
                    </td>
                    <td class="right nowrap">Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</td>
                    <td class="right nowrap">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                    <td class="right nowrap"><strong>Rp {{ number_format($nilaiStok, 0, ',', '.') }}</strong></td>
                    <td class="center nowrap">
                        @if ((int) $product->stok === 0)
                            <span class="badge-status out">HABIS</span>
                        @elseif ($product->stok <= $product->stok_minimum)
                            <span class="badge-status low">MENIPIS</span>
                        @else
                            <span class="badge-status safe">AMAN</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="empty">Tidak ada barang untuk filter ini.</td>
                </tr>
            @endforelse
        </tbody>

        @if ($products->count() > 0)
            <tfoot>
                <tr class="grand-total-row">
                    <td colspan="4">TOTAL KESELURUHAN ({{ number_format($summary['products'], 0, ',', '.') }}
                        BARANG)</td>
                    <td class="right nowrap">{{ number_format($products->sum('stok'), 0, ',', '.') }}</td>
                    <td colspan="3" class="right nowrap">Total Nilai Persediaan:</td>
                    <td class="right nowrap">Rp {{ number_format($summary['inventoryValue'], 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>

</body>

</html>
