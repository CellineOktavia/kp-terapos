<?php

namespace Tests\Feature;

use App\Models\CrossSubsidyLedgerEntry;
use App\Models\CrossSubsidyProgram;
use App\Models\Penjualan;
use App\Models\Product;
use App\Models\User;
use App\Services\CrossSubsidyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CrossSubsidyTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_manage_the_program_but_co_owner_is_forbidden(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $source = Product::factory()->create(['harga_beli' => 5000, 'harga_jual' => 10000, 'stok' => 2]);
        $recipient = Product::factory()->create(['harga_beli' => 5000, 'harga_jual' => 10000, 'stok' => 2]);

        $this->actingAs($owner)->put(route('subsidi-silang.update'), $this->settings($source, $recipient))
            ->assertRedirect(route('subsidi-silang.edit'));
        $this->assertTrue(CrossSubsidyProgram::current()->fresh()->is_active);
        $this->get(route('subsidi-silang.edit'))
            ->assertOk()
            ->assertSee('Dana Terkumpul')
            ->assertSee('Subsidi Silang');
        $this->post(route('penjualan.store'), [
            'tanggal' => '2026-11-24',
            'items' => [['product_id' => $source->id, 'qty' => 1]],
            'bayar' => $source->harga_jual,
        ])->assertRedirect(route('penjualan.show', 1));
        $sale = Penjualan::query()->sole();
        $this->get(route('penjualan.show', $sale))
            ->assertOk()
            ->assertSee('Retur atau Pembatalan Penjualan');
        $this->get(route('penjualan.index'))->assertOk();
        $this->get(route('laporan.penjualan'))->assertOk();
        $this->get(route('owner.dashboard'))->assertOk();
        $this->get(route('dashboard.realtime'))->assertOk();

        $coOwner = User::factory()->create(['role' => 'co_owner']);
        $this->actingAs($coOwner)->get(route('subsidi-silang.edit'))->assertForbidden();
        $this->put(route('subsidi-silang.update'), $this->settings($source, $recipient))
            ->assertForbidden();
        $this->post(route('penjualan.cancel', $sale), [
            'request_token' => (string) Str::uuid(),
        ])->assertForbidden();
    }

    public function test_funding_uses_realized_margin_after_transaction_discount(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $source = Product::factory()->create([
            'harga_beli' => 200000,
            'harga_jual' => 400000,
            'stok' => 2,
        ]);
        $recipient = Product::factory()->create();
        CrossSubsidyProgram::current()->update($this->settings($source, $recipient));

        $this->actingAs($owner)->post(route('penjualan.store'), [
            'tanggal' => '2026-11-24',
            'items' => [['product_id' => $source->id, 'qty' => 1]],
            'bayar' => 380000,
        ])->assertRedirect(route('penjualan.show', 1));

        $contribution = CrossSubsidyLedgerEntry::query()->where('entry_type', 'contribution')->sole();
        $this->assertSame('90000.00', $contribution->amount);
        $this->assertSame('50.00', $contribution->rate_snapshot);
        $this->assertSame(90000.0, app(CrossSubsidyService::class)->balance(CrossSubsidyProgram::current()));
    }

    public function test_subsidy_is_limited_by_balance_and_return_cancellation_and_repeated_submit_adjust_ledger_once(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $source = Product::factory()->create([
            'harga_beli' => 50,
            'harga_jual' => 100,
            'stok' => 5,
        ]);
        $recipient = Product::factory()->create([
            'harga_beli' => 100,
            'harga_jual' => 200,
            'stok' => 5,
        ]);
        CrossSubsidyProgram::current()->update($this->settings($source, $recipient, [
            'subsidy_amount' => 20,
            'maximum_subsidy' => 50,
            'minimum_selling_price' => 180,
        ]));

        $this->actingAs($owner)->post(route('penjualan.store'), [
            'tanggal' => '2026-11-24',
            'items' => [['product_id' => $source->id, 'qty' => 1]],
            'bayar' => 100,
        ])->assertRedirect(route('penjualan.show', 1));
        $sourceSale = Penjualan::query()->findOrFail(1);

        $this->post(route('penjualan.store'), [
            'tanggal' => '2026-11-24',
            'items' => [['product_id' => $recipient->id, 'qty' => 2]],
            'cross_subsidy_amount' => 999999,
            'total' => 1,
            'bayar' => 375,
        ])->assertRedirect(route('penjualan.show', 2));
        $recipientSale = Penjualan::query()->findOrFail(2);
        $recipientDetail = $recipientSale->detailPenjualans()->sole();

        $this->assertSame('25.00', $recipientSale->cross_subsidy_amount);
        $this->assertSame('375.00', $recipientSale->total);
        $this->assertSame('12.50', $recipientDetail->cross_subsidy_per_unit);
        $this->assertSame(0.0, app(CrossSubsidyService::class)->balance(CrossSubsidyProgram::current()));

        $returnToken = (string) Str::uuid();
        $returnData = [
            'request_token' => $returnToken,
            'items' => [['detail_penjualan_id' => $recipientDetail->id, 'qty' => 1]],
        ];
        $this->post(route('penjualan.returns.store', $recipientSale), $returnData)
            ->assertRedirect(route('penjualan.show', $recipientSale));
        $this->post(route('penjualan.returns.store', $recipientSale), $returnData)
            ->assertRedirect(route('penjualan.show', $recipientSale));
        $this->assertSame(4, $recipient->fresh()->stok);
        $this->assertSame(12.5, app(CrossSubsidyService::class)->balance(CrossSubsidyProgram::current()));
        $this->assertDatabaseCount('penjualan_returns', 1);
        $this->assertSame('partially_returned', $recipientSale->fresh()->status);

        $this->post(route('penjualan.cancel', $sourceSale), [
            'request_token' => (string) Str::uuid(),
        ])->assertRedirect(route('penjualan.show', $sourceSale));
        $this->assertSame(5, $source->fresh()->stok);
        $this->assertSame(-12.5, app(CrossSubsidyService::class)->balance(CrossSubsidyProgram::current()));
        $this->assertSame('cancelled', $sourceSale->fresh()->status);

        $this->post(route('penjualan.store'), [
            'tanggal' => '2026-11-24',
            'items' => [['product_id' => $recipient->id, 'qty' => 1]],
            'bayar' => 200,
        ])->assertRedirect(route('penjualan.show', 3));
        $newSale = Penjualan::query()->findOrFail(3);
        $this->assertSame('0.00', $newSale->cross_subsidy_amount);
        $this->assertSame('200.00', $newSale->total);
    }

    public function test_transaction_subsidy_cap_limits_the_total_amount(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $source = Product::factory()->create(['harga_beli' => 50, 'harga_jual' => 100, 'stok' => 5]);
        $recipient = Product::factory()->create(['harga_beli' => 100, 'harga_jual' => 200, 'stok' => 5]);
        CrossSubsidyProgram::current()->update($this->settings($source, $recipient, [
            'subsidy_amount' => 20,
            'maximum_subsidy' => 30,
            'minimum_selling_price' => 180,
        ]));

        $this->actingAs($owner)->post(route('penjualan.store'), [
            'tanggal' => '2026-11-24',
            'items' => [['product_id' => $source->id, 'qty' => 4]],
            'bayar' => 400,
        ])->assertRedirect(route('penjualan.show', 1));
        $this->post(route('penjualan.store'), [
            'tanggal' => '2026-11-24',
            'items' => [['product_id' => $recipient->id, 'qty' => 2]],
            'bayar' => 370,
        ])->assertRedirect(route('penjualan.show', 2));

        $sale = Penjualan::query()->findOrFail(2);
        $this->assertSame('30.00', $sale->cross_subsidy_amount);
        $this->assertSame('370.00', $sale->total);
        $this->assertSame('15.00', $sale->detailPenjualans()->sole()->cross_subsidy_per_unit);
    }

    public function test_minimum_selling_price_is_not_crossed_by_subsidy(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $source = Product::factory()->create(['harga_beli' => 50, 'harga_jual' => 100, 'stok' => 5]);
        $recipient = Product::factory()->create(['harga_beli' => 100, 'harga_jual' => 200, 'stok' => 5]);
        CrossSubsidyProgram::current()->update($this->settings($source, $recipient, [
            'subsidy_amount' => 20,
            'maximum_subsidy' => 50,
            'minimum_selling_price' => 195,
        ]));

        $this->actingAs($owner)->post(route('penjualan.store'), [
            'tanggal' => '2026-11-24',
            'items' => [['product_id' => $source->id, 'qty' => 4]],
            'bayar' => 400,
        ])->assertRedirect(route('penjualan.show', 1));
        $this->post(route('penjualan.store'), [
            'tanggal' => '2026-11-24',
            'items' => [['product_id' => $recipient->id, 'qty' => 2]],
            'bayar' => 390,
        ])->assertRedirect(route('penjualan.show', 2));

        $sale = Penjualan::query()->findOrFail(2);
        $this->assertSame('10.00', $sale->cross_subsidy_amount);
        $this->assertSame('390.00', $sale->total);
        $this->assertSame('5.00', $sale->detailPenjualans()->sole()->cross_subsidy_per_unit);
    }

    public function test_inactive_program_never_applies_or_records_subsidy(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $source = Product::factory()->create(['harga_beli' => 50, 'harga_jual' => 100, 'stok' => 2]);
        $recipient = Product::factory()->create(['harga_beli' => 100, 'harga_jual' => 200, 'stok' => 2]);
        CrossSubsidyProgram::current()->update($this->settings($source, $recipient, ['is_active' => false]));

        $this->actingAs($owner)->post(route('penjualan.store'), [
            'tanggal' => '2026-11-24',
            'items' => [['product_id' => $recipient->id, 'qty' => 1]],
            'bayar' => 200,
        ])->assertRedirect(route('penjualan.show', 1));

        $sale = Penjualan::query()->sole();
        $this->assertSame('0.00', $sale->cross_subsidy_amount);
        $this->assertSame('200.00', $sale->total);
        $this->assertDatabaseCount('cross_subsidy_ledger_entries', 0);
    }

    public function test_full_cancellation_refunds_the_actual_discounted_total(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $product = Product::factory()->create([
            'harga_beli' => 300000,
            'harga_jual' => 400000,
            'stok' => 2,
        ]);
        $this->actingAs($owner)->post(route('penjualan.store'), [
            'tanggal' => '2026-11-24',
            'items' => [['product_id' => $product->id, 'qty' => 1]],
            'bayar' => 380000,
        ])->assertRedirect(route('penjualan.show', 1));
        $sale = Penjualan::query()->sole();

        $this->post(route('penjualan.cancel', $sale), [
            'request_token' => (string) Str::uuid(),
        ])->assertRedirect(route('penjualan.show', $sale));

        $this->assertSame('380000.00', $sale->fresh()->returns()->sole()->refund_amount);
        $this->assertSame(2, $product->fresh()->stok);
        $this->assertSame('cancelled', $sale->fresh()->status);
    }

    private function settings(Product $source, Product $recipient, array $overrides = []): array
    {
        return array_merge([
            'is_active' => 1,
            'source_product_id' => $source->id,
            'recipient_product_id' => $recipient->id,
            'allocation_percentage' => 50,
            'subsidy_amount' => 10,
            'maximum_subsidy' => 30,
            'minimum_selling_price' => 180,
        ], $overrides);
    }
}
