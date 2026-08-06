<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\Repositories\UserRepository;

final class UserService
{
    public function __construct(
        private UserRepository $repository
    ) {
    }



    public function findById(
        int $id
    ): ?User {

        return $this->repository
                    ->findById($id);
    }



    public function findByUsername(
        string $username
    ): ?User {

        return $this->repository
                    ->findByUsername($username);
    }



    public function findByEmail(
        string $email
    ): ?User {

        return $this->repository
                    ->findByEmail($email);
    }



    /**
     * @return User[]
     */
    public function findAll(): array
    {
        return $this->repository
                    ->findAll();
    }



    /**
     * @return User[]
     */
    public function findActive(): array
    {
        return $this->repository
                    ->findActive();
    }



    /**
     * @return User[]
     */
    public function findDeleted(): array
    {
        return $this->repository
                    ->findDeleted();
    }



    /**
     * Count active users with a role.
     */
    public function countByRoleId(
        int $roleId
    ): int {

        return $this->repository
                    ->countByRoleId($roleId);
    }



    public function existsByUsername(
        string $username
    ): bool {

        return $this->repository
                    ->existsByUsername($username);
    }



    public function existsByEmail(
        string $email
    ): bool {

        return $this->repository
                    ->existsByEmail($email);
    }



    public function create(
        User $user
    ): int {

        return $this->repository
                    ->create($user);
    }



    public function update(
        User $user
    ): bool {

        return $this->repository
                    ->update($user);
    }
    public function changePassword(
        int $id,
        string $passwordHash
    ): bool {

        return $this->repository
                    ->updatePassword(
                        $id,
                        $passwordHash
                    );
    }



    public function delete(
        int $id,
        int $deletedBy
    ): bool {

        $user =
            $this->findById($id);


        if ($user === null) {

            return false;
        }


        if ($user->isDeleted()) {

            return false;
        }


        return $this->repository
                    ->markDeleted(
                        $id,
                        $deletedBy
                    );
    }



    public function restore(
        int $id
    ): bool {

        $user =
            $this->findById($id);


        if ($user === null) {

            return false;
        }


        if (!$user->isDeleted()) {

            return false;
        }


        return $this->repository
                    ->restore($id);
    }



    public function createUser(
        int $roleId,
        string $username,
        string $email,
        string $passwordHash,
        ?string $firstName = null,
        ?string $lastName = null
    ): int {

        $user = (new User())

            ->setRoleId(
                $roleId
            )

            ->setUsername(
                $username
            )

            ->setEmail(
                $email
            )

            ->setPassword(
                $passwordHash
            )

            ->setFirstName(
                $firstName
            )

            ->setLastName(
                $lastName
            );


        return $this->repository
                    ->create($user);
    }



    public function updateLastLogin(
        int $id
    ): bool {

        $user =
            $this->findById($id);


        if ($user === null) {

            return false;
        }


        $user->setLastLoginAt(
            date('Y-m-d H:i:s')
        );


        return $this->repository
                    ->update($user);
    }
}
