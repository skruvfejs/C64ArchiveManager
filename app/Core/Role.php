<?php

declare(strict_types=1);

namespace App\Core;

final class Role
{
    private function __construct()
    {
    }


    /**
     * System owner.
     * Has unrestricted access to the entire application.
     */
    public const SUPER_ADMIN = 1;


    /**
     * System administrator.
     * Can administer the system except Super Admin accounts.
     */
    public const ADMIN = 2;


    /**
     * Advanced contributor.
     * Can import, edit and delete archive data.
     */
    public const POWER_USER = 3;


    /**
     * Standard contributor.
     * Can browse, download and import.
     */
    public const USER = 4;


    /**
     * Read-only access.
     */
    public const READONLY = 5;


    /**
     * Registered user awaiting approval.
     * Has no permissions until assigned another role.
     */
    public const PENDING = 6;
}

