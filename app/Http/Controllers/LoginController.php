<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Entity\User;
use App\Services\UserService;

final class LoginController extends Controller
{
    public function __construct(
        private readonly UserService $users,
        private readonly Auth $auth,
        private readonly View $view
    ) {
    }

    public function index(): void
    {
        if ($this->auth->check()) {

            header('Location: /');
            exit;
        }

        $this->view->render(
            'auth/login',
            [
                'title' => 'Logga in'
            ]
        );
    }

    public function login(): void
    {
        $username = trim(
            $_POST['username'] ?? ''
        );

        $password =
            $_POST['password'] ?? '';

        if (
            $username === ''
            || $password === ''
        ) {

            $this->showError(
                'Användarnamn och lösenord måste anges.'
            );

            return;
        }

        $user =
            $this->users
                ->findByUsername(
                    $username
                );

        if (
            $user === null
            || !$this->verifyPassword(
                $user,
                $password
            )
        ) {

            $this->showError(
                'Felaktigt användarnamn eller lösenord.'
            );

            return;
        }

        $this->auth->login([
            'id'         => $user->getId(),
            'role_id'    => $user->getRoleId(),
            'username'   => $user->getUsername(),
            'email'      => $user->getEmail(),
            'first_name' => $user->getFirstName(),
            'last_name'  => $user->getLastName(),
        ]);

        $this->users->updateLastLogin(
            $user->getId()
        );

        header('Location: /');
        exit;
    }
    private function verifyPassword(
        User $user,
        string $password
    ): bool {

        return password_verify(
            $password,
            $user->getPassword()
        );
    }



    private function showError(
        string $message
    ): void {

        $this->view->render(
            'auth/login',
            [
                'title' => 'Logga in',
                'error' => $message,
            ]
        );
    }
}
