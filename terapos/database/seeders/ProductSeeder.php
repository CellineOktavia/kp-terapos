<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ProductSeeder extends Seeder
{
    private const SYSTEM_MINIMUM_STOCK = 5;

    public function run(): void
    {
        $products = require database_path('seeders/data/owner_product_master.php');

        if (count($products) !== 150) {
            throw new RuntimeException('Dataset master barang Owner harus berisi tepat 150 barang.');
        }

        if (Product::query()->exists()) {
            throw new RuntimeException(
                'ProductSeeder hanya untuk database tanpa barang. Sinkronisasi barang existing harus direncanakan terpisah agar histori tidak berubah.'
            );
        }

        $categoryIds = Category::query()
            ->whereIn('nama_kategori', array_unique(array_column($products, 'kategori')))
            ->pluck('id', 'nama_kategori');

        foreach (array_unique(array_column($products, 'kategori')) as $category) {
            if (!isset($categoryIds[$category])) {
                throw new RuntimeException("Kategori '{$category}' belum tersedia. Jalankan CategorySeeder terlebih dahulu.");
            }
        }

        DB::transaction(function () use ($products, $categoryIds): void {
            foreach ($products as $product) {
                Product::query()->create([
                    'kode_produk' => $product['kode_produk'],
                    'category_id' => $categoryIds[$product['kategori']],
                    'nama_produk' => $product['nama_produk'],
                    'merk' => $product['merk'],
                    'satuan' => $product['satuan'],
                    'stok' => $product['stok'],
                    'stok_minimum' => self::SYSTEM_MINIMUM_STOCK,
                    'harga_beli' => $product['harga_beli'],
                    'harga_jual' => $product['harga_jual'],
                    'aktif' => true,
                ]);
            }
        });
    }
}
