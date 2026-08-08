<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Authorization;
use App\Core\View;
use App\Http\Request;
use App\Repositories\ReleaseFileRepository;


final class DisksController extends Controller
{

    public function __construct(
        private readonly ReleaseFileRepository $files,
        private readonly View $view,
        private readonly Request $request,
        private readonly DiskController $diskController,
        private readonly Auth $auth,
        private readonly Authorization $authorization
    ) {
    }



    public function index(): void
    {

        if (!$this->auth->check()) {

            header('Location: /login');

            exit;
        }



        $id =
            (int) $this->request->query('id');



        /*
         * Om ett disk-id finns,
         * låt DiskController visa disken.
         */
        if ($id > 0) {

            $this->diskController->index();

            return;
        }



        /*
         * Sökning, sortering och pagination i disklistan
         */

        $search =
            trim(
                (string) $this->request->query('search', '')
            );



        $sort =
            (string) $this->request->query('sort', 'id');



        $page =
            max(
                1,
                (int) $this->request->query('page', 1)
            );



        $perPage = 50;



        $total =
            $this->files->countDisks($search);



        $pages =
            (int) ceil(
                $total / $perPage
            );



        $offset =
            ($page - 1) * $perPage;



        $disks =
            $search === ''

                ? $this->files->findAllDisks(
                    $sort,
                    $perPage,
                    $offset
                )

                : $this->files->searchDisks(
                    $search,
                    $sort,
                    $perPage,
                    $offset
                );



        $this->view->render(
            'disk/list',
            [

                'title' =>
                    'Diskar',


                'disks' =>
                    $disks,


                'search' =>
                    $search,


                'sort' =>
                    $sort,


                'page' =>
                    $page,


                'pages' =>
                    $pages,


                'total' =>
                    $total,


                'perPage' =>
                    $perPage,


                'authorization' =>
                    $this->authorization,

            ]
        );
    }
}

