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
            'kode_produk' => fake()->unique()->numerify('####'),
            'category_id' => Category::query()
                ->whereIn('nama_kategori', [
                    'Lampu Hannochs Sonic',
                    'Lampu Gepeng / Downlight',
                    'Lampu Hannochs Vario',
                    'Lampu Hannochs Premier',
                    'Gembok',
                    'Kabel',
                    'Stop Kontak',
                ])
                ->inRandomOrder()
                ->value('id'),
            'nama_produk' => fake()->randomElement([
                'Lampu Hannochs Sonic 9W',
                'Lampu Hannochs Vario 12W',
                'Lampu Hannochs Premier 15W',
                'Downlight Mitsuyama 12W',
                'Stop Kontak Broco 2 Lubang',
                'Kabel Supreme NYM 2x1.5',
                'Gembok',
            ]),
            'merk' => fake()->randomElement(['Hannochs', 'Mitsuyama', 'Broco', 'Supreme', null]),
            'satuan' => fake()->randomElement(['pcs', 'set', 'meter']),
            'stok' => fake()->numberBetween(0, 100),
            'stok_minimum' => fake()->randomElement([2, 3, 4, 5, 8, 10]),
            'harga_beli' => $buyPrice,
            'harga_jual' => $buyPrice + fake()->numberBetween(1000, 50000),
        ];
    }
}
