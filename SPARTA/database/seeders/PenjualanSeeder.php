<?php

namespace Database\Seeders;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Product;
use App\Models\Customer;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $products = Product::where('stok', '>', 0)->get();
        $user = User::first();

        if ($customers->isEmpty()) {
            $this->command->warn('Data customer masih kosong.');
            return;
        }

        if ($products->isEmpty()) {
            $this->command->warn('Data produk masih kosong atau stok habis.');
            return;
        }

        if (!$user) {
            $this->command->warn('Data user masih kosong.');
            return;
        }

        DB::transaction(function () use ($customers, $products, $user) {
            for ($i = 1; $i <= 50; $i++) {
                $product = $products->random();

                if ($product->stok <= 0) {
                    continue;
                }

                $customer = $customers->random();
                $qty = fake()->numberBetween(1, min(5, $product->stok));
                $harga = $product->harga_jual;
                $subtotal = $qty * $harga;

                $penjualan = Penjualan::create([
                    'nomor_penjualan' => 'PJ-' . now()->format('YmdHis') . '-' . $i,
                    'user_id' => $user->id,
                    'customer_id' => $customer->id,
                    'tanggal' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
                    'total' => $subtotal,
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