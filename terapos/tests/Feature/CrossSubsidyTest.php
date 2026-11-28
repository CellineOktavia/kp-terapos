<?php

namespace Tests\Feature;

use App\Models\Penjualan;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrossSubsidyTest extends TestCase
{
    use RefreshDatabase;

    public function test_subsidy_settings_route_is_not_available(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $this->actingAs($owner)
            ->get('/subsidi-silang')
            ->assertNotFound();

        $this->assertFalse(\Illuminate\Support\Facades\Route::has('subsidi-silang.edit'));
        $this->assertFalse(\Illuminate\Support\Facades\Route::has('subsidi-silang.update'));
    }

    public function test_sale_does_not_add_subsidy_and_price_below_cost_is_allowed(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $product = Product::factory()->create([
            'aktif' => true,
            'harga_beli' => 30000,
            'harga_jual' => 27000,
            'stok' => 5,
        ]);

        $this->actingAs($owner)->post(route('penjualan.store'), [
            'tanggal' => '2026-11-24',
            'request_token' => (string) \Illuminate\Support\Str::uuid(),
            'items' => [['product_id' => $product->id, 'qty' => 1]],
            'bayar' => 27000,
        ])->assertRedirect();

        $sale = Penjualan::query()->sole();
        $this->assertSame('27000.00', $sale->total);
        $this->assertSame('0.00', $sale->cross_subsidy_amount);
    }
}
