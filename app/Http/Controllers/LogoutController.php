<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;

final class LogoutController extends Controller
{
    public function __construct(
        private readonly Auth $auth
    ) {
    }

    public function index(): void
    {
        $this->auth->logout();

        header('Location: /login');
        exit;
    }
}

