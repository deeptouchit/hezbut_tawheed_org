<?php

namespace App\Services\Backup;

use App\Services\Backup\Contracts\BackupInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class FileBackup implements BackupInterface
{
    /**
     * Directories to backup
     */
    private array $backupPaths = [
        'storage/app/public',
        'storage/app/uploads',
        'public/uploads',
        'storage/app/images'
    ];

    /**
     * Excluded patterns
     */
    private array $excludePatterns = [
        '*.tmp',
        '*.cache',
        '*.log',
        'thumbnails/*',
        '.DS_Store',
        'Thumbs.db'
    ];

    /**
     * Maximum file size to include (bytes)
     */
    private int $maxFileSize = 104857600; // 100MB

    /**
     * Create a file backup
     */
    public function create(string $backupId, array $options = []): array
    {
        $this->backupPaths = $options['paths'] ?? $this->backupPaths;
        $this->excludePatterns = $options['exclude'] ?? $this->excludePatterns;
        $this->maxFileSize = $options['max_file_size'] ?? $this->maxFileSize;

        $tempDir = storage_path("backups/temp/{$backupId}_files");
        $finalPath = storage_path("backups/{$backupId}.zip");

        $this->ensureDirectories();

        if (File::exists($tempDir)) {
            File::deleteDirectory($tempDir);
        }

        File::makeDirectory($tempDir, 0755, true);

        try {
            $fileCount = 0;
            $totalSize = 0;

            foreach ($this->backupPaths as $path) {
                $fullPath = base_path($path);

                if (!File::exists($fullPath)) {
                    Log::channel('backup')->warning('Backup path not found', ['path' => $fullPath]);
                    continue;
                }

                $relativePath = str_replace(base_path(), '', $fullPath);
                $targetPath = $tempDir . $relativePath;

                $result = $this->copyFiles($fullPath, $targetPath);
                $fileCount += $result['count'];
                $totalSize += $result['size'];
            }

            // Create zip archive
            $zip = new \ZipArchive();

            if ($zip->open($finalPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                throw new \Exception('Cannot create zip file');
            }

            $this->addFolderToZip($zip, $tempDir, 'files');
            $zip->close();

            // Cleanup temp directory
            File::deleteDirectory($tempDir);

            $finalSize = File::size($finalPath);
            $hash = hash_file('sha256', $finalPath);

            $this->saveMetadata($backupId, $finalSize, $hash, $fileCount);

            return [
                'size' => $finalSize,
                'path' => $finalPath,
                'hash' => $hash,
                'file_count' => $fileCount,
                'total_size' => $totalSize
            ];

        } catch (\Exception $e) {
            if (File::exists($tempDir)) {
                File::deleteDirectory($tempDir);
            }
            throw $e;
        }
    }

    /**
     * Restore files from backup
     */
    public function restore(string $backupId, array $options = []): array
    {
        $backupPath = storage_path("backups/{$backupId}.zip");

        if (!File::exists($backupPath)) {
            throw new \Exception("Backup file not found: {$backupId}");
        }

        $restoreTo = $options['restore_to'] ?? base_path();
        $tempDir = storage_path("backups/restore_temp_" . time());

        File::makeDirectory($tempDir, 0755, true);

        try {
            $zip = new \ZipArchive();
            $zip->open($backupPath);
            $zip->extractTo($tempDir);
            $zip->close();

            $sourceDir = $tempDir . '/files';

            if (!File::exists($sourceDir)) {
                throw new \Exception('Invalid backup structure: files directory not found');
            }

            $restored = $this->copyDirectoryWithProgress($sourceDir, $restoreTo);

            File::deleteDirectory($tempDir);

            return [
                'success' => true,
                'restored_count' => $restored['count'],
                'restored_paths' => $restored['paths']
            ];

        } catch (\Exception $e) {
            if (File::exists($tempDir)) {
                File::deleteDirectory($tempDir);
            }
            throw $e;
        }
    }

    /**
     * List all file backups
     */
    public function list(): array
    {
        $backups = [];
        $backupPath = storage_path('backups');

        if (!File::exists($backupPath)) {
            return [];
        }

        $files = File::glob($backupPath . '/backup_files_*.zip');

        foreach ($files as $file) {
            $backups[] = [
                'name' => basename($file),
                'size' => File::size($file),
                'size_formatted' => $this->formatSize(File::size($file)),
                'created_at' => date('Y-m-d H:i:s', File::lastModified($file)),
                'type' => 'files'
            ];
        }

        return $backups;
    }

    /**
     * Delete a file backup
     */
    public function delete(string $backupId): bool
    {
        $backupPath = storage_path("backups/{$backupId}.zip");
        $metadataPath = storage_path("backups/metadata/{$backupId}.json");

        try {
            if (File::exists($backupPath)) {
                File::delete($backupPath);
            }

            if (File::exists($metadataPath)) {
                File::delete($metadataPath);
            }

            Log::channel('backup')->info('File backup deleted', ['backup_id' => $backupId]);

            return true;

        } catch (\Exception $e) {
            Log::channel('backup')->error('File backup deletion failed', [
                'backup_id' => $backupId,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Get backup file size
     */
    public function getSize(string $backupId): int
    {
        $backupPath = storage_path("backups/{$backupId}.zip");

        if (File::exists($backupPath)) {
            return File::size($backupPath);
        }

        return 0;
    }

    /**
     * Verify backup integrity
     */
    public function verify(string $backupId): bool
    {
        $metadataPath = storage_path("backups/metadata/{$backupId}.json");

        if (!File::exists($metadataPath)) {
            return false;
        }

        $metadata = json_decode(File::get($metadataPath), true);
        $backupPath = storage_path("backups/{$backupId}.zip");

        if (!File::exists($backupPath)) {
            return false;
        }

        // Test zip integrity
        $zip = new \ZipArchive();
        if ($zip->open($backupPath) !== true) {
            return false;
        }

        $isValid = $zip->status === \ZipArchive::ER_OK;
        $zip->close();

        // Verify hash if available
        if ($isValid && isset($metadata['hash'])) {
            $currentHash = hash_file('sha256', $backupPath);
            $isValid = $currentHash === $metadata['hash'];
        }

        return $isValid;
    }

    /**
     * Add files to backup list
     */
    public function addPath(string $path): self
    {
        if (!in_array($path, $this->backupPaths)) {
            $this->backupPaths[] = $path;
        }

        return $this;
    }

    /**
     * Remove path from backup list
     */
    public function removePath(string $path): self
    {
        $key = array_search($path, $this->backupPaths);

        if ($key !== false) {
            unset($this->backupPaths[$key]);
            $this->backupPaths = array_values($this->backupPaths);
        }

        return $this;
    }

    /**
     * Set backup paths
     */
    public function setPaths(array $paths): self
    {
        $this->backupPaths = $paths;
        return $this;
    }

    /**
     * Get backup paths
     */
    public function getPaths(): array
    {
        return $this->backupPaths;
    }

    /**
     * Exclude pattern from backup
     */
    public function exclude(string $pattern): self
    {
        if (!in_array($pattern, $this->excludePatterns)) {
            $this->excludePatterns[] = $pattern;
        }

        return $this;
    }

    /**
     * Copy files recursively
     */
    private function copyFiles(string $source, string $destination): array
    {
        $fileCount = 0;
        $totalSize = 0;

        if (!File::exists($source)) {
            return ['count' => 0, 'size' => 0];
        }

        if (is_file($source)) {
            if ($this->shouldIncludeFile($source)) {
                $this->ensureDirectory(dirname($destination));
                File::copy($source, $destination);
                return ['count' => 1, 'size' => File::size($source)];
            }
            return ['count' => 0, 'size' => 0];
        }

        File::makeDirectory($destination, 0755, true);

        $items = File::files($source);

        foreach ($items as $item) {
            if ($this->shouldIncludeFile($item->getRealPath())) {
                $targetPath = $destination . '/' . $item->getFilename();
                File::copy($item->getRealPath(), $targetPath);
                $fileCount++;
                $totalSize += $item->getSize();
            }
        }

        $directories = File::directories($source);

        foreach ($directories as $directory) {
            $dirName = basename($directory);

            if ($this->shouldExcludeDirectory($dirName)) {
                continue;
            }

            $result = $this->copyFiles($directory, $destination . '/' . $dirName);
            $fileCount += $result['count'];
            $totalSize += $result['size'];
        }

        return ['count' => $fileCount, 'size' => $totalSize];
    }

    /**
     * Copy directory for restore
     */
    private function copyDirectoryWithProgress(string $source, string $destination): array
    {
        $restored = ['count' => 0, 'paths' => []];

        if (!File::exists($source)) {
            return $restored;
        }

        $items = File::allFiles($source);

        foreach ($items as $item) {
            $relativePath = str_replace($source, '', $item->getRealPath());
            $targetPath = $destination . $relativePath;

            $this->ensureDirectory(dirname($targetPath));
            File::copy($item->getRealPath(), $targetPath);

            $restored['count']++;
            $restored['paths'][] = $relativePath;
        }

        return $restored;
    }

    /**
     * Check if file should be included
     */
    private function shouldIncludeFile(string $filePath): bool
    {
        // Check file size
        if (File::size($filePath) > $this->maxFileSize) {
            return false;
        }

        $filename = basename($filePath);

        // Check exclude patterns
        foreach ($this->excludePatterns as $pattern) {
            if (fnmatch($pattern, $filename)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if directory should be excluded
     */
    private function shouldExcludeDirectory(string $dirName): bool
    {
        $excludedDirs = ['.git', 'node_modules', 'vendor', '.idea', '.vscode'];

        return in_array($dirName, $excludedDirs);
    }

    /**
     * Add folder to zip recursively
     */
    private function addFolderToZip(\ZipArchive $zip, string $folder, string $zipFolder): void
    {
        $files = File::allFiles($folder);

        foreach ($files as $file) {
            $relativePath = $zipFolder . '/' . $file->getRelativePathname();
            $zip->addFile($file->getRealPath(), $relativePath);
        }
    }

    /**
     * Ensure directory exists
     */
    private function ensureDirectory(string $path): void
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    /**
     * Ensure backup directories exist
     */
    private function ensureDirectories(): void
    {
        $directories = [
            storage_path('backups'),
            storage_path('backups/temp'),
            storage_path('backups/metadata')
        ];

        foreach ($directories as $dir) {
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
        }
    }

    /**
     * Save backup metadata
     */
    private function saveMetadata(string $backupId, int $size, string $hash, int $fileCount): void
    {
        $metadata = [
            'type' => 'files',
            'size' => $size,
            'hash' => $hash,
            'file_count' => $fileCount,
            'backup_paths' => $this->backupPaths,
            'excluded_patterns' => $this->excludePatterns,
            'created_at' => now()->toISOString(),
            'version' => '1.0'
        ];

        $metadataPath = storage_path("backups/metadata/{$backupId}.json");
        File::put($metadataPath, json_encode($metadata, JSON_PRETTY_PRINT));
    }

    /**
     * Format file size
     */
    private function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        return number_format($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
    }
}
