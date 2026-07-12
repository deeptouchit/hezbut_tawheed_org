<?php

namespace App\Services\Backup;

use App\Services\Backup\Contracts\BackupInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseBackup implements BackupInterface
{
    private int $chunkSize = 1000;
    private array $excludedTables = ['migrations', 'failed_jobs', 'sessions', 'cache', 'cache_locks', 'password_resets'];

    public function create(string $backupId, array $options = []): array
    {
        $this->chunkSize = $options['chunk_size'] ?? $this->chunkSize;

        $tempSqlPath = storage_path("backups/temp/{$backupId}.sql");
        $finalPath = storage_path("backups/{$backupId}.zip");

        $this->ensureDirectories();

        try {
            $handle = fopen($tempSqlPath, 'w');
            $this->writeHeader($handle);

            $tables = $this->getTables();
            $totalTables = 0;

            foreach ($tables as $table) {
                if (in_array($table, $this->excludedTables)) {
                    continue;
                }

                $this->writeTableStructure($handle, $table);
                $rowCount = $this->writeTableData($handle, $table);

                if ($rowCount > 0) {
                    $totalTables++;
                }
            }

            $this->writeFooter($handle);
            fclose($handle);

            // Compress to zip
            $zip = new \ZipArchive();
            if ($zip->open($finalPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                throw new \Exception('Cannot create zip file');
            }
            $zip->addFile($tempSqlPath, 'database.sql');
            $zip->close();

            // Cleanup
            File::delete($tempSqlPath);

            $size = File::size($finalPath);
            $hash = hash_file('sha256', $finalPath);

            $this->saveMetadata($backupId, $size, $hash, $totalTables);

            return [
                'size' => $size,
                'size_formatted' => $this->formatSize($size),
                'path' => $finalPath,
                'hash' => $hash,
                'tables' => $totalTables
            ];

        } catch (\Exception $e) {
            if (isset($handle)) fclose($handle);
            if (File::exists($tempSqlPath)) File::delete($tempSqlPath);
            throw $e;
        }
    }

    public function restore(string $backupId, array $options = []): array
    {
        $backupPath = storage_path("backups/{$backupId}.zip");

        if (!File::exists($backupPath)) {
            throw new \Exception("Backup file not found: {$backupId}");
        }

        $tempDir = storage_path("backups/restore_temp_" . time());
        File::makeDirectory($tempDir, 0755, true);

        try {
            $zip = new \ZipArchive();
            $zip->open($backupPath);
            $zip->extractTo($tempDir);
            $zip->close();

            $sqlFile = $tempDir . '/database.sql';

            if (!File::exists($sqlFile)) {
                throw new \Exception('SQL file not found in backup');
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            $sql = File::get($sqlFile);
            $queries = $this->splitSqlQueries($sql);

            $executed = 0;
            foreach ($queries as $query) {
                if (trim($query)) {
                    try {
                        DB::statement($query);
                        $executed++;
                    } catch (\Exception $e) {
                        // Log error but continue
                        \Illuminate\Support\Facades\Log::warning('Query execution failed', [
                            'query' => substr($query, 0, 500),
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            File::deleteDirectory($tempDir);

            return [
                'success' => true,
                'queries_executed' => $executed
            ];

        } catch (\Exception $e) {
            File::deleteDirectory($tempDir);
            throw $e;
        }
    }

    public function list(): array
    {
        $backups = [];
        $backupPath = storage_path('backups');

        if (!File::exists($backupPath)) {
            return [];
        }

        $files = File::glob($backupPath . '/backup_database_*.zip');

        foreach ($files as $file) {
            $backups[] = [
                'name' => basename($file),
                'size' => File::size($file),
                'created_at' => date('Y-m-d H:i:s', File::lastModified($file))
            ];
        }

        return $backups;
    }

    public function delete(string $backupId): bool
    {
        $backupPath = storage_path("backups/{$backupId}.zip");
        $metadataPath = storage_path("backups/metadata/{$backupId}.json");

        if (File::exists($backupPath)) {
            File::delete($backupPath);
        }

        if (File::exists($metadataPath)) {
            File::delete($metadataPath);
        }

        return true;
    }

    public function getSize(string $backupId): int
    {
        $backupPath = storage_path("backups/{$backupId}.zip");

        if (File::exists($backupPath)) {
            return File::size($backupPath);
        }

        return 0;
    }

    public function verify(string $backupId): bool
    {
        $backupPath = storage_path("backups/{$backupId}.zip");

        if (!File::exists($backupPath)) {
            return false;
        }

        $zip = new \ZipArchive();
        if ($zip->open($backupPath) !== true) {
            return false;
        }

        $isValid = $zip->status === \ZipArchive::ER_OK;
        $zip->close();

        return $isValid;
    }

    private function writeHeader($handle): void
    {
        fwrite($handle, "-- --------------------------------------------------------\n");
        fwrite($handle, "-- Database Backup\n");
        fwrite($handle, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
        fwrite($handle, "-- Database: " . config('database.connections.mysql.database') . "\n");
        fwrite($handle, "-- --------------------------------------------------------\n\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
        fwrite($handle, "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n\n");
    }

    private function writeFooter($handle): void
    {
        fwrite($handle, "\nSET FOREIGN_KEY_CHECKS=1;\n");
        fwrite($handle, "-- Backup completed successfully\n");
    }

    private function writeTableStructure($handle, string $table): void
    {
        $createTable = DB::select("SHOW CREATE TABLE `{$table}`");
        fwrite($handle, "-- --------------------------------------------------------\n");
        fwrite($handle, "-- Table structure for `{$table}`\n");
        fwrite($handle, "-- --------------------------------------------------------\n");
        fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");
        fwrite($handle, $createTable[0]->{'Create Table'} . ";\n\n");
    }

    private function writeTableData($handle, string $table): int
    {
        // Get primary key for ordering
        $primaryKey = $this->getPrimaryKey($table);

        $count = DB::table($table)->count();

        if ($count === 0) {
            return 0;
        }

        fwrite($handle, "-- --------------------------------------------------------\n");
        fwrite($handle, "-- Dumping data for table `{$table}`\n");
        fwrite($handle, "-- Total rows: {$count}\n");
        fwrite($handle, "-- --------------------------------------------------------\n");
        fwrite($handle, "INSERT INTO `{$table}` VALUES\n");

        $offset = 0;
        $firstBatch = true;

        while ($offset < $count) {
            $query = DB::table($table);

            // Add order by if primary key exists
            if ($primaryKey) {
                $query = $query->orderBy($primaryKey);
            } else {
                // If no primary key, use any column for ordering
                $columns = DB::select("SHOW COLUMNS FROM `{$table}`");
                if (!empty($columns)) {
                    $query = $query->orderBy($columns[0]->Field);
                }
            }

            $rows = $query->skip($offset)->take($this->chunkSize)->get();

            foreach ($rows as $index => $row) {
                if (!$firstBatch) {
                    fwrite($handle, ",\n");
                }

                $values = [];
                foreach ((array) $row as $value) {
                    if ($value === null) {
                        $values[] = "NULL";
                    } elseif (is_numeric($value)) {
                        $values[] = $value;
                    } else {
                        $values[] = DB::connection()->getPdo()->quote((string) $value);
                    }
                }

                fwrite($handle, "(" . implode(", ", $values) . ")");
                $firstBatch = false;
            }

            $offset += $this->chunkSize;
        }

        fwrite($handle, ";\n\n");

        return $count;
    }

    private function getPrimaryKey(string $table): ?string
    {
        $result = DB::select("SHOW KEYS FROM `{$table}` WHERE Key_name = 'PRIMARY'");

        if (!empty($result)) {
            return $result[0]->Column_name;
        }

        return null;
    }

    private function getTables(): array
    {
        $tables = DB::select('SHOW TABLES');
        $database = config('database.connections.mysql.database');
        $key = "Tables_in_{$database}";

        return array_map(function($table) use ($key) {
            return $table->$key;
        }, $tables);
    }

    private function splitSqlQueries(string $sql): array
    {
        $queries = [];
        $current = '';
        $lines = explode("\n", $sql);

        foreach ($lines as $line) {
            $current .= $line . "\n";
            if (substr(trim($line), -1) === ';') {
                $queries[] = trim($current);
                $current = '';
            }
        }

        if (trim($current) !== '') {
            $queries[] = trim($current);
        }

        return $queries;
    }

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

    private function saveMetadata(string $backupId, int $size, string $hash, int $tableCount): void
    {
        $metadata = [
            'type' => 'database',
            'size' => $size,
            'hash' => $hash,
            'table_count' => $tableCount,
            'created_at' => now()->toISOString(),
            'database' => config('database.connections.mysql.database'),
            'excluded_tables' => $this->excludedTables
        ];

        $metadataPath = storage_path("backups/metadata/{$backupId}.json");
        File::put($metadataPath, json_encode($metadata, JSON_PRETTY_PRINT));
    }

    private function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        return number_format($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
    }
}
