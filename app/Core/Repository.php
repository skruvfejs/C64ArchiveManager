<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOStatement;

abstract class Repository
{
    protected PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->pdo();
    }

    protected function prepare(string $sql): PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    protected function fetchOne(PDOStatement $statement): ?array
    {
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : $row;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function fetchAll(PDOStatement $statement): array
    {
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function lastInsertId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }

    protected function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    protected function commit(): void
    {
        $this->pdo->commit();
    }

    protected function rollBack(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
    }

    protected function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }
}
