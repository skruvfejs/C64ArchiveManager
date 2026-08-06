<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Repository;
use App\Entity\User;

final class UserRepository extends Repository
{
    public function findById(
        int $id
    ): ?User {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM users
            WHERE id = :id
            LIMIT 1
            '
        );

        $stmt->execute([

            'id' => $id

        ]);

        $row =
            $this->fetchOne($stmt);

        if ($row === null) {

            return null;
        }

        return $this->hydrate($row);
    }



    public function findByUsername(
        string $username
    ): ?User {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM users
            WHERE username = :username
            LIMIT 1
            '
        );

        $stmt->execute([

            'username' => $username

        ]);

        $row =
            $this->fetchOne($stmt);

        if ($row === null) {

            return null;
        }

        return $this->hydrate($row);
    }



    public function findByEmail(
        string $email
    ): ?User {

        $stmt = $this->prepare(
            '
            SELECT *
            FROM users
            WHERE email = :email
            LIMIT 1
            '
        );

        $stmt->execute([

            'email' => $email

        ]);

        $row =
            $this->fetchOne($stmt);

        if ($row === null) {

            return null;
        }

        return $this->hydrate($row);
    }



    public function existsByUsername(
        string $username
    ): bool {

        $stmt = $this->prepare(
            '
            SELECT 1
            FROM users
            WHERE username = :username
            LIMIT 1
            '
        );

        $stmt->execute([

            'username' => $username

        ]);

        return $this->fetchOne($stmt) !== null;
    }



    public function existsByEmail(
        string $email
    ): bool {

        $stmt = $this->prepare(
            '
            SELECT 1
            FROM users
            WHERE email = :email
            LIMIT 1
            '
        );

        $stmt->execute([

            'email' => $email

        ]);

        return $this->fetchOne($stmt) !== null;
    }


    /**
     * @return User[]
     */
    public function findAll(): array
    {
        $stmt = $this->prepare(
            '
            SELECT *
            FROM users
            ORDER BY username
            '
        );

        $stmt->execute();

        return array_map(

            fn(array $row): User =>
                $this->hydrate($row),

            $this->fetchAll($stmt)

        );
    }



    /**
     * @return User[]
     */
    public function findActive(): array
    {
        $stmt = $this->prepare(
            '
            SELECT *
            FROM users
            WHERE deleted_at IS NULL
            ORDER BY username
            '
        );

        $stmt->execute();

        return array_map(

            fn(array $row): User =>
                $this->hydrate($row),

            $this->fetchAll($stmt)

        );
    }



    /**
     * @return User[]
     */
    public function findDeleted(): array
    {
        $stmt = $this->prepare(
            '
            SELECT *
            FROM users
            WHERE deleted_at IS NOT NULL
            ORDER BY username
            '
        );

        $stmt->execute();

        return array_map(

            fn(array $row): User =>
                $this->hydrate($row),

            $this->fetchAll($stmt)

        );
    }



    public function create(
        User $user
    ): int {

        $stmt = $this->prepare(
            '
            INSERT INTO users
            (
                role_id,
                username,
                email,
                password,
                first_name,
                last_name,
                theme,
                language
            )
            VALUES
            (
                :role_id,
                :username,
                :email,
                :password,
                :first_name,
                :last_name,
                :theme,
                :language
            )
            '
        );

        $stmt->execute([

            'role_id' =>
                $user->getRoleId(),

            'username' =>
                $user->getUsername(),

            'email' =>
                $user->getEmail(),

            'password' =>
                $user->getPassword(),

            'first_name' =>
                $user->getFirstName(),

            'last_name' =>
                $user->getLastName(),

            'theme' =>
                $user->getTheme(),

            'language' =>
                $user->getLanguage()

        ]);

        return $this->lastInsertId();
    }


    public function update(
        User $user
    ): bool {

        $stmt = $this->prepare(
            '
            UPDATE users
            SET
                role_id = :role_id,
                username = :username,
                email = :email,
                first_name = :first_name,
                last_name = :last_name,
                theme = :theme,
                language = :language,
                updated_at = NOW()
            WHERE id = :id
            '
        );

        return $stmt->execute([

            'id' =>
                $user->getId(),

            'role_id' =>
                $user->getRoleId(),

            'username' =>
                $user->getUsername(),

            'email' =>
                $user->getEmail(),

            'first_name' =>
                $user->getFirstName(),

            'last_name' =>
                $user->getLastName(),

            'theme' =>
                $user->getTheme(),

            'language' =>
                $user->getLanguage()

        ]);
    }



    public function updatePassword(
        int $id,
        string $password
    ): bool {

        $stmt = $this->prepare(
            '
            UPDATE users
            SET
                password = :password,
                updated_at = NOW()
            WHERE id = :id
            '
        );

        return $stmt->execute([

            'id' =>
                $id,

            'password' =>
                $password

        ]);
    }



    public function markDeleted(
        int $id,
        int $deletedBy
    ): bool {

        $stmt = $this->prepare(
            '
            UPDATE users
            SET
                deleted_at = NOW(),
                deleted_by = :deleted_by,
                updated_at = NOW()
            WHERE id = :id
            '
        );

        return $stmt->execute([

            'id' =>
                $id,

            'deleted_by' =>
                $deletedBy

        ]);
    }



    public function restore(
        int $id
    ): bool {

        $stmt = $this->prepare(
            '
            UPDATE users
            SET
                deleted_at = NULL,
                deleted_by = NULL,
                updated_at = NOW()
            WHERE id = :id
            '
        );

        return $stmt->execute([

            'id' =>
                $id

        ]);
    }



    private function hydrate(
        array $row
    ): User {

        return (new User())

            ->setId(
                (int) $row['id']
            )

            ->setRoleId(
                (int) $row['role_id']
            )

            ->setUsername(
                $row['username']
            )

            ->setEmail(
                $row['email']
            )

            ->setPassword(
                $row['password']
            )

            ->setFirstName(
                $row['first_name']
            )

            ->setLastName(
                $row['last_name']
            )

            ->setTheme(
                $row['theme']
            )

            ->setLanguage(
                $row['language']
            )

            ->setLastLoginAt(
                $row['last_login_at']
            )

            ->setDeletedAt(
                $row['deleted_at']
            )

            ->setDeletedBy(
                $row['deleted_by'] !== null
                    ? (int) $row['deleted_by']
                    : null
            )

            ->setCreatedAt(
                $row['created_at']
            )

            ->setUpdatedAt(
                $row['updated_at']
            );
    }
}
