<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Authorization;
use App\Core\Flash;
use App\Core\Role;
use App\Core\View;
use App\Services\AuditLogService;
use App\Services\RoleService;
use App\Services\UserService;

final class UserEditController extends Controller
{
    public function __construct(
        private readonly UserService $users,
        private readonly RoleService $roles,
        private readonly Auth $auth,
        private readonly Authorization $authorization,
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



        $id = (int) (
            $_GET['id'] ?? 0
        );



        $user =
            $this->users->findById($id);



        if ($user === null) {

            $this->flash->error(
                'Användaren hittades inte.'
            );

            header(
                'Location: /users'
            );

            exit;
        }



        $roles =
            $this->roles->findAll();



        $roles = array_filter(
            $roles,
            function ($role) {

                return $role->getId()
                    !== Role::PENDING;
            }
        );



        if (
            !$this->authorization->isSuperAdmin()
        ) {

            $roles = array_filter(
                $roles,
                function ($role) {

                    return $role->getId()
                        !== Role::SUPER_ADMIN;
                }
            );
        }



        $this->view->render(
            'users/edit',
            [
                'title' => 'Redigera användare',
                'user'  => $user,
                'roles' => $roles,
            ]
        );
    }



    public function update(): void
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
                'Location: /users'
            );

            exit;
        }



        $currentUserId =
            $this->auth->id();



        $roleId = (int) (
            $_POST['role_id']
            ?? $user->getRoleId()
        );



        if (
            $user->getRoleId()
            === Role::SUPER_ADMIN
            &&
            $currentUserId
            !== $user->getId()
        ) {

            $this->flash->error(
                'Du får inte ändra andra Super Admin-konton.'
            );

            header(
                'Location: /users'
            );

            exit;
        }



        if (
            $user->getRoleId()
            === Role::SUPER_ADMIN
            &&
            $roleId !== Role::SUPER_ADMIN
            &&
            $this->users->countByRoleId(
                Role::SUPER_ADMIN
            ) <= 1
        ) {

            $this->flash->error(
                'Det måste finnas minst en Super Admin kvar.'
            );

            header(
                'Location: /users'
            );

            exit;
        }



        if (
            $roleId === Role::SUPER_ADMIN
            &&
            !$this->authorization->isSuperAdmin()
        ) {

            $this->flash->error(
                'Endast Super Admin får tilldela Super Admin-rollen.'
            );

            header(
                'Location: /users'
            );

            exit;
        }



        $oldRoleId =
            $user->getRoleId();



        $user->setRoleId(
            $roleId
        );



        $this->users->update(
            $user
        );



        $this->auditLog->log(
            'UPDATE',
            'User',
            $id,
            'Ändrade användarens roll från '
            . $oldRoleId
            . ' till '
            . $roleId
        );



        $this->flash->success(
            'Användaren har uppdaterats.'
        );



        header(
            'Location: /users'
        );

        exit;
    }
}
