<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\View;

final class HomeController extends Controller
{
    public function index(): void
    {
        $view = new View();

        $view->render('home/index', [
            'title' => 'C64 Archive Manager',
            'version' => '1.0',
        ]);
    }
}

