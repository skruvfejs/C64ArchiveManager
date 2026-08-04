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

final class D81ImporterService
{
    public function __construct(
        private D81Parser $parser,
        private D81DirectoryParser $directoryParser,
        private D81FileReader $fileReader,
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
                'D81 file not found.'
            );
        }



        $header =
            $this->parser
                 ->readHeader($filename);



        $checksum =
            $this->checksumService
                 ->all($filename);



        $existingReleaseFile = null;



        if (!$forceDuplicate) {

            $existingReleaseFile =
                $this->releaseFileRepository
                     ->findByMd5AndEntry(
                         $checksum['md5'],
                         $entryId
                     );
        }



        if ($existingReleaseFile !== null) {

            return (new ImportResult(
                0,
                0
            ))->setDuplicate([

                'entryId' =>
                    $entryId,

                'path' =>
                    $filename,

                'filename' =>
                    basename($filename),

                'md5' =>
                    $checksum['md5'],

                'existing' =>
                    $existingReleaseFile

            ]);
        }



        $release = new Release();



        $diskName =
            $header['disk_name'] !== ''
                ? $header['disk_name']
                : basename($filename);



        $version = 'D81';



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



        $releaseId =
            $this->releaseRepository
                 ->create($release);
        $releaseFile = new ReleaseFile();


        $releaseFile
            ->setReleaseId($releaseId)
            ->setFilename(
                basename($filename)
            )
            ->setFormat('D81')
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
