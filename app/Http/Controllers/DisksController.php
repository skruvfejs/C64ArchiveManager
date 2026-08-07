<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\View;
use App\Http\Request;
use App\Repositories\ReleaseFileRepository;

final class DisksController extends Controller
{
    public function __construct(
        private readonly ReleaseFileRepository $files,
        private readonly View $view,
        private readonly Request $request,
        private readonly DiskController $diskController
    ) {
    }



    public function index(): void
    {
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
         * Annars visa lista
         */

        $disks =
            $this->files->findAllDisks();



        $this->view->render(
            'disk/list',
            [
                'title' =>
                    'Diskar',

                'disks' =>
                    $disks,
            ]
        );
    }
}
