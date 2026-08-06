<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Authorization;
use App\Core\Auth;
use App\Core\Flash;
use App\Core\Role;
use App\Core\View;
use App\Services\RoleService;
use App\Services\UserService;

final class UserCreateController extends Controller
{
    public function __construct(
        private readonly UserService $users,
        private readonly RoleService $roles,
        private readonly Authorization $authorization,
        private readonly Auth $auth,
        private readonly View $view,
        private readonly Flash $flash
    ) {
    }



    public function index(): void
    {
        if (!$this->auth->check()) {

            header('Location: /login');

            exit;
        }



        $roles =
            $this->roles->findAll();



        /*
         * Pending används endast vid
         * självregistrering.
         */
        $roles = array_filter(
            $roles,
            function ($role) {

                return $role->getId()
                    !== Role::PENDING;
            }
        );



        /*
         * Endast Super Admin får skapa
         * nya Super Admin-konton.
         */
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
            'users/create',
            [
                'title' =>
                    'Skapa användare',

                'roles' =>
                    $roles,
            ]
        );
    }



    public function create(): void
    {
        if (!$this->auth->check()) {

            header('Location: /login');

            exit;
        }



        $roleId = (int) (
            $_POST['role_id']
            ?? Role::READONLY
        );



        if (
            $roleId === Role::PENDING
        ) {

            $this->flash->error(
                'Pending-konton kan endast skapas via registrering.'
            );

            header(
                'Location: /users/create'
            );

            exit;
        }



        if (
            $roleId === Role::SUPER_ADMIN
            &&
            !$this->authorization->isSuperAdmin()
        ) {

            $this->flash->error(
                'Endast Super Admin får skapa Super Admin-konton.'
            );

            header(
                'Location: /users/create'
            );

            exit;
        }



        $username =
            trim(
                $_POST['username'] ?? ''
            );


        $email =
            trim(
                $_POST['email'] ?? ''
            );


        $password =
            $_POST['password'] ?? '';



        if (
            $username === ''
            ||
            $email === ''
            ||
            $password === ''
        ) {

            $this->flash->error(
                'Alla obligatoriska fält måste fyllas i.'
            );

            header(
                'Location: /users/create'
            );

            exit;
        }



        if (
            $this->users->existsByUsername(
                $username
            )
        ) {

            $this->flash->error(
                'Användarnamnet finns redan.'
            );

            header(
                'Location: /users/create'
            );

            exit;
        }



        if (
            $this->users->existsByEmail(
                $email
            )
        ) {

            $this->flash->error(
                'E-postadressen finns redan.'
            );

            header(
                'Location: /users/create'
            );

            exit;
        }



        $this->users->createUser(
            $roleId,
            $username,
            $email,
            password_hash(
                $password,
                PASSWORD_DEFAULT
            ),
            $_POST['first_name'] ?? null,
            $_POST['last_name'] ?? null
        );



        $this->flash->success(
            'Användaren har skapats.'
        );



        header(
            'Location: /users'
        );

        exit;
    }
}
