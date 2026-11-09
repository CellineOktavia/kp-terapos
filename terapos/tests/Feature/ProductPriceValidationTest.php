<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPriceValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_rejects_sell_price_not_greater_than_buy_price(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'owner']));

        $response = $this->from(route('produk.create'))
            ->post(route('produk.store'), [
                'nama_produk' => 'Lampu Rumah',
                'satuan' => 'pcs',
                'stok_minimum' => 10,
                'harga_beli' => 50000,
                'harga_jual' => 50000,
            ]);

        $response->assertRedirect(route('produk.create'));
        $response->assertSessionHasErrors([
            'harga_jual' => 'Harga jual harus lebih besar dari harga beli.',
        ]);
        $this->assertDatabaseCount('products', 0);
    }

    public function test_update_rejects_sell_price_below_buy_price(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'co_owner']));
        $product = Product::factory()->create([
            'harga_beli' => 50000,
            'harga_jual' => 60000,
        ]);

        $response = $this->from(route('produk.edit', $product))
            ->put(route('produk.update', $product), [
                'nama_produk' => $product->nama_produk,
                'satuan' => $product->satuan,
                'stok_minimum' => $product->stok_minimum,
                'harga_beli' => 50000,
                'harga_jual' => 45000,
            ]);

        $response->assertRedirect(route('produk.edit', $product));
        $response->assertSessionHasErrors([
            'harga_jual' => 'Harga jual harus lebih besar dari harga beli.',
        ]);
        $this->assertEquals(60000, $product->fresh()->harga_jual);
    }
}
