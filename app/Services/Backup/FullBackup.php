<?php

namespace App\Services\Backup;

use App\Services\Backup\Contracts\BackupInterface;
use Illuminate\Support\Facades\File;

class FullBackup implements BackupInterface
{
    public function create(string $backupId, array $options = []): array
    {
        $tempDir = storage_path("backups/temp/{$backupId}");
        $finalPath = storage_path("backups/{$backupId}.zip");

        File::makeDirectory($tempDir, 0755, true);

        try {
            // Database backup
            $dbBackup = app(DatabaseBackup::class);
            $dbResult = $dbBackup->create($backupId . '_db', ['compress' => false]);
            rename($dbResult['path'], $tempDir . '/database.sql');

            // Files backup
            $filesBackup = app(FileBackup::class);
            $filesResult = $filesBackup->create($backupId . '_files', ['compress' => false]);
            rename($filesResult['path'], $tempDir . '/files.zip');

            // Create final zip
            $zip = new \ZipArchive();
            $zip->open($finalPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            $this->addFolderToZip($zip, $tempDir, 'full_backup');
            $zip->close();

            // Cleanup
            File::deleteDirectory($tempDir);

            return [
                'size' => File::size($finalPath),
                'path' => $finalPath,
                'components' => ['database', 'files']
            ];

        } catch (\Exception $e) {
            File::deleteDirectory($tempDir);
            throw $e;
        }
    }

    public function restore(string $backupId, array $options = []): array
    {
        throw new \Exception('Full restore not implemented yet');
    }

    public function list(): array { return []; }
    public function delete(string $backupId): bool { return true; }
    public function getSize(string $backupId): int { return 0; }
    public function verify(string $backupId): bool { return true; }

    private function addFolderToZip(\ZipArchive $zip, string $folder, string $zipFolder): void
    {
        $files = File::allFiles($folder);

        foreach ($files as $file) {
            $relativePath = $zipFolder . '/' . $file->getRelativePathname();
            $zip->addFile($file->getRealPath(), $relativePath);
        }
    }
}
