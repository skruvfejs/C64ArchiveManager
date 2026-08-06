<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Core\Authorization;
use RuntimeException;

abstract class PermissionMiddleware extends Middleware
{
    public function __construct(
        private readonly Authorization $authorization
    ) {
    }

    /**
     * Return the permission required by this middleware.
     */
    abstract protected function permission(): string;

    public function handle(): void
    {
        try {

            $this->authorization->authorize(
                $this->permission()
            );

        } catch (RuntimeException) {

            $this->onUnauthorized();

        }
    }

    /**
     * Called when the current user lacks the required permission.
     */
    protected function onUnauthorized(): void
    {
        http_response_code(403);

        echo '403 Forbidden';

        exit;
    }
}

