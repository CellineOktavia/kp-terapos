@extends('app.master')

@section('content')
<style>
.dashboard-shell{display:flex;flex-direction:column;gap:18px}.dashboard-header,.activity-card,.chart-card,.kpi-card{background:#fff;border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 4px 16px rgba(15,23,42,.055)}
.dashboard-header{padding:20px 22px}.kpi-card{padding:18px;transition:.18s ease}.kpi-card:hover,.activity-card:hover,.chart-card:hover{transform:translateY(-2px);box-shadow:0 10px 24px rgba(15,23,42,.09)}
.kpi-icon{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.25rem}.kpi-label{font-size:.78rem;font-weight:700;color:#64748b}.kpi-value{font-size:1.55rem;font-weight:800;color:#0f172a}.chart-card{padding:18px}.chart-wrap{position:relative;height:300px}.activity-card{overflow:hidden}.activity-header{min-height:62px;padding:14px 18px;border-bottom:1px solid #eef2f7}.activity-row{display:flex;align-items:center;gap:12px;padding:12px 18px;border-bottom:1px solid #f1f5f9;text-decoration:none;color:inherit}.activity-row:last-child{border-bottom:0}.activity-row:hover{background:#f8fafc}.activity-icon{width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}.critical-row{border-left:3px solid transparent}.critical-row:hover{border-left-color:#ef4444}.progress-thin{height:7px;border-radius:999px}
@media(max-width:991px){.chart-wrap{height:250px}}
</style>
<div class="container-fluid px-0 dashboard-shell">
    <div class="dashboard-header d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h1 class="h4 fw-bold mb-1">Dashboard</h1>
            <p class="text-muted mb-1">Ringkasan aktivitas dan perkembangan Toko Terang Abadi.</p>
            <small class="text-muted">{{ $periodLabel ?? now()->locale('id')->translatedFormat('d M Y') }} · {{ auth()->user()->name }} · {{ auth()->user()->display_role }}</small>
        </div>
        <form method="GET" action="{{ auth()->user()->isOwner() ? route('owner.dashboard') : route('dashboard') }}" class="d-flex flex-wrap gap-2">
            @foreach(['today'=>'Hari Ini','week'=>'Minggu Ini','month'=>'Bulan Ini','year'=>'Tahun Ini'] as $key=>$label)
                <button class="btn btn-sm {{ ($periodKey ?? 'month') === $key ? 'btn-primary' : 'btn-outline-secondary' }}" name="period" value="{{ $key }}">{{ $label }}</button>
            @endforeach
        </form>
    </div>

    <div class="row g-3">
        <div class="col-12 col-sm-6 col-xl-3"><div class="kpi-card d-flex justify-content-between align-items-center"><div><div class="kpi-label">Omzet Penjualan</div><div class="kpi-value" id="metricSales">Rp {{ number_format($todaySales ?? 0,0,',','.') }}</div><small class="text-muted">Periode aktif</small></div><div class="kpi-icon bg-success bg-opacity-10 text-success"><i class="bi bi-cash-stack"></i></div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="kpi-card d-flex justify-content-between align-items-center"><div><div class="kpi-label">Jumlah Transaksi</div><div class="kpi-value" id="metricTransactions">{{ number_format($monthSalesCount ?? 0,0,',','.') }}</div><small class="text-muted">Periode aktif</small></div><div class="kpi-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-receipt"></i></div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="kpi-card d-flex justify-content-between align-items-center"><div><div class="kpi-label">Jumlah Barang Aktif</div><div class="kpi-value">{{ number_format($activeProductCount ?? 0,0,',','.') }}</div><small class="text-muted">Produk operasional</small></div><div class="kpi-icon" style="background:#ede9fe;color:#7c3aed"><i class="bi bi-box-seam"></i></div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><a href="{{ route('stok.kritis') }}" class="text-decoration-none"><div class="kpi-card d-flex justify-content-between align-items-center"><div><div class="kpi-label text-danger">Stok Kritis</div><div class="kpi-value text-danger">{{ number_format($criticalStockCount ?? 0,0,',','.') }}</div><small class="text-muted">Habis + menipis</small></div><div class="kpi-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-exclamation-triangle"></i></div></div></a></div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8"><div class="chart-card h-100"><div class="d-flex justify-content-between"><div><h2 class="h6 fw-bold mb-1">Tren Penjualan</h2><small class="text-muted">Omzet setelah diskon dan retur</small></div></div><div class="chart-wrap mt-3"><canvas id="salesChart"></canvas></div></div></div>
        <div class="col-lg-4"><div class="chart-card h-100"><h2 class="h6 fw-bold mb-1">Kondisi Persediaan</h2><div class="chart-wrap mt-2" style="height:220px"><canvas id="inventoryChart"></canvas></div><div class="small mt-2 d-grid gap-1"><div class="d-flex justify-content-between"><span><i class="bi bi-circle-fill text-success me-1"></i>Aman</span><strong>{{ $safeStockCount ?? 0 }}</strong></div><div class="d-flex justify-content-between"><span><i class="bi bi-circle-fill text-warning me-1"></i>Menipis</span><strong>{{ $lowStockCount ?? 0 }}</strong></div><div class="d-flex justify-content-between"><span><i class="bi bi-circle-fill text-danger me-1"></i>Habis</span><strong>{{ $outStockCount ?? 0 }}</strong></div></div></div></div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6"><div class="chart-card h-100"><h2 class="h6 fw-bold mb-1">Barang Terlaris</h2><small class="text-muted">5 produk aktif teratas pada periode aktif</small><div class="chart-wrap mt-3" style="height:260px"><canvas id="topProductsChart"></canvas></div></div></div>
        <div class="col-lg-6"><div class="activity-card h-100"><div class="activity-header d-flex justify-content-between align-items-center" style="background:#fff4f4"><div class="d-flex align-items-center gap-2"><i class="bi bi-exclamation-triangle-fill text-danger"></i><div><h2 class="h6 fw-bold mb-0">Stok Perlu Perhatian</h2><small class="text-muted">Segera periksa kebutuhan restok</small></div></div><a href="{{ route('stok.kritis') }}" class="small fw-bold text-decoration-none">Lihat Semua</a></div>
        @forelse($criticalProducts as $item)
            @php($percentage=$item->stok_minimum>0?min(100,$item->stok/$item->stok_minimum*100):($item->stok>0?100:0))
            <a href="{{ route('produk.show',$item->id) }}" class="activity-row critical-row"><div class="activity-icon {{ $item->stok==0?'bg-danger bg-opacity-10 text-danger':'bg-warning bg-opacity-25 text-warning' }}"><i class="bi bi-box-seam"></i></div><div class="flex-grow-1 min-w-0"><div class="fw-bold text-truncate">{{ $item->nama_produk }}</div><small class="text-muted">{{ $item->kode_produk }} · Stok {{ $item->stok }}/{{ $item->stok_minimum }} {{ $item->satuan }}</small><div class="progress progress-thin mt-2"><div class="progress-bar {{ $item->stok==0?'bg-danger':'bg-warning' }}" style="width:{{ $percentage }}%"></div></div></div><span class="badge {{ $item->stok==0?'bg-danger':'bg-warning text-dark' }}">{{ $item->stok==0?'Habis':'Menipis' }}</span></a>
        @empty
            <div class="p-4 text-center text-success"><i class="bi bi-check-circle fs-4"></i><div class="mt-2 fw-semibold">Seluruh stok barang dalam kondisi aman.</div></div>
        @endforelse
        </div></div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6"><div class="activity-card h-100"><div class="activity-header d-flex justify-content-between align-items-center" style="background:#f0fdf4"><div class="d-flex align-items-center gap-2"><i class="bi bi-receipt text-success"></i><h2 class="h6 fw-bold mb-0">Penjualan Terbaru</h2></div><a href="{{ route('penjualan.index') }}" class="small fw-bold text-decoration-none">Semua Transaksi</a></div>
        @forelse($latestSales as $sale)<a href="{{ route('penjualan.show',$sale->id) }}" class="activity-row"><div class="activity-icon bg-success bg-opacity-10 text-success"><i class="bi bi-cart-check"></i></div><div class="flex-grow-1"><div class="fw-bold">{{ $sale->nomor_penjualan }}</div><small class="text-muted">{{ $sale->tanggal ? \Carbon\Carbon::parse($sale->tanggal)->locale('id')->translatedFormat('d M Y') : '-' }} · {{ $sale->user->name ?? '-' }}</small></div><div class="text-end"><div class="fw-bold text-success">Rp {{ number_format(max(0,$sale->total-($sale->returned_amount??0)),0,',','.') }}</div><small>{{ $sale->status==='cancelled'?'Batal':($sale->returned_amount>0?'Diretur':'Selesai') }}</small></div><i class="bi bi-chevron-right text-muted"></i></a>@empty<div class="p-4 text-center text-muted">Belum ada penjualan.</div>@endforelse
        </div></div>
        <div class="col-lg-6"><div class="activity-card h-100"><div class="activity-header d-flex justify-content-between align-items-center" style="background:#eff6ff"><div class="d-flex align-items-center gap-2"><i class="bi bi-box-seam text-primary"></i><h2 class="h6 fw-bold mb-0">Penerimaan Terbaru</h2></div><a href="{{ route('penerimaan.index') }}" class="small fw-bold text-decoration-none">Semua Penerimaan</a></div>
        @forelse($latestReceipts as $receipt)<a href="{{ route('penerimaan.show',$receipt->id) }}" class="activity-row"><div class="activity-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-box-arrow-in-down"></i></div><div class="flex-grow-1"><div class="fw-bold">{{ $receipt->nomor_penerimaan }}</div><small class="text-muted">{{ $receipt->tanggal ? \Carbon\Carbon::parse($receipt->tanggal)->locale('id')->translatedFormat('d M Y') : '-' }} · {{ $receipt->user->name ?? '-' }}</small></div><div class="text-end"><div class="fw-bold text-primary">{{ $receipt->detail_penerimaan_barangs_count ?? 0 }} jenis barang</div><small>{{ number_format($receipt->detail_penerimaan_barangs_sum_qty ?? 0,0,',','.') }} qty · Stok Masuk</small></div><i class="bi bi-chevron-right text-muted"></i></a>@empty<div class="p-4 text-center text-muted">Belum ada penerimaan.</div>@endforelse
        </div></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded',function(){
 const rupiah=v=>'Rp '+Number(v||0).toLocaleString('id-ID');
 const salesLabels=@json($salesChartLabels??[]);
 const salesValues=@json($salesChartValues??[]);
 const inventoryValues=[{{ $safeStockCount??0 }},{{ $lowStockCount??0 }},{{ $outStockCount??0 }}];
 const activeCount={{ $activeProductCount??0 }};
 const topLabels=@json(($topProducts??collect())->pluck('nama_produk')->values()->all());
 const topValues=@json(($topProducts??collect())->pluck('qty_terjual')->map(fn($v)=>(float)$v)->values()->all());
 if(window.salesChartInstance)window.salesChartInstance.destroy();
 window.salesChartInstance=new Chart(document.getElementById('salesChart'),{type:'line',data:{labels:salesLabels,datasets:[{data:salesValues,borderColor:'#2563eb',backgroundColor:'rgba(37,99,235,.12)',fill:true,tension:.35,pointRadius:3}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>rupiah(c.raw)}}},scales:{y:{beginAtZero:true,ticks:{callback:v=>'Rp '+Number(v).toLocaleString('id-ID')}},x:{grid:{display:false}}}}});
 if(window.inventoryChartInstance)window.inventoryChartInstance.destroy();
 const inventoryCanvas=document.getElementById('inventoryChart');
 window.inventoryChartInstance=new Chart(inventoryCanvas,{type:'doughnut',plugins:[{id:'inventoryCenter',beforeDraw(chart){const ctx=chart.ctx;const center=chart.getDatasetMeta(0).data[0];if(!center)return;ctx.save();ctx.textAlign='center';ctx.textBaseline='middle';ctx.fillStyle='#0f172a';ctx.font='800 22px system-ui';ctx.fillText(String(activeCount),center.x,center.y-5);ctx.fillStyle='#64748b';ctx.font='600 10px system-ui';ctx.fillText('BARANG AKTIF',center.x,center.y+16);ctx.restore();}}],data:{labels:['Aman','Menipis','Habis'],datasets:[{data:inventoryValues,backgroundColor:['#22c55e','#f59e0b','#ef4444'],borderWidth:3,borderColor:'#fff'}]},options:{responsive:true,maintainAspectRatio:false,cutout:'68%',plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>c.label+': '+c.raw+' barang'}}}}});
 if(window.topProductsChartInstance)window.topProductsChartInstance.destroy();
 const topCanvas=document.getElementById('topProductsChart');
 window.topProductsChartInstance=new Chart(topCanvas,{type:'bar',data:{labels:topLabels.length?topLabels:['Tidak ada penjualan'],datasets:[{data:topValues.length?topValues:[0],backgroundColor:'#3b82f6',borderRadius:6}]},options:{indexAxis:'y',responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{beginAtZero:true},y:{grid:{display:false}}}}});
});
</script>
@endpush
