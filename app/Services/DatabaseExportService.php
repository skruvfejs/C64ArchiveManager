<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use RuntimeException;

final class DatabaseExportService
{
    public function __construct(
        private readonly Database $database,
        private readonly BackupService $backupService
    ) {
    }

    public function exportFull(
        string $description = ''
    ): array {

        return $this->export(
            'full',
            [],
            $description
        );
    }

    public function exportArchive(
        string $description = ''
    ): array {

        return $this->export(
            'archive',
            [
                'entries',
                'releases',
                'release_files',
                'directory_entries',
                'entry_types',
                'tags',
                'entry_tags',
                'images',
            ],
            $description
        );
    }

    public function exportSystem(
        string $description = ''
    ): array {

        return $this->export(
            'system',
            [
                'users',
                'roles',
                'audit_logs',
                'import_logs',
            ],
            $description
        );
    }

    private function export(
        string $type,
        array $tables,
        string $description
    ): array {

        $filename = $this->backupService
            ->createFilename($type);

        $sqlFile =
            $this->backupService
                ->downloadPath($filename);

        $database =
            $this->database
                ->pdo()
                ->query('SELECT DATABASE()')
                ->fetchColumn();

        if (!$database) {
            throw new RuntimeException(
                'No database selected.'
            );
        }
        $command = 'mariadb-dump';

        if (trim((string) shell_exec('which mariadb-dump')) === '') {
            $command = 'mysqldump';
        }

        $config = require dirname(__DIR__, 2)
            . '/config/database.php';

        $cmd = sprintf(
            '%s --host=%s --user=%s --password=%s %s',
            escapeshellcmd($command),
            escapeshellarg($config['host']),
            escapeshellarg($config['username']),
            escapeshellarg($config['password']),
            escapeshellarg($database)
        );

        if (!empty($tables)) {
            $cmd .= ' ' . implode(
                ' ',
                array_map(
                    static fn(string $table): string =>
                        escapeshellarg($table),
                    $tables
                )
            );
        }

        $cmd .= ' > ' . escapeshellarg($sqlFile);

        exec(
            $cmd,
            $output,
            $resultCode
        );

        if ($resultCode !== 0) {
            throw new RuntimeException(
                'Database export failed.'
            );
        }

        $metadata = [
            'format'              => 1,
            'application'         => 'C64 Archive Manager',
            'version'             => '1.0.0',
            'backup_type'         => $type,
            'description'         => $description,
            'created_at'          => date('Y-m-d H:i:s'),
            'database'            => $database,
            'database_version'    => $this->database
                ->pdo()
                ->query('SELECT VERSION()')
                ->fetchColumn(),
            'checksum'            => hash_file(
                'sha256',
                $sqlFile
            ),
        ];

        $this->backupService->saveMetadata(
            $filename,
            $metadata
        );
        return [
            'filename' => $filename,
            'path' => $sqlFile,
            'metadata' => $metadata,
        ];
    }
}
