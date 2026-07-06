<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Backup\BackupManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    protected BackupManager $backupManager;

    public function __construct(BackupManager $backupManager)
    {
        $this->backupManager = $backupManager;
    }

    /**
     * Display backup management page
     */
    public function index()
    {
        // authorizeAccess('backup_access'); // Uncomment if you have authorization

        $backups = $this->backupManager->listBackups();
        $diskSpace = $this->getDiskSpace();

        return view('admin.backup.index', [
            'backups' => $backups,
            'diskSpace' => $diskSpace,
            'totalBackups' => count($backups),
            'totalSize' => $this->getTotalBackupSize($backups)
        ]);
    }

    /**
     * Create a new backup
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:database,files,full',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $result = $this->backupManager->create($validated['type'], [
                'description' => $validated['description'] ?? null
            ]);

            Log::channel('backup')->info('Backup created successfully', [
                'type' => $validated['type'],
                'backup_id' => $result['backup_id'] ?? ($result['path'] ?? 'unknown'),
                'size' => $result['size'] ?? 0
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Backup created successfully',
                    'data' => [
                        'backup_id' => $result['backup_id'] ?? pathinfo($result['path'] ?? '', PATHINFO_FILENAME),
                        'size' => $result['size'] ?? 0,
                        'size_formatted' => $result['size_formatted'] ?? $this->formatSize($result['size'] ?? 0),
                        'tables' => $result['tables'] ?? 0
                    ]
                ]);
            }

            return redirect()->route('admin.backup.index')->with([
                'message' => 'Backup created successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::channel('backup')->error('Backup creation failed', [
                'type' => $validated['type'],
                'error' => $e->getMessage()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup failed: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.backup.index')->with([
                'message' => 'Backup failed: ' . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }


    /**
     * Download a backup file
     */
    public function download($backupId)
    {
        // authorizeAccess('backup_download');

        $backupPath = storage_path("backups/{$backupId}.zip");

        if (!File::exists($backupPath)) {
            return redirect()->route('admin.backup.index')->with([
                'message' => 'Backup file not found.',
                'alert-type' => 'error'
            ]);
        }

        Log::channel('backup')->info('Backup downloaded', [
            'backup_id' => $backupId,
            'user_id' => auth()->id(),
            'ip' => request()->ip()
        ]);

        return response()->download($backupPath, "{$backupId}.zip", [
            'Content-Type' => 'application/zip',
            'Content-Length' => File::size($backupPath)
        ]);
    }

    /**
     * Delete a backup
     */
    public function destroy($backupId)
    {
        // authorizeAccess('backup_delete');

        try {
            $result = $this->backupManager->deleteBackup($backupId);

            if ($result) {
                Log::channel('backup')->info('Backup deleted', [
                    'backup_id' => $backupId,
                    'user_id' => auth()->id()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Backup deleted successfully.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete backup.'
            ], 500);
        } catch (\Exception $e) {
            Log::channel('backup')->error('Backup deletion failed', [
                'backup_id' => $backupId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore a backup
     */
    public function restore(Request $request, $backupId)
    {
        // authorizeAccess('backup_restore');

        $validated = $request->validate([
            'confirm' => 'required|boolean',
            'type' => 'required|string'
        ]);

        if (!$validated['confirm']) {
            return response()->json([
                'success' => false,
                'message' => 'Restore not confirmed'
            ], 400);
        }

        try {
            $result = $this->backupManager->restore($backupId, [
                'type' => $validated['type']
            ]);

            Log::channel('backup')->warning('Backup restored', [
                'backup_id' => $backupId,
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Backup restored successfully.',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::channel('backup')->error('Restore failed', [
                'backup_id' => $backupId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Restore failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify backup integrity
     */
    public function verify($backupId)
    {
        // authorizeAccess('backup_access');

        try {
            $isValid = $this->backupManager->verify($backupId);

            return response()->json([
                'success' => true,
                'valid' => $isValid,
                'message' => $isValid ? 'Backup is valid' : 'Backup integrity check failed'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Verification failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clean up old backups
     */
    public function cleanup(Request $request)
    {
        // authorizeAccess('backup_delete');

        $validated = $request->validate([
            'days' => 'nullable|integer|min:1|max:365'
        ]);

        $days = $validated['days'] ?? config('backup.retention_days', 30);
        $cutoffDate = now()->subDays($days);

        $backups = $this->backupManager->listBackups();
        $deleted = 0;
        $failed = 0;

        foreach ($backups as $backup) {
            if (strtotime($backup['created_at']) < $cutoffDate->timestamp) {
                if ($this->backupManager->deleteBackup($backup['id'])) {
                    $deleted++;
                } else {
                    $failed++;
                }
            }
        }

        Log::channel('backup')->info('Cleanup completed', [
            'deleted' => $deleted,
            'failed' => $failed,
            'days' => $days
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$deleted} old backups deleted, {$failed} failed.",
                'deleted' => $deleted,
                'failed' => $failed
            ]);
        }

        return redirect()->route('admin.backup.index')->with([
            'message' => "{$deleted} old backups deleted, {$failed} failed.",
            'alert-type' => 'success'
        ]);
    }

    /**
     * Get backup details
     */
    public function details($backupId)
    {
        // authorizeAccess('backup_access');

        $backups = $this->backupManager->listBackups();
        $backup = collect($backups)->firstWhere('id', $backupId);

        if (!$backup) {
            return response()->json([
                'success' => false,
                'message' => 'Backup not found'
            ], 404);
        }

        $metadataPath = storage_path("backups/metadata/{$backupId}.json");
        $metadata = File::exists($metadataPath) ? json_decode(File::get($metadataPath), true) : null;

        return response()->json([
            'success' => true,
            'data' => [
                'backup' => $backup,
                'metadata' => $metadata
            ]
        ]);
    }

    /**
     * Upload a backup file
     */
    public function upload(Request $request)
    {
        // authorizeAccess('backup_create');

        $validated = $request->validate([
            'backup_file' => 'required|file|mimes:zip|max:204800', // Max 200MB
            'description' => 'nullable|string|max:500'
        ]);

        try {
            $file = $request->file('backup_file');
            $originalName = $file->getClientOriginalName();
            $backupId = pathinfo($originalName, PATHINFO_FILENAME);

            // Check if backup already exists
            if (File::exists(storage_path("backups/{$backupId}.zip"))) {
                throw new \Exception('Backup with this name already exists');
            }

            // Move file to backup directory
            $file->move(storage_path('backups'), "{$backupId}.zip");

            // Save metadata
            $metadata = [
                'type' => $this->detectBackupType($backupId),
                'description' => $validated['description'],
                'uploaded_by' => auth()->id(),
                'uploaded_at' => now()->toISOString(),
                'original_name' => $originalName
            ];

            $metadataPath = storage_path("backups/metadata/{$backupId}.json");
            File::put($metadataPath, json_encode($metadata, JSON_PRETTY_PRINT));

            Log::channel('backup')->info('Backup uploaded', [
                'backup_id' => $backupId,
                'size' => File::size(storage_path("backups/{$backupId}.zip")),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Backup uploaded successfully',
                'backup_id' => $backupId
            ]);
        } catch (\Exception $e) {
            Log::channel('backup')->error('Backup upload failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get disk space information
     */
    private function getDiskSpace(): array
    {
        $backupDir = storage_path('backups');

        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $free = disk_free_space($backupDir);
        $total = disk_total_space($backupDir);
        $used = $total - $free;

        return [
            'free'            => $this->formatSize($free),
            'free_bytes'      => $free,
            'used'            => $this->formatSize($used),
            'used_bytes'      => $used,
            'total'           => $this->formatSize($total),
            'total_bytes'     => $total,
            'percentage_used' => round(($used / $total) * 100, 2),
            'percentage_free' => round(($free / $total) * 100, 2)
        ];
    }

    /**
     * Get total backup size
     */
    private function getTotalBackupSize(array $backups): string
    {
        $total = array_sum(array_column($backups, 'size'));
        return $this->formatSize($total);
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

    /**
     * Detect backup type from name
     */
    private function detectBackupType(string $backupId): string
    {
        if (str_contains($backupId, 'database')) return 'database';
        if (str_contains($backupId, 'files')) return 'files';
        if (str_contains($backupId, 'full')) return 'full';
        return 'unknown';
    }
}
