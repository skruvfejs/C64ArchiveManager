<?php

declare(strict_types=1);

namespace App\Core;

final class Auth
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function login(array $user): void
    {
        $this->session->regenerate();

        $this->session->set('user', [
            'id'         => (int) $user['id'],
            'role_id'    => (int) $user['role_id'],
            'username'   => $user['username'],
            'email'      => $user['email'],
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
        ]);
    }

    public function logout(): void
    {
        $this->session->destroy();
    }

    public function check(): bool
    {
        return $this->session->has('user');
    }

    public function user(): ?array
    {
        return $this->session->get('user');
    }

    public function id(): ?int
    {
        $user = $this->user();

        return $user['id'] ?? null;
    }

    public function roleId(): ?int
    {
        $user = $this->user();

        return $user['role_id'] ?? null;
    }

    public function isAdmin(): bool
    {
        return $this->roleId() === 1;
    }
}

