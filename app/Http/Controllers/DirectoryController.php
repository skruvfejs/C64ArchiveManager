<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\View;
use App\Repositories\ReleaseRepository;
use App\Repositories\ReleaseFileRepository;
use App\Repositories\DirectoryEntryRepository;
use App\Http\Request;

final class DirectoryController extends Controller
{
    public function __construct(
        private ReleaseRepository $releases,
        private ReleaseFileRepository $files,
        private DirectoryEntryRepository $entries,
        private Request $request
    ) {
    }


    public function index(): void
    {
        $id =
            (int) $this->request->query('id');


        if ($id <= 0) {

            http_response_code(400);

            echo 'Missing release id';

            return;
        }


        $release =
            $this->releases->findById($id);


        if ($release === null) {

            http_response_code(404);

            echo 'Release not found';

            return;
        }


        $files =
            $this->files->findByRelease($id);


        $directories = [];


        foreach ($files as $file) {

            $directories[$file->getId()] =
                $this->entries->findByReleaseFile(
                    $file->getId()
                );
        }


        $view = new View();


        $view->render(
            'disk/directory',
            [
                'title' =>
                    'C64 Directory',

                'release' =>
                    $release,

                'files' =>
                    $files,

                'directories' =>
                    $directories
            ]
        );
    }
}

