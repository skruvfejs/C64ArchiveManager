<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Role;
use App\Core\View;
use App\Services\UserService;

final class DeletedUsersController extends Controller
{
    public function __construct(
        private readonly UserService $users,
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
            'users/deleted',
            [
                'title' =>
                    'Borttagna användare',

                'users' =>
                    $this->users->findDeleted(),
            ]
        );
    }



    public function restore(): void
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
         * Super Admin-konton hanteras
         * inte via återställning.
         */
        if (
            $user->getRoleId()
            === Role::SUPER_ADMIN
        ) {

            http_response_code(403);

            echo 'Super Admin-konton kan inte återställas här.';

            return;
        }



        if (
            !$user->isDeleted()
        ) {

            http_response_code(400);

            echo 'Användaren är inte borttagen.';

            return;
        }



        $this->users->restore(
            $id
        );


        header(
            'Location: /users/deleted'
        );

        exit;
    }
}
