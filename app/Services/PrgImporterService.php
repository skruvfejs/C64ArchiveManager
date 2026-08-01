<?php

declare(strict_types=1);

namespace App\Services;

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
        int $entryId
    ): int {

        if (!is_file($filename)) {
            throw new RuntimeException(
                'PRG file not found.'
            );
        }


        return $this->importData(
            file_get_contents($filename),
            basename($filename),
            $entryId
        );
    }


    /**
     * Importerar PRG-data från minnet.
     *
     * Används av P00-import.
     */
    public function importData(
        string $data,
        string $filename,
        int $entryId
    ): int {

        if (strlen($data) < 2) {

            throw new RuntimeException(
                'Invalid PRG data.'
            );
        }


        /*
         * PRG:
         *
         * byte 0-1 = load address
         */

        $loadAddress =
            unpack(
                'v',
                substr($data, 0, 2)
            )[1];


        /*
         * Skapa release
         */

        $release = new Release();

        $release
            ->setEntryId($entryId)
            ->setName(
                pathinfo(
                    $filename,
                    PATHINFO_FILENAME
                )
            )
            ->setVersion('PRG');


        $releaseId =
            $this->releaseRepository
                 ->create($release);



        /*
         * Checksums på originaldata
         */

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



        /*
         * Skapa filpost
         */

        $releaseFile = new ReleaseFile();

        $releaseFile
            ->setReleaseId($releaseId)
            ->setFilename($filename)
            ->setFormat('PRG')
            ->setPath($filename)
            ->setSize(strlen($data))
            ->setCrc32($checksum['crc32'])
            ->setMd5($checksum['md5'])
            ->setSha1($checksum['sha1']);


        $this->releaseFileRepository
             ->create($releaseFile);


        return $releaseId;
    }
}

