<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\View;
use App\Repositories\ReleaseRepository;
use App\Repositories\ReleaseFileRepository;
use App\Repositories\DirectoryEntryRepository;
use App\Services\DiskGeometry;
use App\Http\Request;

final class DirectoryController extends Controller
{
    public function __construct(
        private ReleaseRepository $releases,
        private ReleaseFileRepository $files,
        private DirectoryEntryRepository $entries,
        private DiskGeometry $geometry,
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

        $blocksUsed = [];

        $blocksFree = [];

        $totalBlocks = [];

        $diskTypes = [];

        $tracks = [];


        foreach ($files as $file) {

            $entries =
                $this->entries->findByReleaseFile(
                    $file->getId()
                );


            $directories[$file->getId()] =
                $entries;



            /*
             * T64 har ingen BAM/blockräkning.
             * Därför används file_size.
             *
             * D64/D71/D81 använder blocks.
             */
            if (
                strtoupper(
                    $file->getFormat()
                ) === 'T64'
            ) {


                $used =
                    array_sum(
                        array_map(
                            fn($entry): int =>
                                $entry->getFileSize() ?? 0,
                            $entries
                        )
                    );


            } else {


                $used =
                    array_sum(
                        array_map(
                            fn($entry): int =>
                                $entry->getBlocks(),
                            $entries
                        )
                    );

            }



            $blocksUsed[$file->getId()] =
                $used;



            $format =
                $file->getFormat();



            $total =
                $this->geometry->totalBlocks(
                    $format
                );



            $totalBlocks[$file->getId()] =
                $total;



            $diskTypes[$file->getId()] =
                $this->geometry->diskType(
                    $format
                );



            $tracks[$file->getId()] =
                $this->geometry->tracks(
                    $format
                );


            /*
             * T64 har ingen total blockkapacitet.
             * Behåll 0 från DiskGeometry.
             */
            if (
                strtoupper(
                    $format
                ) === 'T64'
            ) {

                $blocksFree[$file->getId()] = 0;


            } else {


                $blocksFree[$file->getId()] =
                    $total > 0
                        ? $total - $used
                        : 0;

            }
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
                    $directories,


                'blocksUsed' =>
                    $blocksUsed,


                'blocksFree' =>
                    $blocksFree,


                'totalBlocks' =>
                    $totalBlocks,


                'diskTypes' =>
                    $diskTypes,


                'tracks' =>
                    $tracks

            ]
        );
    }
}
