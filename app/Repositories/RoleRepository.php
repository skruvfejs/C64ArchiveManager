<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Entity\Role;
use PDO;

final class RoleRepository
{
    private PDO $pdo;

    public function __construct(
        Database $database
    ) {
        $this->pdo = $database->pdo();
    }

    public function findById(
        int $id
    ): ?Role {

        $stmt = $this->pdo->prepare(
            'SELECT *
             FROM roles
             WHERE id = :id
             LIMIT 1'
        );

        $stmt->execute([
            'id' => $id,
        ]);

        $row = $stmt->fetch(
            PDO::FETCH_ASSOC
        );

        if ($row === false) {

            return null;
        }

        return $this->map(
            $row
        );
    }

    public function findByName(
        string $name
    ): ?Role {

        $stmt = $this->pdo->prepare(
            'SELECT *
             FROM roles
             WHERE name = :name
             LIMIT 1'
        );

        $stmt->execute([
            'name' => $name,
        ]);

        $row = $stmt->fetch(
            PDO::FETCH_ASSOC
        );

        if ($row === false) {

            return null;
        }

        return $this->map(
            $row
        );
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT *
             FROM roles
             ORDER BY id'
        );

        $roles = [];

        while (
            $row = $stmt->fetch(
                PDO::FETCH_ASSOC
            )
        ) {

            $roles[] =
                $this->map($row);
        }

        return $roles;
    }

    private function map(
        array $row
    ): Role {

        return (new Role())

            ->setId(
                (int)$row['id']
            )

            ->setName(
                $row['name']
            );
    }
}

