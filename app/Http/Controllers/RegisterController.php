<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Services\RegistrationService;
use RuntimeException;

final class RegisterController extends Controller
{
    public function __construct(
        private readonly RegistrationService $registration,
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
            'auth/register',
            [
                'title' => 'Registrera användare',
            ]
        );
    }

    public function register(): void
    {
        if ($this->auth->check()) {

            header('Location: /');
            exit;
        }

        $username = trim(
            $_POST['username'] ?? ''
        );

        $email = trim(
            $_POST['email'] ?? ''
        );

        $password =
            $_POST['password'] ?? '';

        $firstName = trim(
            $_POST['first_name'] ?? ''
        );

        $lastName = trim(
            $_POST['last_name'] ?? ''
        );

        try {

            $this->registration->register(
                $username,
                $email,
                $password,
                $firstName !== ''
                    ? $firstName
                    : null,
                $lastName !== ''
                    ? $lastName
                    : null
            );

            $this->view->render(
                'auth/register',
                [
                    'title'   => 'Registrera användare',
                    'success' =>
                        'Kontot har skapats. En administratör måste tilldela en roll innan kontot kan användas.',
                ]
            );

            return;

        } catch (RuntimeException $exception) {

            $this->showError(
                $exception->getMessage()
            );

            return;
        }
    }

    private function showError(
        string $message
    ): void {

        $this->view->render(
            'auth/register',
            [
                'title' => 'Registrera användare',
                'error' => $message,
            ]
        );
    }
}

