<?php

namespace Tests\Feature;

use Tests\TestCase;

class OwnerProductMasterDatasetTest extends TestCase
{
    private function products(): array
    {
        return require database_path('seeders/data/owner_product_master.php');
    }

    public function test_owner_dataset_has_150_unique_products_and_expected_category_counts(): void
    {
        $products = $this->products();
        $codes = array_column($products, 'kode_produk');
        $names = array_column($products, 'nama_produk');
        $categories = [];

        foreach ($products as $product) {
            $categories[$product['kategori']] = ($categories[$product['kategori']] ?? 0) + 1;
            $this->assertArrayNotHasKey('barcode', $product);
            $this->assertDoesNotMatchRegularExpression('/^TERA/i', $product['kode_produk']);
        }

        $this->assertCount(150, $products);
        $this->assertCount(150, array_unique($codes));
        $this->assertCount(150, array_unique($names));
        $this->assertSame([
            'Lampu Hannochs Sonic' => 15,
            'Lampu Gepeng / Downlight' => 34,
            'Lampu Hannochs Vario' => 10,
            'Lampu Hannochs Premier' => 11,
            'Gembok' => 6,
            'Kabel' => 36,
            'Stop Kontak' => 38,
        ], $categories);
    }

    public function test_owner_codes_keep_leading_zeroes_and_nonsequential_codes(): void
    {
        $codes = array_column($this->products(), 'kode_produk');

        foreach (['0001', '0097', '0415', '0416', '0417'] as $code) {
            $this->assertContains($code, $codes);
        }

        $this->assertSame('0001', $codes[0]);
        $this->assertSame('0147', $codes[149]);
    }

    public function test_owner_sample_product_values_match_the_workbook(): void
    {
        $products = collect($this->products())->keyBy('kode_produk');

        $this->assertSame([
            'kategori' => 'Lampu Hannochs Sonic',
            'nama_produk' => 'Lampu Hannochs Sonic 3W',
            'stok' => 86,
            'harga_beli' => 9500,
            'harga_jual' => 15000,
        ], array_intersect_key($products['0001'], array_flip([
            'kategori',
            'nama_produk',
            'stok',
            'harga_beli',
            'harga_jual',
        ])));

        $this->assertSame([
            'nama_produk' => 'Kabel NYM PRABA 3X4 50M',
            'stok' => 2,
            'harga_beli' => 1870000,
            'harga_jual' => 2200000,
            'merk' => 'Praba',
            'satuan' => 'roll',
        ], array_intersect_key($products['0091'], array_flip([
            'nama_produk',
            'stok',
            'harga_beli',
            'harga_jual',
            'merk',
            'satuan',
        ])));

        $this->assertSame([
            'nama_produk' => 'Stop Kontak Steele 4 lobang',
            'stok' => 74,
            'harga_beli' => 7500,
            'harga_jual' => 11000,
        ], array_intersect_key($products['0147'], array_flip([
            'nama_produk',
            'stok',
            'harga_beli',
            'harga_jual',
        ])));
    }

    public function test_blank_excel_categories_are_filled_down_in_the_dataset(): void
    {
        $products = collect($this->products())->keyBy('kode_produk');

        $this->assertSame('Lampu Hannochs Sonic', $products['0002']['kategori']);
        $this->assertSame('Lampu Gepeng / Downlight', $products['0012']['kategori']);
        $this->assertNull($products['0045']['merk']);
        $this->assertSame('meter', $products['0081']['satuan']);
    }
}
