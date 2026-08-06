<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Authorization;
use App\Core\Auth;
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
        private readonly View $view
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
         *
         * Nya användare skapade av admin
         * ska alltid få en aktiv roll.
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



        /*
         * Pending får aldrig skapas
         * via adminpanelen.
         */
        if (
            $roleId === Role::PENDING
        ) {

            http_response_code(403);

            echo 'Pending-konton kan endast skapas via registrering.';

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

            echo 'Endast Super Admin får skapa Super Admin-konton.';

            return;
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

            http_response_code(400);

            echo 'Alla obligatoriska fält måste fyllas i.';

            return;
        }



        if (
            $this->users->existsByUsername(
                $username
            )
        ) {

            http_response_code(400);

            echo 'Användarnamnet finns redan.';

            return;
        }



        if (
            $this->users->existsByEmail(
                $email
            )
        ) {

            http_response_code(400);

            echo 'E-postadressen finns redan.';

            return;
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



        header(
            'Location: /users'
        );

        exit;
    }
}

