<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Core\Permission;

final class EditMiddleware extends PermissionMiddleware
{
    protected function permission(): string
    {
        return Permission::EDIT;
    }
}
