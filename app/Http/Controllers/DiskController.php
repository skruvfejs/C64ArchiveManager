<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Repositories\ReleaseRepository;
use App\Repositories\ReleaseFileRepository;
use App\Repositories\DirectoryEntryRepository;
use App\Services\C64DiskReader;
use App\Services\C64BamBuilder;
use App\Services\C64BamReader;
use App\Services\C64BamComparator;
use App\Services\C64DiskIntegrityChecker;
use App\Http\Request;

final class DiskController extends Controller
{
    public function __construct(
        private ReleaseRepository $releases,
        private ReleaseFileRepository $files,
        private DirectoryEntryRepository $entries,
        private Request $request,
        private Auth $auth,
        private View $view
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


        $search =
            trim(
                (string) $this->request->query('search', '')
            );


        $sort =
            (string) $this->request->query('sort', '');


        $files =
            $this->files->findByRelease($id);


        $directories = [];

        $integrity = null;

        $comparison = null;
        foreach ($files as $file) {

            $entries =
                $this->entries->findByReleaseFile(
                    $file->getId()
                );


            if ($search !== '') {

                $entries =
                    array_filter(
                        $entries,
                        function ($entry) use ($search): bool {

                            return stripos(
                                $entry->getFilename(),
                                $search
                            ) !== false;
                        }
                    );


                $entries =
                    array_values($entries);
            }



            switch ($sort) {

                case 'name':

                    usort(
                        $entries,
                        function ($a, $b): int {

                            return strcasecmp(
                                $a->getFilename(),
                                $b->getFilename()
                            );
                        }
                    );

                    break;



                case 'blocks':

                    usort(
                        $entries,
                        function ($a, $b): int {

                            return $a->getBlocks()
                                <=>
                                $b->getBlocks();
                        }
                    );

                    break;



                case 'track':

                    usort(
                        $entries,
                        function ($a, $b): int {

                            return [
                                $a->getStartTrack(),
                                $a->getStartSector()
                            ]
                            <=>
                            [
                                $b->getStartTrack(),
                                $b->getStartSector()
                            ];
                        }
                    );

                    break;
            }



            $directories[$file->getId()] =
                $entries;



            /*
             * Run C64 integrity check
             * on first disk image.
             */

            if (
                $integrity === null
            ) {

                $reader =
                    new C64DiskReader(
                        $file->getPath()
                    );


                $builder =
                    new C64BamBuilder(
                        strtoupper(
                            $file->getFormat()
                        )
                    );


                /*
                 * Reserve:
                 * Track 18 sector 0 BAM
                 * Track 18 directory sectors
                 */

                $builder->reserveD64SystemTracks();



                foreach ($entries as $entry) {

                    if (
                        $entry->getStartTrack() <= 0
                    ) {

                        continue;
                    }


                    $chain =
                        $reader->readFileChain(
                            $entry->getStartTrack(),
                            $entry->getStartSector()
                        );


                    $builder->addSectors(
                        $chain
                    );
                }


                $calculated =
                    $builder->getLayout();


                $bamReader =
                    new C64BamReader(
                        $file->getPath()
                    );


                $realBam =
                    $bamReader->read();



                $comparator =
                    new C64BamComparator();



                $comparison =
                    $comparator->compare(
                        $realBam,
                        $calculated
                    );



                $checker =
                    new C64DiskIntegrityChecker();



                $integrity =
                    $checker->check(
                        $comparison
                    );
            }
        }



        $this->view->render(
            'disk/index',
            [
                'title' =>
                    'C64 Disk Explorer',


                'release' =>
                    $release,


                'files' =>
                    $files,


                'directories' =>
                    $directories,


                'search' =>
                    $search,


                'sort' =>
                    $sort,


                'integrity' =>
                    $integrity,


                'comparison' =>
                    $comparison
            ]
        );
    }
}
