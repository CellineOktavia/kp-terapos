<?php

namespace Database\Factories;

use App\Models\Faktur;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailFakturFactory extends Factory
{
    public function definition(): array
    {
        $qty = fake()->numberBetween(1, 20);

        $harga = fake()->numberBetween(
            50000,
            300000
        );

        return [

            'faktur_id' =>
            Faktur::inRandomOrder()->value('id'),

            'product_id' =>
            Product::inRandomOrder()->value('id'),

            'qty' =>
            $qty,

            'harga' =>
            $harga,

            'subtotal' =>
            $qty * $harga,

        ];
    }
}
