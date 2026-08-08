<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Core\Permission;


final class ViewLogsMiddleware extends PermissionMiddleware
{

    protected function permission(): string
    {
        return Permission::VIEW_LOGS;
    }


    protected function onUnauthorized(): void
    {
        http_response_code(403);

        echo '403 Forbidden';

        exit;
    }
}
