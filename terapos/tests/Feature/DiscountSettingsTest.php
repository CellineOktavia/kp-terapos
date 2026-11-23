<?php

namespace Tests\Feature;

use App\Models\DiscountSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscountSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_view_and_update_the_discount_rule(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'name' => 'Owner Uji']);

        $this->actingAs($owner)->get(route('diskon.edit'))
            ->assertOk()
            ->assertSee('Kelola Diskon Penjualan')
            ->assertSee('Kelola Diskon')
            ->assertSee('value="300000"', false);

        $this->put(route('diskon.update'), [
            'is_active' => 1,
            'condition_operator' => 'gte',
            'minimum_purchase' => '350.000',
            'percentage' => '7.5',
        ])->assertRedirect(route('diskon.edit'));

        $this->assertDatabaseHas('discount_settings', [
            'id' => 1,
            'is_active' => true,
            'minimum_purchase' => 350000,
            'percentage' => '7.50',
            'condition_operator' => 'gte',
            'updated_by' => $owner->id,
        ]);
    }

    public function test_co_owner_cannot_view_or_change_discount_settings_or_see_sidebar_link(): void
    {
        $coOwner = User::factory()->create(['role' => 'co_owner']);

        $this->actingAs($coOwner)->get(route('diskon.edit'))->assertForbidden();
        $this->get(route('penjualan.create'))->assertOk()->assertDontSee('Kelola Diskon');
        $this->put(route('diskon.update'), [
            'is_active' => 0,
            'condition_operator' => 'gte',
            'minimum_purchase' => 0,
            'percentage' => 1,
        ])->assertForbidden();
        $this->assertSame(300000, DiscountSetting::current()->minimum_purchase);
    }

    public function test_inactive_discount_is_not_announced_on_the_sale_form(): void
    {
        DiscountSetting::current()->update(['is_active' => false]);

        $this->actingAs(User::factory()->create(['role' => 'owner']))
            ->get(route('penjualan.create'))
            ->assertOk()
            ->assertDontSee('id="sale-discount-info"', false)
            ->assertDontSee('Diskon 5% untuk pembelian');
    }

    public function test_discount_settings_reject_negative_minimum_and_out_of_range_percentages(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'owner']));
        $data = [
            'is_active' => 1,
            'condition_operator' => 'gt',
            'minimum_purchase' => '-300.000',
            'percentage' => 101,
        ];

        $this->from(route('diskon.edit'))->put(route('diskon.update'), $data)
            ->assertRedirect(route('diskon.edit'))
            ->assertSessionHasErrors(['minimum_purchase', 'percentage']);

        $this->assertSame(300000, DiscountSetting::current()->minimum_purchase);
    }
}
