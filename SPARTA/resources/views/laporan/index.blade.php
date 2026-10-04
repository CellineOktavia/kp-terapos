@extends('app.master')

@section('content')
    <style>
        /* ==========================
           LAPORAN PAGE
        ========================== */

        .laporan-header {
            margin-bottom: 2rem;
        }

        .laporan-title {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: .5rem;
        }

        .laporan-desc {
            color: #64748b;
            max-width: 700px;
            line-height: 1.7;
        }

        .dashboard-card {
            background: #fff;
            border-radius: 20px;
            padding: 24px;
            box-shadow:
                0 10px 30px rgba(15, 23, 42, .06);

            transition: all .3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
            border: none;
        }

        .dashboard-card::before {
            content: '';
            position: absolute;

            top: 0;
            left: 0;

            width: 100%;
            height: 4px;

            background: linear-gradient(90deg,
                    #2563eb,
                    #60a5fa);

            transform: scaleX(0);
            transform-origin: left;
            transition: .4s ease;
        }

        .dashboard-card:hover::before {
            transform: scaleX(1);
        }

        .dashboard-card:hover {
            transform: translateY(-8px);

            box-shadow:
                0 18px 40px rgba(37, 99, 235, .15);
        }

        .dashboard-card::after {
            content: "→";

            position: absolute;

            right: 22px;
            bottom: 18px;

            font-size: 22px;
            font-weight: bold;

            color: #cbd5e1;

            transition: .3s;
        }

        .dashboard-card:hover::after {
            color: #2563eb;
            transform: translateX(5px);
        }

        .card-icon {
            width: 60px;
            height: 60px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 16px;

            margin-bottom: 18px;

            font-size: 1.5rem;
        }

        .icon-primary {
            background: rgba(37, 99, 235, .1);
            color: #2563eb;
        }

        .icon-success {
            background: rgba(22, 163, 74, .1);
            color: #16a34a;
        }

        .icon-info {
            background: rgba(14, 165, 233, .1);
            color: #0ea5e9;
        }

        .icon-warning {
            background: rgba(245, 158, 11, .1);
            color: #d97706;
        }

        .icon-danger {
            background: rgba(239, 68, 68, .1);
            color: #dc2626;
        }

        .icon-dark {
            background: rgba(51, 65, 85, .1);
            color: #334155;
        }

        .dashboard-card h5 {
            color: #0f172a;
            font-weight: 700;
            margin-bottom: 10px;
            transition: .3s;
        }

        .dashboard-card p {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .dashboard-card:hover h5 {
            color: #2563eb;
        }

        .page-summary {
            background: linear-gradient(135deg,
                    #2563eb,
                    #1e40af);

            border-radius: 20px;
            padding: 28px;

            color: white;

            margin-bottom: 30px;

            box-shadow:
                0 15px 40px rgba(37, 99, 235, .2);
        }

        .page-summary h4 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .page-summary p {
            margin: 0;
            opacity: .95;
        }
    </style>

    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="laporan-header">

            <h2 class="laporan-title">
                Laporan SPARTA
            </h2>

            <p class="laporan-desc">

                Pilih jenis laporan yang ingin ditampilkan untuk memantau
                data operasional, transaksi, supplier, pelanggan,
                serta inventori pada sistem SPARTA.

            </p>

        </div>

        {{-- INFO CARD --}}
        <div class="page-summary">

            <h4>
                Pusat Laporan Sistem
            </h4>

            <p>

                Seluruh laporan tersedia dalam satu halaman untuk
                membantu proses monitoring dan pengambilan keputusan
                bisnis Richie Motor secara cepat dan akurat.

            </p>

        </div>

        {{-- MENU LAPORAN --}}
        <div class="row g-4">

            {{-- PRODUK --}}
            <div class="col-lg-4 col-md-6">

                <a href="{{ route('laporan.produk') }}" class="text-decoration-none">

                    <div class="dashboard-card">

                        <div class="card-icon icon-primary">

                            <i class="bi bi-box-seam"></i>

                        </div>

                        <h5>
                            Laporan Produk
                        </h5>

                        <p>

                            Menampilkan data produk, stok barang,
                            dan informasi sparepart yang tersedia.

                        </p>

                    </div>

                </a>

            </div>

            {{-- SUPPLIER --}}
            <div class="col-lg-4 col-md-6">

                <a href="{{ route('laporan.supplier') }}" class="text-decoration-none">

                    <div class="dashboard-card">

                        <div class="card-icon icon-success">

                            <i class="bi bi-truck"></i>

                        </div>

                        <h5>
                            Laporan Supplier
                        </h5>

                        <p>

                            Menampilkan data supplier dan aktivitas
                            pemasokan barang.

                        </p>

                    </div>

                </a>

            </div>

            {{-- CUSTOMER --}}
            <div class="col-lg-4 col-md-6">

                <a href="{{ route('laporan.customer') }}" class="text-decoration-none">

                    <div class="dashboard-card">

                        <div class="card-icon icon-info">

                            <i class="bi bi-people"></i>

                        </div>

                        <h5>
                            Laporan Pelanggan
                        </h5>

                        <p>

                            Menampilkan data pelanggan yang terdaftar
                            dalam sistem.

                        </p>

                    </div>

                </a>

            </div>

            {{-- PEMBELIAN --}}
            <div class="col-lg-4 col-md-6">

                <a href="{{ route('laporan.pembelian') }}" class="text-decoration-none">

                    <div class="dashboard-card">

                        <div class="card-icon icon-warning">

                            <i class="bi bi-cart-check"></i>

                        </div>

                        <h5>
                            Laporan Pembelian
                        </h5>

                        <p>

                            Menampilkan transaksi pembelian sparepart
                            dari supplier.

                        </p>

                    </div>

                </a>

            </div>

            {{-- PENJUALAN --}}
            <div class="col-lg-4 col-md-6">

                <a href="{{ route('laporan.penjualan') }}" class="text-decoration-none">

                    <div class="dashboard-card">

                        <div class="card-icon icon-danger">

                            <i class="bi bi-receipt"></i>

                        </div>

                        <h5>
                            Laporan Penjualan
                        </h5>

                        <p>

                            Menampilkan transaksi penjualan dan
                            pendapatan perusahaan.

                        </p>

                    </div>

                </a>

            </div>

            {{-- STOK --}}
            <div class="col-lg-4 col-md-6">

                <a href="{{ route('laporan.stok') }}" class="text-decoration-none">

                    <div class="dashboard-card">

                        <div class="card-icon icon-dark">

                            <i class="bi bi-bar-chart-line"></i>

                        </div>

                        <h5>
                            Laporan Stok
                        </h5>

                        <p>

                            Menampilkan kondisi stok barang dan
                            riwayat perubahan stok.

                        </p>

                    </div>

                </a>

            </div>

        </div>

    </div>
@endsection
