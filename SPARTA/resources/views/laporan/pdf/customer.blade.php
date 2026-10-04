<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">

    
    <title>
        Laporan Pelanggan
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

        .report-table tr:hover {
            background: #eef4ff;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 25px;
            border-top: 1px solid #d1d5db;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }

        .total-box {
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

        <h2>LAPORAN DATA PELANGGAN</h2>

        <p>
            Sparepart Inventory Management System
        </p>

    </div>

    {{-- INFO CETAK --}}
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
                    Total Pelanggan
                </td>

                <td>
                    : {{ $customers->count() }}
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

                <th width="100">
                    Kode
                </th>

                <th>
                    Nama Pelanggan
                </th>

                <th width="120">
                    Telepon
                </th>

                <th>
                    Alamat
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($customers as $customer)
                <tr>

                    <td class="text-center">

                        {{ $loop->iteration }}

                    </td>

                    <td>

                        {{ $customer->kode_customer }}

                    </td>

                    <td>

                        {{ $customer->nama_customer }}

                    </td>

                    <td>

                        {{ $customer->telepon }}

                    </td>

                    <td>

                        {{ $customer->alamat }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="text-center">

                        Tidak ada data pelanggan

                    </td>

                </tr>
            @endforelse

        </tbody>

    </table>

    {{-- FOOTER --}}
    <div class="footer">

        <strong>SPARTA</strong> -
        Sparepart Inventory Management System

        <br>

        © {{ date('Y') }} Richie Motor

    </div>
    

</body>

</html>
