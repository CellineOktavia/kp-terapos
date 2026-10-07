@extends('app.master')

@section('content')
    <style>
        .dashboard-card {
            border: none;
            border-radius: 16px;
            transition: all 0.25s ease-in-out;
            background: #ffffff;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08) !important;
        }

        .icon-shape {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .table-custom th {
            background-color: #f8fafc;
            color: #64748b;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            border-top: none;
            padding: 12px 16px;
        }

        .table-custom td {
            padding: 14px 16px;
            color: #334155;
            font-size: 0.9rem;
        }

        .badge-soft-danger {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .badge-soft-warning {
            background-color: #fef3c7;
            color: #d97706;
        }

        /* Styling Filter Segmented Pills Minimalis */
        .filter-pills-group {
            background-color: #f1f5f9;
            padding: 4px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 2px;
        }

        .filter-pill-btn {
            border: none;
            background: transparent;
            color: #64748b;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 8px;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .filter-pill-btn:hover {
            color: #1e293b;
        }

        .filter-pill-btn.active {
            background-color: #ffffff;
            color: #2563eb;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.06);
        }

        .filter-pill-btn i {
            font-size: 0.85rem;
        }
    </style>

    <div class="container-fluid py-4">
        {{-- HEADER DASHBOARD --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Dashboard TERAPOS</h1>
                <p class="text-muted mb-0">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong> 👋</p>
            </div>
            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2 fw-semibold fs-6">
                {{ Auth::user()->isOwner() ? 'Owner' : 'Co-Owner' }}
            </span>
        </div>

        {{-- RINGKASAN HARI INI --}}
        <h2 class="h6 fw-bold text-uppercase text-muted tracking-wide mb-3">Hari Ini</h2>
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card dashboard-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small fw-medium mb-1">Omzet Penjualan</div>
                            <div class="h4 fw-bold text-dark mb-0">Rp {{ number_format($todaySales ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="icon-shape bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card dashboard-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small fw-medium mb-1">Transaksi Penjualan</div>
                            <div class="h4 fw-bold text-dark mb-0">{{ number_format($todaySalesCount ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="icon-shape bg-success bg-opacity-10 text-success">
                            <i class="bi bi-cart-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card dashboard-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small fw-medium mb-1">Produk Terjual</div>
                            <div class="h4 fw-bold text-dark mb-0">{{ number_format($todayItemsSold ?? 0, 0, ',', '.') }}
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
                <a href="{{ route('stok.kritis') }}" class="text-decoration-none">
                    <div class="card dashboard-card shadow-sm h-100 border-start border-4 border-danger">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-medium mb-1">Stok Kritis</div>
                                <div class="h4 fw-bold text-danger mb-0">
                                    {{ number_format($criticalStockCount ?? 0, 0, ',', '.') }} <span
                                        class="fs-6 text-muted fw-normal">produk</span>
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
        <h2 class="h6 fw-bold text-uppercase text-muted tracking-wide mb-3">Bulan Ini</h2>
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card dashboard-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-medium mb-1">Omzet Penjualan</div>
                        <div class="h3 fw-bold text-dark mb-0">Rp {{ number_format($monthSales ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card dashboard-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-medium mb-1">Total Transaksi</div>
                        <div class="h3 fw-bold text-dark mb-0">{{ number_format($monthSalesCount ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card dashboard-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small fw-medium mb-1">Total Produk Terjual</div>
                        <div class="h3 fw-bold text-dark mb-0">{{ number_format($monthItemsSold ?? 0, 0, ',', '.') }} <span
                                class="fs-6 text-muted fw-normal">item</span></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- GRAFIK OMZET PENJUALAN + FILTER --}}
        <div class="card dashboard-card shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div>
                        <h2 class="h5 fw-bold text-dark mb-1">Grafik Omzet Penjualan</h2>
                        <p class="text-muted small mb-0">Tren pendapatan berdasarkan data riil tersimpan</p>
                    </div>

                    {{-- Filter Pills Segmented --}}
                    <div class="filter-pills-group" id="chartFilterPills">
                        <button type="button"
                            class="filter-pill-btn {{ request('chart_range') == 'today' ? 'active' : '' }}"
                            data-value="today">
                            <i class="bi bi-clock-history"></i> Hari Ini
                        </button>
                        <button type="button"
                            class="filter-pill-btn {{ request('chart_range', '7') == '7' ? 'active' : '' }}"
                            data-value="7">
                            <i class="bi bi-calendar-event"></i> 7 Hari
                        </button>
                        <button type="button" class="filter-pill-btn {{ request('chart_range') == '30' ? 'active' : '' }}"
                            data-value="30">
                            <i class="bi bi-calendar-month"></i> 30 Hari
                        </button>
                        <button type="button"
                            class="filter-pill-btn {{ request('chart_range') == '365' ? 'active' : '' }}" data-value="365">
                            <i class="bi bi-calendar3"></i> 1 Tahun
                        </button>
                    </div>
                </div>

                <div style="height: 340px;">
                    <canvas id="salesChart" aria-label="Grafik omzet penjualan"></canvas>
                </div>
            </div>
        </div>

        {{-- TABEL PRODUK TERLARIS & STOK KRITIS --}}
        <div class="row g-4 mb-4">
            {{-- Produk Terlaris --}}
            <div class="col-12 col-lg-6">
                <div class="card dashboard-card shadow-sm h-100">
                    <div
                        class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-4 px-4 pb-2">
                        <h2 class="h6 fw-bold text-dark mb-0">Produk Terlaris</h2>
                        <span class="small text-muted">Berdasarkan total unit terjual</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-end">Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topProducts ?? [] as $product)
                                    <tr>
                                        <td class="fw-medium text-dark">{{ $product->nama_produk }}</td>
                                        <td class="text-end fw-semibold">
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
                <div class="card dashboard-card shadow-sm h-100">
                    <div
                        class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-4 px-4 pb-2">
                        <h2 class="h6 fw-bold text-dark mb-0">Stok Perlu Perhatian</h2>
                        <a href="{{ route('stok.kritis') }}"
                            class="btn btn-sm btn-link text-primary text-decoration-none fw-semibold">Lihat Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Stok</th>
                                    <th>Min</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($criticalProducts ?? [] as $product)
                                    <tr>
                                        <td class="fw-medium text-dark">{{ $product->nama_produk }}</td>
                                        <td>{{ $product->stok }} <span
                                                class="text-muted small">{{ $product->satuan ?? '' }}</span></td>
                                        <td>{{ $product->stok_minimum }} <span
                                                class="text-muted small">{{ $product->satuan ?? '' }}</span></td>
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

        {{-- Penjualan Terbaru --}}
        {{-- Penjualan Terbaru --}}
        <div class="card dashboard-card shadow-sm">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2">
                <h2 class="h6 fw-bold text-dark mb-0">
                    Penjualan Terbaru
                </h2>
            </div>

            <div class="table-responsive">

                <table class="table table-custom align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Nomor Penjualan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Kasir / User</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($latestSales ?? [] as $sale)
                            <tr>

                                <td>
                                    <a href="{{ route('penjualan.show', $sale->id) }}"
                                        class="fw-semibold text-primary text-decoration-none">
                                        {{ $sale->nomor_penjualan }}
                                    </a>
                                </td>

                                <td>
                                    {{ $sale->tanggal?->format('d/m/Y') ?? '-' }}
                                </td>

                                <td class="fw-bold text-dark">
                                    Rp {{ number_format($sale->total ?? 0, 0, ',', '.') }}
                                </td>

                                <td>
                                    {{ $sale->user?->name ?? '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Belum ada transaksi penjualan terbaru.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                /*
                |--------------------------------------------------------------------------
                | ELEMENT
                |--------------------------------------------------------------------------
                */

                const canvas = document.getElementById('salesChart');

                const filterButtons = document.querySelectorAll(
                    '#chartFilterPills .filter-pill-btn'
                );


                if (!canvas) {
                    console.error('Canvas salesChart tidak ditemukan.');
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | DATA AWAL DARI LARAVEL
                |--------------------------------------------------------------------------
                */

                const initialLabels = @json(($salesChartLabels ?? collect())->values()->all());

                const initialValues = @json(($salesChartValues ?? collect())->values()->all());


                /*
                |--------------------------------------------------------------------------
                | CANVAS
                |--------------------------------------------------------------------------
                */

                const ctx = canvas.getContext('2d');


                /*
                |--------------------------------------------------------------------------
                | GRADIENT
                |--------------------------------------------------------------------------
                */

                const gradient = ctx.createLinearGradient(
                    0,
                    0,
                    0,
                    340
                );

                gradient.addColorStop(
                    0,
                    'rgba(37, 99, 235, 0.25)'
                );

                gradient.addColorStop(
                    1,
                    'rgba(37, 99, 235, 0)'
                );


                /*
                |--------------------------------------------------------------------------
                | CHART
                |--------------------------------------------------------------------------
                */

                const salesChart = new Chart(ctx, {

                    type: 'line',

                    data: {

                        labels: initialLabels,

                        datasets: [

                            {
                                label: 'Omzet Penjualan',

                                data: initialValues,

                                borderColor: '#2563eb',

                                borderWidth: 3,

                                backgroundColor: gradient,

                                fill: true,

                                tension: 0.35,

                                pointBackgroundColor: '#2563eb',

                                pointBorderColor: '#ffffff',

                                pointBorderWidth: 2,

                                pointRadius: 4,

                                pointHoverRadius: 6
                            }

                        ]
                    },


                    options: {

                        responsive: true,

                        maintainAspectRatio: false,


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
                                    color: '#94a3b8',

                                    font: {
                                        size: 11
                                    }
                                }
                            },


                            y: {

                                beginAtZero: true,

                                grid: {
                                    color: '#f1f5f9'
                                },

                                ticks: {

                                    color: '#94a3b8',

                                    font: {
                                        size: 11
                                    },

                                    callback: function(value) {

                                        if (value >= 1000000) {

                                            return 'Rp ' +
                                                (
                                                    value / 1000000
                                                ).toFixed(1) +
                                                ' Jt';
                                        }


                                        if (value >= 1000) {

                                            return 'Rp ' +
                                                (
                                                    value / 1000
                                                ).toFixed(0) +
                                                ' rb';
                                        }


                                        return 'Rp ' +
                                            Number(value).toLocaleString('id-ID');
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

                                        return 'Omzet: Rp ' +
                                            Number(context.raw || 0)
                                            .toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });


                /*
                |--------------------------------------------------------------------------
                | LOAD DATA GRAFIK
                |--------------------------------------------------------------------------
                */

                async function loadSalesChart(range) {

                    const url = new URL(
                        window.location.href
                    );

                    url.searchParams.set(
                        'chart_range',
                        range
                    );

                    // Hindari cache browser
                    url.searchParams.set(
                        '_',
                        Date.now()
                    );


                    const response = await fetch(
                        url.toString(), {
                            method: 'GET',

                            cache: 'no-store',

                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',

                                'Accept': 'application/json'
                            }
                        }
                    );


                    if (!response.ok) {

                        throw new Error(
                            `HTTP Error ${response.status}`
                        );
                    }


                    const data = await response.json();


                    console.log(
                        'Data grafik:',
                        data
                    );


                    if (
                        data.success !== true ||
                        !Array.isArray(data.labels) ||
                        !Array.isArray(data.values)
                    ) {

                        throw new Error(
                            'Format response grafik tidak valid.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE CHART
                    |--------------------------------------------------------------------------
                    */

                    salesChart.data.labels = data.labels;

                    salesChart.data.datasets[0].data =
                        data.values;


                    salesChart.update();


                    return data;
                }


                /*
                |--------------------------------------------------------------------------
                | FILTER BUTTON
                |--------------------------------------------------------------------------
                */

                filterButtons.forEach(function(button) {

                    button.addEventListener(
                        'click',
                        async function() {

                            const selectedRange =
                                this.dataset.value;


                            const previousButton =
                                document.querySelector(
                                    '#chartFilterPills .filter-pill-btn.active'
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | ACTIVE BUTTON
                            |--------------------------------------------------------------------------
                            */

                            filterButtons.forEach(
                                function(btn) {

                                    btn.classList.remove(
                                        'active'
                                    );

                                }
                            );


                            this.classList.add(
                                'active'
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | DISABLE BUTTON
                            |--------------------------------------------------------------------------
                            */

                            filterButtons.forEach(
                                function(btn) {

                                    btn.disabled = true;

                                }
                            );


                            try {

                                await loadSalesChart(
                                    selectedRange
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | UPDATE URL
                                |--------------------------------------------------------------------------
                                */

                                const url = new URL(
                                    window.location.href
                                );

                                url.searchParams.set(
                                    'chart_range',
                                    selectedRange
                                );


                                window.history.replaceState({},
                                    '',
                                    url.toString()
                                );


                            } catch (error) {

                                console.error(
                                    'Gagal memperbarui grafik:',
                                    error
                                );


                                /*
                                |--------------------------------------------------------------------------
                                | KEMBALIKAN BUTTON SEBELUMNYA
                                |--------------------------------------------------------------------------
                                */

                                filterButtons.forEach(
                                    function(btn) {

                                        btn.classList.remove(
                                            'active'
                                        );

                                    }
                                );


                                if (previousButton) {

                                    previousButton.classList.add(
                                        'active'
                                    );
                                }


                            } finally {

                                filterButtons.forEach(
                                    function(btn) {

                                        btn.disabled = false;

                                    }
                                );
                            }
                        }
                    );
                });


                /*
                |--------------------------------------------------------------------------
                | AUTO REFRESH
                |--------------------------------------------------------------------------
                |
                | Update grafik setiap 10 detik.
                |
                */

                setInterval(
                    async function() {

                            const activeButton =
                                document.querySelector(
                                    '#chartFilterPills .filter-pill-btn.active'
                                );


                            const currentRange =
                                activeButton?.dataset.value || '7';


                            try {

                                await loadSalesChart(
                                    currentRange
                                );

                                console.log(
                                    'Grafik otomatis diperbarui:',
                                    currentRange
                                );

                            } catch (error) {

                                console.error(
                                    'Auto refresh grafik gagal:',
                                    error
                                );
                            }

                        },
                        10000
                );

            });
        </script>
    @endpush
