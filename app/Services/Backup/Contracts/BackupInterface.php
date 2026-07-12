<?php

namespace App\Services\Backup\Contracts;

interface BackupInterface
{
    public function create(string $backupId, array $options = []): array;
    public function restore(string $backupId, array $options = []): array;
    public function list(): array;
    public function delete(string $backupId): bool;
    public function getSize(string $backupId): int;
    public function verify(string $backupId): bool;
}
