<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupController extends Controller
{
    /**
     * Path direktori tempat menyimpan file backup.
     */
    private function getBackupPath()
    {
        $path = storage_path('app/backups');
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        return $path;
    }

    /**
     * Menampilkan daftar backup.
     */
    public function index()
    {
        $backupDir = $this->getBackupPath();
        $files = File::files($backupDir);
        $backups = [];

        foreach ($files as $file) {
            // Hanya ambil file dengan ekstensi .sqlite atau .db
            if (in_array($file->getExtension(), ['sqlite', 'db'])) {
                $backups[] = [
                    'filename'    => $file->getFilename(),
                    'size'        => $file->getSize(),
                    'modified_at' => Carbon::createFromTimestamp($file->getMTime()),
                ];
            }
        }

        // Urutkan backup terbaru di paling atas
        usort($backups, function ($a, $b) {
            return $b['modified_at']->timestamp <=> $a['modified_at']->timestamp;
        });

        $latestBackup = count($backups) > 0 ? $backups[0] : null;

        return view('backup.index', compact('backups', 'latestBackup'));
    }

    /**
     * Proses pembuatan backup file SQLite.
     */
    public function store(Request $request)
    {
        try {
            $databasePath = database_path('database.sqlite');

            // Cek apakah file database utama SQLite ada
            if (!File::exists($databasePath)) {
                return redirect()->back()->with('error', 'File database.sqlite tidak ditemukan di folder database.');
            }

            $backupDir = $this->getBackupPath();
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $backupFileName = "backup_terapos_{$timestamp}.sqlite";
            $destinationPath = $backupDir . '/' . $backupFileName;

            // Lakukan pengopian file database secara murni
            if (File::copy($databasePath, $destinationPath)) {
                return redirect()->back()->with('success', "Backup database berhasil dibuat: {$backupFileName}");
            }

            return redirect()->back()->with('error', 'Gagal membuat backup database.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Download file backup.
     */
    public function download($filename)
    {
        // Sanitasi nama file untuk mencegah path traversal vulnerability
        $cleanFilename = basename($filename);
        $filePath = $this->getBackupPath() . '/' . $cleanFilename;

        if (File::exists($filePath)) {
            return response()->download($filePath);
        }

        return redirect()->back()->with('error', 'File backup tidak ditemukan.');
    }
}
