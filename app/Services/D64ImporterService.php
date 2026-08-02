<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Release;
use App\Entity\ReleaseFile;
use App\Repositories\ReleaseRepository;
use App\Repositories\ReleaseFileRepository;
use App\Repositories\DirectoryEntryRepository;
use RuntimeException;

final class D64ImporterService
{
    public function __construct(
        private D64Parser $parser,
        private D64DirectoryParser $directoryParser,
        private D64BamParser $bamParser,
        private D64FileReader $fileReader,
        private ChecksumService $checksumService,
        private ReleaseRepository $releaseRepository,
        private ReleaseFileRepository $releaseFileRepository,
        private DirectoryEntryRepository $directoryEntryRepository
    ) {
    }


    public function import(
        string $filename,
        int $entryId
    ): int {

        if (!is_file($filename)) {

            throw new RuntimeException(
                'D64 file not found.'
            );
        }


        /*
         * Läs diskmetadata
         */

        $header =
            $this->parser
                 ->readHeader($filename);


        /*
         * Beräkna checksum först
         */

        $checksum =
            $this->checksumService
                 ->all($filename);



        /*
         * Finns redan samma disk?
         */

        $existingReleaseFile =
            $this->releaseFileRepository
                 ->findByMd5(
                     $checksum['md5']
                 );


        if ($existingReleaseFile !== null) {

            $releaseFileId =
                $existingReleaseFile->getId();


            $releaseId =
                $existingReleaseFile->getReleaseId();


        } else {


            /*
             * Skapa ny release
             */

            $release = new Release();


            $release
                ->setEntryId($entryId)
                ->setName(
                    $header['disk_name'] !== ''
                        ? $header['disk_name']
                        : basename($filename)
                )
                ->setVersion(
                    $header['dos_type'] ?? null
                );


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
                ->setFormat('D64')
                ->setDiskName(
                    $header['disk_name'] !== ''
                        ? $header['disk_name']
                        : basename($filename)
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
         * Läs katalog
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
}

