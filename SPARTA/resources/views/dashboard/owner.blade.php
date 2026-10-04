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
        content: 'OWNER';
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

    /* ─────────────────────────────────────────────
   STAT CARDS
───────────────────────────────────────────── */

    .stat-card {
        background: var(--white);
        border-radius: var(--radius-lg);
        padding: 26px 24px;
        box-shadow: var(--shadow-card);
        border: 1px solid var(--border);
        position: relative;
        overflow: hidden;
        transition: all .3s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-6px);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
    }

    /* ========================================
   PENDAPATAN
======================================== */

    .stat-card.c-blue {
        background: linear-gradient(135deg,
                #ffffff,
                #f8fbff);
    }

    .stat-card.c-blue::before {
        background: linear-gradient(90deg,
                #2563eb,
                #60a5fa);
    }

    .c-blue .stat-icon-wrap {
        background: #eff6ff;
        color: #2563eb;
    }

    /* ========================================
   PEMBELIAN
======================================== */

    .stat-card.c-green {
        background: linear-gradient(135deg,
                #ffffff,
                #f6fffa);
    }

    .stat-card.c-green::before {
        background: linear-gradient(90deg,
                #10b981,
                #34d399);
    }

    .c-green .stat-icon-wrap {
        background: #ecfdf5;
        color: #059669;
    }

    /* ========================================
   PROFIT
======================================== */

    .stat-card.c-profit {
        background: linear-gradient(135deg,
                #ffffff,
                #f0fdf4);
    }

    .stat-card.c-profit::before {
        background: linear-gradient(90deg,
                #059669,
                #10b981);
    }

    .c-profit .stat-icon-wrap {
        background: rgba(16, 185, 129, .12);
        color: #059669;
    }

    .c-profit .stat-value {
        color: #065f46;
    }

    .stat-card.c-profit:hover {
        box-shadow:
            0 15px 35px rgba(16, 185, 129, .18);
    }

    /* ========================================
   PRODUK TERJUAL
======================================== */

    .stat-card.c-amber {
        background: linear-gradient(135deg,
                #ffffff,
                #fffaf0);
    }

    .stat-card.c-amber::before {
        background: linear-gradient(90deg,
                #f59e0b,
                #fbbf24);
    }

    .c-amber .stat-icon-wrap {
        background: #fffbeb;
        color: #d97706;
    }

    /* ========================================
   PELANGGAN
======================================== */

    .stat-card.c-purple {
        background: linear-gradient(135deg,
                #ffffff,
                #faf7ff);
    }

    .stat-card.c-purple::before {
        background: linear-gradient(90deg,
                #8b5cf6,
                #a78bfa);
    }

    .c-purple .stat-icon-wrap {
        background: #f5f3ff;
        color: #7c3aed;
    }

    /* ========================================
   PROFIT NEGATIF (OPSIONAL)
======================================== */

    .stat-card.c-loss {
        background: linear-gradient(135deg,
                #ffffff,
                #fff5f5);
    }

    .stat-card.c-loss::before {
        background: linear-gradient(90deg,
                #ef4444,
                #dc2626);
    }

    .c-loss .stat-icon-wrap {
        background: #fee2e2;
        color: #dc2626;
    }

    .c-loss .stat-value {
        color: #991b1b;
    }

    .stat-card.c-loss:hover {
        box-shadow:
            0 15px 35px rgba(239, 68, 68, .18);
    }

    /* ========================================
   KOMPONEN UMUM
======================================== */

    .stat-icon-wrap {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        margin-bottom: 16px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.1;
        color: var(--navy);
        margin-bottom: 6px;
    }

    .stat-value.rupiah {
        font-size: 1.6rem;
    }

    .stat-label {
        font-size: .82rem;
        font-weight: 700;
        color: var(--slate);
        letter-spacing: .08em;
        text-transform: uppercase;
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

    /* ── Dual chart legend ────────────────────────────── */
    .chart-legend {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .82rem;
        font-weight: 600;
        color: var(--slate);
    }

    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
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

    /* ── Top Products Table ───────────────────────────── */
    .top-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 6px;
    }

    .top-table thead th {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--slate);
        padding: 0 12px 8px;
        border-bottom: 1px solid var(--border);
    }

    .top-table tbody tr {
        background: var(--white);
        transition: box-shadow .2s, transform .2s;
    }

    .top-table tbody tr:hover {
        box-shadow: var(--shadow-hover);
        transform: translateY(-2px);
    }

    .top-table tbody td {
        padding: 12px 12px;
        font-size: .87rem;
        color: var(--navy);
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
    }

    .top-table tbody td:first-child {
        border-left: 1px solid var(--border);
        border-radius: var(--radius-sm) 0 0 var(--radius-sm);
    }

    .top-table tbody td:last-child {
        border-right: 1px solid var(--border);
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        font-family: var(--font-mono);
        font-size: .82rem;
    }

    .rank-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        font-size: .75rem;
        font-weight: 800;
    }

    .rank-1 {
        background: #fef3c7;
        color: #92400e;
    }

    .rank-2 {
        background: #f1f5f9;
        color: #334155;
    }

    .rank-3 {
        background: #fef2f2;
        color: #991b1b;
    }

    .rank-n {
        background: var(--ice);
        color: var(--slate);
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

        .stat-value.rupiah {
            font-size: 1.1rem;
        }
    }
</style>

@extends('app.master')
@section('content')
    <div class="container-fluid py-4">

        {{-- ══ HERO ═══════════════════════════════════════════ --}}
        <div class="sparta-hero">
            <div class="hero-badge">
                <span class="dot"></span> Analytics · Owner View
            </div>
            <h1 class="hero-title">Richie Motor<br>Analytics Dashboard</h1>
            <p class="hero-subtitle">SPARTA · Laporan & Kinerja Bisnis</p>
            <div class="hero-divider"></div>
            <p class="hero-welcome">Halo, {{ Auth::user()->name }} 👋</p>
            <span class="hero-role">{{ strtoupper(Auth::user()->role) }}</span>
        </div>

        {{-- ══ STAT CARDS ═════════════════════════════════════ --}}
        <div class="row g-3 mb-4">

            {{-- Pendapatan --}}
            <div class="col-6 col-lg">
                <div class="stat-card c-blue">
                    <div class="stat-icon-wrap">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>

                    <div class="stat-value rupiah">
                        Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
                    </div>

                    <div class="stat-label">
                        Total Pendapatan
                    </div>
                </div>
            </div>

            {{-- Pembelian --}}
            <div class="col-6 col-lg">
                <div class="stat-card c-green">
                    <div class="stat-icon-wrap">
                        <i class="bi bi-cart-check-fill"></i>
                    </div>

                    <div class="stat-value rupiah">
                        Rp {{ number_format($totalPembelian, 0, ',', '.') }}
                    </div>

                    <div class="stat-label">
                        Total Pembelian
                    </div>
                </div>
            </div>

            {{-- PROFIT --}}
            <div class="stat-card {{ $profit >= 0 ? 'c-profit' : 'c-loss' }}">
                <div class="stat-icon-wrap">
                    <i class="bi {{ $profit >= 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }}"></i>
                </div>

                <div class="stat-value rupiah">
                    Rp {{ number_format($profit, 0, ',', '.') }}
                </div>

                <div class="stat-label">
                    Profit Bersih
                </div>
            </div>

            {{-- Produk Terjual --}}
            <div class="col-6 col-lg">
                <div class="stat-card c-amber">
                    <div class="stat-icon-wrap">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>

                    <div class="stat-value">
                        {{ $totalProdukTerjual }}
                    </div>

                    <div class="stat-label">
                        Produk Terjual
                    </div>
                </div>
            </div>

            {{-- Pelanggan --}}
            <div class="col-6 col-lg">
                <div class="stat-card c-purple">
                    <div class="stat-icon-wrap">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div class="stat-value">
                        {{ $totalCustomer }}
                    </div>

                    <div class="stat-label">
                        Pelanggan
                    </div>
                </div>
            </div>

        </div>

        {{-- ══ REVENUE LINE CHART ══════════════════════════════ --}}
        <div class="panel mb-4">
            <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
                <div>
                    <div class="section-eyebrow">Keuangan</div>
                    <div class="section-title">Tren Pendapatan & Pengeluaran</div>
                </div>
            </div>

            {{-- Filter Chips --}}
            <div class="filter-toolbar">
                <a href="{{ route('owner.dashboard', ['filter' => 'hari']) }}"
                    class="filter-chip {{ $filter == 'hari' ? 'active' : '' }}">
                    <i class="bi bi-sun me-1"></i>Hari Ini
                </a>
                <a href="{{ route('owner.dashboard', ['filter' => 'bulan']) }}"
                    class="filter-chip {{ $filter == 'bulan' ? 'active' : '' }}">
                    <i class="bi bi-calendar-month me-1"></i>Bulan Ini
                </a>
                <a href="{{ route('owner.dashboard', ['filter' => 'tahun']) }}"
                    class="filter-chip {{ $filter == 'tahun' ? 'active' : '' }}">
                    <i class="bi bi-calendar3 me-1"></i>Tahun Ini
                </a>
            </div>

            {{-- Custom Date Range --}}
            <form method="GET" action="{{ route('owner.dashboard') }}">
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

            {{-- Legend --}}
            <div class="chart-legend mt-4">
                <div class="legend-item">
                    <span class="legend-dot" style="background:#2563eb;"></span> Pendapatan
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background:#f59e0b;"></span> Pengeluaran
                </div>
            </div>

            {{-- Canvas --}}
            <div class="chart-wrap" id="chart-section">
                <canvas id="ownerChart"></canvas>
            </div>
        </div>

        {{-- ══ TOP PRODUCTS ════════════════════════════════════ --}}
        <div class="panel mb-4">
            <div class="section-eyebrow">Produk</div>
            <div class="section-title mb-3">Produk Terlaris</div>
            <table class="top-table">
                <thead>
                    <tr>
                        <th style="width:44px;">#</th>
                        <th>Nama Produk</th>
                        <th class="text-end">Terjual</th>
                        <th class="text-end">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topProduk as $i => $produk)
                        <tr>
                            <td>
                                <span
                                    class="rank-badge {{ $i === 0 ? 'rank-1' : ($i === 1 ? 'rank-2' : ($i === 2 ? 'rank-3' : 'rank-n')) }}">
                                    {{ $i + 1 }}
                                </span>
                            </td>
                            <td style="font-weight:600;">{{ $produk->nama_produk }}</td>
                            <td class="text-end">{{ number_format($produk->total_terjual, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($produk->total_pendapatan, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ══ SYSTEM SUMMARY ══════════════════════════════════ --}}
        <div class="summary-panel mb-4">
            <div class="sp-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
            <h5>Tentang Dashboard Ini</h5>
            <p class="sp-sub">SPARTA Analytics · Richie Motor · Owner View</p>
            <p>
                Dashboard ini menyajikan ringkasan <strong>kinerja keuangan</strong>,
                <strong>tren pendapatan</strong>, <strong>pengeluaran operasional</strong>,
                serta <strong>produk terlaris</strong> dalam periode yang dipilih —
                dirancang khusus untuk pemantauan strategis oleh pemilik usaha.
            </p>
        </div>

    </div>

    {{-- ══ FOOTER ══════════════════════════════════════════ --}}
    <footer class="sparta-footer">
        <strong>SPARTA</strong> — Richie Motor Analytics Dashboard<br>
        <small>© {{ date('Y') }} Richie Motor. All Rights Reserved.</small>
    </footer>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (() => {
            const labels = {!! json_encode($dataHarian->pluck('tanggal')->values()) !!};
            const pendapatan = {!! json_encode($dataHarian->pluck('pendapatan')->values()) !!};
            const pengeluaran = {!! json_encode($dataHarian->pluck('pengeluaran')->values()) !!};

            const canvas = document.getElementById('ownerChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');

            /* gradient fills */
            const gradBlue = ctx.createLinearGradient(0, 0, 0, 300);
            gradBlue.addColorStop(0, 'rgba(37,99,235,.20)');
            gradBlue.addColorStop(1, 'rgba(37,99,235,.0)');

            const gradAmber = ctx.createLinearGradient(0, 0, 0, 300);
            gradAmber.addColorStop(0, 'rgba(245,158,11,.15)');
            gradAmber.addColorStop(1, 'rgba(245,158,11,.0)');

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: pendapatan,
                            borderColor: '#2563eb',
                            backgroundColor: gradBlue,
                            fill: true,
                            tension: 0.42,
                            pointRadius: 5,
                            pointHoverRadius: 9,
                            pointBackgroundColor: '#2563eb',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2.5,
                            borderWidth: 2.5,
                            order: 1,
                        },
                        {
                            label: 'Pengeluaran (Rp)',
                            data: pengeluaran,
                            borderColor: '#f59e0b',
                            backgroundColor: gradAmber,
                            fill: true,
                            tension: 0.42,
                            pointRadius: 5,
                            pointHoverRadius: 9,
                            pointBackgroundColor: '#f59e0b',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2.5,
                            borderWidth: 2.5,
                            borderDash: [6, 3],
                            order: 2,
                        }
                    ]
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
                                label: c => ' ' + c.dataset.label.split(' ')[0] + ': Rp ' + c.raw
                                    .toLocaleString('id-ID')
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
