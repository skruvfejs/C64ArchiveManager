<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\View;
use App\Http\Request;
use App\Repositories\ReleaseRepository;
use App\Repositories\ReleaseFileRepository;
use App\Repositories\EntryRepository;
use App\Repositories\DirectoryEntryRepository;

final class ReleaseController extends Controller
{
    public function __construct(
        private ReleaseRepository $releases,
        private ReleaseFileRepository $files,
        private EntryRepository $entries,
        private DirectoryEntryRepository $directories,
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


        $entry =
            $this->entries->findById(
                $release->getEntryId()
            );


        $files =
            $this->files->findByRelease(
                $id
            );


        $fileData = [];


        foreach ($files as $file) {

            $directoryEntries =
                $this->directories
                     ->findByReleaseFile(
                         $file->getId()
                     );


            $md5Duplicates = [];


            $md5 =
                $file->getMd5();


            if ($md5 !== null && $md5 !== '') {

                $matches =
                    $this->files
                         ->findAllByMd5(
                             $md5
                         );


                foreach ($matches as $match) {

                    if ($match->getId() === $file->getId()) {
                        continue;
                    }


                    $duplicateRelease =
                        $this->releases->findById(
                            $match->getReleaseId()
                        );


                    if ($duplicateRelease === null) {
                        continue;
                    }


                    $duplicateEntry =
                        $this->entries->findById(
                            $duplicateRelease->getEntryId()
                        );


                    $md5Duplicates[] = [

                        'release' =>
                            $duplicateRelease,

                        'entry' =>
                            $duplicateEntry,

                        'file' =>
                            $match
                    ];
                }
            }


            $fileData[] = [

                'file' =>
                    $file,

                'directoryCount' =>
                    count($directoryEntries),

                'md5Duplicates' =>
                    $md5Duplicates
            ];
        }


        $view =
            new View();


        $view->render(
            'release/index',
            [
                'title' =>
                    'Release',

                'release' =>
                    $release,

                'entry' =>
                    $entry,

                'files' =>
                    $fileData
            ]
        );
    }
}
