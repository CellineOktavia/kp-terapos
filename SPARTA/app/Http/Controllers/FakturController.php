<?php

namespace App\Http\Controllers;

use App\Models\DetailFaktur;
use App\Models\Faktur;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FakturController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $fakturs = Faktur::query()
            ->with('user')
            ->withCount('detailFakturs')
            ->when($search, function ($query) use ($search) {
                $query->where('nomor_faktur', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('faktur.index', compact('fakturs', 'search'));
    }

    public function create()
    {
        $products = Product::orderBy('nama_produk')->get();
        $generatedNumber = $this->generateNextPurchaseNumber();

        return view(
            'faktur.create',
            compact('products', 'generatedNumber')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => [
                'required',
                'distinct',
                'exists:products,id',
            ],
            'items.*.qty' => [
                'required',
                'integer',
                'min:1',
            ],
            'items.*.harga' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        for ($attempt = 1; ; $attempt++) {
            try {
                DB::transaction(function () use ($validated) {
                    $purchaseNumber = $this->generateNextPurchaseNumber();
                    $items = collect($validated['items']);
                    $productIds = $items->pluck('product_id')->map(
                        fn ($id) => (int) $id
                    )->unique()->sort()->values();

                    $products = Product::query()
                        ->whereIn('id', $productIds)
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');

                    if ($products->count() !== $productIds->count()) {
                        throw ValidationException::withMessages([
                            'items' => 'Salah satu produk tidak lagi tersedia.',
                        ]);
                    }

                    $faktur = Faktur::create([
                        'nomor_faktur' => $purchaseNumber,
                        'user_id' => Auth::id(),
                        'supplier_id' => null,
                        'tanggal' => $validated['tanggal'],
                        'total' => 0,
                    ]);

                    $total = 0;
                    foreach ($items as $item) {
                        $product = $products->get((int) $item['product_id']);
                        $qty = (int) $item['qty'];
                        $harga = (float) $item['harga'];
                        $subtotal = round($qty * $harga, 2);
                        $total += $subtotal;

                        DetailFaktur::create([
                            'faktur_id' => $faktur->id,
                            'product_id' => $product->id,
                            'qty' => $qty,
                            'harga' => $harga,
                            'subtotal' => $subtotal,
                        ]);

                        $product->stok += $qty;
                        $product->harga_beli = $harga;
                        $product->save();

                        StockMovement::create([
                            'product_id' => $product->id,
                            'jenis' => 'masuk',
                            'qty' => $qty,
                            'keterangan' => 'Pembelian ' . $purchaseNumber,
                        ]);
                    }

                    $faktur->update(['total' => round($total, 2)]);
                });

                break;
            } catch (QueryException $exception) {
                if ($attempt >= 5 || !$this->isPurchaseNumberCollision($exception)) {
                    throw $exception;
                }
            }
        }

        return redirect()
            ->route('faktur.index')
            ->with('success', 'Pembelian berhasil disimpan dan stok telah diperbarui.');
    }

    public function show(Faktur $faktur)
    {
        $faktur->load([
            'user',
            'detailFakturs.product',
        ]);

        return view('faktur.show', compact('faktur'));
    }

    public function destroy(Faktur $faktur)
    {
        if (Auth::user()->role !== 'owner') {
            abort(403);
        }

        try {
            DB::transaction(function () use ($faktur) {
                $lockedFaktur = Faktur::query()
                    ->whereKey($faktur->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $details = $lockedFaktur->detailFakturs()
                    ->orderBy('product_id')
                    ->get();

                $products = Product::query()
                    ->whereIn('id', $details->pluck('product_id')->unique())
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($details as $detail) {
                    $product = $products->get($detail->product_id);

                    if (!$product || $product->stok < $detail->qty) {
                        throw ValidationException::withMessages([
                            'purchase' => "Pembelian {$lockedFaktur->nomor_faktur} tidak dapat dibatalkan karena stok {$detail->product?->nama_produk} sudah digunakan atau keluar.",
                        ]);
                    }

                    $product->stok -= $detail->qty;
                    $product->save();

                    StockMovement::create([
                        'product_id' => $product->id,
                        'jenis' => 'keluar',
                        'qty' => $detail->qty,
                        'keterangan' => 'Pembatalan Pembelian ' . $lockedFaktur->nomor_faktur,
                    ]);
                }

                $lockedFaktur->delete();
            });
        } catch (ValidationException $exception) {
            return back()->withErrors($exception->errors());
        }

        return redirect()
            ->route('faktur.index')
            ->with('success', 'Pembelian dibatalkan dan stok telah dikoreksi.');
    }

    private function generateNextPurchaseNumber(): string
    {
        $largestNumber = Faktur::query()
            ->where('nomor_faktur', 'like', 'PB%')
            ->pluck('nomor_faktur')
            ->reduce(function (int $largest, string $number): int {
                if (preg_match('/^PB(\d+)$/', $number, $matches)) {
                    return max($largest, (int) $matches[1]);
                }

                return $largest;
            }, 0);

        $sequence = $largestNumber + 1;
        do {
            $number = 'PB' . str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);
            $sequence++;
        } while (Faktur::where('nomor_faktur', $number)->exists());

        return $number;
    }

    private function isPurchaseNumberCollision(QueryException $exception): bool
    {
        $sqlState = $exception->errorInfo[0] ?? null;
        $message = strtolower(
            $exception->getPrevious()?->getMessage() ?? $exception->getMessage()
        );

        return in_array($sqlState, ['23000', '23505'], true)
            && str_contains($message, 'nomor_faktur');
    }
}
