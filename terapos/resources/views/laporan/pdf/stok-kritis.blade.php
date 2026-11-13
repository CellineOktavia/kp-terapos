<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Stok Kritis - TERAPOS</title>
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
            border-bottom: 2px solid #dc2626;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .brand-title {
            font-size: 17px;
            font-weight: bold;
            color: #dc2626;
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
            color: #b91c1c;
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
            width: 22%;
        }

        .summary-box-out {
            border: 1px solid #fecaca;
            background-color: #fef2f2;
            width: 22%;
        }

        .summary-box-low {
            border: 1px solid #fde68a;
            background-color: #fffbeb;
            width: 22%;
        }

        .summary-box-highlight {
            border: 1px solid #fca5a5;
            background-color: #fef2f2;
            width: 34%;
        }

        .summary-label {
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 2px;
        }

        .summary-box-out .summary-label {
            color: #b91c1c;
        }

        .summary-box-low .summary-label {
            color: #b45309;
        }

        .summary-box-highlight .summary-label {
            color: #b91c1c;
        }

        .summary-value {
            font-size: 11.5px;
            font-weight: bold;
            color: #0f172a;
        }

        .summary-box-out .summary-value {
            color: #b91c1c;
        }

        .summary-box-low .summary-value {
            color: #b45309;
        }

        .summary-box-highlight .summary-value {
            color: #991b1b;
            font-size: 12px;
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
            background-color: #dc2626;
            color: #ffffff;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 6px 5px;
            border: 1px solid #b91c1c;
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
            background-color: #fffafa;
        }

        .code-text {
            font-family: DejaVu Sans Mono, monospace;
            font-size: 7.5px;
            font-weight: bold;
            color: #0f172a;
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

        .grand-total-row td {
            background-color: #fef2f2 !important;
            border-top: 2px solid #dc2626;
            border-bottom: 2px solid #dc2626;
            padding: 7px 6px;
            font-weight: bold;
            font-size: 8.5px;
            color: #991b1b;
        }

        .empty {
            text-align: center;
            padding: 20px;
            color: #64748b;
        }

        .signature-table {
            width: 100%;
            margin-top: 18px;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 32%;
            text-align: center;
            font-size: 8px;
            color: #334155;
        }

        .signature-space {
            height: 44px;
        }

        .signature-line {
            border-top: 1px solid #64748b;
            width: 75%;
            margin: 0 auto 3px auto;
        }

        .page-footer {
            position: fixed;
            bottom: -10mm;
            left: 0;
            right: 0;
            font-size: 7.5px;
            color: #64748b;
            border-top: 1px solid #fecaca;
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
                <td>TERAPOS — Laporan Stok Kritis & Estimasi Pengadaan Barang</td>
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
                <div class="report-title">Laporan Stok Kritis (Perlu Restock)</div>
                <div class="report-meta">
                    Dicetak: <strong>{{ now()->format('d/m/Y H:i') }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <table class="summary-table">
        <tr>
            <td class="summary-box">
                <div class="summary-label">Total Item Kritis</div>
                <div class="summary-value nowrap">{{ number_format($summary['critical'], 0, ',', '.') }} Item</div>
            </td>
            <td class="summary-box summary-box-out">
                <div class="summary-label">Stok Habis (0)</div>
                <div class="summary-value nowrap">{{ number_format($summary['out'], 0, ',', '.') }} Item</div>
            </td>
            <td class="summary-box summary-box-low">
                <div class="summary-label">Stok Menipis</div>
                <div class="summary-value nowrap">{{ number_format($summary['low'], 0, ',', '.') }} Item</div>
            </td>
            <td class="summary-box summary-box-highlight">
                <div class="summary-label">Est. Biaya Restock Minimal</div>
                <div class="summary-value nowrap">Rp {{ number_format($summary['restockEstimate'], 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="report">
        <thead>
            <tr>
                <th class="center" style="width: 4%;">No</th>
                <th style="width: 12%;">Kode</th>
                <th style="width: 25%;">Nama Barang</th>
                <th style="width: 14%;">Kategori</th>
                <th class="center" style="width: 8%;">Stok</th>
                <th class="center" style="width: 7%;">Min.</th>
                <th class="center" style="width: 9%;">Order Min.</th>
                <th class="right" style="width: 10%;">Harga Beli</th>
                <th class="right" style="width: 11%;">Est. Biaya</th>
                <th class="center" style="width: 7%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                @php
                    $saranOrder = max($product->stok_minimum - $product->stok, 1);
                    $estimasiBiaya = $saranOrder * $product->harga_beli;
                @endphp
                <tr>
                    <td class="center nowrap">{{ $loop->iteration }}</td>
                    <td class="code-text nowrap">{{ $product->kode_produk }}</td>
                    <td><strong>{{ $product->nama_produk }}</strong></td>
                    <td>{{ $product->category?->nama_kategori ?? '-' }}</td>
                    <td class="center nowrap">
                        <strong style="color: {{ (int) $product->stok === 0 ? '#b91c1c' : '#b45309' }};">
                            {{ number_format($product->stok, 0, ',', '.') }}
                        </strong>
                        <span style="font-size: 7px; color: #64748b;">{{ $product->satuan }}</span>
                    </td>
                    <td class="center nowrap" style="color: #64748b;">
                        {{ number_format($product->stok_minimum, 0, ',', '.') }}
                    </td>
                    <td class="center nowrap" style="font-weight: bold; color: #b91c1c;">
                        +{{ number_format($saranOrder, 0, ',', '.') }} {{ $product->satuan }}
                    </td>
                    <td class="right nowrap">Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</td>
                    <td class="right nowrap"><strong>Rp {{ number_format($estimasiBiaya, 0, ',', '.') }}</strong></td>
                    <td class="center nowrap">
                        @if ((int) $product->stok === 0)
                            <span class="badge-status out">HABIS</span>
                        @else
                            <span class="badge-status low">MENIPIS</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="empty">Tidak ada barang dengan stok kritis saat ini.</td>
                </tr>
            @endforelse
        </tbody>

        @if ($products->count() > 0)
            <tfoot>
                <tr class="grand-total-row">
                    <td colspan="6">TOTAL ESTIMASI PENGADAAN ({{ number_format($summary['critical'], 0, ',', '.') }}
                        BARANG)</td>
                    <td class="center nowrap">
                        +{{ number_format($products->sum(fn($p) => max($p->stok_minimum - $p->stok, 1)), 0, ',', '.') }}
                        Unit
                    </td>
                    <td class="right nowrap">Total Estimasi:</td>
                    <td class="right nowrap">Rp {{ number_format($summary['restockEstimate'], 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>

    @if ($products->count() > 0)
        <table class="signature-table">
            <tr>
                <td class="signature-box">
                    <div>Dibuat Oleh (Gudang / Kasir),</div>
                    <div class="signature-space"></div>
                    <div class="signature-line"></div>
                    <div><strong>{{ auth()->user()->name ?? 'Admin' }}</strong></div>
                </td>
                <td style="width: 36%;"></td>
                <td class="signature-box">
                    <div>Disetujui Oleh (Owner),</div>
                    <div class="signature-space"></div>
                    <div class="signature-line"></div>
                    <div><strong>Owner / Pimpinan</strong></div>
                </td>
            </tr>
        </table>
    @endif

</body>

</html>
