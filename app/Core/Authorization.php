<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Authorization
{
    /**
     * @var array<int, array<int, string>>
     */
    private const PERMISSIONS = [

        Role::SUPER_ADMIN => [
            '*',
        ],


        Role::ADMIN => [

            Permission::VIEW,
            Permission::DOWNLOAD,

            Permission::IMPORT,

            Permission::EDIT,
            Permission::DELETE,

            Permission::MANAGE_USERS,
            Permission::MANAGE_ROLES,
            Permission::MANAGE_SYSTEM,
            Permission::MANAGE_IMPORTS,

            Permission::VIEW_LOGS,
        ],


        Role::POWER_USER => [

            Permission::VIEW,
            Permission::DOWNLOAD,

            Permission::IMPORT,

            Permission::EDIT,
            Permission::DELETE,
        ],


        Role::USER => [

            Permission::VIEW,
            Permission::DOWNLOAD,

            Permission::IMPORT,
        ],


        Role::READONLY => [

            Permission::VIEW,
            Permission::DOWNLOAD,
        ],


        /*
         * Registered user awaiting approval.
         *
         * No permissions.
         */
        Role::PENDING => [],
    ];
    public function __construct(
        private readonly Auth $auth
    ) {
    }


    public function roleId(): ?int
    {
        return $this->auth->roleId();
    }


    /**
     * @return string[]
     */
    public function permissions(): array
    {
        $roleId = $this->roleId();

        if ($roleId === null) {

            return [];
        }

        return self::PERMISSIONS[$roleId] ?? [];
    }


    public function can(
        string $permission
    ): bool {

        $permissions = $this->permissions();

        if (
            in_array(
                '*',
                $permissions,
                true
            )
        ) {

            return true;
        }

        return in_array(
            $permission,
            $permissions,
            true
        );
    }


    public function authorize(
        string $permission
    ): void {

        if (
            $this->can($permission)
        ) {

            return;
        }

        throw new RuntimeException(
            sprintf(
                'Permission denied: %s',
                $permission
            )
        );
    }


    public function isSuperAdmin(): bool
    {
        return $this->roleId()
            === Role::SUPER_ADMIN;
    }


    public function isAdmin(): bool
    {
        return $this->roleId()
            === Role::ADMIN;
    }


    public function isPowerUser(): bool
    {
        return $this->roleId()
            === Role::POWER_USER;
    }


    public function isUser(): bool
    {
        return $this->roleId()
            === Role::USER;
    }


    public function isReadonly(): bool
    {
        return $this->roleId()
            === Role::READONLY;
    }


    public function isPending(): bool
    {
        return $this->roleId()
            === Role::PENDING;
    }
}
