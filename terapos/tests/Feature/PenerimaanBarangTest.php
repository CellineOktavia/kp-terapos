<?php

namespace Tests\Feature;

use App\Models\PenerimaanBarang;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenerimaanBarangTest extends TestCase
{
    use RefreshDatabase;

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
                ['product_id' => $firstProduct->id, 'qty' => 10, 'harga_beli' => $firstProduct->harga_beli],
                ['product_id' => $secondProduct->id, 'qty' => 5, 'harga_beli' => $secondProduct->harga_beli + 100],
            ],
        ]);

        $receiptId = PenerimaanBarang::where('nomor_penerimaan', 'TRM000005')->value('id');
        $response->assertRedirect(route('penerimaan.show', $receiptId));
        $response->assertSessionHas('success', 'Penerimaan TRM000005 berhasil. Stok 2 barang telah diperbarui.');
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
        $this->assertDatabaseHas('detail_penerimaan_barangs', [
            'product_id' => $firstProduct->id,
            'harga_beli' => $firstProduct->harga_beli,
            'stok_sebelum' => 2,
            'stok_sesudah' => 12,
        ]);
        $this->assertSame((float) $secondProduct->harga_beli + 100, (float) $secondProduct->fresh()->harga_beli);
        $this->assertDatabaseHas('product_price_histories', [
            'product_id' => $secondProduct->id,
            'jenis_harga' => 'beli',
            'harga_lama' => $secondProduct->harga_beli,
            'harga_baru' => $secondProduct->harga_beli + 100,
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

    public function test_inactive_product_cannot_be_received(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $product = Product::factory()->create(['aktif' => false, 'stok' => 3]);

        $response = $this->actingAs($owner)->post(route('penerimaan.store'), [
            'tanggal' => '2026-11-12',
            'items' => [[
                'product_id' => $product->id,
                'qty' => 2,
                'harga_beli' => $product->harga_beli,
            ]],
        ]);

        $response->assertSessionHasErrors('items');
        $this->assertSame(3, $product->fresh()->stok);
        $this->assertDatabaseCount('penerimaan_barangs', 0);
    }
}
