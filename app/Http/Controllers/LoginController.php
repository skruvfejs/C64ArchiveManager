<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Authorization;
use App\Core\View;
use App\Entity\User;
use App\Services\SettingsService;
use App\Services\UserService;

final class LoginController extends Controller
{
    /**
     * Pending-rollen.
     *
     * TODO:
     * Ersätt med RoleRepository/RoleService senare.
     */
    private const PENDING_ROLE_ID = 6;

    public function __construct(
        private readonly UserService $users,
        private readonly SettingsService $settingsService,
        private readonly Authorization $authorization,
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


        /*
         * Pending-användare får inte logga in.
         */
        if (
            $user->getRoleId() === self::PENDING_ROLE_ID
        ) {

            $this->showError(
                'Ditt konto väntar på att en administratör ska godkänna det.'
            );

            return;
        }


        /*
         * Underhållsläge.
         *
         * När underhållsläget är aktivt får endast
         * Super-admin logga in.
         */
        if (
            $this->settingsService->get(
                'maintenance_mode',
                '0'
            ) === '1'
            && !$this->isSuperAdmin(
                $user->getRoleId()
            )
        ) {

            $this->showError(
                'Systemet är för närvarande i underhållsläge. Endast Super-admin kan logga in.'
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


    private function isSuperAdmin(
        int $roleId
    ): bool {

        return $roleId === 1;
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
