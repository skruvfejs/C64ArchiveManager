<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Authorization;
use App\Core\Permission;
use App\Core\View;
use App\Http\Request;
use App\Repositories\AuditLogRepository;
use App\Services\SettingsService;

final class AuditLogController extends Controller
{
    public function __construct(
        private readonly AuditLogRepository $logs,
        private readonly Auth $auth,
        private readonly Authorization $authorization,
        private readonly View $view,
        private readonly Request $request,
        private readonly SettingsService $settings
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



        $page =
            max(
                1,
                (int) $this->request->query('page', 1)
            );


        $perPage =
            (int) $this->settings->get(
                'items_per_page',
                '25'
            );


        $total =
            $this->logs->countAll();


        $pages =
            (int) ceil(
                $total / $perPage
            );


        $offset =
            ($page - 1) * $perPage;


        $logs =
            $this->logs->findAllWithUsers(
                $perPage,
                $offset
            );


        $this->view->render(
            'users/logs',
            [
                'title' =>
                    'Audit log',

                'logs' =>
                    $logs,

                'page' =>
                    $page,

                'pages' =>
                    $pages,

                'total' =>
                    $total,

                'perPage' =>
                    $perPage
            ]
        );
    }
}
