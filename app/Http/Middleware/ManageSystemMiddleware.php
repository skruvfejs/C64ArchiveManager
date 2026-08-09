<?php

declare(strict_types=1);

namespace App\Http\Middleware;

final class ManageSystemMiddleware extends PermissionMiddleware
{
    protected function permission(): string
    {
        return 'manage_system';
    }
}
