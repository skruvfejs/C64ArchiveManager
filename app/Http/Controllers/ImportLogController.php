<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\View;
use App\Services\ImportLogService;

final class ImportLogController extends Controller
{
    public function __construct(
        private ImportLogService $logs
    ) {
    }


    public function index(): void
    {
        $logs =
            $this->logs->latest();


        $view =
            new View();


        $view->render(
            'import/logs',
            [
                'title' =>
                    'Import Logs',

                'logs' =>
                    $logs
            ]
        );
    }
}

