@extends('app.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Dashboard TERAPOS</h1>
                <p class="text-muted mb-0">Selamat datang, {{ Auth::user()->name }}</p>
            </div>
            <span class="badge rounded-pill text-bg-primary px-3 py-2">
                {{ Auth::user()->isOwner() ? 'Owner' : 'Co-Owner' }}
            </span>
        </div>

        <h2 class="h5 fw-bold mb-3">Hari Ini</h2>
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="text-muted small mb-2">Omzet Penjualan</div>
                        <div class="h4 fw-bold mb-0">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="text-muted small mb-2">Transaksi Penjualan</div>
                        <div class="h4 fw-bold mb-0">{{ number_format($todaySalesCount, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="text-muted small mb-2">Produk Terjual</div>
                        <div class="h4 fw-bold mb-0">{{ number_format($todayItemsSold, 0, ',', '.') }} item</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="text-muted small mb-2">Total Pembelian</div>
                        <div class="h4 fw-bold mb-0">Rp {{ number_format($todayPurchases, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-2">
                <a href="{{ route('stok.kritis') }}" class="card shadow-sm h-100 border-0 text-decoration-none">
                    <div class="card-body">
                        <div class="text-muted small mb-2">Stok Kritis</div>
                        <div class="h4 fw-bold text-danger mb-0">{{ number_format($criticalStockCount, 0, ',', '.') }}</div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="text-muted small mb-2">Transaksi Pembelian</div>
                        <div class="h4 fw-bold mb-0">{{ number_format($todayPurchasesCount, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="h5 fw-bold mb-3">Bulan Ini</h2>
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="text-muted small mb-2">Omzet Penjualan</div>
                        <div class="h4 fw-bold mb-0">Rp {{ number_format($monthSales, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="text-muted small mb-2">Transaksi Penjualan</div>
                        <div class="h4 fw-bold mb-0">{{ number_format($monthSalesCount, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="text-muted small mb-2">Produk Terjual</div>
                        <div class="h4 fw-bold mb-0">{{ number_format($monthItemsSold, 0, ',', '.') }} item</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="text-muted small mb-2">Total Pembelian</div>
                        <div class="h4 fw-bold mb-0">Rp {{ number_format($monthPurchases, 0, ',', '.') }}</div>
                        <div class="small text-muted mt-1">{{ number_format($monthPurchasesCount, 0, ',', '.') }} transaksi</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h2 class="h5 fw-bold mb-3">Penjualan 7 Hari Terakhir</h2>
                <div style="height: 320px">
                    <canvas id="salesChart" aria-label="Grafik omzet penjualan 7 hari terakhir"></canvas>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h2 class="h5 fw-bold mb-0">Produk Terlaris</h2>
                        <span class="small text-muted">Berdasarkan jumlah terjual</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-end">Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topProducts as $product)
                                    <tr>
                                        <td>{{ $product->nama_produk }}</td>
                                        <td class="text-end">
                                            {{ number_format($product->qty_terjual, 0, ',', '.') }}
                                            {{ $product->satuan }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">
                                            Tidak ada data penjualan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h2 class="h5 fw-bold mb-0">Stok Perlu Perhatian</h2>
                        <a href="{{ route('stok.kritis') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Stok</th>
                                    <th>Minimum</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($criticalProducts as $product)
                                    <tr>
                                        <td>{{ $product->nama_produk }}</td>
                                        <td>{{ $product->stok }} {{ $product->satuan }}</td>
                                        <td>{{ $product->stok_minimum }} {{ $product->satuan }}</td>
                                        <td>
                                            @if ((int) $product->stok === 0)
                                                <span class="badge text-bg-danger">HABIS</span>
                                            @else
                                                <span class="badge text-bg-warning">MENIPIS</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Tidak ada produk dengan stok kritis.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3">
                        <h2 class="h5 fw-bold mb-0">Penjualan Terbaru</h2>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Kasir/User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($latestSales as $sale)
                                    <tr>
                                        <td>
                                            <a href="{{ route('penjualan.show', $sale->id) }}">
                                                {{ $sale->nomor_penjualan }}
                                            </a>
                                        </td>
                                        <td>{{ $sale->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                        <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                                        <td>{{ $sale->user?->name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Belum ada transaksi penjualan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3">
                        <h2 class="h5 fw-bold mb-0">Pembelian Terbaru</h2>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nomor Pembelian</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($latestPurchases as $purchase)
                                    <tr>
                                        <td>
                                            <a href="{{ route('faktur.show', $purchase->id) }}">
                                                {{ $purchase->nomor_faktur }}
                                            </a>
                                        </td>
                                        <td>{{ $purchase->tanggal ?? '-' }}</td>
                                        <td>Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                                        <td>{{ $purchase->user?->name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Belum ada transaksi pembelian.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const salesChart = document.getElementById('salesChart');

        if (salesChart) {
            new Chart(salesChart, {
                type: 'line',
                data: {
                    labels: @json($salesChartLabels->all()),
                    datasets: [{
                        label: 'Omzet Penjualan',
                        data: @json($salesChartValues->all()),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.12)',
                        fill: true,
                        tension: 0.3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => 'Rp ' + Number(value).toLocaleString('id-ID')
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: context => 'Rp ' + Number(context.raw).toLocaleString('id-ID')
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush
