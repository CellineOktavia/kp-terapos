<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode_supplier' =>
            fake()->unique()->numerify('SUP###'),
            'nama_supplier' =>
            fake()->company(),
            'nama_kontak' =>
            fake()->name(),
            'telepon' =>
            fake()->phoneNumber(),
            'email' =>
            fake()->safeEmail(),
            'alamat' =>
            fake()->address(),
            'aktif' =>
            true,
        ];
    }
}
