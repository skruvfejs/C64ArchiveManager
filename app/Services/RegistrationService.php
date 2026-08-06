<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class RegistrationService
{
    public function __construct(
        private readonly UserService $users,
        private readonly RoleService $roles
    ) {
    }

    public function register(
        string $username,
        string $email,
        string $password,
        ?string $firstName = null,
        ?string $lastName = null
    ): int {

        $username = trim($username);
        $email = trim($email);

        $this->validateUsername(
            $username
        );

        $this->validateEmail(
            $email
        );

        $this->validatePassword(
            $password
        );

        if (
            $this->users->existsByUsername(
                $username
            )
        ) {

            throw new RuntimeException(
                'Användarnamnet används redan.'
            );
        }

        if (
            $this->users->existsByEmail(
                $email
            )
        ) {

            throw new RuntimeException(
                'E-postadressen används redan.'
            );
        }

        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $pendingRole =
            $this->roles->getPendingRole();

        return $this->users->createUser(
            $pendingRole->getId(),
            $username,
            $email,
            $passwordHash,
            $firstName,
            $lastName
        );
    }

    public function usernameAvailable(
        string $username
    ): bool
    {
        return !$this->users->existsByUsername(
            trim($username)
        );
    }

    public function emailAvailable(
        string $email
    ): bool
    {
        return !$this->users->existsByEmail(
            trim($email)
        );
    }
    private function validateUsername(
        string $username
    ): void {

        if ($username === '') {

            throw new RuntimeException(
                'Användarnamn måste anges.'
            );
        }

        if (mb_strlen($username) < 3) {

            throw new RuntimeException(
                'Användarnamnet måste vara minst 3 tecken.'
            );
        }

        if (mb_strlen($username) > 50) {

            throw new RuntimeException(
                'Användarnamnet får vara högst 50 tecken.'
            );
        }

        if (
            !preg_match(
                '/^[A-Za-z0-9_-]+$/',
                $username
            )
        ) {

            throw new RuntimeException(
                'Användarnamnet innehåller otillåtna tecken.'
            );
        }
    }

    private function validateEmail(
        string $email
    ): void {

        if ($email === '') {

            throw new RuntimeException(
                'E-post måste anges.'
            );
        }

        if (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {

            throw new RuntimeException(
                'Ogiltig e-postadress.'
            );
        }
    }

    private function validatePassword(
        string $password
    ): void {

        if ($password === '') {

            throw new RuntimeException(
                'Lösenord måste anges.'
            );
        }

        if (strlen($password) < 8) {

            throw new RuntimeException(
                'Lösenordet måste vara minst 8 tecken.'
            );
        }
    }
}
