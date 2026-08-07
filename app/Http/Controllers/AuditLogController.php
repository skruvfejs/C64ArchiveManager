<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Authorization;
use App\Core\Permission;
use App\Core\View;
use App\Repositories\AuditLogRepository;

final class AuditLogController extends Controller
{
    public function __construct(
        private readonly AuditLogRepository $logs,
        private readonly Auth $auth,
        private readonly Authorization $authorization,
        private readonly View $view
    ) {
    }



    public function index(): void
    {
        if (
            !$this->auth->check()
        ) {

            header(
                'Location: /login'
            );

            exit;
        }



        if (
            !$this->authorization->can(
                Permission::VIEW_LOGS
            )
        ) {

            http_response_code(403);

            echo 'Forbidden';

            exit;
        }



        $this->view->render(
            'users/logs',
            [
                'title' =>
                    'Audit log',

                'logs' =>
                    $this->logs->findAllWithUsers(),
            ]
        );
    }
}
