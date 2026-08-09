<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;

final class DatabaseService
{
    public function __construct(
        private readonly Database $database
    ) {
    }

    private function pdo(): PDO
    {
        return $this->database->pdo();
    }

    public function getDatabaseName(): string
    {
        return (string) $this->pdo()
            ->query('SELECT DATABASE()')
            ->fetchColumn();
    }

    public function getVersion(): string
    {
        return (string) $this->pdo()
            ->query('SELECT VERSION()')
            ->fetchColumn();
    }

    public function getTableCount(): int
    {
        return count(
            $this->pdo()
                ->query('SHOW TABLES')
                ->fetchAll()
        );
    }

    public function getDatabaseSize(): string
    {
        $statement = $this->pdo()->prepare(
            '
            SELECT
                SUM(data_length + index_length)
            FROM
                information_schema.TABLES
            WHERE
                table_schema = DATABASE()
            '
        );

        $statement->execute();

        $bytes = (int) $statement->fetchColumn();

        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        if ($bytes < 1024 * 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        if ($bytes < 1024 * 1024 * 1024) {
            return number_format($bytes / 1024 / 1024, 2) . ' MB';
        }

        return number_format(
            $bytes / 1024 / 1024 / 1024,
            2
        ) . ' GB';
    }
}
