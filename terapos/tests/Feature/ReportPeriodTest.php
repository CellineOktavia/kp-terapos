<?php

namespace Tests\Feature;

use App\Models\DetailPenerimaanBarang;
use App\Models\DetailPenjualan;
use App\Models\PenerimaanBarang;
use App\Models\Penjualan;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportPeriodTest extends TestCase
{
    use RefreshDatabase;

    public function test_sales_report_uses_custom_date_range_and_net_totals(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $product = Product::factory()->create();
        $this->createSale($owner, $product, 'TRX-IN-RANGE', '2026-01-15', 95000, 5000);
        $this->createSale($owner, $product, 'TRX-OUT-OF-RANGE', '2026-02-15', 190000, 10000);

        $response = $this->actingAs($owner)->get(route('laporan.penjualan', [
            'period' => 'custom',
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-31',
        ]));

        $response->assertOk()
            ->assertSee('TRX-IN-RANGE')
            ->assertDontSee('TRX-OUT-OF-RANGE')
            ->assertSee('Rp 5.000');
    }

    public function test_receipt_report_uses_custom_date_range_and_recorded_buy_price(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $product = Product::factory()->create(['harga_beli' => 999999]);
        $receipt = PenerimaanBarang::create([
            'nomor_penerimaan' => 'TRM-IN-RANGE',
            'user_id' => $owner->id,
            'tanggal' => '2026-01-15',
        ]);
        DetailPenerimaanBarang::create([
            'penerimaan_barang_id' => $receipt->id,
            'product_id' => $product->id,
            'qty' => 3,
            'harga_beli' => 16000,
        ]);
        $outside = PenerimaanBarang::create([
            'nomor_penerimaan' => 'TRM-OUT-OF-RANGE',
            'user_id' => $owner->id,
            'tanggal' => '2026-02-15',
        ]);
        DetailPenerimaanBarang::create([
            'penerimaan_barang_id' => $outside->id,
            'product_id' => $product->id,
            'qty' => 1,
            'harga_beli' => 17500,
        ]);

        $response = $this->actingAs($owner)->get(route('laporan.barang-masuk', [
            'period' => 'custom',
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-31',
        ]));

        $response->assertOk()
            ->assertSee('TRM-IN-RANGE')
            ->assertDontSee('TRM-OUT-OF-RANGE')
            ->assertSee('Rp 16.000')
            ->assertSee('Rp 48.000')
            ->assertDontSee('Rp 999.999');
    }

    private function createSale(User $user, Product $product, string $number, string $date, int $total, int $discount): void
    {
        $sale = Penjualan::create([
            'nomor_penjualan' => $number,
            'user_id' => $user->id,
            'tanggal' => $date,
            'total' => $total,
            'diskon_persen' => $discount ? 5 : 0,
            'diskon_nominal' => $discount,
            'bayar' => $total,
            'kembalian' => 0,
        ]);

        DetailPenjualan::create([
            'penjualan_id' => $sale->id,
            'product_id' => $product->id,
            'qty' => 1,
            'harga' => $total + $discount,
            'subtotal' => $total + $discount,
        ]);
    }
}
