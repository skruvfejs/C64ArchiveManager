<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class User
{
    private PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->pdo();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT *
             FROM users
             WHERE id = :id
             LIMIT 1'
        );

        $stmt->execute([
            'id' => $id
        ]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT *
             FROM users
             WHERE username = :username
               AND active = 1
             LIMIT 1'
        );

        $stmt->execute([
            'username' => $username
        ]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT *
             FROM users
             ORDER BY username'
        );

        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users
            (
                role_id,
                username,
                email,
                password,
                first_name,
                last_name,
                active
            )
            VALUES
            (
                :role_id,
                :username,
                :email,
                :password,
                :first_name,
                :last_name,
                :active
            )'
        );

        $stmt->execute([
            'role_id'    => $data['role_id'],
            'username'   => $data['username'],
            'email'      => $data['email'],
            'password'   => $data['password'],
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'active'     => $data['active'] ?? 1,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE users
             SET
                role_id = :role_id,
                username = :username,
                email = :email,
                first_name = :first_name,
                last_name = :last_name,
                active = :active,
                updated_at = NOW()
             WHERE id = :id'
        );

        return $stmt->execute([
            'id'         => $id,
            'role_id'    => $data['role_id'],
            'username'   => $data['username'],
            'email'      => $data['email'],
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'active'     => $data['active'],
        ]);
    }

    public function updatePassword(int $id, string $passwordHash): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE users
             SET
                password = :password,
                updated_at = NOW()
             WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id,
            'password' => $passwordHash,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM users
             WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}

