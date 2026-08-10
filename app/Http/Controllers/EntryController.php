<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\View;
use App\Http\Request;
use App\Repositories\EntryRepository;
use App\Repositories\ReleaseRepository;
use App\Repositories\ReleaseFileRepository;
use App\Repositories\DirectoryEntryRepository;

final class EntryController extends Controller
{
    public function __construct(
        private EntryRepository $entries,
        private ReleaseRepository $releases,
        private ReleaseFileRepository $files,
        private DirectoryEntryRepository $directories,
        private Request $request,
        private View $view
    ) {
    }


    public function index(): void
    {
        $id =
            (int) $this->request->query('id');


        if ($id <= 0) {

            http_response_code(400);

            echo 'Missing entry id';

            return;
        }


        $entry =
            $this->entries->findById($id);


        if ($entry === null) {

            http_response_code(404);

            echo 'Entry not found';

            return;
        }


        $releases =
            $this->releases->findByEntry(
                $id
            );


        $releaseData = [];

        $seenMd5 = [];


        foreach ($releases as $release) {

            $files =
                $this->files->findByRelease(
                    $release->getId()
                );


            $fileData = [];


            foreach ($files as $file) {

                $directoryEntries =
                    $this->directories
                         ->findByReleaseFile(
                             $file->getId()
                         );


                $duplicate =
                    false;

                $duplicateOf =
                    null;


                $md5 =
                    $file->getMd5();


                if ($md5 !== null && $md5 !== '') {

                    if (isset($seenMd5[$md5])) {

                        $duplicate = true;

                        $duplicateOf =
                            $seenMd5[$md5];

                    } else {

                        $seenMd5[$md5] =
                            $release->getId();
                    }
                }


                $fileData[] = [
                    'file' =>
                        $file,

                    'directoryCount' =>
                        count($directoryEntries),

                    'duplicate' =>
                        $duplicate,

                    'duplicateOf' =>
                        $duplicateOf
                ];
            }


            $releaseData[] = [
                'release' =>
                    $release,

                'files' =>
                    $fileData
            ];
        }


        $this->view->render(
            'entry/index',
            [
                'title' =>
                    'Entry',

                'entry' =>
                    $entry,

                'releases' =>
                    $releaseData,

                'totalReleases' =>
                    count($releases),

                'uniqueImages' =>
                    count($seenMd5)
            ]
        );
    }
}

