<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\View;

final class AdministrationController extends Controller
{
    public function __construct(
        private readonly Auth $auth,
        private readonly View $view
    ) {
    }

    public function index(): void
    {
        if (!$this->auth->check()) {

            header('Location: /login');
            exit;
        }

        $this->view->render(
            'administration/index',
            [
                'title' => 'Administration',
            ]
        );
    }
}
