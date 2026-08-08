<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use RuntimeException;

final class DatabaseImportService
{
    public function __construct(
        private readonly Database $database,
        private readonly BackupService $backupService,
        private readonly DatabaseExportService $exportService
    ) {
    }

    public function importFull(
        string $sqlFile,
        array $metadata = []
    ): array {
        return $this->import(
            $sqlFile,
            'full',
            $metadata
        );
    }

    public function importArchive(
        string $sqlFile,
        array $metadata = []
    ): array {
        return $this->import(
            $sqlFile,
            'archive',
            $metadata
        );
    }

    public function importSystem(
        string $sqlFile,
        array $metadata = []
    ): array {
        return $this->import(
            $sqlFile,
            'system',
            $metadata
        );
    }

    private function import(
        string $sqlFile,
        string $expectedType,
        array $metadata = []
    ): array {

        if (!file_exists($sqlFile)) {
            throw new RuntimeException(
                'SQL file not found.'
            );
        }

        if (empty($metadata)) {
            $metadata = $this->backupService->loadMetadata(
                basename($sqlFile)
            );
        }

        if (!empty($metadata)) {

            if (
                ($metadata['backup_type'] ?? '') !==
                $expectedType
            ) {
                throw new RuntimeException(
                    'Wrong backup type.'
                );
            }

            $checksum = hash_file(
                'sha256',
                $sqlFile
            );

            if (
                ($metadata['checksum'] ?? '') !==
                $checksum
            ) {
                throw new RuntimeException(
                    'Checksum verification failed.'
                );
            }
        }

        /*
         * Safety backup before import.
         */

        $this->exportService->exportFull(
            'Automatic backup before import'
        );

        $command = 'mariadb';

        if (trim((string) shell_exec('which mariadb')) === '') {
            $command = 'mysql';
        }

        $config = require dirname(__DIR__, 2)
            . '/config/database.php';

        $database = $this->database
            ->pdo()
            ->query('SELECT DATABASE()')
            ->fetchColumn();

        if (!$database) {
            throw new RuntimeException(
                'No database selected.'
            );
        }

        $cmd = sprintf(
            '%s --host=%s --user=%s --password=%s %s < %s',
            escapeshellcmd($command),
            escapeshellarg($config['host']),
            escapeshellarg($config['username']),
            escapeshellarg($config['password']),
            escapeshellarg((string) $database),
            escapeshellarg($sqlFile)
        );

        exec(
            $cmd,
            $output,
            $resultCode
        );

        if ($resultCode !== 0) {
            throw new RuntimeException(
                'Database import failed.'
            );
        }

        return [
            'success' => true,
            'type' => $expectedType,
            'filename' => basename($sqlFile),
            'metadata' => $metadata,
        ];
    }
}
