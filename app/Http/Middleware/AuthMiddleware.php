<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Core\Auth;

final class AuthMiddleware
{
    public function __construct(
        private readonly Auth $auth
    ) {
    }

    public function handle(): void
    {
        if ($this->auth->check()) {
            return;
        }

        header('Location: /login');
        exit;
    }
}


