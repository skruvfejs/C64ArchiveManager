<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Role;
use App\Repositories\RoleRepository;
use RuntimeException;

final class RoleService
{
    public function __construct(
        private readonly RoleRepository $repository
    ) {
    }

    public function findById(
        int $id
    ): ?Role {

        return $this->repository
            ->findById($id);
    }

    public function findByName(
        string $name
    ): ?Role {

        return $this->repository
            ->findByName($name);
    }

    public function findAll(): array
    {
        return $this->repository
            ->findAll();
    }

    public function getPendingRole(): Role
    {
        $role = $this->repository
            ->findByName('Pending');

        if ($role === null) {

            throw new RuntimeException(
                'Rollen "Pending" finns inte.'
            );
        }

        return $role;
    }

    public function getReadOnlyRole(): Role
    {
        $role = $this->repository
            ->findByName('ReadOnly');

        if ($role === null) {

            throw new RuntimeException(
                'Rollen "ReadOnly" finns inte.'
            );
        }

        return $role;
    }

    public function getUserRole(): Role
    {
        $role = $this->repository
            ->findByName('User');

        if ($role === null) {

            throw new RuntimeException(
                'Rollen "User" finns inte.'
            );
        }

        return $role;
    }

    public function getPowerUserRole(): Role
    {
        $role = $this->repository
            ->findByName('Power User');

        if ($role === null) {

            throw new RuntimeException(
                'Rollen "Power User" finns inte.'
            );
        }

        return $role;
    }

    public function getAdminRole(): Role
    {
        $role = $this->repository
            ->findByName('Admin');

        if ($role === null) {

            throw new RuntimeException(
                'Rollen "Admin" finns inte.'
            );
        }

        return $role;
    }

    public function getSuperAdminRole(): Role
    {
        $role = $this->repository
            ->findByName('Super Admin');

        if ($role === null) {

            throw new RuntimeException(
                'Rollen "Super Admin" finns inte.'
            );
        }

        return $role;
    }
}

