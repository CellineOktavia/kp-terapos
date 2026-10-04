
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap');

    :root {
        --navy: #0d1b2a;
        --navy-mid: #1a2e45;
        --blue: #2563eb;
        --blue-light: #3b82f6;
        --blue-glow: rgba(37, 99, 235, 0.18);
        --amber: #f59e0b;
        --ice: #f0f6ff;
        --slate: #64748b;
        --border: #e2e8f0;
        --white: #ffffff;

        --radius-sm: 10px;
        --radius-md: 18px;
        --radius-lg: 24px;

        --shadow-card: 0 4px 24px rgba(13, 27, 42, .07);
        --shadow-hover: 0 12px 40px rgba(37, 99, 235, .14);

        --font-main: 'Plus Jakarta Sans', sans-serif;
        --font-mono: 'DM Mono', monospace;
    }

    body {
        font-family: var(--font-main);
        background: #f4f7fb;
        color: var(--navy);
    }

    /* ── Hero ─────────────────────────────────────────── */
    .sparta-hero {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 55%, #1e3a5f 100%);
        border-radius: var(--radius-lg);
        padding: 40px 44px;
        position: relative;
        overflow: hidden;
        margin-bottom: 32px;
        box-shadow: 0 20px 60px rgba(13, 27, 42, .22);
    }

    .sparta-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 320px;
        height: 320px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(37, 99, 235, .35) 0%, transparent 70%);
        pointer-events: none;
    }

    .sparta-hero::after {
        content: 'SPARTA';
        position: absolute;
        bottom: -20px;
        right: 30px;
        font-size: 9rem;
        font-weight: 800;
        letter-spacing: -4px;
        color: rgba(255, 255, 255, .03);
        pointer-events: none;
        user-select: none;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(37, 99, 235, .25);
        border: 1px solid rgba(96, 165, 250, .3);
        color: #93c5fd;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 100px;
        margin-bottom: 16px;
    }

    .hero-badge .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #60a5fa;
        animation: pulse-dot 1.8s ease-in-out infinite;
    }

    @keyframes pulse-dot {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: .4;
            transform: scale(.6);
        }
    }

    .hero-title {
        font-size: 1.85rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .hero-subtitle {
        font-size: .92rem;
        color: #93c5fd;
        font-weight: 500;
        margin-bottom: 24px;
    }

    .hero-divider {
        width: 48px;
        height: 3px;
        background: var(--amber);
        border-radius: 2px;
        margin-bottom: 20px;
    }

    .hero-welcome {
        font-size: 1.1rem;
        font-weight: 700;
        color: #e2e8f0;
    }

    .hero-role {
        display: inline-block;
        background: var(--amber);
        color: var(--navy);
        font-size: .7rem;
        font-weight: 800;
        letter-spacing: .1em;
        text-transform: uppercase;
        padding: 3px 12px;
        border-radius: 100px;
        margin-top: 6px;
    }

    /* ── Stat Cards ───────────────────────────────────── */
    .stat-card {
        background: var(--white);
        border-radius: var(--radius-lg);
        padding: 26px 24px;
        box-shadow: var(--shadow-card);
        border: 1px solid var(--border);
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: block;
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        text-decoration: none;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }

    .stat-card.c-blue::before {
        background: linear-gradient(90deg, #2563eb, #60a5fa);
    }

    .stat-card.c-green::before {
        background: linear-gradient(90deg, #059669, #34d399);
    }

    .stat-card.c-amber::before {
        background: linear-gradient(90deg, #d97706, #fbbf24);
    }

    .stat-card.c-red::before {
        background: linear-gradient(90deg, #dc2626, #f87171);
    }

    .stat-icon-wrap {
        width: 52px;
        height: 52px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 16px;
    }

    .c-blue .stat-icon-wrap {
        background: #eff6ff;
        color: #2563eb;
    }

    .c-green .stat-icon-wrap {
        background: #ecfdf5;
        color: #059669;
    }

    .c-amber .stat-icon-wrap {
        background: #fffbeb;
        color: #d97706;
    }

    .c-red .stat-icon-wrap {
        background: #fef2f2;
        color: #dc2626;
    }

    .stat-value {
        font-size: 2.1rem;
        font-weight: 800;
        color: var(--navy);
        line-height: 1;
        font-variant-numeric: tabular-nums;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: .82rem;
        font-weight: 600;
        color: var(--slate);
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    /* ── Section Label ────────────────────────────────── */
    .section-eyebrow {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: var(--blue);
        margin-bottom: 8px;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--navy);
        margin: 0;
    }

    /* ── Panel Card ───────────────────────────────────── */
    .panel {
        background: var(--white);
        border-radius: var(--radius-lg);
        padding: 28px;
        box-shadow: var(--shadow-card);
        border: 1px solid var(--border);
    }

    /* ── Quick Actions ────────────────────────────────── */
    .quick-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 18px;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: .87rem;
        border: none;
        cursor: pointer;
        transition: transform .2s ease, box-shadow .2s ease;
        text-decoration: none;
        width: 100%;
    }

    .quick-btn:hover {
        transform: translateY(-3px);
        text-decoration: none;
    }

    .quick-btn i {
        font-size: 1.1rem;
    }

    .qb-blue {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: #fff;
        box-shadow: 0 6px 18px rgba(37, 99, 235, .28);
    }

    .qb-green {
        background: linear-gradient(135deg, #059669, #34d399);
        color: #fff;
        box-shadow: 0 6px 18px rgba(5, 150, 105, .28);
    }

    .qb-amber {
        background: linear-gradient(135deg, #d97706, #fbbf24);
        color: #fff;
        box-shadow: 0 6px 18px rgba(217, 119, 6, .28);
    }

    .qb-red {
        background: linear-gradient(135deg, #dc2626, #f87171);
        color: #fff;
        box-shadow: 0 6px 18px rgba(220, 38, 38, .28);
    }

    .qb-blue:hover {
        box-shadow: 0 12px 28px rgba(37, 99, 235, .38);
        color: #fff;
    }

    .qb-green:hover {
        box-shadow: 0 12px 28px rgba(5, 150, 105, .38);
        color: #fff;
    }

    .qb-amber:hover {
        box-shadow: 0 12px 28px rgba(217, 119, 6, .38);
        color: #fff;
    }

    .qb-red:hover {
        box-shadow: 0 12px 28px rgba(220, 38, 38, .38);
        color: #fff;
    }

    /* ── Filter Toolbar ───────────────────────────────── */
    .filter-toolbar {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .filter-chip {
        padding: 7px 18px;
        border-radius: 100px;
        font-weight: 600;
        font-size: .83rem;
        border: 1.5px solid var(--border);
        color: var(--slate);
        background: var(--white);
        cursor: pointer;
        text-decoration: none;
        transition: all .2s;
    }

    .filter-chip:hover {
        border-color: var(--blue-light);
        color: var(--blue);
        text-decoration: none;
    }

    .filter-chip.active {
        background: var(--navy);
        border-color: var(--navy);
        color: #fff;
    }

    /* ── Date Range Form ──────────────────────────────── */
    .date-range-row {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        flex-wrap: wrap;
        background: var(--ice);
        border: 1px solid #dbeafe;
        border-radius: var(--radius-md);
        padding: 16px 20px;
    }

    .date-range-row .date-group {
        flex: 1;
        min-width: 160px;
    }

    .date-range-row label {
        font-size: .78rem;
        font-weight: 700;
        color: var(--slate);
        text-transform: uppercase;
        letter-spacing: .07em;
        margin-bottom: 6px;
        display: block;
    }

    .date-range-row input[type="date"] {
        width: 100%;
        padding: 10px 14px;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--border);
        font-family: var(--font-mono);
        font-size: .88rem;
        color: var(--navy);
        background: var(--white);
        transition: border-color .2s;
    }

    .date-range-row input[type="date"]:focus {
        outline: none;
        border-color: var(--blue);
        box-shadow: 0 0 0 4px var(--blue-glow);
    }

    .btn-apply {
        background: var(--blue);
        color: #fff;
        border: none;
        border-radius: var(--radius-sm);
        padding: 10px 22px;
        font-weight: 700;
        font-size: .87rem;
        cursor: pointer;
        transition: background .2s, box-shadow .2s;
        white-space: nowrap;
        align-self: flex-end;
    }

    .btn-apply:hover {
        background: #1d4ed8;
        box-shadow: 0 8px 20px rgba(37, 99, 235, .3);
    }

    /* ── Info Banner ──────────────────────────────────── */
    .info-banner {
        display: flex;
        align-items: center;
        gap: 12px;
        background: var(--ice);
        border: 1px solid #bfdbfe;
        border-radius: var(--radius-md);
        padding: 14px 18px;
        font-size: .87rem;
        color: #1e40af;
        margin-bottom: 24px;
    }

    .info-banner i {
        color: var(--blue);
        font-size: 1.1rem;
    }

    .info-banner strong {
        color: var(--navy);
    }

    /* ── Chart Area ───────────────────────────────────── */
    .chart-wrap {
        position: relative;
        height: 300px;
        margin-top: 8px;
    }

    /* ── System Summary ───────────────────────────────── */
    .summary-panel {
        background: linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%);
        border-radius: var(--radius-lg);
        padding: 30px 32px;
        color: #e2e8f0;
        position: relative;
        overflow: hidden;
    }

    .summary-panel::before {
        content: '';
        position: absolute;
        bottom: -40px;
        right: -40px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(37, 99, 235, .25) 0%, transparent 70%);
    }

    .summary-panel .sp-icon {
        width: 54px;
        height: 54px;
        background: rgba(37, 99, 235, .25);
        border: 1px solid rgba(96, 165, 250, .3);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #60a5fa;
        font-size: 1.4rem;
        margin-bottom: 16px;
    }

    .summary-panel h5 {
        color: #fff;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .summary-panel .sp-sub {
        color: #93c5fd;
        font-size: .82rem;
        margin-bottom: 16px;
    }

    .summary-panel p {
        font-size: .9rem;
        line-height: 1.8;
        color: #cbd5e1;
    }

    .summary-panel strong {
        color: #93c5fd;
    }

    /* ── Footer ───────────────────────────────────────── */
    .sparta-footer {
        text-align: center;
        padding: 24px 0;
        margin-top: 40px;
        border-top: 1px solid var(--border);
        font-size: .82rem;
        color: var(--slate);
    }

    .sparta-footer strong {
        color: var(--navy);
    }

    /* ── Responsive ───────────────────────────────────── */
    @media (max-width: 576px) {
        .sparta-hero {
            padding: 28px 22px;
        }

        .hero-title {
            font-size: 1.4rem;
        }

        .stat-value {
            font-size: 1.7rem;
        }
    }
</style>

@extends('app.master')
@section('content')
    <div class="container-fluid py-4">

        {{-- ══ HERO ═══════════════════════════════════════════ --}}
        <div class="sparta-hero">
            <div class="hero-badge">
                <span class="dot"></span> Sistem Aktif
            </div>
            <h1 class="hero-title">Sparepart Inventory<br>Management System</h1>
            <p class="hero-subtitle">Richie Motor · Management Dashboard</p>
            <div class="hero-divider"></div>
            <p class="hero-welcome">Halo, {{ Auth::user()->name }} 👋</p>
            <span class="hero-role">{{ strtoupper(Auth::user()->role) }}</span>
        </div>

        {{-- ══ STAT CARDS ═════════════════════════════════════ --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <a href="{{ route('produk.index') }}" class="stat-card c-blue">
                    <div class="stat-icon-wrap"><i class="bi bi-box-seam-fill"></i></div>
                    <div class="stat-value">{{ $totalProduk }}</div>
                    <div class="stat-label">Total Produk</div>
                </a>
            </div>
            <div class="col-6 col-lg-3">
                <a href="{{ route('supplier.index') }}" class="stat-card c-green">
                    <div class="stat-icon-wrap"><i class="bi bi-truck-front-fill"></i></div>
                    <div class="stat-value">{{ $totalSupplier }}</div>
                    <div class="stat-label">Supplier</div>
                </a>
            </div>
            <div class="col-6 col-lg-3">
                <a href="{{ route('faktur.index') }}" class="stat-card c-amber">
                    <div class="stat-icon-wrap"><i class="bi bi-receipt-cutoff"></i></div>
                    <div class="stat-value">{{ $totalFaktur }}</div>
                    <div class="stat-label">Total Faktur</div>
                </a>
            </div>
            <div class="col-6 col-lg-3">
                <a href="{{ route('stok.kritis') }}" class="stat-card c-red">
                    <div class="stat-icon-wrap"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div class="stat-value">{{ $stokKritis }}</div>
                    <div class="stat-label">Stok Kritis</div>
                </a>
            </div>
        </div>

        {{-- ══ QUICK ACTIONS ═══════════════════════════════════ --}}
        <div class="panel mb-4">
            <div class="section-eyebrow">Menu</div>
            <div class="section-title mb-3">Akses Cepat</div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <a href="{{ route('produk.create') }}" class="quick-btn qb-blue">
                        <i class="bi bi-plus-circle-fill"></i> Tambah Produk
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('supplier.create') }}" class="quick-btn qb-green">
                        <i class="bi bi-truck-front-fill"></i> Tambah Supplier
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('faktur.create') }}" class="quick-btn qb-amber">
                        <i class="bi bi-receipt-cutoff"></i> Buat Faktur
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('stok.kritis') }}" class="quick-btn qb-red">
                        <i class="bi bi-exclamation-triangle-fill"></i> Stok Kritis
                    </a>
                </div>
            </div>
        </div>

        {{-- ══ REVENUE CHART ═══════════════════════════════════ --}}
        <div class="panel mb-4">
            <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
                <div>
                    <div class="section-eyebrow">Keuangan</div>
                    <div class="section-title">Statistik Pendapatan</div>
                </div>
            </div>

            {{-- Info Banner --}}
            <div class="info-banner">
                <i class="bi bi-calendar3"></i>
                Menampilkan data dari <strong>&nbsp;{{ $startDate }}&nbsp;</strong> sampai
                <strong>&nbsp;{{ $endDate }}</strong>
            </div>

            {{-- Filter Chips --}}
            <div class="filter-toolbar">
                <a href="{{ route('dashboard', ['filter' => 'hari']) }}"
                    class="filter-chip {{ $filter == 'hari' ? 'active' : '' }}">
                    <i class="bi bi-sun me-1"></i>Hari Ini
                </a>
                <a href="{{ route('dashboard', ['filter' => 'bulan']) }}"
                    class="filter-chip {{ $filter == 'bulan' ? 'active' : '' }}">
                    <i class="bi bi-calendar-month me-1"></i>Bulan Ini
                </a>
                <a href="{{ route('dashboard', ['filter' => 'tahun']) }}"
                    class="filter-chip {{ $filter == 'tahun' ? 'active' : '' }}">
                    <i class="bi bi-calendar3 me-1"></i>Tahun Ini
                </a>
            </div>

            {{-- Custom Date Range --}}
            <form method="GET" action="{{ route('dashboard') }}">
                <input type="hidden" name="filter" value="custom">
                <div class="date-range-row">
                    <div class="date-group">
                        <label>Tanggal Awal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}">
                    </div>
                    <div class="date-group">
                        <label>Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ $endDate }}">
                    </div>
                    <button type="submit" class="btn-apply">
                        <i class="bi bi-funnel-fill me-2"></i>Terapkan
                    </button>
                </div>
            </form>

            {{-- Canvas --}}
            <div class="chart-wrap" id="chart-section">
                <canvas id="incomeChart"></canvas>
            </div>
        </div>

        {{-- ══ SYSTEM SUMMARY ══════════════════════════════════ --}}
        <div class="summary-panel mb-4">
            <div class="sp-icon"><i class="bi bi-info-circle-fill"></i></div>
            <h5>Tentang SPARTA</h5>
            <p class="sp-sub">Sparepart Inventory Management System · Richie Motor</p>
            <p>
                SPARTA membantu administrator mengelola <strong>produk</strong>,
                <strong>supplier</strong>, <strong>transaksi pembelian</strong>,
                <strong>transaksi penjualan</strong>, serta <strong>pemantauan stok kritis</strong>
                secara terintegrasi dalam satu dashboard yang efisien.
            </p>
        </div>

    </div>

    {{-- ══ FOOTER ══════════════════════════════════════════ --}}
    <footer class="sparta-footer">
        <strong>SPARTA</strong> — Sparepart Inventory Management System<br>
        <small>© 2026 Richie Motor. All Rights Reserved.</small>
    </footer>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (() => {
            const labels = {!! json_encode($pendapatanHarian->pluck('tanggal')->values()) !!};
            const values = {!! json_encode($pendapatanHarian->pluck('total')->values()) !!};

            const canvas = document.getElementById('incomeChart');
            if (!canvas) return;

            /* gradient fill */
            const ctx = canvas.getContext('2d');
            const grad = ctx.createLinearGradient(0, 0, 0, 300);
            grad.addColorStop(0, 'rgba(37,99,235,.22)');
            grad.addColorStop(1, 'rgba(37,99,235,.0)');

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: values,
                        borderColor: '#2563eb',
                        backgroundColor: grad,
                        fill: true,
                        tension: 0.42,
                        pointRadius: 5,
                        pointHoverRadius: 9,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2.5,
                        borderWidth: 2.5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 700,
                        easing: 'easeInOutQuart'
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0d1b2a',
                            titleFont: {
                                family: 'Plus Jakarta Sans',
                                weight: '700'
                            },
                            bodyFont: {
                                family: 'DM Mono',
                                size: 13
                            },
                            padding: 14,
                            cornerRadius: 10,
                            callbacks: {
                                label: c => ' Rp ' + c.raw.toLocaleString('id-ID')
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: 'Plus Jakarta Sans',
                                    size: 11
                                },
                                color: '#94a3b8',
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(148,163,184,.12)',
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    family: 'DM Mono',
                                    size: 11
                                },
                                color: '#94a3b8',
                                callback: v => 'Rp ' + v.toLocaleString('id-ID')
                            }
                        }
                    }
                }
            });

            /* smooth-scroll to chart on filter */
            const params = new URLSearchParams(window.location.search);
            if (params.has('filter') || params.has('start_date')) {
                setTimeout(() => {
                    document.getElementById('chart-section')
                        ?.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                }, 350);
            }
        })();
    </script>
@endpush
