<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        
        Category::whereIn('nama_kategori', [
            'Makanan',
            'Minuman',
            'Snack',
            'Sembako',
            'Perlengkapan',
        ])->delete();

        $categories = [
            'Lampu',
            'Elektronik',
            'Kabel & Kelistrikan',
            'Aksesoris Listrik',
            'Peralatan Rumah Tangga',
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