<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Core\Authorization;
use App\Core\View;
use App\Services\SettingsService;

final class MaintenanceMiddleware
{
    public function __construct(
        private readonly SettingsService $settings,
        private readonly Authorization $authorization,
        private readonly View $view
    ) {
    }


    public function handle(): void
    {
        $maintenanceMode =
            $this->settings->get(
                'maintenance_mode',
                '0'
            );


        if ($maintenanceMode !== '1') {
            return;
        }


        if ($this->authorization->isSuperAdmin()) {
            return;
        }


        http_response_code(503);


        $this->view->render(
            'system/maintenance_mode',
            [
                'title' =>
                    $this->viewTitle(),
            ]
        );


        exit;
    }


    private function viewTitle(): string
    {
        return 'Underhållsarbete pågår';
    }
}
