<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\DirectoryEntry;
use App\Entity\ReleaseFile;
use RuntimeException;

final class C64FileExtractorService
{
    public function __construct(
        private D64FileReader $d64Reader,
        private D71FileReader $d71Reader,
        private D81FileReader $d81Reader
    ) {
    }


    public function extract(
        ReleaseFile $releaseFile,
        DirectoryEntry $entry
    ): string {


        $format =
            strtoupper(
                $releaseFile->getFormat()
            );


        return match ($format) {

            'D64' =>
                $this->d64Reader->read(
                    $releaseFile->getPath(),
                    $entry->getStartTrack(),
                    $entry->getStartSector()
                ),


            'D71' =>
                $this->d71Reader->read(
                    $releaseFile->getPath(),
                    $entry->getStartTrack(),
                    $entry->getStartSector()
                ),


            'D81' =>
                $this->d81Reader->read(
                    $releaseFile->getPath(),
                    $entry->getStartTrack(),
                    $entry->getStartSector()
                ),


            default =>
                throw new RuntimeException(
                    'Unsupported disk format: '
                    . $format
                )
        };
    }
}

