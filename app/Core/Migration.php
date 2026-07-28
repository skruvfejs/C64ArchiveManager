<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use RuntimeException;
use Throwable;

final class Migration
{
    private PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->pdo();
    }

    public function migrate(string $path): void
    {
        $this->createMigrationTable();

        $files = glob($path . '/*.php');

        if ($files === false) {
            throw new RuntimeException("Unable to read migration directory.");
        }

        sort($files);

        foreach ($files as $file) {

            $migration = basename($file);

            if ($this->isExecuted($migration)) {
                continue;
            }

            echo "Running {$migration}... ";

            $definition = require $file;

            if (!is_array($definition) || !isset($definition['up'])) {
                throw new RuntimeException(
                    "Migration '{$migration}' must return ['up'=>callable,'down'=>callable]"
                );
            }

            try {

                $definition['up']($this->pdo);

                $this->storeMigration($migration);

                echo "OK\n";

            } catch (Throwable $e) {

                echo "FAILED\n";

                throw $e;
            }
        }

        echo PHP_EOL;
        echo "All migrations completed." . PHP_EOL;
    }

    public function status(string $path): void
    {
        $this->createMigrationTable();

        $files = glob($path . '/*.php');

        if ($files === false) {
            return;
        }

        sort($files);

        echo PHP_EOL;
        echo "Migration status" . PHP_EOL;
        echo "----------------------------------------" . PHP_EOL;

        foreach ($files as $file) {

            $migration = basename($file);

            printf(
                "[%s] %s\n",
                $this->isExecuted($migration) ? "X" : " ",
                $migration
            );
        }

        echo "----------------------------------------" . PHP_EOL;
    }

    public function rollback(string $path): void
    {
        $last = $this->lastMigration();

        if ($last === null) {

            echo "Nothing to rollback." . PHP_EOL;

            return;
        }

        $file = $path . '/' . $last;

        if (!file_exists($file)) {
            throw new RuntimeException("Migration file '{$last}' not found.");
        }

        echo "Rolling back {$last}... ";

        $definition = require $file;

        if (!is_array($definition) || !isset($definition['down'])) {
            throw new RuntimeException(
                "Migration '{$last}' has no down() migration."
            );
        }

        $definition['down']($this->pdo);

        $stmt = $this->pdo->prepare(
            "DELETE FROM migrations WHERE migration = ?"
        );

        $stmt->execute([$last]);

        echo "OK" . PHP_EOL;
    }

    public function fresh(string $path): void
    {
        while ($this->lastMigration() !== null) {

            $this->rollback($path);
        }

        $this->migrate($path);
    }

    private function createMigrationTable(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS migrations
            (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL UNIQUE,
                executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    private function isExecuted(string $migration): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM migrations WHERE migration = ?"
        );

        $stmt->execute([$migration]);

        return (int)$stmt->fetchColumn() > 0;
    }

    private function storeMigration(string $migration): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO migrations (migration) VALUES (?)"
        );

        $stmt->execute([$migration]);
    }

    private function lastMigration(): ?string
    {
        $stmt = $this->pdo->query(
            "SELECT migration
             FROM migrations
             ORDER BY id DESC
             LIMIT 1"
        );

        $migration = $stmt->fetchColumn();

        if ($migration === false) {
            return null;
        }

        return $migration;
    }
}

