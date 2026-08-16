<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Authorization;
use App\Core\View;
use App\Http\Request;
use App\Repositories\EntryRepository;
use App\Repositories\EntryTagRepository;
use App\Repositories\ReleaseRepository;
use App\Repositories\ReleaseFileRepository;
use App\Repositories\DirectoryEntryRepository;
use App\Repositories\TagRepository;
use App\Services\SettingsService;

final class EntryController extends Controller
{
    public function __construct(
        private readonly EntryRepository $entries,
        private readonly EntryTagRepository $entryTags,
        private readonly TagRepository $tags,
        private readonly ReleaseRepository $releases,
        private readonly ReleaseFileRepository $files,
        private readonly DirectoryEntryRepository $directories,
        private readonly Request $request,
        private readonly View $view,
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
         * Om ett Entry-id finns,
         * visa den befintliga Entry-detaljsidan.
         */
        if ($id > 0) {

            $this->showEntry($id);

            return;
        }


        /*
         * Arkivets Entry-lista.
         * Samma sökning, sortering och pagination
         * som används av disklistan.
         */

        $search =
            trim(
                (string) $this->request->query(
                    'search',
                    ''
                )
            );


        $sort =
            (string) $this->request->query(
                'sort',
                'id'
            );


        $page =
            max(
                1,
                (int) $this->request->query(
                    'page',
                    1
                )
            );


        $perPage =
            (int) $this->settings->get(
                'items_per_page',
                '25'
            );


        $total =
            $this->entries->countEntries(
                $search
            );


        $pages =
            (int) ceil(
                $total / $perPage
            );


        $offset =
            ($page - 1) * $perPage;


        $entries =
            $search === ''

                ? $this->entries->findAllEntries(
                    $sort,
                    $perPage,
                    $offset
                )

                : $this->entries->searchEntries(
                    $search,
                    $sort,
                    $perPage,
                    $offset
                );


        $this->view->render(
            'entry/list',
            [

                'title' =>
                    'Arkiv',

                'entries' =>
                    $entries,

                'search' =>
                    $search,

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


    public function printList(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');
            exit;
        }

        $id =
            (int) $this->request->query('id');

        $data =
            $this->getPrintData($id);

        if ($data === null) {
            http_response_code(404);
            echo 'Entry not found';
            return;
        }

        $this->view->render(
            'entry/print-list',
            $data,
            'layouts/print'
        );
    }


    public function printDetails(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login');
            exit;
        }

        $id =
            (int) $this->request->query('id');

        $data =
            $this->getPrintData($id);

        if ($data === null) {
            http_response_code(404);
            echo 'Entry not found';
            return;
        }

        $this->view->render(
            'entry/print-details',
            $data,
            'layouts/print'
        );
    }


    private function getPrintData(
        int $id
    ): ?array {
        if ($id <= 0) {
            return null;
        }

        $entry =
            $this->entries->findById($id);

        if ($entry === null) {
            return null;
        }

        $entryTags =
            $this->entryTags->findByEntryId($id);

        $allTags =
            $this->tags->findAll();

        $totalReleases =
            $this->releases->countByEntry($id);

        $releases =
            $totalReleases > 0
                ? $this->releases->findAllByEntry(
                    $id
                )
                : [];

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

                $md5 =
                    $file->getMd5();

                if (
                    $md5 !== null &&
                    $md5 !== ''
                ) {
                    $seenMd5[$md5] = true;
                }

                $fileData[] = [
                    'file' =>
                        $file,

                    'directoryCount' =>
                        count(
                            $directoryEntries
                        ),
                ];
            }

            $releaseData[] = [
                'release' =>
                    $release,

                'files' =>
                    $fileData,
            ];
        }

        return [
            'title' =>
                'Entry',

            'entry' =>
                $entry,

            'entryTags' =>
                $entryTags,

            'allTags' =>
                $allTags,

            'releases' =>
                $releaseData,

            'totalReleases' =>
                $totalReleases,

            'uniqueImages' =>
                count($seenMd5),
        ];
    }


    private function showEntry(
        int $id
    ): void {

        $entry =
            $this->entries->findById(
                $id
            );


        if ($entry === null) {

            http_response_code(404);

            echo 'Entry not found';

            return;
        }


        /*
         * Entry-tags.
         *
         * EntryTagRepository returnerar EntryTag-objekt.
         * Dessa används av vyn för att identifiera
         * vilka tags som redan är kopplade till Entry.
         */
        $entryTags =
            $this->entryTags->findByEntryId(
                $id
            );


        /*
         * Hämta alla tillgängliga tags för dropdownen.
         */
        $allTags =
            $this->tags->findAll();


        /*
         * Pagination för releases.
         * Samma items_per_page som övriga listor.
         */
        $page =
            max(
                1,
                (int) $this->request->query(
                    'page',
                    1
                )
            );

        $perPage =
            (int) $this->settings->get(
                'items_per_page',
                '25'
            );

        $totalReleases =
            $this->releases->countByEntry(
                $id
            );

        $pages =
            (int) ceil(
                $totalReleases / $perPage
            );

        if ($pages > 0 && $page > $pages) {
            $page = $pages;
        }

        $offset =
            ($page - 1) * $perPage;

        $releases =
            $this->releases->findByEntry(
                $id,
                $perPage,
                $offset
            );


        $releaseData = [];

        /*
         * Hämta MD5 för alla releases i hela Entry:n.
         * Detta gör duplicate-kontrollen korrekt även
         * när original och dubblett ligger på olika sidor.
         */
        $seenMd5 =
            $this->files->findMd5ByEntry(
                $id
            );


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


                if (
                    $md5 !== null
                    &&
                    $md5 !== ''
                ) {

                    if (
                        isset(
                            $seenMd5[$md5]
                        )
                        && $seenMd5[$md5]
                            !== $release->getId()
                    ) {

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
                        count(
                            $directoryEntries
                        ),

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

                'entryTags' =>
                    $entryTags,

                'allTags' =>
                    $allTags,

                'releases' =>
                    $releaseData,

                'totalReleases' =>
                    $totalReleases,

                'uniqueImages' =>
                    count(
                        $seenMd5
                    ),

                'page' =>
                    $page,

                'pages' =>
                    $pages,

                'perPage' =>
                    $perPage

            ]
        );
    }
}
