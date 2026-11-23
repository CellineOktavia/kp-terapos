<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_master_product_search_filters_partial_terms_case_insensitively_by_category_and_brand(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $lighting = Category::query()->create(['nama_kategori' => 'Lampu Hannochs Sonic']);
        $downlight = Category::query()->create(['nama_kategori' => 'Lampu Gepeng / Downlight']);
        $matching = Product::factory()->create([
            'kode_produk' => 'snc3w-15',
            'nama_produk' => 'Lampu Hannochs Sonic 3W',
            'merk' => 'Hannochs',
            'category_id' => $lighting->id,
        ]);
        Product::factory()->create([
            'kode_produk' => 'dlmts6w-50',
            'nama_produk' => 'Downlight Mitsuyama 6W',
            'merk' => 'Mitsuyama',
            'category_id' => $downlight->id,
        ]);

        $this->actingAs($owner)->get(route('produk.index', [
            'search' => 'SONIC',
            'category_id' => $lighting->id,
            'merk' => 'hAnNoChS',
        ]))->assertOk()
            ->assertSee($matching->kode_produk)
            ->assertDontSee('dlmts6w-50');
    }

    public function test_product_creation_suggests_existing_active_products_and_rejects_duplicate_names(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $product = Product::factory()->create([
            'kode_produk' => 'snc3w-15',
            'nama_produk' => 'Lampu Hannochs Sonic 3W',
        ]);
        Product::factory()->create(['kode_produk' => 'old-inactive-1', 'aktif' => false]);

        $this->actingAs($owner)->get(route('produk.create'))
            ->assertOk()
            ->assertSee('product-suggestions', false)
            ->assertSee($product->kode_produk)
            ->assertDontSee('old-inactive-1');

        $this->post(route('produk.store'), [
            'kode_dasar' => 'lampusonic3wbaru',
            'nama_produk' => 'lampu hannochs sonic 3W',
            'category_id' => $product->category_id,
            'merk' => 'Hannochs',
            'satuan' => 'pcs',
            'stok_minimum' => 5,
            'harga_beli' => 9000,
            'harga_jual' => 15000,
        ])->assertSessionHasErrors('nama_produk');

        $this->assertSame(2, Product::query()->count());
    }

    public function test_sale_and_receipt_search_catalogs_include_only_active_products_with_search_fields(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $category = Category::query()->create(['nama_kategori' => 'Lampu Hannochs Sonic']);
        $active = Product::factory()->create([
            'kode_produk' => 'snc3w-15',
            'nama_produk' => 'Lampu Hannochs Sonic 3W',
            'category_id' => $category->id,
            'merk' => 'Hannochs',
            'stok' => 3,
        ]);
        Product::factory()->create([
            'kode_produk' => 'inactive-1',
            'aktif' => false,
        ]);

        $this->actingAs($owner)->get(route('penjualan.create'))
            ->assertOk()
            ->assertSee('data-code="' . $active->kode_produk . '"', false)
            ->assertSee('data-brand="Hannochs"', false)
            ->assertSee('data-category="Lampu Hannochs Sonic"', false)
            ->assertDontSee('inactive-1');

        $this->get(route('penerimaan.create'))
            ->assertOk()
            ->assertSee('receipt-product-results', false)
            ->assertSee('data-code="' . $active->kode_produk . '"', false)
            ->assertSee('data-brand="Hannochs"', false)
            ->assertSee('data-category="Lampu Hannochs Sonic"', false)
            ->assertSee('data-stock="3"', false)
            ->assertSee('data-unit="' . $active->satuan . '"', false)
            ->assertDontSee('inactive-1');
    }
}
