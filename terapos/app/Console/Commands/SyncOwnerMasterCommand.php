<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class SyncOwnerMasterCommand extends Command
{
    protected $signature = 'products:sync-owner-master
        {--dry-run : Validate and report the proposed synchronization without writing}
        {--apply : Apply the synchronization in a single transaction}';

    protected $description = 'Synchronize the Owner product master after an approved dry run';

    private const SYSTEM_MINIMUM_STOCK = 5;

    private const EXACT_REUSE = [
        'TERA0006' => '0002',
        'TERA0007' => '0001',
        'TERA0008' => '0003',
        'TERA0009' => '0004',
        'TERA0013' => '0007',
    ];

    private const EXPECTED_CATEGORY_COUNTS = [
        'Lampu Hannochs Sonic' => 15,
        'Lampu Gepeng / Downlight' => 34,
        'Lampu Hannochs Vario' => 10,
        'Lampu Hannochs Premier' => 11,
        'Gembok' => 6,
        'Kabel' => 36,
        'Stop Kontak' => 38,
    ];

    public function handle(): int
    {
        if ((bool) $this->option('dry-run') === (bool) $this->option('apply')) {
            $this->error('Specify exactly one of --dry-run or --apply.');

            return self::FAILURE;
        }

        $this->assertSchemaReady();

        if ($this->option('dry-run')) {
            $this->displayPlan($this->buildPlan());

            return self::SUCCESS;
        }

        $summary = [];

        DB::transaction(function () use (&$summary): void {
            $plan = $this->buildPlan();
            $historyCounts = $this->historyTableCounts();

            $this->applyPlan($plan);
            $this->assertFinalState($plan, $historyCounts);

            $summary = [
                'reused' => count($plan['reuse']),
                'historical_inactive' => count($plan['historical_ids']),
                'legacy_deleted' => count($plan['delete_ids']),
                'owner_inserted' => count($plan['insert']),
            ];
        });

        $this->info('Owner master synchronization committed successfully.');
        $this->table(
            ['Action', 'Count'],
            [
                ['EXACT product IDs reused', $summary['reused']],
                ['Historical legacy products deactivated', $summary['historical_inactive']],
                ['Legacy products without history deleted', $summary['legacy_deleted']],
                ['Owner products inserted', $summary['owner_inserted']],
                ['Active Owner products', 150],
            ]
        );

        return self::SUCCESS;
    }

    private function assertSchemaReady(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            throw new RuntimeException('This one-time synchronization command is restricted to SQLite.');
        }

        foreach ([
            'products',
            'categories',
            'detail_fakturs',
            'detail_penjualans',
            'detail_penerimaan_barangs',
            'stock_movements',
            'product_price_histories',
        ] as $table) {
            if (!Schema::hasTable($table)) {
                throw new RuntimeException("Required table '{$table}' is missing. Apply the approved migration first.");
            }
        }

        if (!Schema::hasColumn('products', 'aktif')) {
            throw new RuntimeException("The products.aktif column is missing. Apply the approved migration first.");
        }

        if (Schema::hasColumn('products', 'barcode')) {
            throw new RuntimeException('The products.barcode column still exists. Apply and verify the approved migration first.');
        }
    }

    private function buildPlan(): array
    {
        $ownerProducts = require database_path('seeders/data/owner_product_master.php');

        if (count($ownerProducts) !== 150) {
            throw new RuntimeException('Owner dataset must contain exactly 150 products.');
        }

        $ownerCodes = array_column($ownerProducts, 'kode_produk');
        $ownerNames = array_column($ownerProducts, 'nama_produk');
        $categoryCounts = [];

        foreach ($ownerProducts as $product) {
            $categoryCounts[$product['kategori']] = ($categoryCounts[$product['kategori']] ?? 0) + 1;
        }

        sort($ownerCodes, SORT_STRING);
        sort($ownerNames, SORT_STRING);
        ksort($categoryCounts);
        $expectedCategories = self::EXPECTED_CATEGORY_COUNTS;
        ksort($expectedCategories);

        if (count(array_unique($ownerCodes)) !== 150 || count(array_unique($ownerNames)) !== 150) {
            throw new RuntimeException('Owner dataset contains duplicate product codes or names.');
        }

        if ($categoryCounts !== $expectedCategories) {
            throw new RuntimeException('Owner dataset category counts do not match the approved totals.');
        }

        $ownerByCode = collect($ownerProducts)->keyBy('kode_produk');
        $categoryIds = Category::query()
            ->whereIn('nama_kategori', array_keys(self::EXPECTED_CATEGORY_COUNTS))
            ->pluck('id', 'nama_kategori')
            ->all();

        $missingCategories = array_values(array_diff(
            array_keys(self::EXPECTED_CATEGORY_COUNTS),
            array_keys($categoryIds)
        ));

        $existing = Product::query()->orderBy('id')->get();
        $reuse = [];
        $reusedOwnerCodes = [];

        foreach (self::EXACT_REUSE as $oldCode => $ownerCode) {
            $legacy = $existing->firstWhere('kode_produk', $oldCode);
            $ownerProduct = $ownerByCode->get($ownerCode);

            if (!$legacy || !$ownerProduct || $legacy->nama_produk !== $ownerProduct['nama_produk']) {
                throw new RuntimeException("Approved EXACT mapping {$oldCode} -> {$ownerCode} no longer matches.");
            }

            if ($this->hasHistory($legacy->id)) {
                throw new RuntimeException("EXACT product {$oldCode} now has history; refusing to reuse it automatically.");
            }

            if (isset($reusedOwnerCodes[$ownerCode])) {
                throw new RuntimeException("Owner code {$ownerCode} is assigned to multiple existing products.");
            }

            $reuse[$legacy->id] = [
                'legacy_code' => $oldCode,
                'owner_code' => $ownerCode,
                'product' => $ownerProduct,
            ];
            $reusedOwnerCodes[$ownerCode] = true;
        }

        foreach ($ownerProducts as $ownerProduct) {
            $existingCode = $existing->firstWhere('kode_produk', $ownerProduct['kode_produk']);

            if ($existingCode && !isset($reuse[$existingCode->id])) {
                throw new RuntimeException(
                    "Owner code {$ownerProduct['kode_produk']} is already assigned to an unapproved existing product."
                );
            }
        }

        $historicalIds = [];
        $deleteIds = [];
        $reusedIds = array_fill_keys(array_keys($reuse), true);

        foreach ($existing as $product) {
            if (isset($reusedIds[$product->id])) {
                continue;
            }

            if ($this->hasHistory($product->id)) {
                $historicalIds[] = $product->id;
            } else {
                $deleteIds[] = $product->id;
            }
        }

        $insert = array_values(array_filter(
            $ownerProducts,
            fn (array $product): bool => !isset($reusedOwnerCodes[$product['kode_produk']])
        ));

        return [
            'owner_products' => $ownerProducts,
            'owner_codes' => $ownerCodes,
            'owner_names' => $ownerNames,
            'category_ids' => $categoryIds,
            'missing_categories' => $missingCategories,
            'existing_count' => $existing->count(),
            'reuse' => $reuse,
            'historical_ids' => $historicalIds,
            'delete_ids' => $deleteIds,
            'insert' => $insert,
        ];
    }

    private function hasHistory(int $productId): bool
    {
        foreach ([
            'detail_penjualans',
            'detail_penerimaan_barangs',
            'stock_movements',
            'detail_fakturs',
            'product_price_histories',
        ] as $table) {
            if (DB::table($table)->where('product_id', $productId)->exists()) {
                return true;
            }
        }

        return false;
    }

    private function displayPlan(array $plan): void
    {
        $this->info('DRY RUN only; no database rows were changed.');
        $this->table(
            ['Action', 'Count'],
            [
                ['Existing products', $plan['existing_count']],
                ['EXACT IDs reused', count($plan['reuse'])],
                ['Historical legacy products to deactivate', count($plan['historical_ids'])],
                ['Legacy products without history to delete', count($plan['delete_ids'])],
                ['Owner products to insert', count($plan['insert'])],
                ['Owner categories to create', count($plan['missing_categories'])],
                ['Active Owner products after apply', 150],
            ]
        );

        foreach ($plan['reuse'] as $mapping) {
            $this->line("Reuse {$mapping['legacy_code']} -> {$mapping['owner_code']} ({$mapping['product']['nama_produk']})");
        }
    }

    private function historyTableCounts(): array
    {
        return [
            'detail_penjualans' => DB::table('detail_penjualans')->count(),
            'detail_penerimaan_barangs' => DB::table('detail_penerimaan_barangs')->count(),
            'stock_movements' => DB::table('stock_movements')->count(),
            'detail_fakturs' => DB::table('detail_fakturs')->count(),
            'product_price_histories' => DB::table('product_price_histories')->count(),
        ];
    }

    private function applyPlan(array $plan): void
    {
        $categoryIds = $plan['category_ids'];

        foreach ($plan['missing_categories'] as $category) {
            $categoryIds[$category] = Category::query()->create([
                'nama_kategori' => $category,
            ])->id;
        }

        foreach ($plan['reuse'] as $productId => $mapping) {
            $product = $mapping['product'];
            $values = $this->productValues($product, $categoryIds);

            DB::table('products')->where('id', $productId)->update($values + [
                'updated_at' => now(),
            ]);
        }

        if ($plan['historical_ids'] !== []) {
            DB::table('products')
                ->whereIn('id', $plan['historical_ids'])
                ->update(['aktif' => false, 'updated_at' => now()]);
        }

        if ($plan['delete_ids'] !== []) {
            Product::query()->whereIn('id', $plan['delete_ids'])->delete();
        }

        foreach ($plan['insert'] as $product) {
            Product::query()->create($this->productValues($product, $categoryIds));
        }
    }

    private function productValues(array $product, array $categoryIds): array
    {
        $values = [
            'kode_produk' => $product['kode_produk'],
            'category_id' => $categoryIds[$product['kategori']],
            'nama_produk' => $product['nama_produk'],
            'merk' => $product['merk'],
            'satuan' => $product['satuan'],
            'stok' => $product['stok'],
            'stok_minimum' => self::SYSTEM_MINIMUM_STOCK,
            'harga_beli' => $product['harga_beli'],
            'harga_jual' => $product['harga_jual'],
            'deskripsi' => null,
            'aktif' => true,
        ];

        if (Schema::hasColumn('products', 'supplier_id')) {
            $values['supplier_id'] = null;
        }

        return $values;
    }

    private function assertFinalState(array $plan, array $historyCounts): void
    {
        $active = Product::query()->where('aktif', true)->get();

        if ($active->count() !== 150) {
            throw new RuntimeException('Post-sync validation failed: active product count is not 150.');
        }

        $actualCodes = $active->pluck('kode_produk')->all();
        $actualNames = $active->pluck('nama_produk')->all();
        sort($actualCodes, SORT_STRING);
        sort($actualNames, SORT_STRING);

        if ($actualCodes !== $plan['owner_codes'] || $actualNames !== $plan['owner_names']) {
            throw new RuntimeException('Post-sync validation failed: active Owner codes or names differ from the dataset.');
        }

        $actualOwnerRows = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.aktif', true)
            ->select([
                'products.kode_produk',
                'categories.nama_kategori',
                'products.nama_produk',
                'products.merk',
                'products.satuan',
                'products.stok',
                'products.stok_minimum',
                'products.harga_beli',
                'products.harga_jual',
            ])
            ->orderBy('products.kode_produk')
            ->get()
            ->map(fn ($product): array => [
                'kode_produk' => (string) $product->kode_produk,
                'kategori' => $product->nama_kategori,
                'nama_produk' => $product->nama_produk,
                'merk' => $product->merk,
                'satuan' => $product->satuan,
                'stok' => (int) $product->stok,
                'stok_minimum' => (int) $product->stok_minimum,
                'harga_beli' => (int) $product->harga_beli,
                'harga_jual' => (int) $product->harga_jual,
            ])
            ->all();

        $expectedOwnerRows = collect($plan['owner_products'])
            ->map(fn (array $product): array => [
                'kode_produk' => $product['kode_produk'],
                'kategori' => $product['kategori'],
                'nama_produk' => $product['nama_produk'],
                'merk' => $product['merk'],
                'satuan' => $product['satuan'],
                'stok' => $product['stok'],
                'stok_minimum' => self::SYSTEM_MINIMUM_STOCK,
                'harga_beli' => $product['harga_beli'],
                'harga_jual' => $product['harga_jual'],
            ])
            ->sortBy('kode_produk')
            ->values()
            ->all();

        if ($actualOwnerRows !== $expectedOwnerRows) {
            throw new RuntimeException('Post-sync validation failed: active product fields differ from the Owner dataset.');
        }

        $actualCategoryCounts = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.aktif', true)
            ->select('categories.nama_kategori', DB::raw('COUNT(*) as total'))
            ->groupBy('categories.nama_kategori')
            ->pluck('total', 'nama_kategori')
            ->map(fn ($total): int => (int) $total)
            ->all();
        ksort($actualCategoryCounts);

        $expectedCategoryCounts = self::EXPECTED_CATEGORY_COUNTS;
        ksort($expectedCategoryCounts);

        if ($actualCategoryCounts !== $expectedCategoryCounts) {
            throw new RuntimeException('Post-sync validation failed: active category counts differ from the Owner dataset.');
        }

        if (Schema::hasColumn('products', 'barcode')) {
            throw new RuntimeException('Post-sync validation failed: products.barcode still exists.');
        }

        foreach ($plan['historical_ids'] as $productId) {
            $product = Product::query()->find($productId);

            if (!$product || $product->aktif) {
                throw new RuntimeException("Post-sync validation failed: historical product {$productId} was not retained inactive.");
            }
        }

        foreach ($plan['delete_ids'] as $productId) {
            if (Product::query()->whereKey($productId)->exists()) {
                throw new RuntimeException("Post-sync validation failed: legacy product {$productId} still exists.");
            }
        }

        if ($this->historyTableCounts() !== $historyCounts) {
            throw new RuntimeException('Post-sync validation failed: historical record counts changed.');
        }

        $foreignKeyViolations = DB::select('PRAGMA foreign_key_check');

        if ($foreignKeyViolations !== []) {
            throw new RuntimeException('Post-sync validation failed: SQLite foreign_key_check returned violations.');
        }
    }
}
