<?php

namespace Tests\Feature;

use App\Models\Penjualan;
use App\Models\DiscountSetting;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
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

        $response->assertRedirect(route('penjualan.show', 1));
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

        $response->assertRedirect(route('penjualan.show', 1));
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

        $response->assertRedirect(route('penjualan.show', 1));
        $sale = Penjualan::query()->sole();
        $this->assertSame(400000.0, (float) $sale->detailPenjualans->sum('subtotal'));
        $this->assertSame('5.00', $sale->diskon_persen);
        $this->assertSame('20000.00', $sale->diskon_nominal);
        $this->assertSame('380000.00', $sale->total);
        $this->assertSame('400000.00', $sale->bayar);
        $this->assertSame('20000.00', $sale->kembalian);
        $this->assertSame(1, $product->fresh()->stok);
    }

    public function test_gte_operator_applies_discount_at_the_exact_minimum(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'owner']));
        DiscountSetting::current()->update(['condition_operator' => 'gte']);
        $product = Product::factory()->create(['harga_jual' => 300000, 'stok' => 2]);

        $this->post(route('penjualan.store'), [
            'tanggal' => '2026-11-13',
            'items' => [['product_id' => $product->id, 'qty' => 1]],
            'bayar' => 285000,
        ])->assertRedirect(route('penjualan.show', 1));

        $sale = Penjualan::query()->sole();
        $this->assertSame('5.00', $sale->diskon_persen);
        $this->assertSame('15000.00', $sale->diskon_nominal);
        $this->assertTrue($sale->diskon_aktif);
        $this->assertSame(300000, $sale->diskon_minimum_pembelian);
        $this->assertSame('gte', $sale->diskon_operator);
    }

    public function test_inactive_discount_is_not_applied(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'owner']));
        DiscountSetting::current()->update(['is_active' => false]);
        $product = Product::factory()->create(['harga_jual' => 400000, 'stok' => 2]);

        $this->post(route('penjualan.store'), [
            'tanggal' => '2026-11-13',
            'items' => [['product_id' => $product->id, 'qty' => 1]],
            'bayar' => 400000,
        ])->assertRedirect(route('penjualan.show', 1));

        $sale = Penjualan::query()->sole();
        $this->assertSame('0.00', $sale->diskon_persen);
        $this->assertSame('0.00', $sale->diskon_nominal);
        $this->assertFalse($sale->diskon_aktif);
    }

    public function test_changed_settings_only_affect_new_sales_and_each_sale_keeps_its_snapshot(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'owner']));
        $setting = DiscountSetting::current();
        $product = Product::factory()->create(['harga_jual' => 400000, 'stok' => 3]);

        $this->post(route('penjualan.store'), [
            'tanggal' => '2026-11-13',
            'items' => [['product_id' => $product->id, 'qty' => 1]],
            'bayar' => 380000,
        ])->assertRedirect(route('penjualan.show', 1));

        $setting->update([
            'minimum_purchase' => 0,
            'percentage' => 10,
            'condition_operator' => 'gte',
        ]);
        $this->post(route('penjualan.store'), [
            'tanggal' => '2026-11-13',
            'items' => [['product_id' => $product->id, 'qty' => 1]],
            'bayar' => 360000,
        ])->assertRedirect(route('penjualan.show', 2));

        $sales = Penjualan::query()->orderBy('id')->get();
        $this->assertSame('5.00', $sales[0]->diskon_persen);
        $this->assertSame('300000', (string) $sales[0]->diskon_minimum_pembelian);
        $this->assertSame('gt', $sales[0]->diskon_operator);
        $this->assertSame('10.00', $sales[1]->diskon_persen);
        $this->assertSame(0, $sales[1]->diskon_minimum_pembelian);
        $this->assertSame('gte', $sales[1]->diskon_operator);
    }

    public function test_repeated_sale_submission_with_the_same_token_is_processed_once(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'owner']));
        $product = Product::factory()->create(['harga_jual' => 10000, 'stok' => 2]);
        $data = [
            'tanggal' => '2026-11-13',
            'request_token' => (string) Str::uuid(),
            'items' => [['product_id' => $product->id, 'qty' => 1]],
            'bayar' => 10000,
        ];

        $this->post(route('penjualan.store'), $data)->assertRedirect(route('penjualan.show', 1));
        $this->post(route('penjualan.store'), $data)->assertRedirect(route('penjualan.show', 1));

        $this->assertDatabaseCount('penjualans', 1);
        $this->assertSame(1, $product->fresh()->stok);
        $this->assertDatabaseCount('stock_movements', 1);
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

    public function test_inactive_product_cannot_be_sold(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'owner']));
        $product = Product::factory()->create(['aktif' => false, 'stok' => 5]);

        $response = $this->post(route('penjualan.store'), [
            'tanggal' => '2026-11-13',
            'items' => [['product_id' => $product->id, 'qty' => 1]],
            'bayar' => 100000,
        ]);

        $response->assertSessionHasErrors('items');
        $this->assertSame(5, $product->fresh()->stok);
        $this->assertDatabaseCount('penjualans', 0);
    }

    public function test_sale_note_pdf_is_downloadable(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'owner']));
        $product = Product::factory()->create([
            'nama_produk' => 'Lampu Uji',
            'harga_jual' => 10000,
            'stok' => 2,
        ]);
        $this->post(route('penjualan.store'), [
            'tanggal' => '2026-11-13',
            'items' => [['product_id' => $product->id, 'qty' => 1]],
            'bayar' => 10000,
        ]);
        $sale = Penjualan::query()->sole();

        $response = $this->get(route('penjualan.nota', $sale));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }
}
