<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Flash;
use App\Core\Role;
use App\Core\View;
use App\Services\AuditLogService;
use App\Services\UserService;

final class DeletedUsersController extends Controller
{
    public function __construct(
        private readonly UserService $users,
        private readonly Auth $auth,
        private readonly View $view,
        private readonly Flash $flash,
        private readonly AuditLogService $auditLog
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

            $this->flash->error(
                'Användaren hittades inte.'
            );

            header(
                'Location: /users/deleted'
            );

            exit;
        }



        /*
         * Super Admin-konton hanteras
         * inte via återställning.
         */
        if (
            $user->getRoleId()
            === Role::SUPER_ADMIN
        ) {

            $this->flash->error(
                'Super Admin-konton kan inte återställas här.'
            );

            header(
                'Location: /users/deleted'
            );

            exit;
        }



        if (
            !$user->isDeleted()
        ) {

            $this->flash->error(
                'Användaren är inte borttagen.'
            );

            header(
                'Location: /users/deleted'
            );

            exit;
        }



        $this->users->restore(
            $id
        );



        $this->auditLog->log(
            'RESTORE',
            'User',
            $id,
            'Återställde användaren '
            . $user->getUsername()
        );



        $this->flash->success(
            'Användaren har återställts.'
        );



        header(
            'Location: /users/deleted'
        );

        exit;
    }
}
