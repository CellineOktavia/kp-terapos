<?php

namespace Tests\Feature;

use Tests\TestCase;

class OwnerProductMasterDatasetTest extends TestCase
{
    private function products(): array
    {
        return require database_path('seeders/data/owner_product_master.php');
    }

    public function test_dataset_is_pinned_to_the_owner_workbook_version(): void
    {
        $source = database_path('seeders/data/Data_Master_Toko_Terang_Abadi_v4.xlsx');

        $this->assertFileExists($source);
        $this->assertSame(
            'da278a4ed242047b97a15f9b8a1be2ff81d4615075d901edaf2a3a202cec5748',
            hash_file('sha256', $source)
        );
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
            $this->assertMatchesRegularExpression('/^[a-z0-9]+-[0-9]+(?:\.[0-9]+)?$/', $product['kode_produk']);
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

    public function test_owner_codes_match_the_v4_format_and_keep_decimal_suffixes(): void
    {
        $codes = array_column($this->products(), 'kode_produk');

        foreach (['snc3w-15', 'snc5w-17', 'dlmts6w-50', 'nya15m-7.5', 'nym340r50-2200', 'skgpg2l-6.5', 'sklyl4l-27'] as $code) {
            $this->assertContains($code, $codes);
        }

        $this->assertSame('snc3w-15', $codes[0]);
        $this->assertSame('skstl4l-11', $codes[149]);
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
        ], array_intersect_key($products['snc3w-15'], array_flip([
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
        ], array_intersect_key($products['nym340r50-2200'], array_flip([
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
        ], array_intersect_key($products['skstl4l-11'], array_flip([
            'nama_produk',
            'stok',
            'harga_beli',
            'harga_jual',
        ])));
    }

    public function test_blank_excel_categories_are_filled_down_in_the_dataset(): void
    {
        $products = collect($this->products())->keyBy('kode_produk');

        $this->assertSame('Lampu Hannochs Sonic', $products['snc5w-17']['kategori']);
        $this->assertSame('Lampu Gepeng / Downlight', $products['dlebr9w-55']['kategori']);
    }
}
