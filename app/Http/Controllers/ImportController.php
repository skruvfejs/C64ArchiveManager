<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Http\Request;
use App\Repositories\EntryRepository;
use App\Repositories\ReleaseFileRepository;
use App\Repositories\ReleaseRepository;
use App\Repositories\DirectoryEntryRepository;
use App\Services\ChecksumService;
use App\Services\ImporterService;

final class ImportController extends Controller
{
    public function __construct(
        private Request $request,
        private EntryRepository $entries,
        private ReleaseFileRepository $files,
        private ReleaseRepository $releases,
        private DirectoryEntryRepository $directories,
        private ChecksumService $checksum,
        private ImporterService $importer,
        private Auth $auth,
        private View $view
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


        $notes =
            $this->request->post(
                'notes'
            );


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


        $result =
            $this->importer
                 ->import(
                     $target,
                     $entryId,
                     false,
                     $this->auth->id(),
                     $notes
                 );


        if ($result->isDuplicate()) {

            $this->view->render(
                'import/duplicate',
                $result->getDuplicateData()
            );

            return;
        }


        $this->renderImportResult(
            $result->getReleaseId()
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


        $notes =
            $this->request->post(
                'notes'
            );


        $path =
            $this->request->post(
                'path'
            );


        if (empty($path)) {

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


        $result =
            $this->importer
                 ->import(
                     $path,
                     $entryId,
                     true,
                     $this->auth->id(),
                     $notes
                 );


        if ($result->isDuplicate()) {

            $this->view->render(
                'import/duplicate',
                $result->getDuplicateData()
            );

            return;
        }


        $this->renderImportResult(
            $result->getReleaseId()
        );
    }


    private function renderImportResult(
        int $releaseId
    ): void {

        $release =
            $this->releases
                 ->findById(
                     $releaseId
                 );


        $entryName = 'Okänd';

        $filename = null;
        $format = null;
        $md5 = null;
        $size = null;
        $fileCount = 0;


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


            $files =
                $this->files
                     ->findByRelease(
                         $releaseId
                     );


            if (count($files) > 0) {

                $file =
                    $files[0];


                $filename =
                    $file->getFilename();


                $format =
                    $file->getFormat();


                $md5 =
                    $file->getMd5();


                $size =
                    $file->getSize();


                $entries =
                    $this->directories
                         ->findByReleaseFile(
                             $file->getId()
                         );


                $fileCount =
                    count($entries);
            }
        }


        $message =
            'Import OK.<br>'
            . 'Release ID: '
            . $releaseId
            . '<br>'
            . 'Entry: '
            . $entryName;


        if ($filename !== null) {

            $message .=
                '<br>Fil: '
                . $filename
                . '<br>Format: '
                . $format
                . '<br>MD5: '
                . $md5
                . '<br>Storlek: '
                . $size
                . ' bytes'
                . '<br>Katalogposter: '
                . $fileCount;
        }


        $this->render(
            'import/index',
            [
                'message' =>
                    $message
            ]
        );
    }


    private function render(
        string $viewName,
        array $data = []
    ): void {

        $this->view->render(
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
