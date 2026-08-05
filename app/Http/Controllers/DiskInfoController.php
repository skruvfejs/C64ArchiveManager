<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\View;
use App\Http\Request;
use App\Repositories\ReleaseRepository;
use App\Repositories\ReleaseFileRepository;
use App\Repositories\DirectoryEntryRepository;
use App\Services\DiskGeometry;

final class DiskInfoController extends Controller
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


        if (count($files) === 0) {

            http_response_code(404);

            echo 'Disk file not found';

            return;
        }


        $file =
            $files[0];


        $entries =
            $this->entries->findByReleaseFile(
                $file->getId()
            );


        $format =
            strtoupper(
                $file->getFormat()
            );


        $isT64 =
            $format === 'T64';



        if ($isT64) {


            $used =
                array_sum(
                    array_map(
                        fn($entry): int =>
                            $entry->getFileSize() ?? 0,
                        $entries
                    )
                );


            $totalBlocks = 0;

            $blocksFree = 0;


            $fileSize =
                $used;


        } else {


            $used =
                array_sum(
                    array_map(
                        fn($entry): int =>
                            $entry->getBlocks(),
                        $entries
                    )
                );


            $totalBlocks =
                $this->geometry->totalBlocks(
                    $format
                );


            $blocksFree =
                $totalBlocks - $used;


            $fileSize = null;

        }

        $view = new View();


        $view->render(
            'disk/info',
            [

                'title' =>
                    'Disk Information',


                'release' =>
                    $release,


                'file' =>
                    $file,


                'format' =>
                    $format,


                'diskType' =>
                    $this->geometry->diskType(
                        $format
                    ),


                'tracks' =>
                    $this->geometry->tracks(
                        $format
                    ),


                'totalBlocks' =>
                    $totalBlocks,


                'blocksUsed' =>
                    $used,


                'blocksFree' =>
                    $blocksFree,


                'fileCount' =>
                    count($entries),


                'isT64' =>
                    $isT64,


                'fileSize' =>
                    $fileSize

            ]
        );
    }
}
