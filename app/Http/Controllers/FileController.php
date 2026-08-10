<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\View;
use App\Http\Request;
use App\Repositories\DirectoryEntryRepository;
use App\Repositories\ReleaseFileRepository;
use App\Services\C64FileExtractorService;
use RuntimeException;

final class FileController extends Controller
{
    public function __construct(
        private DirectoryEntryRepository $entries,
        private ReleaseFileRepository $files,
        private C64FileExtractorService $extractor,
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

            echo 'Missing file id';

            return;
        }


        $entry =
            $this->entries->findById($id);


        if ($entry === null) {

            http_response_code(404);

            echo 'File not found';

            return;
        }


        $releaseFile =
            $this->files->findById(
                $entry->getReleaseFileId()
            );


        $this->view->render(
            'file/index',
            [
                'title' =>
                    'C64 File Details',

                'entry' =>
                    $entry,

                'releaseFile' =>
                    $releaseFile,

                'releaseId' =>
                    $releaseFile?->getReleaseId()
            ]
        );
    }

    public function download(): void
    {
        $id =
            (int) $this->request->query('id');


        if ($id <= 0) {

            http_response_code(400);

            echo 'Missing file id';

            return;
        }


        $entry =
            $this->entries->findById($id);


        if ($entry === null) {

            http_response_code(404);

            echo 'File not found';

            return;
        }


        $releaseFile =
            $this->files->findById(
                $entry->getReleaseFileId()
            );


        if ($releaseFile === null) {

            http_response_code(404);

            echo 'Disk image not found';

            return;
        }


        $data =
            $this->extractor->extract(
                $releaseFile,
                $entry
            );


        $filename =
            $entry->getFilename();


        if (
            strtoupper(
                $entry->getFiletype()
            ) === 'PRG'
            &&
            !str_ends_with(
                strtoupper($filename),
                '.PRG'
            )
        ) {

            $filename .= '.prg';
        }



        header(
            'Content-Type: application/octet-stream'
        );


        header(
            'Content-Disposition: attachment; filename="'
            . basename($filename)
            . '"'
        );


        header(
            'Content-Length: '
            . strlen($data)
        );


        echo $data;
    }
}
