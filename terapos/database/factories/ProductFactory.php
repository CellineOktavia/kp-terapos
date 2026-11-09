<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $buyPrice = fake()->numberBetween(5000, 300000);

        return [
            'kode_produk' => fake()->unique()->numerify('TERA#####'),
            'category_id' => Category::query()
                ->whereIn('nama_kategori', [
                    'Lampu & Penerangan',
                    'Kelistrikan',
                    'Kabel & Aksesoris Listrik',
                    'Elektronik Rumah Tangga',
                    'Peralatan Rumah Tangga',
                    'Peralatan Kebersihan',
                    'Pecah Belah',
                    'Lain-lain',
                ])
                ->inRandomOrder()
                ->value('id'),
            'nama_produk' => fake()->randomElement([
                'Lampu Hannochs Sonic 9W',
                'Lampu Hannochs Sonic 12W',
                'Stop Kontak 4 Lubang',
                'Saklar Tunggal',
                'Fitting Lampu Putih',
                'Kabel Roll 5 Meter',
                'Terminal Listrik 4 Lubang',
                'Steker Listrik',
                'Sapu Lantai',
                'Pel Lantai',
                'Ember Plastik',
                'Gelas Kaca Set',
                'Piring Kaca Set',
                'Stop Kontak 2 Lubang',
                'Kabel NYM 2x1.5',
            ]),
            'merk' => fake()->optional()->randomElement(['Hannochs', 'Philips', 'Panasonic', 'Cosmos', 'Maspion']),
            'satuan' => fake()->randomElement(['pcs', 'set', 'meter']),
            'stok' => fake()->numberBetween(0, 100),
            'stok_minimum' => fake()->randomElement([2, 3, 4, 5, 8, 10]),
            'harga_beli' => $buyPrice,
            'harga_jual' => $buyPrice + fake()->numberBetween(1000, 50000),
        ];
    }
}
