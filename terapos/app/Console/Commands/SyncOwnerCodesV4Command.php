<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PDO;
use RuntimeException;

class SyncOwnerCodesV4Command extends Command
{
    protected $signature = 'products:sync-owner-codes-v4
        {--dry-run : Validate and report the proposed code changes without writing}
        {--apply : Back up SQLite and apply the code changes in a transaction}';

    protected $description = 'Synchronize active Owner product codes with Excel master v4';

    private const HISTORY_TABLES = [
        'detail_penjualans',
        'detail_penerimaan_barangs',
        'stock_movements',
        'detail_fakturs',
        'product_price_histories',
    ];

    public function handle(): int
    {
        if ((bool) $this->option('dry-run') === (bool) $this->option('apply')) {
            $this->error('Specify exactly one of --dry-run or --apply.');

            return self::FAILURE;
        }

        $this->assertSchemaReady();
        $plan = $this->buildPlan();

        if ($this->option('dry-run')) {
            $this->info('DRY RUN only; no database rows were changed.');
            $this->displayPlan($plan);

            return self::SUCCESS;
        }

        $backupPath = $this->createVerifiedBackup();
        $summary = [];

        DB::transaction(function () use (&$summary): void {
            $plan = $this->buildPlan();
            $productSnapshot = $this->productSnapshot();
            $historySnapshot = $this->historySnapshot();

            $this->applyPlan($plan);
            $this->assertFinalState($plan, $productSnapshot, $historySnapshot);

            $summary = [
                'count' => count($plan),
                'mapping' => $plan,
            ];
        });

        $this->info('Owner product codes synchronized successfully.');
        $this->line("SQLite backup: {$backupPath}");
        $this->displayPlan($summary['mapping']);
        $this->info("Updated codes: {$summary['count']}");

        return self::SUCCESS;
    }

    private function assertSchemaReady(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            throw new RuntimeException('This code synchronization command is restricted to SQLite.');
        }

        foreach (array_merge(['products'], self::HISTORY_TABLES) as $table) {
            if (!Schema::hasTable($table)) {
                throw new RuntimeException("Required table '{$table}' is missing.");
            }
        }

