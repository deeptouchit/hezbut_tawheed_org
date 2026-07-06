<?php

namespace App\Services\Backup;

use App\Services\Backup\Contracts\BackupInterface;
use Illuminate\Support\Facades\File;

class BackupManager
{
    private array $drivers = [];

    public function __construct()
    {
        $this->registerDrivers();
    }

    private function registerDrivers(): void
    {
        $this->drivers = [
            'database' => app(DatabaseBackup::class),
            'files' => app(FileBackup::class),
        ];
    }

    public function create(string $type, array $options = []): array
    {
        if (!isset($this->drivers[$type])) {
            throw new \Exception("Backup driver '{$type}' not found");
        }

        $backupId = $this->generateBackupId($type);

        return $this->drivers[$type]->create($backupId, $options);
    }

    public function restore(string $backupId, array $options = []): array
    {
        $type = $this->detectBackupType($backupId);

        if (!isset($this->drivers[$type])) {
            throw new \Exception("Driver for type '{$type}' not found");
        }

        return $this->drivers[$type]->restore($backupId, $options);
    }

    public function listBackups(): array
    {
        $backups = [];
        $backupPath = storage_path('backups');

        if (!File::exists($backupPath)) {
            return [];
        }

        $files = File::files($backupPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'zip') {
                $backups[] = [
                    'id' => $file->getBasename('.zip'),
                    'filename' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'size_formatted' => $this->formatSize($file->getSize()),
                    'created_at' => date('Y-m-d H:i:s', $file->getMTime()),
                    'type' => $this->detectBackupType($file->getBasename('.zip'))
                ];
            }
        }

        usort($backups, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));

        return $backups;
    }

    public function deleteBackup(string $backupId): bool
    {
        $backupPath = storage_path("backups/{$backupId}.zip");

        if (File::exists($backupPath)) {
            File::delete($backupPath);
            return true;
        }

        return false;
    }

    public function verify(string $backupId): bool
    {
        $type = $this->detectBackupType($backupId);

        if (!isset($this->drivers[$type])) {
            return false;
        }

        return $this->drivers[$type]->verify($backupId);
    }

    private function generateBackupId(string $type): string
    {
        $timestamp = now()->format('Ymd_His');
        return "backup_{$type}_{$timestamp}";
    }

    private function detectBackupType(string $backupId): string
    {
        if (str_contains($backupId, 'database')) return 'database';
        if (str_contains($backupId, 'files')) return 'files';
        return 'database';
    }

    private function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        return number_format($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
    }
}
