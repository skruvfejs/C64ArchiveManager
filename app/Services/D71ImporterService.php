<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Release;
use App\Entity\ReleaseFile;
use App\Repositories\ReleaseRepository;
use App\Repositories\ReleaseFileRepository;
use App\Repositories\DirectoryEntryRepository;
use RuntimeException;

final class D71ImporterService
{
    public function __construct(
        private D71Parser $parser,
        private D71DirectoryParser $directoryParser,
        private D71FileReader $fileReader,
        private ChecksumService $checksumService,
        private ReleaseRepository $releaseRepository,
        private ReleaseFileRepository $releaseFileRepository,
        private DirectoryEntryRepository $directoryEntryRepository
    ) {
    }


    public function import(
        string $filename,
        int $entryId,
        bool $forceDuplicate = false
    ): int {

        if (!is_file($filename)) {

            throw new RuntimeException(
                'D71 file not found.'
            );
        }


        /*
         * Läs diskinfo
         */

        $header =
            $this->parser
                 ->readHeader($filename);



        /*
         * Beräkna checksum
         */

        $checksum =
            $this->checksumService
                 ->all($filename);



        /*
         * Finns samma disk redan?
         *
         * Vid force-import ignoreras MD5.
         */

        $existingReleaseFile = null;


        if (!$forceDuplicate) {

            $existingReleaseFile =
                $this->releaseFileRepository
                     ->findByMd5(
                         $checksum['md5']
                     );
        }


        if ($existingReleaseFile !== null) {

            /*
             * Återanvänd befintlig disk
             */

            $releaseFileId =
                $existingReleaseFile->getId();


            $releaseId =
                $existingReleaseFile->getReleaseId();


        } else {


            /*
             * Skapa ny release
             */

            $release = new Release();


            $diskName =
                $header['disk_name'] !== ''
                    ? $header['disk_name']
                    : basename($filename);


            if ($forceDuplicate) {

                $diskName =
                    $this->createDuplicateName(
                        $entryId,
                        $diskName,
                        'D71'
                    );
            }


            $release
                ->setEntryId($entryId)
                ->setName($diskName)
                ->setVersion('D71');


            $releaseId =
                $this->releaseRepository
                     ->create($release);



            /*
             * Skapa ny release_file
             */

            $releaseFile = new ReleaseFile();


            $releaseFile
                ->setReleaseId($releaseId)
                ->setFilename(
                    basename($filename)
                )
                ->setFormat('D71')
                ->setDiskName(
                    $diskName
                )
                ->setDiskId(
                    $header['disk_id'] ?? null
                )
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


            $releaseFileId =
                $this->releaseFileRepository
                     ->create($releaseFile);
        }

        /*
         * Importera katalogposter
         */

        $entries =
            $this->directoryParser
                 ->readDirectory($filename);



        foreach ($entries as $entry) {


            $entry
                ->setReleaseFileId(
                    $releaseFileId
                );


            $existing =
                $this->directoryEntryRepository
                     ->findExisting(
                         $releaseFileId,
                         $entry->getFilename(),
                         $entry->getDirectoryPosition()
                     );


            if ($existing !== null) {


                $entry->setId(
                    $existing->getId()
                );


                $this->directoryEntryRepository
                     ->update($entry);


            } else {


                $this->directoryEntryRepository
                     ->create($entry);

            }
        }



        return $releaseId;
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

