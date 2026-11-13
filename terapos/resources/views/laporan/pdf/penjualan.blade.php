<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan - TERAPOS</title>
    <style>
        @page {
            margin: 14mm 14mm 16mm 14mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Kop / Header Laporan */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #059669;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }

        .brand-title {
            font-size: 18px;
            font-weight: bold;
            color: #059669;
            letter-spacing: 0.5px;
            margin: 0 0 2px 0;
        }

        .brand-subtitle {
            font-size: 8.5px;
            color: #64748b;
            margin: 0;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            text-align: right;
            margin: 0 0 3px 0;
            text-transform: uppercase;
        }

        .report-meta {
            font-size: 8.5px;
            color: #475569;
            text-align: right;
            margin: 0;
        }

        /* Kartu Ringkasan Eksekutif */
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 0;
            margin: 0 -6px 14px -6px;
        }

        .summary-box {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            border-radius: 4px;
            padding: 8px 10px;
            width: 25%;
        }

        .summary-box-highlight {
            border: 1px solid #6ee7b7;
            background-color: #ecfdf5;
            width: 30%;
        }

        .summary-label {
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 3px;
        }

        .summary-box-highlight .summary-label {
            color: #047857;
        }

        .summary-value {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }

        .summary-box-highlight .summary-value {
            color: #065f46;
            font-size: 13px;
        }

        /* Tabel Laporan Utama */
        table.report {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .report thead th {
            background-color: #059669;
            color: #ffffff;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 7px 6px;
            border: 1px solid #047857;
            text-align: left;
        }

        .report thead th.right { text-align: right; }
        .report thead th.center { text-align: center; }

        /* Baris Header per Nota / Transaksi */
        .trx-header-row td {
            background-color: #f1f5f9;
            border-top: 1.5px solid #94a3b8;
            border-bottom: 1px solid #cbd5e1;
            border-left: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
            padding: 6px 7px;
            font-size: 8.5px;
            color: #0f172a;
        }

        .trx-badge {
            font-weight: bold;
            color: #047857;
        }

        .trx-meta {
            color: #475569;
        }

        .trx-summary-inline {
            text-align: right;
            white-space: nowrap;
        }

        .discount-tag {
            color: #b45309;
            font-weight: bold;
        }

        /* Baris Rincian Item */
        .item-row td {
            padding: 5px 7px;
            border-bottom: 1px solid #e2e8f0;
            border-left: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .code-text {
            font-family: DejaVu Sans Mono, monospace;
            font-size: 8px;
            color: #475569;
        }

        /* Baris Grand Total Bawah */
        .grand-total-row td {
            background-color: #ecfdf5;
            border-top: 2px solid #059669;
            border-bottom: 2px solid #059669;
            border-left: 1px solid #a7f3d0;
            border-right: 1px solid #a7f3d0;
            padding: 8px 7px;
            font-weight: bold;
            font-size: 9.5px;
            color: #065f46;
        }

        .right { text-align: right; }
        .center { text-align: center; }
        .nowrap { white-space: nowrap; }

        .empty {
            text-align: center;
            padding: 24px;
            border: 1px solid #cbd5e1;
            color: #64748b;
        }

        /* Blok Tanda Tangan / Pengesahan */
        .signature-table {
            width: 100%;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 32%;
            text-align: center;
            font-size: 8.5px;
            color: #334155;
        }

        .signature-space {
            height: 48px;
        }

        .signature-line {
            border-top: 1px solid #64748b;
            width: 75%;
            margin: 0 auto 3px auto;
        }
    </style>
</head>
<body>

    @php
        $totalGross = $penjualans->sum(fn($s) => $s->detailPenjualans->sum('subtotal'));
        $totalDiscount = $penjualans->sum('diskon_nominal');
    @endphp

    <!-- Kop Laporan -->
    <table class="header-table">
        <tr>
            <td style="vertical-align: bottom;">
                <div class="brand-title">TERAPOS</div>
                <div class="brand-subtitle">Sistem Informasi Point of Sale & Inventori</div>
            </td>
            <td style="vertical-align: bottom;">
                <div class="report-title">Laporan Penjualan</div>
                <div class="report-meta">
                    Periode: <strong>{{ $period['label'] }}</strong><br>
                    Dicetak pada: {{ now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB
                </div>
            </td>
        </tr>
    </table>

    <!-- Ringkasan Eksekutif (4 Kolom Akuntansi) -->
    <table class="summary-table">
        <tr>
            <td class="summary-box">
                <div class="summary-label">Penjualan Kotor (Subtotal)</div>
                <div class="summary-value nowrap">Rp {{ number_format($totalGross, 0, ',', '.') }}</div>
            </td>
            <td class="summary-box">
                <div class="summary-label">Total Potongan / Diskon</div>
                <div class="summary-value nowrap" style="color: #b45309;">
                    -Rp {{ number_format($totalDiscount, 0, ',', '.') }}
                </div>
            </td>
            <td class="summary-box summary-box-highlight">
                <div class="summary-label">Total Omzet Bersih</div>
                <div class="summary-value nowrap">Rp {{ number_format($summary['total'], 0, ',', '.') }}</div>
            </td>
            <td class="summary-box" style="width: 20%;">
                <div class="summary-label">Volume Penjualan</div>
                <div class="summary-value nowrap">
                    {{ number_format($summary['transactions'], 0, ',', '.') }} Nota /
                    {{ number_format($summary['qty'], 0, ',', '.') }} pcs
                </div>
            </td>
        </tr>
    </table>

    <!-- Tabel Rincian Transaksi & Item -->
    <table class="report">
        <thead>
            <tr>
                <th style="width: 14%;">Kode Produk</th>
                <th style="width: 40%;">Nama Barang</th>
                <th class="center" style="width: 10%;">Qty</th>
                <th class="right" style="width: 18%;">Harga Satuan</th>
                <th class="right" style="width: 18%;">Subtotal Item</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($penjualans as $sale)
                <!-- Sub-Header per Nota (1 Baris Penuh, Anti-Terpotong) -->
                <tr class="trx-header-row">
                    <td colspan="2">
                        <span class="trx-badge">{{ $sale->nomor_penjualan }}</span>
                        <span class="trx-meta">
                             •  {{ $sale->tanggal?->format('d/m/Y') ?? '-' }}
                             •  Kasir: {{ $sale->user?->name ?? '-' }}
                        </span>
                    </td>
                    <td colspan="3" class="trx-summary-inline">
                        <span class="trx-meta">Subtotal:</span>
                        <strong>Rp {{ number_format($sale->detailPenjualans->sum('subtotal'), 0, ',', '.') }}</strong>

                        @if ((float) $sale->diskon_nominal > 0 || (float) $sale->diskon_persen > 0)
                             | 
                            <span class="discount-tag">
                                Diskon ({{ rtrim(rtrim(number_format($sale->diskon_persen, 2, '.', ''), '0'), '.') }}%):
                                -Rp {{ number_format($sale->diskon_nominal, 0, ',', '.') }}
                            </span>
                        @endif

                         | 
                        <span style="color: #047857; font-weight: bold;">
                            Total Nota: Rp {{ number_format($sale->total, 0, ',', '.') }}
                        </span>
                    </td>
                </tr>

                <!-- Rincian Barang per Transaksi -->
                @forelse ($sale->detailPenjualans as $detail)
                    <tr class="item-row">
                        <td class="code-text nowrap">{{ $detail->product?->kode_produk ?? '-' }}</td>
                        <td>{{ $detail->product?->nama_produk ?? 'Barang tidak tersedia' }}</td>
                        <td class="center nowrap">{{ number_format($detail->qty, 0, ',', '.') }}</td>
                        <td class="right nowrap">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="right nowrap">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr class="item-row">
                        <td colspan="5" class="center" style="color: #64748b; font-style: italic;">
                            Tidak ada detail item pada transaksi ini.
                        </td>
                    </tr>
                @endforelse
            @empty
                <tr>
                    <td colspan="5" class="empty">Tidak ada transaksi penjualan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>

        @if ($penjualans->count() > 0)
            <tfoot>
                <tr class="grand-total-row">
                    <td colspan="2">
                        GRAND TOTAL ({{ number_format($summary['transactions'], 0, ',', '.') }} TRANSAKSI)
                    </td>
                    <td class="center nowrap">{{ number_format($summary['qty'], 0, ',', '.') }} pcs</td>
                    <td class="right nowrap">Net Sales:</td>
                    <td class="right nowrap">Rp {{ number_format($summary['total'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- Blok Pengesahan Laporan -->
    @if ($penjualans->count() > 0)
        <table class="signature-table">
            <tr>
                <td class="signature-box">
                    <div>Dibuat Oleh,</div>
                    <div class="signature-space"></div>
                    <div class="signature-line"></div>
                    <div><strong>{{ auth()->user()->name ?? 'Admin / Kasir' }}</strong></div>
                </td>
                <td style="width: 36%;"></td>
                <td class="signature-box">
                    <div>Mengetahui / Disetujui Oleh,</div>
                    <div class="signature-space"></div>
                    <div class="signature-line"></div>
                    <div><strong>Owner / Pimpinan</strong></div>
                </td>
            </tr>
        </table>
    @endif

</body>
</html>