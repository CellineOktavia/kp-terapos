@extends('app.master')

@section('content')
    <style>
        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }

        .dash-header-box {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 20px 24px;
        }

        .dashboard-card {
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            background: #ffffff;
            box-shadow: 0 2px 12px rgba(15, 23, 42, 0.025) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06) !important;
        }

        .metric-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 4px;
        }

        .metric-value {
            font-size: clamp(1.2rem, 2vw, 1.5rem);
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
            line-height: 1.25;
        }

        .icon-shape {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .live-indicator {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 999px;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #16a34a;
            animation: livePulse 2s infinite;
        }

        @keyframes livePulse {
            0% {
                box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.55);
            }

            70% {
                box-shadow: 0 0 0 7px rgba(22, 163, 74, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(22, 163, 74, 0);
            }
        }

        .section-caption {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
        }

        .chart-container-responsive {
            position: relative;
            height: 320px;
            width: 100%;
        }

        @media (max-width: 575.98px) {
            .dash-header-box {
                padding: 16px;
            }

            .chart-container-responsive {
                height: 240px;
            }

            .icon-shape {
                width: 38px;
                height: 38px;
                font-size: 1.05rem;
            }
        }

        .table-custom th {
            background-color: #f8fafc;
            color: #64748b;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            border-top: none;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 16px;
            white-space: nowrap;
        }

        .table-custom td {
            padding: 13px 16px;
            color: #334155;
            font-size: 0.875rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .badge-soft-danger {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .badge-soft-warning {
            background-color: #fef3c7;
            color: #d97706;
        }
    </style>

    <div class="container-fluid px-0 py-2">
        {{-- HEADER DASHBOARD --}}
        <div
            class="dash-header-box d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <h1 class="h4 fw-bold text-dark mb-0" style="letter-spacing: -0.02em;">Dashboard</h1>
                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-1 fw-semibold"
                        style="font-size: 0.75rem;">
                        {{ auth()->user()->display_role }}
                    </span>
                </div>
                <p class="text-muted small mb-0">
                    Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong> — Ringkasan penjualan & inventori
                    <strong>TERAPOS</strong>.
                </p>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="live-indicator" id="liveStatusBadge" title="Data diperbarui otomatis setiap 10 detik">
                    <span class="live-dot"></span>
                    <span>Live • <span id="lastUpdatedText">{{ now()->format('H:i:s') }}</span></span>
                </span>
                <button type="button" id="btnRefreshDashboard"
                    class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold" title="Perbarui Sekarang">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>

        {{-- RINGKASAN HARI INI --}}
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h2 class="section-caption mb-0">Ringkasan Hari Ini</h2>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card dashboard-card h-100">
                    <div class="card-body p-3 p-md-4 d-flex align-items-center justify-content-between gap-2">
                        <div class="min-w-0">
                            <div class="metric-label">Omzet Hari Ini</div>
                            <div class="metric-value tabular-nums text-truncate" id="metricTodaySales">
                                Rp {{ number_format($todaySales ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="icon-shape bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card dashboard-card h-100">
                    <div class="card-body p-3 p-md-4 d-flex align-items-center justify-content-between gap-2">
                        <div>
                            <div class="metric-label">Transaksi Hari Ini</div>
                            <div class="metric-value tabular-nums" id="metricTodaySalesCount">
                                {{ number_format($todaySalesCount ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="icon-shape bg-success bg-opacity-10 text-success">
                            <i class="bi bi-cart-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card dashboard-card h-100">
                    <div class="card-body p-3 p-md-4 d-flex align-items-center justify-content-between gap-2">
                        <div>
                            <div class="metric-label">Barang Terjual</div>
                            <div class="metric-value tabular-nums">
                                <span
                                    id="metricTodayItemsSold">{{ number_format($todayItemsSold ?? 0, 0, ',', '.') }}</span>
                                <span class="fs-6 text-muted fw-normal">item</span>
                            </div>
                        </div>
                        <div class="icon-shape bg-info bg-opacity-10 text-info">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <a href="{{ route('laporan.stok-kritis') }}" class="text-decoration-none">
                    <div class="card dashboard-card h-100 border-start border-4 border-danger">
                        <div class="card-body p-3 p-md-4 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <div class="metric-label text-danger">Stok Kritis</div>
                                <div class="metric-value text-danger tabular-nums">
                                    <span
                                        id="metricCriticalStockCount">{{ number_format($criticalStockCount ?? 0, 0, ',', '.') }}</span>
                                    <span class="fs-6 text-muted fw-normal">barang</span>
                                </div>
                            </div>
                            <div class="icon-shape bg-danger bg-opacity-10 text-danger">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- RINGKASAN BULAN INI --}}
        <div class="mb-2">
            <h2 class="section-caption mb-0">Performa Bulan Ini</h2>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
                <div class="card dashboard-card h-100">
                    <div class="card-body p-3 p-md-4 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="metric-label">Omzet Bulan Ini</div>
                            <div class="metric-value tabular-nums" id="metricMonthSales">
                                Rp {{ number_format($monthSales ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="icon-shape bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card dashboard-card h-100">
                    <div class="card-body p-3 p-md-4 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="metric-label">Transaksi Bulan Ini</div>
                            <div class="metric-value tabular-nums" id="metricMonthSalesCount">
                                {{ number_format($monthSalesCount ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="icon-shape bg-secondary bg-opacity-10 text-secondary">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- GRAFIK PENJUALAN TUJUH HARI (REAL-TIME) --}}
        <div class="card dashboard-card mb-4">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h2 class="h6 fw-bold text-dark mb-1">Grafik Penjualan 7 Hari Terakhir</h2>
                        <p class="text-muted small mb-0">Pergerakan omzet harian yang diperbarui secara otomatis</p>
                    </div>
                    <span class="badge bg-light text-secondary border fw-normal px-2 py-1" style="font-size: 0.72rem;">
                        Auto-sync 10 dtk
                    </span>
                </div>

                <div class="chart-container-responsive">
                    <canvas id="salesChart" aria-label="Grafik omzet penjualan"></canvas>
                </div>
            </div>
        </div>

        {{-- TABEL BARANG TERLARIS & STOK KRITIS --}}
        <div class="row g-3 g-md-4 mb-4">
            {{-- Barang Terlaris --}}
            <div class="col-12 col-lg-6">
                <div class="card dashboard-card h-100 overflow-hidden">
                    <div
                        class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-3 pt-md-4 px-3 px-md-4 pb-2">
                        <h2 class="h6 fw-bold text-dark mb-0">Barang Terlaris</h2>
                        <span class="small text-muted">Total unit terjual</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th class="text-end">Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topProducts ?? [] as $product)
                                    <tr>
                                        <td class="fw-medium text-dark">{{ $product->nama_produk }}</td>
                                        <td class="text-end fw-semibold tabular-nums text-nowrap">
                                            {{ number_format($product->qty_terjual ?? 0, 0, ',', '.') }}
                                            <span class="text-muted small fw-normal">{{ $product->satuan ?? '' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">Belum ada data penjualan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Stok Perlu Perhatian --}}
            <div class="col-12 col-lg-6">
                <div class="card dashboard-card h-100 overflow-hidden">
                    <div
                        class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-3 pt-md-4 px-3 px-md-4 pb-2">
                        <h2 class="h6 fw-bold text-dark mb-0">Stok Perlu Perhatian</h2>
                        <a href="{{ route('laporan.stok-kritis') }}"
                            class="btn btn-sm btn-link text-primary text-decoration-none fw-semibold p-0">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Stok</th>
                                    <th>Min</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($criticalProducts ?? [] as $product)
                                    <tr>
                                        <td class="fw-medium text-dark">{{ $product->nama_produk }}</td>
                                        <td class="tabular-nums text-nowrap">
                                            {{ $product->stok }} <span
                                                class="text-muted small">{{ $product->satuan ?? '' }}</span>
                                        </td>
                                        <td class="tabular-nums text-nowrap">
                                            {{ $product->stok_minimum }} <span
                                                class="text-muted small">{{ $product->satuan ?? '' }}</span>
                                        </td>
                                        <td>
                                            @if ((int) $product->stok === 0)
                                                <span class="badge badge-soft-danger rounded-pill px-2 py-1">Habis</span>
                                            @else
                                                <span
                                                    class="badge badge-soft-warning rounded-pill px-2 py-1">Menipis</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Stok dalam kondisi aman.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- PENJUALAN TERBARU --}}
        <div class="card dashboard-card overflow-hidden">
            <div
                class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-3 pt-md-4 px-3 px-md-4 pb-2">
                <h2 class="h6 fw-bold text-dark mb-0">Penjualan Terbaru</h2>
                <a href="{{ route('penjualan.index') }}"
                    class="btn btn-sm btn-link text-primary text-decoration-none fw-semibold p-0">
                    Semua Transaksi
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nomor Penjualan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Operator</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestSales ?? [] as $sale)
                            <tr>
                                <td class="text-nowrap">
                                    <a href="{{ route('penjualan.show', $sale->id) }}"
                                        class="fw-semibold text-primary text-decoration-none">
                                        {{ $sale->nomor_penjualan }}
                                    </a>
                                </td>
                                <td class="text-nowrap">{{ $sale->tanggal?->format('d/m/Y') ?? '-' }}</td>
                                <td class="fw-bold text-dark tabular-nums text-nowrap">
                                    Rp {{ number_format($sale->total ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="text-nowrap">{{ $sale->user?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada transaksi penjualan
                                    terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('salesChart');
            if (!canvas) return;

            const initialLabels = @json(($salesChartLabels ?? collect())->values()->all());
            const initialValues = @json(($salesChartValues ?? collect())->values()->all());

            const ctx = canvas.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 320);
            gradient.addColorStop(0, 'rgba(37, 99, 235, 0.18)');
            gradient.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: initialLabels,
                    datasets: [{
                        label: 'Omzet Penjualan',
                        data: initialValues,
                        borderColor: '#2563eb',
                        borderWidth: 2.5,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#2563eb',
                        pointBorderWidth: 2.5,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 450
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 11
                                },
                                maxRotation: 0,
                                autoSkip: true
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 11
                                },
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
                                    }
                                    if (value >= 1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                                    }
                                    return 'Rp ' + Number(value).toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return 'Omzet: Rp ' + Number(context.raw || 0).toLocaleString(
                                        'id-ID');
                                }
                            }
                        }
                    }
                }
            });

            // Format angka Rupiah & ribuan
            const formatNumber = (num) => Number(num || 0).toLocaleString('id-ID');
            const formatRupiah = (num) => 'Rp ' + formatNumber(num);

            const realtimeUrl = @json(Route::has('dashboard.realtime') ? route('dashboard.realtime') : null);
            const refreshBtn = document.getElementById('btnRefreshDashboard');
            const lastUpdatedEl = document.getElementById('lastUpdatedText');

            async function syncRealtimeDashboard() {
                if (!realtimeUrl || document.hidden) return;

                try {
                    if (refreshBtn) refreshBtn.classList.add('disabled');

                    const response = await fetch(realtimeUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) return;
                    const data = await response.json();

                    // Update kartu metrik secara halus
                    const elTodaySales = document.getElementById('metricTodaySales');
                    const elTodayCount = document.getElementById('metricTodaySalesCount');
                    const elTodayItems = document.getElementById('metricTodayItemsSold');
                    const elCritical = document.getElementById('metricCriticalStockCount');
                    const elMonthSales = document.getElementById('metricMonthSales');
                    const elMonthCount = document.getElementById('metricMonthSalesCount');

                    if (elTodaySales) elTodaySales.textContent = formatRupiah(data.todaySales);
                    if (elTodayCount) elTodayCount.textContent = formatNumber(data.todaySalesCount);
                    if (elTodayItems) elTodayItems.textContent = formatNumber(data.todayItemsSold);
                    if (elCritical) elCritical.textContent = formatNumber(data.criticalStockCount);
                    if (elMonthSales) elMonthSales.textContent = formatRupiah(data.monthSales);
                    if (elMonthCount) elMonthCount.textContent = formatNumber(data.monthSalesCount);

                    // Update grafik hanya jika label/nilai berubah
                    if (Array.isArray(data.labels) && Array.isArray(data.values)) {
                        const oldDataStr = JSON.stringify(salesChart.data.datasets[0].data);
                        const newDataStr = JSON.stringify(data.values);
                        const oldLabelStr = JSON.stringify(salesChart.data.labels);
                        const newLabelStr = JSON.stringify(data.labels);

                        if (oldDataStr !== newDataStr || oldLabelStr !== newLabelStr) {
                            salesChart.data.labels = data.labels;
                            salesChart.data.datasets[0].data = data.values;
                            salesChart.update();
                        }
                    }

                    if (lastUpdatedEl && data.updatedAt) {
                        lastUpdatedEl.textContent = data.updatedAt;
                    }
                } catch (err) {
                    console.warn('Gagal menyinkronkan data dashboard:', err);
                } finally {
                    if (refreshBtn) refreshBtn.classList.remove('disabled');
                }
            }

            if (refreshBtn) {
                refreshBtn.addEventListener('click', syncRealtimeDashboard);
            }

            // Polling otomatis setiap 10 detik
            setInterval(syncRealtimeDashboard, 10000);
        });
    </script>
@endpush
