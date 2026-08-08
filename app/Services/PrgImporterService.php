<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\ImportResult;
use App\Entity\Release;
use App\Entity\ReleaseFile;
use App\Repositories\ReleaseRepository;
use App\Repositories\ReleaseFileRepository;
use RuntimeException;

final class PrgImporterService
{
    public function __construct(
        private PrgParser $parser,
        private ChecksumService $checksumService,
        private ReleaseRepository $releaseRepository,
        private ReleaseFileRepository $releaseFileRepository
    ) {
    }



    public function import(
        string $filename,
        int $entryId,
        bool $forceDuplicate = false,
        ?string $notes = null
    ): ImportResult {


        if (!is_file($filename)) {

            throw new RuntimeException(
                'PRG file not found.'
            );
        }



        return $this->importData(
            file_get_contents($filename),
            basename($filename),
            $filename,
            $entryId,
            $forceDuplicate,
            $notes
        );
    }



    public function importData(
        string $data,
        string $filename,
        string $sourcePath,
        int $entryId,
        bool $forceDuplicate = false,
        ?string $notes = null,
        string $format = 'PRG'
    ): ImportResult {


        if (strlen($data) < 2) {

            throw new RuntimeException(
                'Invalid PRG data.'
            );
        }



        $tmp =
            tempnam(
                sys_get_temp_dir(),
                'prg_'
            );



        file_put_contents(
            $tmp,
            $data
        );



        $checksum =
            $this->checksumService
                 ->all($tmp);



        unlink($tmp);



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
                    $sourcePath,

                'filename' =>
                    $filename,

                'md5' =>
                    $checksum['md5'],

                'existing' =>
                    $existingReleaseFile,

                'notes' =>
                    $notes

            ]);
        }



        $name =
            pathinfo(
                $filename,
                PATHINFO_FILENAME
            );
        if (
            $this->releaseRepository
                 ->existsByEntryNameVersion(
                     $entryId,
                     $name,
                     $format
                 )
        ) {

            $name =
                $this->createDuplicateName(
                    $entryId,
                    $name,
                    $format
                );
        }



        if ($forceDuplicate) {

            $name =
                $this->createDuplicateName(
                    $entryId,
                    $name,
                    $format
                );
        }



        $release = new Release();



        $release
            ->setEntryId($entryId)
            ->setName($name)
            ->setVersion($format)
            ->setNotes($notes);



        $releaseId =
            $this->releaseRepository
                 ->create($release);



        $releaseFile = new ReleaseFile();



        $releaseFile
            ->setReleaseId($releaseId)
            ->setFilename($filename)
            ->setFormat($format)
            ->setPath($sourcePath)
            ->setSize(strlen($data))
            ->setCrc32($checksum['crc32'])
            ->setMd5($checksum['md5'])
            ->setSha1($checksum['sha1']);



        $this->releaseFileRepository
             ->create($releaseFile);



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
