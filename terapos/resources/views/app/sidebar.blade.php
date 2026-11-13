<style>
    .sidebar-menu {
        padding: 10px 8px 24px;
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
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 5px;
        padding: 11px 14px;
        border: 0;
        border-radius: 12px;
        color: #334155;
        background: transparent;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .25s ease;
    }

    .sidebar-item-left {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .sidebar-item-left span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sidebar-menu .list-group-item i {
        width: 20px;
        text-align: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .sidebar-menu .list-group-item:hover {
        color: #2563eb;
        background: #eff6ff;
        transform: translateX(4px);
    }

    .sidebar-menu .list-group-item.active {
        color: #ffffff;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        box-shadow: 0 8px 20px rgba(37, 99, 235, .20);
    }

    .sidebar-badge-critical {
        background-color: #fee2e2;
        color: #b91c1c;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 999px;
        flex-shrink: 0;
    }

    .sidebar-menu .list-group-item.active .sidebar-badge-critical {
        background-color: rgba(255, 255, 255, 0.22);
        color: #ffffff;
    }

    .sidebar-mobile-header {
        display: none;
    }

    .mobile-sidebar-toggle {
        display: none;
    }

    .sidebar-backdrop {
        display: none;
    }

    @media (max-width: 991.98px) {
        .mobile-sidebar-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: fixed;
            bottom: 18px;
            right: 18px;
            z-index: 1040;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: #ffffff;
            border: none;
            border-radius: 999px;
            padding: 12px 18px;
            font-size: 0.875rem;
            font-weight: 700;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.35);
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .mobile-sidebar-toggle:active {
            transform: scale(0.96);
        }

        .sidebar-drawer {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 280px;
            max-width: 82vw;
            background-color: #ffffff;
            z-index: 1050;
            overflow-y: auto;
            transform: translateX(-105%);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 10px 0 30px rgba(15, 23, 42, 0.15);
            -webkit-overflow-scrolling: touch;
        }

        .sidebar-drawer.show {
            transform: translateX(0);
        }

        .sidebar-mobile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px;
            border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(135deg, #ffffff 0%, #eff6ff 100%);
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .sidebar-close-btn {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #475569;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .sidebar-menu .list-group-item {
            padding: 13px 14px;
            font-size: 0.925rem;
        }

        .sidebar-menu .list-group-item:hover {
            transform: none;
        }

        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            background-color: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(2px);
            z-index: 1045;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }
    }
</style>

@php
    $criticalStockCount = \App\Models\Product::whereColumn('stok', '<=', 'stok_minimum')->count();
    $isOwner = auth()->user()->isOwner();
@endphp

<!-- Tombol Floating Buka Menu di Handphone -->
<button type="button" class="mobile-sidebar-toggle" id="btnOpenTeraposSidebar" aria-label="Buka Menu Navigasi">
    <i class="bi bi-list fs-5"></i>
    <span>Menu</span>
    @if ($criticalStockCount > 0)
        <span class="badge bg-danger rounded-pill">{{ $criticalStockCount }}</span>
    @endif
</button>

<!-- Backdrop Gelap Mobile -->
<div class="sidebar-backdrop" id="teraposSidebarBackdrop"></div>

<!-- Container Sidebar -->
<aside class="sidebar-drawer" id="teraposSidebarDrawer">
    <div class="sidebar-mobile-header">
        <div class="d-flex align-items-center gap-2">
            <span class="d-inline-flex align-items-center justify-content-center rounded-3 text-white fw-bold"
                style="width: 32px; height: 32px; background: linear-gradient(135deg, #2563eb, #3b82f6);">
                T
            </span>
            <div>
                <div class="fw-bold text-dark lh-1" style="font-size: 15px;">TERAPOS</div>
                <small class="text-muted" style="font-size: 11px;">{{ auth()->user()->name ?? 'Menu Utama' }}</small>
            </div>
        </div>
        <button type="button" class="sidebar-close-btn" id="btnCloseTeraposSidebar" aria-label="Tutup Menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="list-group list-group-flush sidebar-menu">
        <!-- Dashboard -->
        <a href="{{ $isOwner ? route('owner.dashboard') : route('dashboard') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('owner.dashboard', 'dashboard') ? 'active' : '' }}">
            <div class="sidebar-item-left">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </div>
        </a>

        <!-- Master Data -->
        <div class="sidebar-title">Master Data</div>
        <a href="{{ route('produk.index') }}"
            class="list-group-item list-group-item-action {{ request()->is('produk*') ? 'active' : '' }}">
            <div class="sidebar-item-left">
                <i class="bi bi-box-seam"></i>
                <span>Barang</span>
            </div>
        </a>
        <a href="{{ route('kategori.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
            <div class="sidebar-item-left">
                <i class="bi bi-tags"></i>
                <span>Kategori</span>
            </div>
        </a>
        <a href="{{ route('penerimaan.index') }}"
            class="list-group-item list-group-item-action {{ request()->is('penerimaan*') ? 'active' : '' }}">
            <div class="sidebar-item-left">
                <i class="bi bi-box-arrow-in-down"></i>
                <span>Penerimaan Barang</span>
            </div>
        </a>

        <!-- Penjualan -->
        <div class="sidebar-title">Penjualan</div>
        <a href="{{ route('penjualan.index') }}"
            class="list-group-item list-group-item-action {{ request()->is('penjualan*') ? 'active' : '' }}">
            <div class="sidebar-item-left">
                <i class="bi bi-receipt"></i>
                <span>Penjualan</span>
            </div>
        </a>

        <!-- Stok -->
        <div class="sidebar-title">Stok</div>
        <a href="{{ route('stok.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('stok.index') ? 'active' : '' }}">
            <div class="sidebar-item-left">
                <i class="bi bi-boxes"></i>
                <span>Stok Barang</span>
            </div>
        </a>
        @if (Route::has('stok.kritis'))
            <a href="{{ route('stok.kritis') }}"
                class="list-group-item list-group-item-action {{ request()->routeIs('stok.kritis') ? 'active' : '' }}">
                <div class="sidebar-item-left">
                    <i class="bi bi-exclamation-triangle"></i>
                    <span>Stok Kritis</span>
                </div>
                @if ($criticalStockCount > 0)
                    <span class="sidebar-badge-critical">{{ $criticalStockCount }}</span>
                @endif
            </a>
        @endif
        <a href="{{ route('stok.riwayat') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('stok.riwayat*') ? 'active' : '' }}">
            <div class="sidebar-item-left">
                <i class="bi bi-clock-history"></i>
                <span>Riwayat Stok</span>
            </div>
        </a>
        @if ($isOwner)
            <a href="{{ route('stok.adjustment.create') }}"
                class="list-group-item list-group-item-action {{ request()->routeIs('stok.adjustment*') ? 'active' : '' }}">
                <div class="sidebar-item-left">
                    <i class="bi bi-sliders"></i>
                    <span>Penyesuaian Stok</span>
                </div>
            </a>
        @endif

        <!-- Laporan -->
        <div class="sidebar-title">Laporan</div>
        @if ($isOwner)
            <a href="{{ route('laporan.penjualan') }}"
                class="list-group-item list-group-item-action {{ request()->routeIs('laporan.penjualan*') ? 'active' : '' }}">
                <div class="sidebar-item-left">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Laporan Penjualan</span>
                </div>
            </a>
            <a href="{{ route('laporan.stok') }}"
                class="list-group-item list-group-item-action {{ request()->routeIs('laporan.stok', 'laporan.stok.pdf') ? 'active' : '' }}">
                <div class="sidebar-item-left">
                    <i class="bi bi-box-seam"></i>
                    <span>Laporan Stok</span>
                </div>
            </a>
            <a href="{{ route('laporan.stok-kritis') }}"
                class="list-group-item list-group-item-action {{ request()->routeIs('laporan.stok-kritis', 'laporan.stok-kritis.pdf') ? 'active' : '' }}">
                <div class="sidebar-item-left">
                    <i class="bi bi-clipboard2-pulse"></i>
                    <span>Laporan Stok Kritis</span>
                </div>
                @if ($criticalStockCount > 0)
                    <span class="sidebar-badge-critical">{{ $criticalStockCount }}</span>
                @endif
            </a>
        @else
            <a href="{{ route('laporan.index') }}"
                class="list-group-item list-group-item-action {{ request()->routeIs('laporan.index') ? 'active' : '' }}">
                <div class="sidebar-item-left">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    <span>Laporan</span>
                </div>
            </a>
            <a href="{{ route('laporan.stok-kritis') }}"
                class="list-group-item list-group-item-action {{ request()->routeIs('laporan.stok-kritis', 'laporan.stok-kritis.pdf') ? 'active' : '' }}">
                <div class="sidebar-item-left">
                    <i class="bi bi-clipboard2-pulse"></i>
                    <span>Laporan Stok Kritis</span>
                </div>
                @if ($criticalStockCount > 0)
                    <span class="sidebar-badge-critical">{{ $criticalStockCount }}</span>
                @endif
            </a>
        @endif

        <!-- Sistem (Khusus Owner) -->
        @if ($isOwner)
            <div class="sidebar-title">Sistem</div>
            <a href="{{ route('backup.index') }}"
                class="list-group-item list-group-item-action {{ request()->is('backup*') ? 'active' : '' }}">
                <div class="sidebar-item-left">
                    <i class="bi bi-database-down"></i>
                    <span>Backup Database</span>
                </div>
            </a>
        @endif
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openBtn = document.getElementById('btnOpenTeraposSidebar');
        const closeBtn = document.getElementById('btnCloseTeraposSidebar');
        const drawer = document.getElementById('teraposSidebarDrawer');
        const backdrop = document.getElementById('teraposSidebarBackdrop');

        if (!openBtn || !drawer || !backdrop) return;

        function openSidebar() {
            drawer.classList.add('show');
            backdrop.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            drawer.classList.remove('show');
            backdrop.classList.remove('show');
            document.body.style.overflow = '';
        }

        openBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        backdrop.addEventListener('click', closeSidebar);

        drawer.querySelectorAll('a.list-group-item').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    closeSidebar();
                }
            });
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                closeSidebar();
            }
        });
    });
</script>
