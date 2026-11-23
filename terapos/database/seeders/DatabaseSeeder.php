<?php

namespace Database\Seeders;

use App\Models\Product;
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

        if (!is_file(database_path('seeders/data/owner_product_master.php'))) {
            throw new RuntimeException(
                'Seeding dibatalkan: hasil ekstraksi master barang Owner tidak ditemukan.'
            );
        }

        if (Product::query()->exists()) {
            throw new RuntimeException(
                'Seeding dibatalkan: database sudah memiliki barang. Sinkronisasi master harus direncanakan agar histori transaksi tetap aman.'
            );
        }

        $this->call([
            CategorySeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
