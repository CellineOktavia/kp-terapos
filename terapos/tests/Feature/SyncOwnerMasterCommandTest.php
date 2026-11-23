<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncOwnerMasterCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_destructive_sync_command_is_disabled_without_changing_products(): void
    {
        $product = Product::factory()->create(['kode_produk' => 'snc3w-15']);

        $this->artisan('products:sync-owner-master', ['--apply' => true])
            ->expectsOutputToContain('This legacy command is disabled')
            ->assertExitCode(1);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'kode_produk' => 'snc3w-15',
            'aktif' => true,
        ]);
        $this->assertDatabaseCount('products', 1);
    }
}
