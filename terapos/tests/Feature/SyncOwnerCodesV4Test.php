<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPriceHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SyncOwnerCodesV4Test extends TestCase
{
    use RefreshDatabase;

    public function test_dry_run_maps_all_v4_codes_without_changing_ids_or_product_history(): void
    {
        $dataset = require database_path('seeders/data/owner_product_master.php');
        $owner = User::factory()->create(['role' => 'owner']);
        $categories = [];

        foreach (array_unique(array_column($dataset, 'kategori')) as $name) {
            $categories[$name] = Category::query()->create(['nama_kategori' => $name])->id;
        }

        $idsByName = [];
        foreach ($dataset as $index => $product) {
            $created = Product::query()->create([
                'kode_produk' => 'legacy-' . ($index + 1),
                'category_id' => $categories[$product['kategori']],
                'nama_produk' => $product['nama_produk'],
                'merk' => $product['merk'],
                'satuan' => $product['satuan'],
                'stok' => $product['stok'],
                'stok_minimum' => 5,
                'harga_beli' => $product['harga_beli'],
                'harga_jual' => $product['harga_jual'],
                'aktif' => true,
            ]);
            $idsByName[$product['nama_produk']] = $created->id;
        }

        ProductPriceHistory::query()->create([
            'product_id' => $idsByName['Lampu Hannochs Sonic 3W'],
            'jenis_harga' => 'jual',
            'harga_lama' => 14000,
            'harga_baru' => 15000,
            'user_id' => $owner->id,
            'keterangan' => 'Riwayat untuk uji sinkronisasi',
        ]);

        $result = Artisan::call('products:sync-owner-codes-v4', ['--dry-run' => true]);
        $output = Artisan::output();

        $this->assertSame(0, $result);
        $this->assertStringContainsString('snc3w-15', $output);
        $this->assertStringContainsString('sklyl4l-27', $output);
        $this->assertSame(150, substr_count($output, 'legacy-'));
        $this->assertDatabaseCount('products', 150);
        $this->assertDatabaseCount('product_price_histories', 1);
        $this->assertSame(
            $idsByName['Lampu Hannochs Sonic 3W'],
            Product::query()->where('nama_produk', 'Lampu Hannochs Sonic 3W')->value('id')
        );
        $this->assertDatabaseHas('products', [
            'id' => $idsByName['Lampu Hannochs Sonic 3W'],
            'kode_produk' => 'legacy-1',
        ]);
        $this->assertDatabaseHas('product_price_histories', [
            'product_id' => $idsByName['Lampu Hannochs Sonic 3W'],
            'user_id' => $owner->id,
        ]);
    }
}
