<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\View;
use App\Http\Request;
use App\Repositories\EntryRepository;
use App\Repositories\ReleaseFileRepository;
use App\Repositories\ReleaseRepository;
use App\Services\ChecksumService;
use App\Services\ImporterService;

final class ImportController extends Controller
{
    public function __construct(
        private Request $request,
        private EntryRepository $entries,
        private ReleaseFileRepository $files,
        private ReleaseRepository $releases,
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
        $entryValue =
            $this->request->post(
                'entry_id'
            );


        $entryId =
            $entryValue !== null &&
            $entryValue !== ''
                ? (int) $entryValue
                : null;



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



        $release =
            $this->releases
                 ->findById(
                     $releaseId
                 );


        $entryName =
            'Okänd';



        if ($release !== null) {

            $entry =
                $this->entries
                     ->findById(
                         $release->getEntryId()
                     );


            if ($entry !== null) {

                $entryName =
                    $entry->getTitle();
            }
        }



        $this->render(
            'import/index',
            [
                'message' =>
                    'Import OK.<br>'
                    . 'Release ID: '
                    . $releaseId
                    . '<br>'
                    . 'Entry: '
                    . $entryName
            ]
        );
    }



    public function force(): void
    {
        $entryValue =
            $this->request->post(
                'entry_id'
            );


        $entryId =
            $entryValue !== null &&
            $entryValue !== ''
                ? (int) $entryValue
                : null;



        $path =
            $this->request->post(
                'path'
            );



        if (
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



        $release =
            $this->releases
                 ->findById(
                     $releaseId
                 );


        $entryName =
            'Okänd';



        if ($release !== null) {

            $entry =
                $this->entries
                     ->findById(
                         $release->getEntryId()
                     );


            if ($entry !== null) {

                $entryName =
                    $entry->getTitle();
            }
        }



        $this->render(
            'import/index',
            [
                'message' =>
                    'Import OK.<br>'
                    . 'Release ID: '
                    . $releaseId
                    . '<br>'
                    . 'Entry: '
                    . $entryName
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

