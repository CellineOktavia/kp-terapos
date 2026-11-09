<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Lampu & Penerangan',
            'Kelistrikan',
            'Kabel & Aksesoris Listrik',
            'Elektronik Rumah Tangga',
            'Peralatan Kebersihan',
            'Peralatan Rumah Tangga',
            'Pecah Belah',
            'Lain-lain',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['nama_kategori' => $name],
                []
            );
        }
    }
}