<?php

namespace Tests\Feature;

use App\Models\PenerimaanBarang;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenerimaanBarangTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_catalog_has_150_valid_household_items(): void
    {
        $catalog = ProductSeeder::catalog();

        $this->assertCount(150, $catalog);

        foreach ($catalog as $item) {
            $this->assertGreaterThan($item['harga_beli'], $item['harga_jual']);
            $this->assertGreaterThanOrEqual(1, $item['stok']);
            $this->assertGreaterThanOrEqual(1, $item['stok_minimum']);
            $this->assertDoesNotMatchRegularExpression(
                '/\b(aki|ban|oli|busi|motor|mobil|truck|truk|sparepart|onderdil)\b|kampas\s+rem|filter\s+(oli|udara)/i',
                $item['name']
            );
        }
    }

    public function test_owner_receipt_increments_stock_and_records_movements(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $firstProduct = Product::factory()->create(['stok' => 2]);
        $secondProduct = Product::factory()->create(['stok' => 7]);

        PenerimaanBarang::create([
            'nomor_penerimaan' => 'TRM000004',
            'user_id' => $owner->id,
            'tanggal' => '2026-11-12',
        ]);

        $response = $this->actingAs($owner)->post(route('penerimaan.store'), [
            'tanggal' => '2026-11-12',
            'keterangan' => 'Barang masuk',
            'items' => [
                ['product_id' => $firstProduct->id, 'qty' => 10],
                ['product_id' => $secondProduct->id, 'qty' => 5],
            ],
        ]);

        $response->assertRedirect(route('penerimaan.index'));
        $this->assertDatabaseHas('penerimaan_barangs', [
            'nomor_penerimaan' => 'TRM000005',
            'user_id' => $owner->id,
        ]);
        $this->assertSame(12, $firstProduct->fresh()->stok);
        $this->assertSame(12, $secondProduct->fresh()->stok);
        $this->assertDatabaseCount('detail_penerimaan_barangs', 2);
        $this->assertDatabaseCount('stock_movements', 2);
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $firstProduct->id,
            'jenis' => 'masuk',
            'qty' => 10,
            'keterangan' => 'Penerimaan Barang TRM000005',
        ]);
    }

    public function test_co_owner_cannot_create_a_receipt(): void
    {
        $coOwner = User::factory()->create(['role' => 'co_owner']);
        $product = Product::factory()->create(['stok' => 2]);

        $response = $this->actingAs($coOwner)->post(route('penerimaan.store'), [
            'tanggal' => '2026-11-12',
            'items' => [
                ['product_id' => $product->id, 'qty' => 3],
            ],
        ]);

        $response->assertForbidden();
        $this->assertSame(2, $product->fresh()->stok);
        $this->assertDatabaseCount('penerimaan_barangs', 0);
        $this->assertDatabaseCount('stock_movements', 0);
    }
}
