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
            'kode_produk' => fake()->unique()->numerify('TERA####'),
            'category_id' => Category::query()
                ->whereIn('nama_kategori', [
                    'Lampu & Penerangan',
                    'Kelistrikan',
                    'Kabel & Aksesoris Listrik',
                    'Elektronik Rumah Tangga',
                    'Peralatan Rumah Tangga',
                    'Peralatan Kebersihan',
                    'Kebutuhan Kebersihan',
                    'Peralatan Dapur',
                    'Pecah Belah',
                    'Lain-lain',
                ])
                ->inRandomOrder()
                ->value('id'),
            'nama_produk' => fake()->randomElement([
                'Lampu Hannochs Sonic 3W',
                'Lampu Hannochs Sonic 5W',
                'Lampu Hannochs Sonic 7W',
                'Lampu Hannochs Sonic 9W',
                'Lampu Hannochs Sonic 12W',
                'Lampu Hannochs Sonic 15W',
                'Stop Kontak 2 Lubang',
                'Stop Kontak 4 Lubang',
                'Saklar Tunggal',
                'Saklar Ganda',
                'Fitting Lampu',
                'Steker Universal',
                'Kabel Roll 10 Meter',
                'Kabel NYM 2x1.5',
                'Kabel NYA 2.5 mm',
                'Mama Lemon Sabun Cuci Piring 680 ml',
                'Wipol Pembersih Lantai 780 ml',
                'Kipas Angin Berdiri 16 Inci',
                'Rice Cooker 1.8 Liter',
                'Setrika Listrik Anti Lengket',
                'Blender 1.5 Liter',
                'Ember Plastik 10 Liter',
                'Baskom Plastik 36 cm',
                'Sapu Lantai',
                'Pel Microfiber',
                'Keset Pintu Microfiber',
                'Wajan Anti Lengket 24 cm',
                'Panci Stainless 20 cm',
                'Talenan Plastik',
                'Gelas Kaca Set 6 Buah',
                'Piring Makan Set 6 Buah',
                'Toples Kaca 1 Liter',
                'Terminal Listrik 4 Lubang',
            ]),
            'merk' => fake()->randomElement([
                'Hannochs',
                'Philips',
                'Panasonic',
                'Cosmos',
                'Maspion',
                'Broco',
                'Eterna',
                'Lion Star',
                'Nagata',
                'Mama Lemon',
                'Sunlight',
                'Wipol',
                'Miyako',
                'Maxim',
                'Luminarc',
            ]),
            'satuan' => fake()->randomElement(['pcs', 'set', 'meter']),
            'stok' => fake()->numberBetween(0, 100),
            'stok_minimum' => fake()->randomElement([2, 3, 4, 5, 8, 10]),
            'harga_beli' => $buyPrice,
            'harga_jual' => $buyPrice + fake()->numberBetween(1000, 50000),
        ];
    }
}
