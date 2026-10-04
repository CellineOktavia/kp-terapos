<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Supplier;

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
        return [

            'kode_produk' => fake()->unique()->numerify('PRD###'),

            'supplier_id' => Supplier::inRandomOrder()->value('id'),

            'nama_produk' => fake()->randomElement([
                'Oli Yamalube',
                'Ban IRC',
                'Kampas Rem',
                'Aki GS Astra',
                'Busi NGK'
            ]),

            'merk' => fake()->randomElement([
                'Yamaha',
                'Honda',
                'Suzuki'
            ]),

            'stok' => fake()->numberBetween(0, 100),

            'stok_minimum' => 10,

            'harga_beli' => fake()->numberBetween(50000, 300000),

            'harga_jual' => fake()->numberBetween(80000, 400000),

        ];
    }
}
