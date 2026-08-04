<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\ImportResult;
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
        int $entryId,
        bool $forceDuplicate = false
    ): ImportResult {

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


            $version =
                $header['dos_type'] ?? null;


            if ($forceDuplicate) {

                $diskName =
                    $this->createDuplicateName(
                        $entryId,
                        $diskName,
                        $version
                    );
            }


            $release
                ->setEntryId($entryId)
                ->setName($diskName)
                ->setVersion($version);


            if (
                !$forceDuplicate &&
                $this->releaseRepository
                     ->existsByEntryNameVersion(
                         $entryId,
                         $diskName,
                         $version
                     )
            ) {

                throw new RuntimeException(
                    'Release already exists.'
                );
            }


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



        return new ImportResult(
            $releaseId,
            count($entries)
        );
    }


    private function createDuplicateName(
        int $entryId,
        string $name,
        ?string $version
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
