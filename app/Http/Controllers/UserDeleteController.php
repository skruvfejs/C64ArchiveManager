<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Flash;
use App\Core\Role;
use App\Services\UserService;

final class UserDeleteController extends Controller
{
    public function __construct(
        private readonly UserService $users,
        private readonly Auth $auth,
        private readonly Flash $flash
    ) {
    }



    public function delete(): void
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

            $this->flash->error(
                'Användaren hittades inte.'
            );

            header(
                'Location: /users'
            );

            exit;
        }



        /*
         * Förhindra att man tar bort sig själv.
         */
        if (
            $this->auth->id()
            === $user->getId()
        ) {

            $this->flash->error(
                'Du kan inte ta bort ditt eget konto.'
            );

            header(
                'Location: /users'
            );

            exit;
        }



        /*
         * Super Admin-konton skyddas.
         */
        if (
            $user->getRoleId()
            === Role::SUPER_ADMIN
        ) {

            $this->flash->error(
                'Super Admin-konton kan inte tas bort.'
            );

            header(
                'Location: /users'
            );

            exit;
        }



        $result =
            $this->users->delete(
                $id,
                $this->auth->id()
            );



        if (!$result) {

            $this->flash->error(
                'Kunde inte ta bort användaren.'
            );

            header(
                'Location: /users'
            );

            exit;
        }



        $this->flash->success(
            'Användaren har tagits bort.'
        );



        header(
            'Location: /users'
        );

        exit;
    }
}
