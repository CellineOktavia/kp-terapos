<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Supplier;

class FakturFactory extends Factory
{
    public function definition(): array
    {
        return [

            'nomor_faktur' =>
            fake()->unique()->numerify('INV-#####'),

            'user_id' =>
            User::inRandomOrder()->value('id'),

            'supplier_id' =>
            Supplier::inRandomOrder()->value('id'),

            'total' =>
            fake()->numberBetween(
                100000,
                5000000
            ),

            'tanggal' =>
            fake()->date(),

        ];
    }
}
