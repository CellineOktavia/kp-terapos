<?php

namespace App\Services;

use App\Models\CrossSubsidyLedgerEntry;
use App\Models\CrossSubsidyProgram;
use App\Models\DetailPenjualan;
use App\Models\DetailPenjualanReturn;
use App\Models\Penjualan;
use App\Models\PenjualanReturn;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenjualanReturnService
{
    public function process(
        Penjualan $sale,
        string $requestToken,
        int $userId,
        array $items = [],
        bool $cancel = false
    ): PenjualanReturn {
        try {
            return DB::transaction(function () use ($sale, $requestToken, $userId, $items, $cancel) {
                $existingReturn = PenjualanReturn::query()->where('request_token', $requestToken)->first();
                if ($existingReturn) {
                    if ($existingReturn->penjualan_id !== $sale->id) {
                        throw ValidationException::withMessages([
                            'request_token' => 'Token permintaan retur sudah digunakan untuk transaksi lain.',
                        ]);
                    }

                    return $existingReturn;
                }

                $sale = Penjualan::query()->whereKey($sale->id)->lockForUpdate()->firstOrFail();
                if (! in_array($sale->status, ['completed', 'partially_returned'], true)) {
                    throw ValidationException::withMessages([
                        'items' => 'Transaksi ini sudah dibatalkan atau seluruh barangnya telah diretur.',
                    ]);
                }

                $details = DetailPenjualan::query()
                    ->where('penjualan_id', $sale->id)
                    ->with('product')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');
                $detailIds = $details->keys()->all();
                $returnedQuantities = DB::table('detail_penjualan_returns')
                    ->select('detail_penjualan_id', DB::raw('SUM(qty) as returned_qty'))
                    ->whereIn('detail_penjualan_id', $detailIds)
                    ->groupBy('detail_penjualan_id')
                    ->pluck('returned_qty', 'detail_penjualan_id');

                if ($cancel) {
                    $items = $details->map(function (DetailPenjualan $detail) use ($returnedQuantities) {
                        return [
                            'detail_penjualan_id' => $detail->id,
                            'qty' => $detail->qty - (int) $returnedQuantities->get($detail->id, 0),
                        ];
                    })->filter(fn (array $item) => $item['qty'] > 0)->values()->all();
                }

                if ($items === []) {
                    throw ValidationException::withMessages([
                        'items' => 'Pilih setidaknya satu barang yang belum diretur.',
                    ]);
                }

                $returnPlan = [];
                $productIds = [];
                foreach ($items as $item) {
                    $detailId = (int) $item['detail_penjualan_id'];
                    $detail = $details->get($detailId);
                    if (! $detail) {
                        throw ValidationException::withMessages([
                            'items' => 'Salah satu barang bukan bagian dari transaksi ini.',
                        ]);
                    }

                    $previousQty = (int) $returnedQuantities->get($detailId, 0);
                    $remainingQty = $detail->qty - $previousQty;
                    $qty = (int) $item['qty'];
                    if ($qty < 1 || $qty > $remainingQty) {
                        throw ValidationException::withMessages([
                            'items' => "Jumlah retur {$detail->product?->nama_produk} melebihi sisa barang yang dapat diretur ({$remainingQty}).",
                        ]);
                    }

                    $newReturnedQty = $previousQty + $qty;
                    $netLineAmount = (float) $detail->subtotal - (float) $detail->discount_share;
                    $grossBefore = round($netLineAmount * $previousQty / $detail->qty, 2);
                    $grossAfter = round($netLineAmount * $newReturnedQty / $detail->qty, 2);
                    $subsidyRefund = round((float) $detail->cross_subsidy_per_unit * $qty, 2);
                    $refundAmount = max(0, round($grossAfter - $grossBefore - $subsidyRefund, 2));

                    $returnPlan[] = [
                        'detail' => $detail,
                        'qty' => $qty,
                        'previous_qty' => $previousQty,
                        'new_returned_qty' => $newReturnedQty,
                        'refund_amount' => $refundAmount,
                    ];
                    $productIds[] = $detail->product_id;
                }

                $products = Product::query()
                    ->whereIn('id', array_unique($productIds))
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $return = PenjualanReturn::create([
                    'penjualan_id' => $sale->id,
                    'user_id' => $userId,
                    'request_token' => $requestToken,
                    'reason' => $cancel ? 'cancellation' : 'return',
                    'refund_amount' => array_sum(array_column($returnPlan, 'refund_amount')),
                ]);

                $program = CrossSubsidyProgram::query()->whereKey(1)->lockForUpdate()->first();
                foreach ($returnPlan as $line) {
                    /** @var DetailPenjualan $detail */
                    $detail = $line['detail'];
                    $qty = $line['qty'];
                    DetailPenjualanReturn::create([
                        'penjualan_return_id' => $return->id,
                        'detail_penjualan_id' => $detail->id,
                        'qty' => $qty,
                        'refund_amount' => $line['refund_amount'],
                    ]);

                    $product = $products->get($detail->product_id);
                    if (! $product) {
                        throw ValidationException::withMessages([
                            'items' => 'Produk dari transaksi tidak tersedia; retur dibatalkan.',
                        ]);
                    }

                    $product->stok += $qty;
                    $product->save();
                    StockMovement::create([
                        'product_id' => $product->id,
                        'jenis' => 'masuk',
                        'qty' => $qty,
                        'keterangan' => ($cancel ? 'Pembatalan penjualan ' : 'Retur penjualan ')
                            .$sale->nomor_penjualan,
                    ]);

                    $this->reverseLedgerEntries(
                        $detail,
                        $return,
                        $line['new_returned_qty'],
                        $program,
                        $userId
                    );
                }

                $allReturned = true;
                foreach ($details as $detail) {
                    $newQty = (int) $returnedQuantities->get($detail->id, 0);
                    foreach ($returnPlan as $line) {
                        if ($line['detail']->id === $detail->id) {
                            $newQty = $line['new_returned_qty'];
                            break;
                        }
                    }
                    if ($newQty < $detail->qty) {
                        $allReturned = false;
                        break;
                    }
                }

                $sale->update([
                    'status' => $allReturned
                        ? ($cancel ? 'cancelled' : 'returned')
                        : 'partially_returned',
                ]);

                return $return;
            });
        } catch (QueryException $exception) {
            if (! $this->isRequestTokenCollision($exception)) {
                throw $exception;
            }

            $existingReturn = PenjualanReturn::query()->where('request_token', $requestToken)->first();
            if (! $existingReturn) {
                throw $exception;
            }
            if ($existingReturn->penjualan_id !== $sale->id) {
                throw ValidationException::withMessages([
                    'request_token' => 'Token permintaan retur sudah digunakan untuk transaksi lain.',
                ]);
            }

            return $existingReturn;
        }
    }

    private function reverseLedgerEntries(
        DetailPenjualan $detail,
        PenjualanReturn $return,
        int $newReturnedQty,
        ?CrossSubsidyProgram $currentProgram,
        int $userId
    ): void {
        if (! $currentProgram) {
            return;
        }

        $entries = CrossSubsidyLedgerEntry::query()
            ->where('detail_penjualan_id', $detail->id)
            ->whereIn('entry_type', ['contribution', 'subsidy_use'])
            ->get();

        foreach ($entries as $entry) {
            $isContribution = $entry->entry_type === 'contribution';
            $returnType = $isContribution ? 'source_return' : 'subsidy_return';
            $previousReversal = (float) CrossSubsidyLedgerEntry::query()
                ->where('reverses_entry_id', $entry->id)
                ->where('entry_type', $returnType)
                ->sum('amount');

            $targetReversal = round(abs((float) $entry->amount) * $newReturnedQty / $detail->qty, 2);
            $alreadyReversed = abs($previousReversal);
            $reversalAmount = max(0, round($targetReversal - $alreadyReversed, 2));
            if ($reversalAmount <= 0) {
                continue;
            }

            CrossSubsidyLedgerEntry::create([
                'program_id' => $entry->program_id,
                'product_id' => $entry->product_id,
                'penjualan_id' => $entry->penjualan_id,
                'detail_penjualan_id' => $detail->id,
                'penjualan_return_id' => $return->id,
                'reverses_entry_id' => $entry->id,
                'user_id' => $userId,
                'entry_type' => $returnType,
                'amount' => $isContribution ? -$reversalAmount : $reversalAmount,
                'rate_snapshot' => $entry->rate_snapshot,
                'unit_amount_snapshot' => $entry->unit_amount_snapshot,
                'description' => $isContribution
                    ? 'Pembalikan kontribusi dari retur penjualan '.$entry->penjualan?->nomor_penjualan
                    : 'Pengembalian subsidi dari retur penjualan '.$entry->penjualan?->nomor_penjualan,
            ]);
        }

    }

    private function isRequestTokenCollision(QueryException $exception): bool
    {
        $sqlState = $exception->errorInfo[0] ?? null;
        $message = strtolower($exception->getPrevious()?->getMessage() ?? $exception->getMessage());

        return in_array($sqlState, ['23000', '23505'], true)
            && str_contains($message, 'request_token');
    }
}
