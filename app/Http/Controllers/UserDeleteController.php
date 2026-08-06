<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Role;
use App\Services\UserService;

final class UserDeleteController extends Controller
{
    public function __construct(
        private readonly UserService $users,
        private readonly Auth $auth
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

            http_response_code(404);

            echo 'Användaren hittades inte.';

            return;
        }



        /*
         * Förhindra att man tar bort sig själv.
         */
        if (
            $this->auth->id()
            === $user->getId()
        ) {

            http_response_code(403);

            echo 'Du kan inte ta bort ditt eget konto.';

            return;
        }



        /*
         * Super Admin-konton skyddas.
         */
        if (
            $user->getRoleId()
            === Role::SUPER_ADMIN
        ) {

            http_response_code(403);

            echo 'Super Admin-konton kan inte tas bort.';

            return;
        }



        $result =
            $this->users->delete(
                $id,
                $this->auth->id()
            );



        if (!$result) {

            http_response_code(500);

            echo 'Kunde inte ta bort användaren.';

            return;
        }



        header(
            'Location: /users'
        );

        exit;
    }
}
