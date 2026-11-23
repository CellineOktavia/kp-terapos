<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncOwnerMasterCommandTest extends TestCase
{
    use RefreshDatabase;

    private const EXACT_MAPPINGS = [
        'TERA0006' => ['0002', 'Lampu Hannochs Sonic 5W'],
        'TERA0007' => ['0001', 'Lampu Hannochs Sonic 3W'],
        'TERA0008' => ['0003', 'Lampu Hannochs Sonic 7W'],
        'TERA0009' => ['0004', 'Lampu Hannochs Sonic 9W'],
        'TERA0013' => ['0007', 'Lampu Hannochs Sonic 20W'],
    ];

    public function test_dry_run_does_not_change_products(): void
    {
        $category = $this->createOwnerCategories()['Lampu Hannochs Sonic'];
        $this->createExactLegacyProducts($category);
        $this->createLegacyProduct($category, 'TERA0151', 'Legacy dengan histori');
        $this->createLegacyProduct($category, 'TERA0152', 'Legacy tanpa histori');

        $before = Product::query()->orderBy('id')->get()->map(fn (Product $product) => [
            $product->id,
            $product->kode_produk,
            $product->nama_produk,
            $product->aktif,
        ])->all();

        $this->artisan('products:sync-owner-master', ['--dry-run' => true])
            ->expectsOutputToContain('DRY RUN only')
            ->assertExitCode(0);

        $after = Product::query()->orderBy('id')->get()->map(fn (Product $product) => [
            $product->id,
            $product->kode_produk,
            $product->nama_produk,
            $product->aktif,
        ])->all();

        $this->assertSame($before, $after);
        $this->assertCount(7, Product::all());
    }

    public function test_apply_reuses_exact_ids_preserves_history_and_produces_150_active_owner_products(): void
    {
        $categories = $this->createOwnerCategories();
        $this->createExactLegacyProducts($categories['Lampu Hannochs Sonic']);
        $exactIds = Product::query()
            ->whereIn('kode_produk', array_keys(self::EXACT_MAPPINGS))
            ->pluck('id', 'kode_produk')
            ->all();
        $historical = $this->createLegacyProduct(
            $categories['Gembok'],
            'TERA0151',
            'Legacy berhistori'
        );
        $movement = StockMovement::query()->create([
            'product_id' => $historical->id,
            'jenis' => 'masuk',
            'qty' => 3,
            'keterangan' => 'Histori harus tetap terhubung',
        ]);
        $noHistory = $this->createLegacyProduct(
            $categories['Gembok'],
            'TERA0152',
            'Legacy tanpa histori'
        );

        $this->artisan('products:sync-owner-master', ['--apply' => true])
            ->expectsOutputToContain('Owner master synchronization committed successfully.')
            ->assertExitCode(0);

        foreach (self::EXACT_MAPPINGS as $oldCode => [$ownerCode, $name]) {
            $this->assertDatabaseHas('products', [
                'id' => $exactIds[$oldCode],
                'kode_produk' => $ownerCode,
                'nama_produk' => $name,
                'aktif' => true,
            ]);
        }

        $this->assertDatabaseHas('products', ['id' => $historical->id, 'aktif' => false]);
        $this->assertDatabaseHas('stock_movements', [
            'id' => $movement->id,
            'product_id' => $historical->id,
        ]);
        $this->assertDatabaseMissing('products', ['id' => $noHistory->id]);
        $this->assertSame(150, Product::query()->where('aktif', true)->count());
        $this->assertSame(1, Product::query()->where('aktif', false)->count());
        $this->assertSame(151, Product::query()->count());
        $this->assertFalse(\Illuminate\Support\Facades\Schema::hasColumn('products', 'barcode'));
    }

    public function test_apply_creates_missing_owner_categories_inside_the_sync(): void
    {
        $category = Category::query()->create(['nama_kategori' => 'Lampu Hannochs Sonic']);
        $this->createExactLegacyProducts($category);

        $this->artisan('products:sync-owner-master', ['--apply' => true])
            ->assertExitCode(0);

        $this->assertSame(7, Category::query()->count());
        $this->assertSame(150, Product::query()->where('aktif', true)->count());
    }

    private function createOwnerCategories(): array
    {
        $names = [
            'Lampu Hannochs Sonic',
            'Lampu Gepeng / Downlight',
            'Lampu Hannochs Vario',
            'Lampu Hannochs Premier',
            'Gembok',
            'Kabel',
            'Stop Kontak',
        ];
        $categories = [];

        foreach ($names as $name) {
            $categories[$name] = Category::query()->create(['nama_kategori' => $name]);
        }

        return $categories;
    }

    private function createExactLegacyProducts(Category $category): void
    {
        foreach (self::EXACT_MAPPINGS as $oldCode => [$ownerCode, $name]) {
            Product::factory()->create([
                'kode_produk' => $oldCode,
                'category_id' => $category->id,
                'nama_produk' => $name,
                'merk' => 'Hannochs',
                'satuan' => 'pcs',
            ]);
        }
    }

    private function createLegacyProduct(Category $category, string $code, string $name): Product
    {
        return Product::factory()->create([
            'kode_produk' => $code,
            'category_id' => $category->id,
            'nama_produk' => $name,
            'merk' => null,
            'satuan' => 'pcs',
        ]);
    }
}
