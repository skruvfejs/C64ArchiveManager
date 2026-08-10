<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Authorization;
use App\Core\View;
use App\Http\Request;
use App\Repositories\ReleaseFileRepository;
use App\Repositories\TagRepository;
use App\Repositories\DiskTagRepository;
use App\Services\SettingsService;


final class DisksController extends Controller
{

    public function __construct(
        private readonly ReleaseFileRepository $files,
        private readonly View $view,
        private readonly DiskTagRepository $diskTags,
        private readonly TagRepository $tags,
        private readonly Request $request,
        private readonly DiskController $diskController,
        private readonly Auth $auth,
        private readonly Authorization $authorization,
        private readonly SettingsService $settings
    ) {
    }



    public function index(): void
    {

        if (!$this->auth->check()) {

            header('Location: /login');

            exit;
        }



        $id =
            (int) $this->request->query('id');



        /*
         * Om ett disk-id finns,
         * låt DiskController visa disken.
         */
        if ($id > 0) {

            $this->diskController->index();

            return;
        }



        /*
         * Sökning, sortering och pagination i disklistan
         */

        $search =
            trim(
                (string) $this->request->query('search', '')
            );

        $tagId =
            max(
                0,
                (int) $this->request->query('tag', 0)
            );



        $sort =
            (string) $this->request->query('sort', 'id');



        $page =
            max(
                1,
                (int) $this->request->query('page', 1)
            );



        $perPage =
            (int) $this->settings->get(
                'items_per_page',
                '25'
            );



        $total =
            $tagId > 0
                ? $this->files->countDisksByTag(
                    $tagId,
                    $search
                )
                : $this->files->countDisks(
                    $search
                );



        $pages =
            (int) ceil(
                $total / $perPage
            );



        $offset =
            ($page - 1) * $perPage;



        if ($tagId > 0) {

            $disks =
                $search === ''

                    ? $this->files->findAllDisksByTag(
                        $tagId,
                        $sort,
                        $perPage,
                        $offset
                    )

                    : $this->files->searchDisksByTag(
                        $tagId,
                        $search,
                        $sort,
                        $perPage,
                        $offset
                    );

        } else {

            $disks =
                $search === ''

                    ? $this->files->findAllDisks(
                        $sort,
                        $perPage,
                        $offset
                    )

                    : $this->files->searchDisks(
                        $search,
                        $sort,
                        $perPage,
                        $offset
                    );
        }



        $diskTags = [];

        foreach ($disks as $disk) {
            $diskTags[$disk->getId()] =
                $this->diskTags->findByDiskId(
                    $disk->getId()
                );
        }


        $this->view->render(
            'disk/list',
            [

                'title' =>
                    'Diskar',


                'disks' =>
                    $disks,

                'diskTags' =>
                    $diskTags,

                'allTags' =>
                    $this->tags->findAll(),


                'search' =>
                    $search,


                'tagId' =>
                    $tagId,


                'sort' =>
                    $sort,


                'page' =>
                    $page,


                'pages' =>
                    $pages,


                'total' =>
                    $total,


                'perPage' =>
                    $perPage,


                'authorization' =>
                    $this->authorization,

            ]
        );
    }
}

