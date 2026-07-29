<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\User;

final class LoginController extends Controller
{
    public function __construct(
        private readonly User $users,
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
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {

            $this->showError(
                'Användarnamn och lösenord måste anges.'
            );

            return;
        }

        $user = $this->users->findByUsername($username);

        if (
            $user === null ||
            !password_verify($password, $user['password'])
        ) {

            $this->showError(
                'Felaktigt användarnamn eller lösenord.'
            );

            return;
        }

        $this->auth->login($user);

        header('Location: /');
        exit;
    }

    private function showError(string $message): void
    {
        $this->view->render(
            'auth/login',
            [
                'title' => 'Logga in',
                'error' => $message
            ]
        );
    }
}

