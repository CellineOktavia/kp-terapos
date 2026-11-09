<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Penjualan;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function penjualan(Request $request)
    {
        $period = $this->resolvePeriod($request);
        $query = $this->saleQuery($period['start'], $period['end']);

        $penjualans = (clone $query)
            ->with(['customer', 'user', 'detailPenjualans'])
            ->withCount('detailPenjualans')
            ->withSum('detailPenjualans', 'qty')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        $summary = $this->saleSummary($period['start'], $period['end']);

        return view('laporan.penjualan', [
            'penjualans' => $penjualans,
            'summary' => $summary,
            'period' => $period,
        ]);
    }

    public function stok(Request $request)
    {
        $filters = $this->validateStockFilters($request);
        $productsQuery = $this->stockQuery($filters);

        $products = (clone $productsQuery)
            ->with('category')
            ->orderBy('nama_produk')
            ->paginate(25)
            ->withQueryString();

        $summary = [
            'products' => (clone $productsQuery)->count(),
            'critical' => (clone $productsQuery)
                ->whereColumn('stok', '<=', 'stok_minimum')
                ->count(),
            'inventoryValue' => (float) (clone $productsQuery)
                ->selectRaw('COALESCE(SUM(stok * harga_beli), 0) as inventory_value')
                ->first()
                ->inventory_value,
        ];
        $categories = Category::orderBy('nama_kategori')->get(['id', 'nama_kategori']);

        return view('laporan.stok', compact('products', 'summary', 'categories', 'filters'));
    }

    public function penjualanPdf(Request $request)
    {
        $period = $this->resolvePeriod($request);
        $penjualans = $this->saleQuery($period['start'], $period['end'])
            ->with(['customer', 'user', 'detailPenjualans.product'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();
        $summary = $this->saleSummary($period['start'], $period['end']);

        return Pdf::loadView('laporan.pdf.penjualan', compact('penjualans', 'summary', 'period'))
            ->download('laporan-penjualan.pdf');
    }

    public function stokPdf(Request $request)
    {
        $filters = $this->validateStockFilters($request);
        $products = $this->stockQuery($filters)
            ->with('category')
            ->orderBy('nama_produk')
            ->get();

        $summary = [
            'products' => $products->count(),
            'critical' => $products->filter(fn (Product $product) => $product->stok <= $product->stok_minimum)->count(),
            'inventoryValue' => (float) $products->sum(
                fn (Product $product) => $product->stok * $product->harga_beli
            ),
        ];

        return Pdf::loadView('laporan.pdf.stok', compact('products', 'summary', 'filters'))
            ->download('laporan-stok.pdf');
    }

    private function resolvePeriod(Request $request): array
    {
        $validated = $request->validate([
            'period' => ['nullable', Rule::in(['today', 'week', 'month', 'custom'])],
            'start_date' => ['required_if:period,custom', 'nullable', 'date'],
            'end_date' => ['required_if:period,custom', 'nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $period = $validated['period'] ?? 'month';
        $now = now();

        [$start, $end] = match ($period) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'week' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'custom' => [
                Carbon::parse($validated['start_date'])->startOfDay(),
                Carbon::parse($validated['end_date'])->endOfDay(),
            ],
            default => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
        };

        return [
            'key' => $period,
            'start' => $start,
            'end' => $end,
            'label' => $start->locale('id')->translatedFormat('d F Y')
                . ' - ' . $end->locale('id')->translatedFormat('d F Y'),
        ];
    }

    private function saleQuery(Carbon $start, Carbon $end): Builder
    {
        return Penjualan::query()
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()]);
    }

    private function saleSummary(Carbon $start, Carbon $end): array
    {
        $query = $this->saleQuery($start, $end);

        return [
            'total' => (float) (clone $query)->sum('total'),
            'transactions' => (clone $query)->count(),
            'qty' => (int) DB::table('detail_penjualans')
                ->join('penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id')
                ->whereBetween('penjualans.tanggal', [$start->toDateString(), $end->toDateString()])
                ->sum('detail_penjualans.qty'),
        ];
    }

    private function validateStockFilters(Request $request): array
    {
        return $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['all', 'safe', 'low', 'out'])],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);
    }

    private function stockQuery(array $filters): Builder
    {
        return Product::query()
            ->when($filters['search'] ?? null, function (Builder $query, string $search) {
                $query->where(function (Builder $productQuery) use ($search) {
                    $productQuery->where('kode_produk', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%")
                        ->orWhere('nama_produk', 'like', "%{$search}%");
                });
            })
            ->when($filters['category_id'] ?? null, fn (Builder $query, string $categoryId) => $query->where('category_id', $categoryId))
            ->when(($filters['status'] ?? 'all') !== 'all', function (Builder $query) use ($filters) {
                match ($filters['status']) {
                    'out' => $query->where('stok', 0),
                    'low' => $query->where('stok', '>', 0)->whereColumn('stok', '<=', 'stok_minimum'),
                    'safe' => $query->whereColumn('stok', '>', 'stok_minimum'),
                };
            });
    }
}
