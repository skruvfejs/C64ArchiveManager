<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\View;
use App\Repositories\ImportLogRepository;


final class HomeController extends Controller
{

    public function __construct(
        private readonly ImportLogRepository $logs
    ) {
    }



    public function index(): void
    {
        $view = new View();


        $imports =
            $this->logs->findLatest(5);



        $view->render('home/index', [

            'title' =>
                'C64 Archive Manager',


            'version' =>
                '1.0',


            'imports' =>
                $imports,

        ]);
    }
}

