<!DOCTYPE html>

<html>

<head>


    <meta charset="UTF-8">

    <title>
        Laporan Produk
    </title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 22px;
            color: #2563eb;
            margin-bottom: 4px;
        }

        .header h2 {
            font-size: 16px;
            margin-bottom: 4px;
        }

        .header p {
            color: #666;
            font-size: 11px;
        }

        .info {
            margin-bottom: 15px;
        }

        .info table {
            width: 100%;
        }

        .info td {
            padding: 2px 0;
            border: none;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .report-table th {
            background: #2563eb;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }

        .report-table td {
            padding: 8px;
            border: 1px solid #d1d5db;
        }

        .report-table tr:nth-child(even) {
            background: #f8fafc;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .status-normal {
            color: #16a34a;
            font-weight: bold;
        }

        .status-kritis {
            color: #dc2626;
            font-weight: bold;
        }

        .summary {
            margin-top: 15px;
            text-align: right;
            font-size: 12px;
            font-weight: bold;
        }

        .footer {
            margin-top: 25px;
            border-top: 1px solid #d1d5db;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
    <div class="header">

        <h1>SPARTA</h1>

        <h2>LAPORAN DATA PRODUK</h2>

        <p>
            Sparepart Inventory Management System
        </p>

    </div>

    <div class="info">

        <table>

            <tr>
                <td width="150">
                    Tanggal Cetak
                </td>

                <td>
                    : {{ now()->format('d-m-Y H:i') }}
                </td>
            </tr>

            <tr>
                <td>
                    Total Produk
                </td>

                <td>
                    : {{ $products->count() }}
                </td>
            </tr>

        </table>

    </div>

    @php
        $totalNilaiInventori = 0;
        $totalStok = 0;
    @endphp

    <table class="report-table">

        <thead>

            <tr>

                <th width="40">
                    No
                </th>

                <th width="90">
                    Kode
                </th>

                <th>
                    Nama Produk
                </th>

                <th width="110">
                    Merk
                </th>

                <th width="70">
                    Stok
                </th>

                <th width="120">
                    Harga Jual
                </th>

                <th width="80">
                    Status
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($products as $product)
                @php
                    $totalNilaiInventori += $product->stok * $product->harga_jual;
                    $totalStok += $product->stok;
                @endphp

                <tr>

                    <td class="text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        {{ $product->kode_produk }}
                    </td>

                    <td>
                        {{ $product->nama_produk }}
                    </td>

                    <td>
                        {{ $product->merk }}
                    </td>

                    <td class="text-center">
                        {{ $product->stok }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                    </td>

                    <td class="text-center">

                        @if ($product->stok <= $product->stok_minimum)
                            <span class="status-kritis">
                                KRITIS
                            </span>
                        @else
                            <span class="status-normal">
                                NORMAL
                            </span>
                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="text-center">

                        Tidak ada data produk

                    </td>

                </tr>
            @endforelse

        </tbody>

    </table>

    <div class="summary">

        Total Item Stok :
        {{ number_format($totalStok) }}

        <br>

        Total Nilai Inventori :
        Rp {{ number_format($totalNilaiInventori, 0, ',', '.') }}

    </div>

    <div class="footer">

        <strong>SPARTA</strong>
        - Sparepart Inventory Management System

        <br>

        © {{ date('Y') }} Richie Motor

    </div>


    </body>

</html>
