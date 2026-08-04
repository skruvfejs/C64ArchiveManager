<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\ImportResult;
use App\Entity\Release;
use App\Entity\ReleaseFile;
use App\Repositories\ReleaseRepository;
use App\Repositories\ReleaseFileRepository;
use RuntimeException;

final class T64ImporterService
{
    public function __construct(
        private T64Parser $parser,
        private ChecksumService $checksumService,
        private ReleaseRepository $releaseRepository,
        private ReleaseFileRepository $releaseFileRepository
    ) {
    }


    public function import(
        string $filename,
        int $entryId,
        bool $forceDuplicate = false
    ): ImportResult {

        if (!is_file($filename)) {

            throw new RuntimeException(
                'T64 file not found.'
            );
        }


        /*
         * Läs T64-header
         */

        $header =
            $this->parser
                 ->parse($filename);



        /*
         * Beräkna checksum
         */

        $checksum =
            $this->checksumService
                 ->all($filename);



        /*
         * Kontrollera om filen redan finns
         */

        $existingReleaseFile = null;


        if (!$forceDuplicate) {

            $existingReleaseFile =
                $this->releaseFileRepository
                     ->findByMd5(
                         $checksum['md5']
                     );
        }



        /*
         * Återanvänd befintlig release
         */

        if ($existingReleaseFile !== null) {

            $releaseId =
                $existingReleaseFile
                     ->getReleaseId();


        } else {


            /*
             * Skapa release
             */

            $release = new Release();


            $name =
                $header['description'] !== ''
                    ? $header['description']
                    : basename($filename);


            if ($forceDuplicate) {

                $name =
                    $this->createDuplicateName(
                        $entryId,
                        $name,
                        'T64'
                    );
            }


            $release
                ->setEntryId($entryId)
                ->setName($name)
                ->setVersion('T64');


            $releaseId =
                $this->releaseRepository
                     ->create($release);
            /*
             * Skapa release_file
             */

            $releaseFile = new ReleaseFile();


            $releaseFile
                ->setReleaseId($releaseId)
                ->setFilename(
                    basename($filename)
                )
                ->setFormat('T64')
                ->setPath($filename)
                ->setSize(
                    filesize($filename)
                )
                ->setCrc32(
                    $checksum['crc32']
                )
                ->setMd5(
                    $checksum['md5']
                )
                ->setSha1(
                    $checksum['sha1']
                );


            $this->releaseFileRepository
                 ->create($releaseFile);
        }


        return new ImportResult(
            $releaseId,
            1
        );
    }



    private function createDuplicateName(
        int $entryId,
        string $name,
        string $version
    ): string {

        $duplicateName =
            $name . ' (duplicate)';


        $counter = 2;


        while (
            $this->releaseRepository
                 ->existsByEntryNameVersion(
                     $entryId,
                     $duplicateName,
                     $version
                 )
        ) {

            $duplicateName =
                $name
                . ' (duplicate '
                . $counter
                . ')';


            $counter++;
        }


        return $duplicateName;
    }
}
