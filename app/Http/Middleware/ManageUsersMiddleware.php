<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Core\Authorization;

final class ManageUsersMiddleware extends PermissionMiddleware
{
    protected function permission(): string
    {
        return 'manage_users';
    }
}
