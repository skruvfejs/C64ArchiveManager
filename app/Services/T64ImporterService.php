<?php

declare(strict_types=1);

namespace App\Services;

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
        int $entryId
    ): int {

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
         * Skapa release
         */

        $release = new Release();

        $release
            ->setEntryId($entryId)
            ->setName(
                $header['description'] !== ''
                    ? $header['description']
                    : basename($filename)
            )
            ->setVersion(
                'T64'
            );


        $releaseId =
            $this->releaseRepository
                 ->create($release);



        /*
         * Skapa release_file
         */

        $checksum =
            $this->checksumService
                 ->all($filename);


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


        return $releaseId;
    }
}

