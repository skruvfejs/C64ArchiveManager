<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\View;
use App\Http\Request;
use App\Repositories\DirectoryEntryRepository;

final class FileController extends Controller
{
    public function __construct(
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


        $view = new View();


        $view->render(
            'file/index',
            [
                'title' =>
                    'C64 File Details',

                'entry' =>
                    $entry
            ]
        );
    }
}

