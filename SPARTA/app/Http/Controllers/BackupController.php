<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class BackupController extends Controller
{
    private const BACKUP_PATTERN = '/\Aterapos-backup-\d{8}-\d{6}(?:-\d+)?\.sqlite\z/';

    public function index(): View
    {
        $backups = $this->listBackups();

        return view('backup.index', [
            'backups' => $backups,
            'latestBackup' => $backups[0] ?? null,
        ]);
    }

    public function store(): RedirectResponse
    {
        $connection = DB::connection();

        if ($connection->getDriverName() !== 'sqlite') {
            return back()->with('error', 'Backup database hanya tersedia untuk koneksi SQLite.');
        }

        $configuredPath = $connection->getConfig('database');
        if (
            !is_string($configuredPath)
            || $configuredPath === ''
            || $configuredPath === ':memory:'
            || str_starts_with($configuredPath, 'file:')
        ) {
            return back()->with('error', 'File database SQLite tidak ditemukan.');
        }

        $sourcePath = realpath($configuredPath) ?: realpath(base_path($configuredPath));
        if ($sourcePath === false || !is_file($sourcePath) || !is_readable($sourcePath)) {
            return back()->with('error', 'File database SQLite tidak ditemukan.');
        }

        $backupDirectory = storage_path('app/private/backups');
        $backupPath = null;
        $sqliteSource = null;
        $sqliteBackup = null;

        try {
            File::ensureDirectoryExists($backupDirectory, 0700, true);
            $backupPath = $this->nextBackupPath($backupDirectory);

            if (class_exists(\SQLite3::class)) {
                $sqliteSource = new \SQLite3($sourcePath, SQLITE3_OPEN_READONLY);
                $sqliteSource->busyTimeout(5000);
                $sqliteBackup = new \SQLite3(
                    $backupPath,
                    SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE
                );
                $sqliteBackup->busyTimeout(5000);

                if (!$sqliteSource->backup($sqliteBackup)) {
                    throw new RuntimeException('SQLite online backup did not complete.');
                }
            } else {
                $pdo = $connection->getPdo();
                $pdo->exec('VACUUM INTO ' . $pdo->quote($backupPath));
            }

            if (!is_file($backupPath) || filesize($backupPath) <= 0) {
                throw new RuntimeException('The generated SQLite backup is empty.');
            }

            if ($sqliteBackup !== null) {
                $sqliteBackup->close();
                $sqliteBackup = null;
            }
            if ($sqliteSource !== null) {
                $sqliteSource->close();
                $sqliteSource = null;
            }

            $this->validateBackup($backupPath);

            return redirect()
                ->route('backup.index')
                ->with('success', 'Backup database berhasil dibuat.');
        } catch (Throwable $exception) {
            if ($sqliteBackup instanceof \SQLite3) {
                $sqliteBackup->close();
            }
            if ($sqliteSource instanceof \SQLite3) {
                $sqliteSource->close();
            }
            if ($backupPath !== null && is_file($backupPath)) {
                File::delete($backupPath);
            }

            report($exception);

            return back()->with('error', 'Backup database gagal dibuat.');
        }
    }

    public function download(string $filename): BinaryFileResponse
    {
        abort_unless(
            basename($filename) === $filename
                && preg_match(self::BACKUP_PATTERN, $filename) === 1,
            404
        );

        $backupDirectory = storage_path('app/private/backups');
        $directoryPath = realpath($backupDirectory);
        $filePath = realpath($backupDirectory . DIRECTORY_SEPARATOR . $filename);

        abort_unless(
            $directoryPath !== false
                && $filePath !== false
                && str_starts_with($filePath, $directoryPath . DIRECTORY_SEPARATOR)
                && is_file($filePath)
                && is_readable($filePath),
            404
        );

        return response()->download($filePath, $filename);
    }

    private function listBackups(): array
    {
        $backupDirectory = storage_path('app/private/backups');
        if (!File::isDirectory($backupDirectory)) {
            return [];
        }

        $backups = [];
        foreach (File::files($backupDirectory) as $file) {
            $filename = $file->getFilename();
            if (preg_match(self::BACKUP_PATTERN, $filename) !== 1) {
                continue;
            }

            $backups[] = [
                'filename' => $filename,
                'size' => $file->getSize(),
                'modified_at' => Carbon::createFromTimestamp($file->getMTime()),
            ];
        }

        usort(
            $backups,
            fn (array $left, array $right) => $right['modified_at']->getTimestamp()
                <=> $left['modified_at']->getTimestamp()
        );

        return $backups;
    }

    private function nextBackupPath(string $backupDirectory): string
    {
        $baseName = 'terapos-backup-' . now()->format('Ymd-His');
        $suffix = 0;

        do {
            $filename = $baseName . ($suffix === 0 ? '' : '-' . $suffix) . '.sqlite';
            $path = $backupDirectory . DIRECTORY_SEPARATOR . $filename;
            $suffix++;
        } while (file_exists($path));

        return $path;
    }

    private function validateBackup(string $backupPath): void
    {
        if (class_exists(\SQLite3::class)) {
            $database = new \SQLite3($backupPath, SQLITE3_OPEN_READONLY);
            try {
                $result = $database->querySingle('PRAGMA quick_check');
                if ($result !== 'ok') {
                    throw new RuntimeException('SQLite quick_check failed for the generated backup.');
                }
            } finally {
                $database->close();
            }

            return;
        }

        $database = new \PDO(
            'sqlite:' . $backupPath,
            null,
            null,
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
        if ($database->query('PRAGMA quick_check')->fetchColumn() !== 'ok') {
            throw new RuntimeException('SQLite quick_check failed for the generated backup.');
        }
    }
}
