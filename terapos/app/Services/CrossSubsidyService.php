<?php

namespace App\Services;

use App\Models\CrossSubsidyLedgerEntry;
use App\Models\CrossSubsidyProgram;
use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CrossSubsidyService
{
    /**
     * @param array<int, array{product: Product, qty: int, subtotal: float}> $lines
     * @return array{discount_shares: array<int, float>, contributions: array<int, float>, subsidy_per_unit: array<int, float>, subsidy_total: float, balance_before: float}
     */
    public function calculateSaleBenefits(array $lines, float $discount, ?CrossSubsidyProgram $program): array
    {
        $subtotals = [];
        foreach ($lines as $productId => $line) {
            $subtotals[$productId] = $line['subtotal'];
        }

        $discountShares = $this->allocateMoney($discount, $subtotals);
        $contributions = [];
        $subsidyPerUnit = [];
        $subsidyTotal = 0.0;
        $balance = $program?->is_active ? $this->balance($program) : 0.0;

        if ($program?->is_active) {
            $sourceId = (int) $program->source_product_id;
            if (isset($lines[$sourceId])) {
                $line = $lines[$sourceId];
                $netSubtotal = $line['subtotal'] - ($discountShares[$sourceId] ?? 0);
                $cost = (float) $line['product']->harga_beli * $line['qty'];
                $margin = max(0, $netSubtotal - $cost);
                $contribution = $this->money($margin * (float) $program->allocation_percentage / 100);
                if ($contribution > 0) {
                    $contributions[$sourceId] = $contribution;
                }
            }

            $recipientId = (int) $program->recipient_product_id;
            if ($balance > 0 && isset($lines[$recipientId])) {
                $line = $lines[$recipientId];
                $qty = $line['qty'];
                $netSubtotal = $line['subtotal'] - ($discountShares[$recipientId] ?? 0);
                $minimumTotal = (float) $program->minimum_selling_price * $qty;
                $priceFloorHeadroom = max(0, $netSubtotal - $minimumTotal);
                $grantCents = min(
                    $this->cents((float) $program->subsidy_amount) * $qty,
                    $this->cents((float) $program->maximum_subsidy),
                    $this->cents($balance),
                    $this->cents($priceFloorHeadroom)
                );
                $perUnitCents = intdiv(max(0, $grantCents), $qty);
                if ($perUnitCents > 0) {
                    $subsidyPerUnit[$recipientId] = $perUnitCents / 100;
                    $subsidyTotal = ($perUnitCents * $qty) / 100;
                }
            }
        }

        return [
            'discount_shares' => $discountShares,
            'contributions' => $contributions,
            'subsidy_per_unit' => $subsidyPerUnit,
            'subsidy_total' => $subsidyTotal,
            'balance_before' => $balance,
        ];
    }

    /**
     * @param array<int, array{product: Product, qty: int, subtotal: float}> $lines
     * @param array<int, DetailPenjualan> $details
     */
    public function recordSaleEntries(
        Penjualan $sale,
        array $lines,
        array $details,
        array $benefits,
        ?CrossSubsidyProgram $program,
        int $userId
    ): void {
        if (!$program?->is_active) {
            return;
        }

        foreach ($benefits['contributions'] as $productId => $amount) {
            CrossSubsidyLedgerEntry::create([
                'program_id' => $program->id,
                'product_id' => $productId,
                'penjualan_id' => $sale->id,
                'detail_penjualan_id' => $details[$productId]->id,
                'user_id' => $userId,
                'entry_type' => 'contribution',
                'amount' => $amount,
                'rate_snapshot' => $program->allocation_percentage,
                'description' => 'Kontribusi margin dari penjualan ' . $sale->nomor_penjualan,
            ]);
        }

        foreach ($benefits['subsidy_per_unit'] as $productId => $perUnit) {
            $qty = $lines[$productId]['qty'];
            CrossSubsidyLedgerEntry::create([
                'program_id' => $program->id,
                'product_id' => $productId,
                'penjualan_id' => $sale->id,
                'detail_penjualan_id' => $details[$productId]->id,
                'user_id' => $userId,
                'entry_type' => 'subsidy_use',
                'amount' => -$perUnit * $qty,
                'unit_amount_snapshot' => $perUnit,
                'description' => 'Subsidi produk pada penjualan ' . $sale->nomor_penjualan,
            ]);
        }
    }

    public function balance(CrossSubsidyProgram $program): float
    {
        return (float) CrossSubsidyLedgerEntry::query()
            ->where('program_id', $program->id)
            ->sum('amount');
    }

    /**
     * Allocate rounded cents proportionally, assigning residual cents by largest fractional remainder.
     *
     * @param array<int, float> $weights
     * @return array<int, float>
     */
    private function allocateMoney(float $amount, array $weights): array
    {
        $totalCents = $this->cents($amount);
        $weightCents = array_map(fn (float $weight): int => $this->cents($weight), $weights);
        $weightTotal = array_sum($weightCents);
        if ($totalCents <= 0 || $weightTotal <= 0) {
            return array_fill_keys(array_keys($weights), 0.0);
        }

        $allocations = [];
        $remainders = [];
        $allocatedCents = 0;
        foreach ($weightCents as $key => $weight) {
            $exact = $totalCents * ($weight / $weightTotal);
            $base = (int) floor($exact);
            $allocations[$key] = $base;
            $remainders[$key] = $exact - $base;
            $allocatedCents += $base;
        }

        $keys = array_keys($remainders);
        usort($keys, fn ($left, $right) => $remainders[$right] <=> $remainders[$left]);
        for ($i = 0, $remaining = $totalCents - $allocatedCents; $i < $remaining; $i++) {
            $allocations[$keys[$i]]++;
        }

        return array_map(fn (int $cents): float => $cents / 100, $allocations);
    }

    private function cents(float $amount): int
    {
        return (int) round($amount * 100);
    }

    private function money(float $amount): float
    {
        return round($amount, 2);
    }
}
