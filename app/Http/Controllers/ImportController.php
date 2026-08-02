<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\View;
use App\Http\Request;
use App\Repositories\EntryRepository;
use App\Repositories\ReleaseFileRepository;
use App\Services\ChecksumService;
use App\Services\ImporterService;

final class ImportController extends Controller
{
    public function __construct(
        private Request $request,
        private EntryRepository $entries,
        private ReleaseFileRepository $files,
        private ChecksumService $checksum,
        private ImporterService $importer
    ) {
    }


    public function index(): void
    {
        $this->render(
            'import/index',
            [
                'message' => null
            ]
        );
    }


    public function upload(): void
    {
        $entryId =
            (int) $this->request->post(
                'entry_id'
            );


        if ($entryId <= 0) {

            $this->render(
                'import/index',
                [
                    'message' =>
                        'Ingen entry vald.'
                ]
            );

            return;
        }


        if (!isset($_FILES['disk'])) {

            $this->render(
                'import/index',
                [
                    'message' =>
                        'Ingen fil vald.'
                ]
            );

            return;
        }


        if (
            $_FILES['disk']['error']
            !== UPLOAD_ERR_OK
        ) {

            $this->render(
                'import/index',
                [
                    'message' =>
                        'Uppladdningsfel.'
                ]
            );

            return;
        }


        $original =
            basename(
                $_FILES['disk']['name']
            );


        $targetDir =
            dirname(__DIR__, 3)
            . '/storage/imports';


        if (!is_dir($targetDir)) {

            mkdir(
                $targetDir,
                0775,
                true
            );
        }


        $target =
            $targetDir
            . '/'
            . $original;


        move_uploaded_file(
            $_FILES['disk']['tmp_name'],
            $target
        );


        $checksum =
            $this->checksum
                 ->all($target);


        $existing =
            $this->files
                 ->findByMd5(
                     $checksum['md5']
                 );


        if ($existing !== null) {

            $view =
                new View();


            $view->render(
                'import/duplicate',
                [
                    'entryId' =>
                        $entryId,

                    'path' =>
                        $target,

                    'filename' =>
                        $original,

                    'md5' =>
                        $checksum['md5'],

                    'existing' =>
                        $existing
                ]
            );

            return;
        }


        $releaseId =
            $this->importer
                 ->import(
                     $target,
                     $entryId
                 );


        $this->render(
            'import/index',
            [
                'message' =>
                    'Import OK. Release ID: '
                    . $releaseId
            ]
        );
    }


    public function force(): void
    {
        $entryId =
            (int) $this->request->post(
                'entry_id'
            );


        $path =
            $this->request->post(
                'path'
            );


        if (
            $entryId <= 0 ||
            empty($path)
        ) {

            $this->render(
                'import/index',
                [
                    'message' =>
                        'Felaktiga importdata.'
                ]
            );

            return;
        }


        if (!is_file($path)) {

            $this->render(
                'import/index',
                [
                    'message' =>
                        'Importfil saknas.'
                ]
            );

            return;
        }


        $releaseId =
            $this->importer
                 ->import(
                     $path,
                     $entryId,
                     true
                 );


        $this->render(
            'import/index',
            [
                'message' =>
                    'Import OK. Release ID: '
                    . $releaseId
            ]
        );
    }


    private function render(
        string $viewName,
        array $data = []
    ): void {

        $view =
            new View();


        $view->render(
            $viewName,
            array_merge(
                [
                    'title' =>
                        'C64 Import',

                    'entries' =>
                        $this->entries->findAll()
                ],
                $data
            )
        );
    }
}

