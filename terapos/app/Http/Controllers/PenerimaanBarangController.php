<?php

namespace App\Http\Controllers;

use App\Models\DetailPenerimaanBarang;
use App\Models\PenerimaanBarang;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenerimaanBarangController extends Controller
{
    public function index(Request $request)
    {
        $endDateRules = ['nullable', 'date'];
        if ($request->filled('start_date')) {
            $endDateRules[] = 'after_or_equal:start_date';
        }

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => $endDateRules,
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $penerimaans = PenerimaanBarang::query()
            ->with('user')
            ->withCount('detailPenerimaanBarangs')
            ->withSum('detailPenerimaanBarangs', 'qty')
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($receiptQuery) use ($search) {
                    $receiptQuery->where('nomor_penerimaan', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('detailPenerimaanBarangs.product', function ($productQuery) use ($search) {
                            $productQuery->where('nama_produk', 'like', "%{$search}%")
                                ->orWhere('kode_produk', 'like', "%{$search}%");
                        });
                });
            })
            ->when($filters['start_date'] ?? null, fn ($query, $date) => $query->whereDate('tanggal', '>=', $date))
            ->when($filters['end_date'] ?? null, fn ($query, $date) => $query->whereDate('tanggal', '<=', $date))
            ->when($filters['user_id'] ?? null, fn ($query, $userId) => $query->where('user_id', $userId))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $users = User::query()
            ->whereIn('role', ['owner', 'co_owner'])
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('penerimaan.index', compact('penerimaans', 'users', 'filters'));
    }

    public function create()
    {
        $products = Product::query()->orderBy('nama_produk')->get();

        return view('penerimaan.create', [
            'products' => $products,
            'generatedNumber' => $this->generateNextReceiptNumber(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'distinct', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        for ($attempt = 1; ; $attempt++) {
            try {
                DB::transaction(function () use ($validated) {
                    $items = collect($validated['items']);
                    $productIds = $items->pluck('product_id')
                        ->map(fn ($id) => (int) $id)
                        ->unique()
                        ->sort()
                        ->values();

                    $products = Product::query()
                        ->whereIn('id', $productIds)
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');

                    if ($products->count() !== $productIds->count()) {
                        throw ValidationException::withMessages([
                            'items' => 'Salah satu barang tidak lagi tersedia.',
                        ]);
                    }

                    $nomorPenerimaan = $this->generateNextReceiptNumber();
                    $penerimaan = PenerimaanBarang::create([
                        'nomor_penerimaan' => $nomorPenerimaan,
                        'user_id' => Auth::id(),
                        'tanggal' => $validated['tanggal'],
                        'keterangan' => $validated['keterangan'] ?? null,
                    ]);

                    foreach ($items as $item) {
                        $product = $products->get((int) $item['product_id']);
                        $qty = (int) $item['qty'];

                        DetailPenerimaanBarang::create([
                            'penerimaan_barang_id' => $penerimaan->id,
                            'product_id' => $product->id,
                            'qty' => $qty,
                        ]);

                        $product->stok += $qty;
                        $product->save();

                        StockMovement::create([
                            'product_id' => $product->id,
                            'jenis' => 'masuk',
                            'qty' => $qty,
                            'keterangan' => 'Penerimaan Barang ' . $nomorPenerimaan,
                        ]);
                    }
                });

                break;
            } catch (QueryException $exception) {
                if ($attempt >= 5 || !$this->isReceiptNumberCollision($exception)) {
                    throw $exception;
                }
            }
        }

        return redirect()
            ->route('penerimaan.index')
            ->with('success', 'Penerimaan barang berhasil disimpan dan stok telah diperbarui.');
    }

    public function show(PenerimaanBarang $penerimaanBarang)
    {
        $penerimaanBarang->load([
            'user',
            'detailPenerimaanBarangs.product',
        ]);

        return view('penerimaan.show', ['penerimaan' => $penerimaanBarang]);
    }

    private function generateNextReceiptNumber(): string
    {
        $largestNumber = PenerimaanBarang::query()
            ->pluck('nomor_penerimaan')
            ->reduce(function (int $largest, string $number): int {
                if (preg_match('/^TRM(\d{6})$/', $number, $matches)) {
                    return max($largest, (int) $matches[1]);
                }

                return $largest;
            }, 0);

        $sequence = $largestNumber + 1;
        if ($sequence > 999999) {
            throw new \RuntimeException('Nomor penerimaan telah mencapai batas sequence enam digit.');
        }

        do {
            $number = 'TRM' . str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);
            $sequence++;
        } while (PenerimaanBarang::where('nomor_penerimaan', $number)->exists());

        return $number;
    }

    private function isReceiptNumberCollision(QueryException $exception): bool
    {
        $sqlState = $exception->errorInfo[0] ?? null;
        $message = strtolower(
            $exception->getPrevious()?->getMessage() ?? $exception->getMessage()
        );

        return in_array($sqlState, ['23000', '23505'], true)
            && str_contains($message, 'nomor_penerimaan');
    }
}
