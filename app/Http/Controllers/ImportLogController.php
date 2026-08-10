<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\View;
use App\Http\Request;
use App\Services\ImportLogService;
use App\Services\SettingsService;

final class ImportLogController extends Controller
{
    public function __construct(
        private ImportLogService $logs,
        private View $view,
        private Request $request,
        private SettingsService $settings
    ) {
    }


    public function index(): void
    {
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
            $this->logs->count();


        $pages =
            (int) ceil(
                $total / $perPage
            );


        $offset =
            ($page - 1) * $perPage;


        $logs =
            $this->logs->latest(
                $perPage,
                $offset
            );


        $this->view->render(
            'import/logs',
            [
                'title' =>
                    'Import Logs',

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
