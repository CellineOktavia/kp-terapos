<style>
    /* =========================
   SIDEBAR SPARTA
========================= */

    .sidebar-menu {
        padding: 8px;
    }

    .sidebar-title {
        margin: 18px 12px 8px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #94a3b8;
    }

    .sidebar-menu .list-group-item {
        border: none;
        border-radius: 12px;
        margin-bottom: 6px;
        padding: 12px 14px;
        font-weight: 600;
        color: #334155;
        background: transparent;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all .25s ease;
    }

    .sidebar-menu .list-group-item i {
        width: 20px;
        text-align: center;
        font-size: 1rem;
    }

    .sidebar-menu .list-group-item:hover {
        background: #eff6ff;
        color: #2563eb;
        transform: translateX(4px);
    }

    .sidebar-menu .list-group-item.active {
        background: linear-gradient(135deg,
                #2563eb,
                #3b82f6);
        color: white;
        box-shadow: 0 8px 20px rgba(37, 99, 235, .20);
    }

    .sidebar-menu .list-group-item.active i {
        color: white;
    }
</style>

<div class="list-group list-group-flush sidebar-menu">

    @if (Auth::user()->role == 'owner')
        <a href="{{ route('owner.dashboard') }}"
            class="list-group-item list-group-item-action {{ request()->is('owner/dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i>
            Dashboard Owner
        </a>

        <div class="sidebar-title">
            Transaksi
        </div>

        <a href="/penjualan"
            class="list-group-item list-group-item-action {{ request()->is('penjualan*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>
            Data Penjualan
        </a>

        <a href="/faktur" class="list-group-item list-group-item-action {{ request()->is('faktur*') ? 'active' : '' }}">
            <i class="bi bi-cart3"></i>
            Data Pembelian
        </a>

        <div class="sidebar-title">
            Laporan
        </div>

        <a href="/laporan/penjualan"
            class="list-group-item list-group-item-action {{ request()->is('laporan/penjualan*') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i>
            Laporan Penjualan
        </a>

        <a href="/laporan/pembelian"
            class="list-group-item list-group-item-action {{ request()->is('laporan/pembelian*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i>
            Laporan Pembelian
        </a>

        <a href="/laporan/stok"
            class="list-group-item list-group-item-action {{ request()->is('laporan/stok*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            Laporan Stok
        </a>

        <a href="/laporan/customer"
            class="list-group-item list-group-item-action {{ request()->is('laporan/customer*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            Laporan Customer
        </a>

        <a href="/laporan/supplier"
            class="list-group-item list-group-item-action {{ request()->is('laporan/supplier*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i>
            Laporan Supplier
        </a>
    @endif

    @if (Auth::user()->role == 'admin')
        <a href="/dashboard"
            class="list-group-item list-group-item-action {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i>
            Dashboard Admin
        </a>

        <div class="sidebar-title">
            Master Data
        </div>

        <a href="{{ route('customer.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('customer.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            Pelanggan
        </a>

        <a href="/produk"
            class="list-group-item list-group-item-action {{ request()->is('produk*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            Produk
        </a>

        <a href="/supplier"
            class="list-group-item list-group-item-action {{ request()->is('supplier*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i>
            Supplier
        </a>

        <div class="sidebar-title">
            Pembelian
        </div>

        <a href="/faktur"
            class="list-group-item list-group-item-action {{ request()->is('faktur*') ? 'active' : '' }}">
            <i class="bi bi-cart3"></i>
            Faktur Pembelian
        </a>

        <div class="sidebar-title">
            Penjualan
        </div>

        <a href="/penjualan"
            class="list-group-item list-group-item-action {{ request()->is('penjualan*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>
            Faktur Penjualan
        </a>

        <div class="sidebar-title">
            Inventory
        </div>

        <a href="/stok-kritis"
            class="list-group-item list-group-item-action {{ request()->is('stok-kritis*') ? 'active' : '' }}">
            <i class="bi bi-exclamation-triangle"></i>
            Stok Kritis
        </a>

        <a href="/riwayat-stok"
            class="list-group-item list-group-item-action {{ request()->is('riwayat-stok*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            Riwayat Stok
        </a>

        <div class="sidebar-title">
            Laporan
        </div>

        <a href="/laporan"
            class="list-group-item list-group-item-action {{ request()->is('laporan*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i>
            Laporan
        </a>
    @endif

</div>
