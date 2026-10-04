<!DOCTYPE html>

<html>

<head>

    
    <meta charset="UTF-8">

    <title>
        Laporan Pembelian
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

        .footer {
            margin-top: 25px;
            border-top: 1px solid #d1d5db;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }

        .summary {
            margin-top: 15px;
            text-align: right;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
    

</head>

<body>

    
    {{-- HEADER --}}
    <div class="header">

        <h1>SPARTA</h1>

        <h2>LAPORAN PEMBELIAN</h2>

        <p>
            Sparepart Inventory Management System
        </p>

    </div>

    {{-- INFORMASI LAPORAN --}}
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
                    Total Faktur
                </td>

                <td>
                    : {{ $fakturs->count() }}
                </td>
            </tr>

        </table>

    </div>

    {{-- TABEL --}}
    <table class="report-table">

        <thead>

            <tr>

                <th width="40">
                    No
                </th>

                <th width="140">
                    No Faktur
                </th>

                <th>
                    Supplier
                </th>

                <th width="100">
                    Tanggal
                </th>

                <th width="130">
                    Total
                </th>

            </tr>

        </thead>

        <tbody>

            @php
                $grandTotal = 0;
            @endphp

            @forelse($fakturs as $faktur)
                @php
                    $grandTotal += $faktur->total;
                @endphp

                <tr>

                    <td class="text-center">

                        {{ $loop->iteration }}

                    </td>

                    <td>

                        {{ $faktur->nomor_faktur }}

                    </td>

                    <td>

                        {{ $faktur->supplier->nama_supplier ?? '-' }}

                    </td>

                    <td>

                        {{ \Carbon\Carbon::parse($faktur->tanggal)->format('d-m-Y') }}

                    </td>

                    <td class="text-right">

                        Rp {{ number_format($faktur->total, 0, ',', '.') }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="text-center">

                        Tidak ada data pembelian

                    </td>

                </tr>
            @endforelse

        </tbody>

    </table>

    {{-- TOTAL --}}
    <div class="summary">

        Total Pembelian :
        Rp {{ number_format($grandTotal, 0, ',', '.') }}

    </div>

    {{-- FOOTER --}}
    <div class="footer">

        <strong>SPARTA</strong>
        - Sparepart Inventory Management System

        <br>

        © {{ date('Y') }} Richie Motor

    </div>
    

</body>

</html>
