<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Authorization;
use App\Core\View;
use App\Services\RoleService;
use App\Services\UserService;

final class UsersController extends Controller
{
    public function __construct(
        private readonly UserService $users,
        private readonly RoleService $roles,
        private readonly Authorization $authorization,
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
            'users/index',
            [
                'title' => 'Användare',

                /*
                 * Visa endast aktiva användare.
                 * Soft deleted användare visas
                 * under separat vy senare.
                 */
                'users' =>
                    $this->users->findActive(),

                'roles' =>
                    $this->roles->findAll(),


                /*
                 * Används av vyn för att
                 * dölja redigering av Super Admin
                 * för vanliga administratörer.
                 */
                'isSuperAdmin' =>
                    $this->authorization
                        ->isSuperAdmin(),
            ]
        );
    }
}

