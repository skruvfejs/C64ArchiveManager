<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\View;
use App\Repositories\ImportLogRepository;
use App\Services\SettingsService;

final class HomeController extends Controller
{
    public function __construct(
        private readonly ImportLogRepository $logs,
        private readonly SettingsService $settings,
        private readonly View $view
    ) {
    }


    public function index(): void
    {
        $imports =
            $this->logs->findLatest(5);


        $siteName =
            $this->settings->get(
                'site_name',
                'C64 Archive Manager'
            );


        $this->view->render('home/index', [

            'title' =>
                $siteName,

            'version' =>
                '1.0',

            'imports' =>
                $imports,

        ]);
    }
}
