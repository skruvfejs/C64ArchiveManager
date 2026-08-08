<?php

declare(strict_types=1);

namespace App\Services;

final class BackupService
{
    private string $backupDirectory;

    public function __construct()
    {
        $this->backupDirectory = dirname(__DIR__, 2) . '/storage/backups';

        $this->createDirectory();
    }

    public function createDirectory(): void
    {
        if (!is_dir($this->backupDirectory)) {
            mkdir($this->backupDirectory, 0755, true);
        }
    }

    public function getDirectory(): string
    {
        return $this->backupDirectory;
    }

    public function listBackups(): array
    {
        $files = glob($this->backupDirectory . '/*.sql');

        if ($files === false || empty($files)) {
            return [];
        }

        usort(
            $files,
            static fn(string $a, string $b): int =>
                filemtime($b) <=> filemtime($a)
        );

        $backups = [];

        foreach ($files as $file) {

            $filename = basename($file);

            $metadata = $this->loadMetadata($filename);

            $backups[] = [
                'filename'    => $filename,
                'path'        => $file,
                'type'        => $metadata['backup_type'] ?? 'unknown',
                'description' => $metadata['description'] ?? '',
                'size'        => filesize($file),
                'sizeText'    => $this->formatSize(
                    filesize($file)
                ),
                'modified'    => filemtime($file),
                'createdBy'   => $metadata['created_by'] ?? '',
                'version'     => $metadata['version'] ?? '',
            ];
        }

        return $backups;
    }

    public function getLastBackup(): ?array
    {
        $backups = $this->listBackups();

        if (empty($backups)) {
            return null;
        }

        return $backups[0];
    }

    public function getStatistics(): array
    {
        $backups = $this->listBackups();

        $size = 0;

        foreach ($backups as $backup) {
            $size += $backup['size'];
        }

        return [
            'count' => count($backups),
            'size' => $size,
            'sizeText' => $this->formatSize($size),
        ];
    }

    public function loadMetadata(
        string $sqlFilename
    ): array {

        $jsonFile = preg_replace(
            '/\.sql$/i',
            '.json',
            $this->backupDirectory . '/' . $sqlFilename
        );

        if (
            $jsonFile === null ||
            !file_exists($jsonFile)
        ) {
            return [];
        }

        $json = file_get_contents($jsonFile);

        if ($json === false) {
            return [];
        }

        return json_decode(
            $json,
            true
        ) ?? [];
    }
    public function saveMetadata(
        string $sqlFilename,
        array $metadata
    ): void {

        $jsonFile = preg_replace(
            '/\.sql$/i',
            '.json',
            $this->backupDirectory . '/' . $sqlFilename
        );

        if ($jsonFile === null) {
            return;
        }

        file_put_contents(
            $jsonFile,
            json_encode(
                $metadata,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );
    }

    public function delete(
        string $filename
    ): bool {

        $sqlFile = $this->backupDirectory . '/' . $filename;

        $jsonFile = preg_replace(
            '/\.sql$/i',
            '.json',
            $sqlFile
        );

        $deleted = true;

        if (file_exists($sqlFile)) {
            $deleted = unlink($sqlFile);
        }

        if (
            $jsonFile !== null &&
            file_exists($jsonFile)
        ) {
            unlink($jsonFile);
        }

        return $deleted;
    }

    public function downloadPath(
        string $filename
    ): string {

        return $this->backupDirectory . '/' . $filename;
    }

    public function createFilename(
        string $type
    ): string {

        return sprintf(
            'c64archive_%s_%s.sql',
            strtolower($type),
            date('Y-m-d_His')
        );
    }

    private function formatSize(
        int $bytes
    ): string {

        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        if ($bytes < 1024 * 1024) {
            return number_format(
                $bytes / 1024,
                2
            ) . ' KB';
        }

        if ($bytes < 1024 * 1024 * 1024) {
            return number_format(
                $bytes / 1024 / 1024,
                2
            ) . ' MB';
        }

        return number_format(
            $bytes / 1024 / 1024 / 1024,
            2
        ) . ' GB';
    }
}
