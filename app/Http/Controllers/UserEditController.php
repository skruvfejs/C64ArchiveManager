<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Authorization;
use App\Core\Role;
use App\Core\View;
use App\Services\RoleService;
use App\Services\UserService;

final class UserEditController extends Controller
{
    public function __construct(
        private readonly UserService $users,
        private readonly RoleService $roles,
        private readonly Auth $auth,
        private readonly Authorization $authorization,
        private readonly View $view
    ) {
    }



    public function index(): void
    {
        if (!$this->auth->check()) {

            header('Location: /login');

            exit;
        }


        $id = (int) (
            $_GET['id'] ?? 0
        );


        $user =
            $this->users->findById($id);


        if ($user === null) {

            http_response_code(404);

            echo 'Användaren hittades inte.';

            return;
        }


        $roles =
            $this->roles->findAll();


        if (
            !$this->authorization->isSuperAdmin()
        ) {

            $roles = array_filter(
                $roles,
                function ($role) {

                    return $role->getId()
                        !== Role::SUPER_ADMIN;
                }
            );
        }


        $this->view->render(
            'users/edit',
            [
                'title' => 'Redigera användare',
                'user'  => $user,
                'roles' => $roles,
            ]
        );
    }



    public function update(): void
    {
        if (!$this->auth->check()) {

            header('Location: /login');

            exit;
        }


        $id = (int) (
            $_POST['id'] ?? 0
        );


        $user =
            $this->users->findById($id);


        if ($user === null) {

            http_response_code(404);

            echo 'Användaren hittades inte.';

            return;
        }


        $currentUserId =
            $this->auth->id();


        $roleId = (int) (
            $_POST['role_id']
            ?? $user->getRoleId()
        );
        /*
         * Skydda Super Admin-konton.
         *
         * Ett Super Admin-konto får endast
         * ändras av samma konto.
         */
        if (
            $user->getRoleId()
            === Role::SUPER_ADMIN
            &&
            $currentUserId
            !== $user->getId()
        ) {

            http_response_code(403);

            echo 'Du får inte ändra andra Super Admin-konton.';

            return;
        }



        /*
         * Skydda sista Super Admin.
         *
         * Om detta är sista aktiva Super Admin
         * får rollen inte ändras till något annat.
         */
        if (
            $user->getRoleId()
            === Role::SUPER_ADMIN
            &&
            $roleId !== Role::SUPER_ADMIN
            &&
            $this->users->countByRoleId(
                Role::SUPER_ADMIN
            ) <= 1
        ) {

            http_response_code(403);

            echo 'Det måste finnas minst en Super Admin kvar.';

            return;
        }



        /*
         * Endast Super Admin får skapa
         * nya Super Admin-konton.
         */
        if (
            $roleId === Role::SUPER_ADMIN
            &&
            !$this->authorization->isSuperAdmin()
        ) {

            http_response_code(403);

            echo 'Endast Super Admin får tilldela Super Admin-rollen.';

            return;
        }



        $user->setRoleId(
            $roleId
        );


        $this->users->update(
            $user
        );


        header(
            'Location: /users'
        );

        exit;
    }
}
