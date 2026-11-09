<style>
    .sidebar-menu {
        padding: 8px;
    }

    .sidebar-title {
        margin: 18px 12px 8px;
        color: #94a3b8;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .sidebar-menu .list-group-item {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 6px;
        padding: 12px 14px;
        border: 0;
        border-radius: 12px;
        color: #334155;
        background: transparent;
        font-weight: 600;
        transition: all .25s ease;
    }

    .sidebar-menu .list-group-item i {
        width: 20px;
        text-align: center;
        font-size: 1rem;
    }

    .sidebar-menu .list-group-item:hover {
        color: #2563eb;
        background: #eff6ff;
        transform: translateX(4px);
    }

    .sidebar-menu .list-group-item.active {
        color: white;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        box-shadow: 0 8px 20px rgba(37, 99, 235, .20);
    }
</style>

<div class="list-group list-group-flush sidebar-menu">
    @php
        $criticalStockCount = \App\Models\Product::whereColumn('stok', '<=', 'stok_minimum')->count();
    @endphp

    <a href="{{ auth()->user()->isOwner() ? route('owner.dashboard') : route('dashboard') }}"
        class="list-group-item list-group-item-action {{ request()->routeIs('owner.dashboard', 'dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i>
        Dashboard
    </a>

    @if (auth()->user()->isOwner())
        <div class="sidebar-title">Master Data</div>
        <a href="{{ route('produk.index') }}"
            class="list-group-item list-group-item-action {{ request()->is('produk*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            Produk
        </a>
        <a href="{{ route('kategori.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i>
            Kategori
        </a>

        <div class="sidebar-title">Penjualan</div>
        <a href="{{ route('penjualan.index') }}"
            class="list-group-item list-group-item-action {{ request()->is('penjualan*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>
            Penjualan
        </a>

        <div class="sidebar-title">Stok</div>
        <a href="{{ route('stok.index') }}"
            class="list-group-item list-group-item-action {{ request()->is('stok') ? 'active' : '' }}">
            <i class="bi bi-boxes"></i>
            Stok Barang
        </a>
        <a href="{{ route('stok.kritis') }}"
            class="list-group-item list-group-item-action {{ request()->is('stok-kritis*') ? 'active' : '' }}">
            <i class="bi bi-exclamation-triangle"></i>
            Stok Kritis ({{ $criticalStockCount }})
        </a>
        <a href="{{ route('stok.riwayat') }}"
            class="list-group-item list-group-item-action {{ request()->is('riwayat-stok*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            Riwayat Stok
        </a>
        <a href="{{ route('stok.adjustment.create') }}"
            class="list-group-item list-group-item-action {{ request()->is('stok/adjustment*') ? 'active' : '' }}">
            <i class="bi bi-sliders"></i>
            Penyesuaian Stok
        </a>

        <div class="sidebar-title">Laporan</div>
        <a href="{{ route('laporan.penjualan') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('laporan.penjualan*') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i>
            Laporan Penjualan
        </a>
        <a href="{{ route('laporan.stok') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('laporan.stok*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            Laporan Stok
        </a>

        <div class="sidebar-title">Sistem</div>
        <a href="{{ route('backup.index') }}"
            class="list-group-item list-group-item-action {{ request()->is('backup*') ? 'active' : '' }}">
            <i class="bi bi-database-down"></i>
            Backup Database
        </a>
    @else
        <div class="sidebar-title">Master Data</div>
        <a href="{{ route('customer.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('customer.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            Pelanggan
        </a>
        <a href="{{ route('produk.index') }}"
            class="list-group-item list-group-item-action {{ request()->is('produk*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            Produk
        </a>
        <a href="{{ route('kategori.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i>
            Kategori
        </a>

        <div class="sidebar-title">Penjualan</div>
        <a href="{{ route('penjualan.index') }}"
            class="list-group-item list-group-item-action {{ request()->is('penjualan*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>
            Penjualan
        </a>

        <div class="sidebar-title">Stok</div>
        <a href="{{ route('stok.index') }}"
            class="list-group-item list-group-item-action {{ request()->is('stok') ? 'active' : '' }}">
            <i class="bi bi-boxes"></i>
            Stok Barang
        </a>
        <a href="{{ route('stok.kritis') }}"
            class="list-group-item list-group-item-action {{ request()->is('stok-kritis*') ? 'active' : '' }}">
            <i class="bi bi-exclamation-triangle"></i>
            Stok Kritis ({{ $criticalStockCount }})
        </a>
        <a href="{{ route('stok.riwayat') }}"
            class="list-group-item list-group-item-action {{ request()->is('riwayat-stok*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            Riwayat Stok
        </a>

        <div class="sidebar-title">Laporan</div>
        <a href="{{ route('laporan.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('laporan.index') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i>
            Laporan
        </a>
    @endif
</div>
