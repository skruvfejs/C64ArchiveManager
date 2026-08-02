<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\View;
use App\Repositories\ReleaseRepository;
use App\Repositories\ReleaseFileRepository;
use App\Repositories\DirectoryEntryRepository;
use App\Http\Request;

final class DiskController extends Controller
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


        $search =
            trim(
                (string) $this->request->query('search', '')
            );


        $sort =
            (string) $this->request->query('sort', '');


        $files =
            $this->files->findByRelease($id);


        $directories = [];


        foreach ($files as $file) {

            $entries =
                $this->entries->findByReleaseFile(
                    $file->getId()
                );


            if ($search !== '') {

                $entries =
                    array_filter(
                        $entries,
                        function ($entry) use ($search): bool {

                            return stripos(
                                $entry->getFilename(),
                                $search
                            ) !== false;

                        }
                    );


                $entries =
                    array_values($entries);
            }


            switch ($sort) {

                case 'name':

                    usort(
                        $entries,
                        function ($a, $b): int {

                            return strcasecmp(
                                $a->getFilename(),
                                $b->getFilename()
                            );

                        }
                    );

                    break;


                case 'blocks':

                    usort(
                        $entries,
                        function ($a, $b): int {

                            return $a->getBlocks()
                                <=>
                                $b->getBlocks();

                        }
                    );

                    break;


                case 'track':

                    usort(
                        $entries,
                        function ($a, $b): int {

                            return [
                                $a->getStartTrack(),
                                $a->getStartSector()
                            ]
                            <=>
                            [
                                $b->getStartTrack(),
                                $b->getStartSector()
                            ];

                        }
                    );

                    break;
            }


            $directories[$file->getId()] =
                $entries;
        }


        $view = new View();


        $view->render(
            'disk/index',
            [
                'title' =>
                    'C64 Disk Explorer',

                'release' =>
                    $release,

                'files' =>
                    $files,

                'directories' =>
                    $directories,

                'search' =>
                    $search,

                'sort' =>
                    $sort
            ]
        );
    }
}
