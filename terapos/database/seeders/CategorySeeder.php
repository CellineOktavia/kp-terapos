<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Lampu Hannochs Sonic',
            'Lampu Gepeng / Downlight',
            'Lampu Hannochs Vario',
            'Lampu Hannochs Premier',
            'Gembok',
            'Kabel',
            'Stop Kontak',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['nama_kategori' => $name],
                []
            );
        }
    }
}