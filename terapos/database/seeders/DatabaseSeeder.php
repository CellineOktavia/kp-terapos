<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (!is_file(database_path('seeders/data/Data_Master_Barang_Owner_v2.xlsx'))) {
            throw new RuntimeException(
                'Seeding dibatalkan: Data_Master_Barang_Owner_v2.xlsx belum tersedia. Tidak ada akun atau barang yang ditulis.'
            );
        }

        $this->call([
            CategorySeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
