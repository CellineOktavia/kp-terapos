<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CategorySeeder::class);

        $products = [
            [
                'Lampu Hannochs Sonic 9W', 'Lampu & Penerangan', 'Hannochs', 16000, 23000, 10, 'pcs',
            ],
            [
                'Lampu Hannochs Sonic 12W', 'Lampu & Penerangan', 'Hannochs', 19200, 27000, 10, 'pcs',
            ],
            [
                'Stop Kontak 4 Lubang', 'Kelistrikan', null, 22000, 30000, 5, 'pcs',
            ],
            [
                'Saklar Tunggal', 'Kelistrikan', null, 10000, 15000, 8, 'pcs',
            ],
            [
                'Fitting Lampu Putih', 'Kelistrikan', null, 5000, 8000, 10, 'pcs',
            ],
            [
                'Kabel Roll 5 Meter', 'Kabel & Aksesoris Listrik', null, 35000, 45000, 3, 'pcs',
            ],
            [
                'Terminal Listrik 4 Lubang', 'Kelistrikan', null, 28000, 38000, 4, 'pcs',
            ],
            [
                'Steker / Colokan Listrik', 'Kelistrikan', null, 5000, 8000, 10, 'pcs',
            ],
            [
                'Sapu Lantai', 'Peralatan Kebersihan', null, 20000, 28000, 3, 'pcs',
            ],
            [
                'Pel Lantai', 'Peralatan Kebersihan', null, 25000, 35000, 3, 'pcs',
            ],
            [
                'Ember Plastik', 'Peralatan Rumah Tangga', null, 18000, 25000, 2, 'pcs',
            ],
            [
                'Gelas Kaca Set', 'Pecah Belah', null, 35000, 48000, 2, 'set',
            ],
            [
                'Piring Kaca Set', 'Pecah Belah', null, 45000, 60000, 2, 'set',
            ],
            [
                'Stop Kontak 2 Lubang', 'Kelistrikan', null, 15000, 22000, 5, 'pcs',
            ],
            [
                'Kabel NYM 2x1.5', 'Kabel & Aksesoris Listrik', null, 55000, 70000, 3, 'meter',
            ],
        ];

        foreach ($products as $index => [
            $name,
            $categoryName,
            $brand,
            $buyPrice,
            $sellPrice,
            $minimumStock,
            $unit,
        ]) {
            $number = $index + 1;
            $code = 'TERA' . str_pad((string) $number, 3, '0', STR_PAD_LEFT);
            $barcodeBody = (string) (200000000000 + $number);
            $barcode = $barcodeBody . $this->ean13CheckDigit($barcodeBody);

            Product::updateOrCreate(
                ['barcode' => $barcode],
                [
                    'kode_produk' => $code,
                    'barcode' => $barcode,
                    'category_id' => Category::where(
                        'nama_kategori',
                        $categoryName,
                    )->value('id'),
                    'nama_produk' => $name,
                    'merk' => $brand,
                    'satuan' => $unit,
                    'stok_minimum' => $minimumStock,
                    'harga_beli' => $buyPrice,
                    'harga_jual' => $sellPrice,
                    'deskripsi' => null,
                ]
            );
        }
    }

    private function ean13CheckDigit(string $body): string
    {
        $sum = 0;
        foreach (str_split($body) as $index => $digit) {
            $sum += (int) $digit * ($index % 2 === 0 ? 1 : 3);
        }

        return (string) ((10 - ($sum % 10)) % 10);
    }
}