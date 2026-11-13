<?php

namespace Tests\Feature;

use App\Models\Penjualan;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenjualanDiscountTest extends TestCase
{
    use RefreshDatabase;

    public function test_subtotal_of_exactly_three_hundred_thousand_has_no_discount(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'owner']));
        $product = Product::factory()->create([
            'harga_beli' => 250000,
            'harga_jual' => 300000,
            'stok' => 2,
        ]);

        $response = $this->post(route('penjualan.store'), [
            'tanggal' => '2026-11-13',
            'items' => [['product_id' => $product->id, 'qty' => 1]],
            'bayar' => 300000,
        ]);

        $response->assertRedirect(route('penjualan.index'));
        $sale = Penjualan::query()->sole();
        $this->assertSame('0.00', $sale->diskon_persen);
        $this->assertSame('0.00', $sale->diskon_nominal);
        $this->assertSame('300000.00', $sale->total);
    }

    public function test_subtotal_above_three_hundred_thousand_receives_five_percent_discount(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'owner']));
        $product = Product::factory()->create([
            'harga_beli' => 200000,
            'harga_jual' => 300001,
            'stok' => 2,
        ]);

        $response = $this->post(route('penjualan.store'), [
            'tanggal' => '2026-11-13',
            'items' => [['product_id' => $product->id, 'qty' => 1]],
            'bayar' => 285000.95,
        ]);

        $response->assertRedirect(route('penjualan.index'));
        $sale = Penjualan::query()->sole();
        $this->assertSame('5.00', $sale->diskon_persen);
        $this->assertSame('15000.05', $sale->diskon_nominal);
        $this->assertSame('285000.95', $sale->total);
    }

    public function test_four_hundred_thousand_subtotal_has_twenty_thousand_discount_and_correct_change(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'owner']));
        $product = Product::factory()->create([
            'harga_beli' => 300000,
            'harga_jual' => 400000,
            'stok' => 2,
        ]);

        $response = $this->post(route('penjualan.store'), [
            'tanggal' => '2026-11-13',
            'items' => [['product_id' => $product->id, 'qty' => 1]],
            'bayar' => 400000,
            'diskon_persen' => 0,
            'diskon_nominal' => 0,
            'total' => 400000,
        ]);

        $response->assertRedirect(route('penjualan.index'));
        $sale = Penjualan::query()->sole();
        $this->assertSame(400000.0, (float) $sale->detailPenjualans->sum('subtotal'));
        $this->assertSame('5.00', $sale->diskon_persen);
        $this->assertSame('20000.00', $sale->diskon_nominal);
        $this->assertSame('380000.00', $sale->total);
        $this->assertSame('400000.00', $sale->bayar);
        $this->assertSame('20000.00', $sale->kembalian);
    }

    public function test_payment_below_discounted_total_is_rejected_and_stock_is_unchanged(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'owner']));
        $product = Product::factory()->create([
            'harga_beli' => 300000,
            'harga_jual' => 400000,
            'stok' => 2,
        ]);

        $response = $this->from(route('penjualan.create'))
            ->post(route('penjualan.store'), [
                'tanggal' => '2026-11-13',
                'items' => [['product_id' => $product->id, 'qty' => 1]],
                'bayar' => 379999,
            ]);

        $response->assertRedirect(route('penjualan.create'));
        $response->assertSessionHasErrors('bayar');
        $this->assertDatabaseCount('penjualans', 0);
        $this->assertSame(2, $product->fresh()->stok);
    }
}
