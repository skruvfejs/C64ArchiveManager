<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Authorization;
use App\Core\View;
use App\Http\Request;
use App\Services\RoleService;
use App\Services\SettingsService;
use App\Services\UserService;

final class UsersController extends Controller
{
    public function __construct(
        private readonly UserService $users,
        private readonly RoleService $roles,
        private readonly Authorization $authorization,
        private readonly Auth $auth,
        private readonly View $view,
        private readonly Request $request,
        private readonly SettingsService $settings
    ) {
    }


    public function index(): void
    {
        if (!$this->auth->check()) {

            header('Location: /login');

            exit;
        }


        $page =
            max(
                1,
                (int) $this->request->query('page', 1)
            );


        $perPage =
            (int) $this->settings->get(
                'items_per_page',
                '25'
            );


        $total =
            $this->users->countActive();


        $pages =
            (int) ceil(
                $total / $perPage
            );


        $offset =
            ($page - 1) * $perPage;


        $users =
            $this->users->findActive(
                $perPage,
                $offset
            );


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
                    $users,

                'page' =>
                    $page,

                'pages' =>
                    $pages,

                'total' =>
                    $total,

                'perPage' =>
                    $perPage,

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

