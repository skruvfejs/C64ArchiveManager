<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Flash;
use App\Core\View;
use App\Services\AuditLogService;
use App\Services\UserService;

final class PasswordController extends Controller
{
    public function __construct(
        private readonly Auth $auth,
        private readonly UserService $users,
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
            'account/password',
            [
                'title' =>
                    'Ändra lösenord',
            ]
        );
    }



    public function update(): void
    {
        if (!$this->auth->check()) {

            header('Location: /login');

            exit;
        }



        $userId =
            $this->auth->id();



        $user =
            $this->users->findById(
                $userId
            );



        if ($user === null) {

            $this->flash->error(
                'Användaren hittades inte.'
            );

            header(
                'Location: /account/password'
            );

            exit;
        }



        $currentPassword =
            $_POST['current_password'] ?? '';



        $newPassword =
            $_POST['password'] ?? '';



        $confirmPassword =
            $_POST['password_confirmation'] ?? '';



        if (
            !password_verify(
                $currentPassword,
                $user->getPassword()
            )
        ) {

            $this->flash->error(
                'Nuvarande lösenord är fel.'
            );

            header(
                'Location: /account/password'
            );

            exit;
        }



        if (
            $newPassword !== $confirmPassword
        ) {

            $this->flash->error(
                'De nya lösenorden matchar inte.'
            );

            header(
                'Location: /account/password'
            );

            exit;
        }



        if (
            strlen($newPassword) < 8
        ) {

            $this->flash->error(
                'Lösenordet måste vara minst 8 tecken.'
            );

            header(
                'Location: /account/password'
            );

            exit;
        }



        $this->users->changePassword(
            $userId,
            password_hash(
                $newPassword,
                PASSWORD_DEFAULT
            )
        );



        $this->auditLog->log(
            'CHANGE_PASSWORD',
            'User',
            $userId,
            'Ändrade sitt lösenord'
        );



        $this->flash->success(
            'Lösenordet har ändrats.'
        );



        header(
            'Location: /account/password'
        );

        exit;
    }
}
