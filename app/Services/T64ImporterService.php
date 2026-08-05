<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\ImportResult;
use App\Entity\Release;
use App\Entity\ReleaseFile;
use App\Entity\DirectoryEntry;
use App\Repositories\ReleaseRepository;
use App\Repositories\ReleaseFileRepository;
use App\Repositories\DirectoryEntryRepository;
use RuntimeException;

final class T64ImporterService
{
    public function __construct(
        private T64Parser $parser,
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
                'T64 file not found.'
            );
        }


        $header =
            $this->parser
                 ->parse($filename);


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



        $release =
            new Release();


        $name =
            $header['description'] !== ''
                ? $header['description']
                : basename($filename);


        $version = 'T64';


        if ($forceDuplicate) {

            $name =
                $this->createDuplicateName(
                    $entryId,
                    $name,
                    $version
                );
        }


        $release
            ->setEntryId($entryId)
            ->setName($name)
            ->setVersion($version);



        $releaseId =
            $this->releaseRepository
                 ->create($release);



        $releaseFile =
            new ReleaseFile();


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


        $releaseFileId =
            $this->releaseFileRepository
                 ->create($releaseFile);

        $entries =
            $this->parser
                 ->readEntries($filename);



        foreach ($entries as $position => $t64Entry) {


            $entry =
                new DirectoryEntry();


            $entry
                ->setReleaseFileId(
                    $releaseFileId
                )
                ->setFilename(
                    $t64Entry['name']
                )
                ->setDirectoryPosition(
                    $position
                )
                ->setFiletype(
                    'PRG'
                )
                ->setBlocks(
                    0
                );


            $this->directoryEntryRepository
                 ->create($entry);
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
