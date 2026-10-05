<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CategorySeeder::class);

        $supplierId = Supplier::query()->orderBy('id')->value('id');

        $products = [
            [
                'TERA001',
                '2000000000015',
                'Lampu Hannochs Sonic Kuning 15W',
                'Lampu',
                'Hannochs',
                18000,
                25000,
                'pcs',
            ],
            [
                'TERA002',
                '2000000000022',
                'Lampu Hannochs Sonic Putih 12W',
                'Lampu',
                'Hannochs',
                16000,
                23000,
                'pcs',
            ],
            [
                'TERA003',
                '2000000000039',
                'Stop Kontak 4 Lubang',
                'Elektronik',
                null,
                22000,
                30000,
                'pcs',
            ],
            [
                'TERA004',
                '2000000000046',
                'Kabel Roll 5 Meter',
                'Elektronik',
                null,
                35000,
                45000,
                'pcs',
            ],
            [
                'TERA005',
                '2000000000053',
                'Sapu Lantai',
                'Peralatan Rumah Tangga',
                null,
                15000,
                22000,
                'pcs',
            ],
        ];

        foreach ($products as [
            $code,
            $barcode,
            $name,
            $categoryName,
            $brand,
            $buyPrice,
            $sellPrice,
            $unit
        ]) {
            Product::updateOrCreate(
                ['barcode' => $barcode],
                [
                    'kode_produk' => $code,
                    'barcode' => $barcode,
                    'category_id' => Category::where(
                        'nama_kategori',
                        $categoryName
                    )->value('id'),
                    'supplier_id' => $supplierId,
                    'nama_produk' => $name,
                    'merk' => $brand,
                    'satuan' => $unit,
                    'stok' => 0,
                    'stok_minimum' => 2,
                    'harga_beli' => $buyPrice,
                    'harga_jual' => $sellPrice,
                    'deskripsi' => null,
                ]
            );
        }
    }
}