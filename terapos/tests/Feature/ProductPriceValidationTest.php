<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductPriceValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_barcode_is_removed_from_the_active_product_schema(): void
    {
        $this->assertFalse(Schema::hasColumn('products', 'barcode'));
    }

    public function test_create_allows_sell_price_below_buy_price(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'owner']));

        $response = $this->post(route('produk.store'), [
            'nama_produk' => 'Lampu Uji',
            'satuan' => 'pcs',
            'stok_minimum' => 5,
            'harga_beli' => '1.870.000',
            'harga_jual' => '1.800.000',
        ]);

        $response->assertRedirect(route('produk.index'));
        $this->assertDatabaseHas('products', [
            'kode_produk' => '0001',
            'harga_beli' => 1870000,
            'harga_jual' => 1800000,
        ]);
    }

    public function test_owner_update_allows_subsidy_crossing_and_records_sale_price_history(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $this->actingAs($owner);
        $product = Product::factory()->create([
            'harga_beli' => 50000,
            'harga_jual' => 60000,
        ]);

        $response = $this->put(route('produk.update', $product), [
            'nama_produk' => $product->nama_produk,
            'satuan' => $product->satuan,
            'stok_minimum' => $product->stok_minimum,
            'harga_beli' => '50.000',
            'harga_jual' => '45.000',
        ]);

        $response->assertRedirect(route('produk.show', $product));
        $response->assertSessionHas('success', fn (string $message) => str_contains($message, 'subsidi silang'));
        $this->assertSame(45000.0, (float) $product->fresh()->harga_jual);
        $this->assertDatabaseHas('product_price_histories', [
            'product_id' => $product->id,
            'jenis_harga' => 'jual',
            'harga_lama' => 60000,
            'harga_baru' => 45000,
            'user_id' => $owner->id,
        ]);
    }

    public function test_co_owner_cannot_change_sale_price(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'co_owner']));
        $product = Product::factory()->create(['harga_beli' => 50000, 'harga_jual' => 60000]);

        $response = $this->put(route('produk.update', $product), [
            'nama_produk' => $product->nama_produk,
            'satuan' => $product->satuan,
            'stok_minimum' => $product->stok_minimum,
            'harga_beli' => $product->harga_beli,
            'harga_jual' => 45000,
        ]);

        $response->assertForbidden();
        $this->assertSame(60000.0, (float) $product->fresh()->harga_jual);
    }

    public function test_product_with_stock_history_is_deactivated_instead_of_deleted(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $product = Product::factory()->create();
        StockMovement::create([
            'product_id' => $product->id,
            'jenis' => 'masuk',
            'qty' => 1,
            'keterangan' => 'Riwayat uji',
        ]);

        $this->actingAs($owner)->delete(route('produk.destroy', $product))
            ->assertRedirect(route('produk.index'));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'aktif' => false]);
    }

    public function test_new_product_code_preserves_four_digit_owner_code_sequence(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        Product::factory()->create(['kode_produk' => '0415']);
        Product::factory()->create(['kode_produk' => '0417']);

        $this->actingAs($owner)->get(route('produk.create'))
            ->assertOk()
            ->assertSee('value="0418"', false);
    }
}
