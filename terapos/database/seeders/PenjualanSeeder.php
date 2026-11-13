<?php

namespace Database\Seeders;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::where('stok', '>', 0)->get();
        $user = User::query()->whereIn('role', ['owner', 'co_owner'])->first();

        if ($products->isEmpty()) {
            $this->command->warn('Data produk masih kosong atau stok habis.');
            return;
        }

        if (!$user) {
            $this->command->warn('Data user masih kosong.');
            return;
        }

        DB::transaction(function () use ($products, $user) {
            $sequence = Penjualan::query()
                ->pluck('nomor_penjualan')
                ->reduce(function (int $largest, string $number): int {
                    if (preg_match('/^TRX(\d{6})$/', $number, $matches)) {
                        return max($largest, (int) $matches[1]);
                    }

                    return $largest;
                }, 0) + 1;

            for ($i = 1; $i <= 50; $i++) {
                $product = $products->random();

                if ($product->stok <= 0) {
                    continue;
                }

                $qty = fake()->numberBetween(1, min(5, $product->stok));
                $harga = $product->harga_jual;
                $subtotal = $qty * $harga;
                $diskonPersen = $subtotal > 300000 ? 5 : 0;
                $diskonNominal = round($subtotal * $diskonPersen / 100, 2);
                $total = round($subtotal - $diskonNominal, 2);
                do {
                    $saleNumber = 'TRX' . str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);
                    $sequence++;
                } while (Penjualan::where('nomor_penjualan', $saleNumber)->exists());

                $penjualan = Penjualan::create([
                    'nomor_penjualan' => $saleNumber,
                    'user_id' => $user->id,
                    'tanggal' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
                    'total' => $total,
                    'diskon_persen' => $diskonPersen,
                    'diskon_nominal' => $diskonNominal,
                    'bayar' => $total,
                    'kembalian' => 0,
                ]);

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'harga' => $harga,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stok', $qty);

                StockMovement::create([
                    'product_id' => $product->id,
                    'jenis' => 'keluar',
                    'qty' => $qty,
                    'keterangan' => 'Seeder Penjualan ' . $penjualan->nomor_penjualan,
                ]);
            }
        });
    }
}