        if (!Schema::hasColumn('products', 'aktif')) {
            throw new RuntimeException('The products.aktif column is missing.');
        }
    }

    private function buildPlan(): array
    {
        $dataset = require database_path('seeders/data/owner_product_master.php');

        if (count($dataset) !== 150) {
            throw new RuntimeException('The v4 product dataset must contain exactly 150 products.');
        }

        $codes = array_column($dataset, 'kode_produk');
        $names = array_column($dataset, 'nama_produk');
        if (count(array_unique($codes)) !== 150 || count(array_unique($names)) !== 150) {
            throw new RuntimeException('The v4 product dataset contains duplicate codes or names.');
        }

        foreach ($dataset as $product) {
            if (!preg_match('/^[a-z0-9]+-[0-9]+(?:\.[0-9]+)?$/', $product['kode_produk'])) {
                throw new RuntimeException("Invalid v4 product code '{$product['kode_produk']}'.");
            }
        }

        $active = DB::table('products')->where('aktif', true)->orderBy('id')->get();
        if ($active->count() !== 150) {
            throw new RuntimeException('The database must contain exactly 150 active products.');
        }

        $activeByName = $active->groupBy('nama_produk');
        $plan = [];

        foreach ($dataset as $product) {
            $matches = $activeByName->get($product['nama_produk'], collect());
            if ($matches->count() !== 1) {
                throw new RuntimeException(
                    "Product name '{$product['nama_produk']}' must match exactly one active database product."
                );
            }

            $existing = $matches->first();
            $plan[] = [
                'id' => (int) $existing->id,
                'nama_produk' => $product['nama_produk'],
                'kode_lama' => (string) $existing->kode_produk,
                'kode_baru' => $product['kode_produk'],
            ];
        }

        $mappedIds = array_fill_keys(array_column($plan, 'id'), true);
        foreach ($plan as $mapping) {
            $conflict = DB::table('products')
                ->where('kode_produk', $mapping['kode_baru'])
                ->whereNotIn('id', array_keys($mappedIds))
                ->exists();

            if ($conflict) {
                throw new RuntimeException(
                    "New code '{$mapping['kode_baru']}' is assigned to a product outside the active mapping."
                );
            }
        }

        return $plan;
    }

    private function displayPlan(array $plan): void
    {
        $this->table(
            ['ID', 'Nama Barang', 'Kode Lama', 'Kode Baru'],
            array_map(fn (array $row): array => [
                $row['id'],
                $row['nama_produk'],
                $row['kode_lama'],
                $row['kode_baru'],
            ], $plan)
        );
    }

    private function createVerifiedBackup(): string
    {
        $this->assertDatabaseIntegrity(DB::connection()->getPdo());
        $configuredPath = DB::connection()->getDatabaseName();
        $databasePath = $configuredPath === ':memory:'
            ? $configuredPath
            : (str_starts_with($configuredPath, DIRECTORY_SEPARATOR) ? $configuredPath : base_path($configuredPath));
        $databasePath = $databasePath === ':memory:' ? $databasePath : (realpath($databasePath) ?: null);

        if (!$databasePath || ($databasePath !== ':memory:' && !is_file($databasePath))) {
            throw new RuntimeException('The active SQLite database file could not be resolved for backup.');
        }

        $directory = storage_path('app/private/backups');
        if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) {
            throw new RuntimeException('Unable to create the private SQLite backup directory.');
        }

        $backupPath = $directory . DIRECTORY_SEPARATOR
            . 'terapos-before-owner-codes-v4-' . now()->format('Ymd-His') . '.sqlite';
        if (file_exists($backupPath)) {
            throw new RuntimeException("Backup file already exists: {$backupPath}");
        }

        $escapedPath = str_replace("'", "''", $backupPath);
        DB::statement("VACUUM INTO '{$escapedPath}'");

        $backup = new PDO('sqlite:' . $backupPath);
        $backup->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->assertDatabaseIntegrity($backup);

        return $backupPath;
    }

    private function assertDatabaseIntegrity(PDO $connection): void
    {
        $integrity = $connection->query('PRAGMA integrity_check')->fetchAll(PDO::FETCH_COLUMN);
        if ($integrity !== ['ok']) {
            throw new RuntimeException('SQLite integrity_check failed: ' . implode('; ', $integrity));
        }

        $foreignKeyViolations = $connection->query('PRAGMA foreign_key_check')->fetchAll(PDO::FETCH_ASSOC);
        if ($foreignKeyViolations !== []) {
            throw new RuntimeException('SQLite foreign_key_check found violations.');
        }
    }

    private function productSnapshot(): array
    {
        return DB::table('products')->orderBy('id')->get()->map(function ($product): array {
            $row = (array) $product;
            unset($row['kode_produk']);

            return $row;
        })->all();
    }

    private function historySnapshot(): array
    {
        $snapshot = [];

        foreach (self::HISTORY_TABLES as $table) {
            $snapshot[$table] = DB::table($table)->orderBy('id')->get()->map(
                fn ($row): array => (array) $row
            )->all();
        }

        return $snapshot;
    }

    private function applyPlan(array $plan): void
    {
        foreach ($plan as $mapping) {
            $temporaryCode = '__owner-v4-sync-' . $mapping['id'] . '-' . bin2hex(random_bytes(6));
            DB::table('products')
                ->where('id', $mapping['id'])
                ->update(['kode_produk' => $temporaryCode]);
        }

        foreach ($plan as $mapping) {
            DB::table('products')
                ->where('id', $mapping['id'])
                ->update(['kode_produk' => $mapping['kode_baru']]);
        }
    }

    private function assertFinalState(array $plan, array $productSnapshot, array $historySnapshot): void
    {
        $actual = DB::table('products')->where('aktif', true)->orderBy('id')->get();
        if ($actual->count() !== 150) {
            throw new RuntimeException('Post-sync validation failed: active product count changed.');
        }

        foreach ($plan as $mapping) {
            $product = $actual->firstWhere('id', $mapping['id']);
            if (!$product || $product->nama_produk !== $mapping['nama_produk']
                || $product->kode_produk !== $mapping['kode_baru']) {
                throw new RuntimeException("Post-sync validation failed for product ID {$mapping['id']}.");
            }
        }

        if ($actual->pluck('kode_produk')->unique()->count() !== 150) {
            throw new RuntimeException('Post-sync validation failed: active product codes are not unique.');
        }

        if ($this->productSnapshot() !== $productSnapshot) {
            throw new RuntimeException('Post-sync validation failed: product IDs or non-code fields changed.');
        }

        if ($this->historySnapshot() !== $historySnapshot) {
            throw new RuntimeException('Post-sync validation failed: product history changed.');
        }

        $violations = DB::select('PRAGMA foreign_key_check');
        if ($violations !== []) {
            throw new RuntimeException('Post-sync validation failed: foreign_key_check found violations.');
        }
    }
}
