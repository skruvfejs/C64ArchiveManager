<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\DirectoryEntry;
use RuntimeException;

final class D64DirectoryParser
{
    private const DIRECTORY_TRACK = 18;
    private const DIRECTORY_SECTOR = 1;


    public function __construct(
        private PetsciiDecoder $decoder
    ) {
    }


    /**
     * @return DirectoryEntry[]
     */
    public function readDirectory(
        string $filename
    ): array {

        $fp = $this->open($filename);

        try {

            $entries = [];

            $position = 0;

            $track =
                self::DIRECTORY_TRACK;

            $sector =
                self::DIRECTORY_SECTOR;


            while ($track !== 0) {

                $data =
                    $this->readSector(
                        $fp,
                        $track,
                        $sector
                    );


                $nextTrack =
                    ord($data[0]);

                $nextSector =
                    ord($data[1]);


                for (
                    $i = 0;
                    $i < 8;
                    $i++
                ) {

                    $offset =
                        2 + ($i * 32);


                    if (
                        $offset + 31 >= strlen($data)
                    ) {
                        continue;
                    }


                    $type =
                        ord(
                            $data[$offset]
                        );


                    if (
                        ($type & 0x07) === 0
                    ) {
                        continue;
                    }


                    $entry =
                        new DirectoryEntry();


                    $entry->setDirectoryPosition(
                        $position
                    );


                    $entry->setFilename(
                        $this->decoder->decode(
                            substr(
                                $data,
                                $offset + 3,
                                16
                            )
                        )
                    );


                    $entry->setFiletype(
                        $this->decodeType(
                            $type
                        )
                    );


                    $entry->setStartTrack(
                        ord(
                            $data[$offset + 1]
                        )
                    );


                    $entry->setStartSector(
                        ord(
                            $data[$offset + 2]
                        )
                    );
                    $entry->setBlocks(
                        ord(
                            $data[$offset + 30]
                        )
                        |
                        (
                            ord(
                                $data[$offset + 31]
                            )
                            << 8
                        )
                    );


                    $entry->setLocked(
                        ($type & 0x40) !== 0
                    );


                    $entry->setClosed(
                        ($type & 0x80) !== 0
                    );


                    $entries[] =
                        $entry;


                    $position++;
                }


                $track =
                    $nextTrack;


                $sector =
                    $nextSector;
            }


            return $entries;


        } finally {

            fclose($fp);

        }
    }


    private function decodeType(
        int $type
    ): string {

        return match ($type & 0x07) {

            0 => 'DEL',
            1 => 'SEQ',
            2 => 'PRG',
            3 => 'USR',
            4 => 'REL',

            default => '???'
        };
    }
    private function open(
        string $filename
    ) {

        if (!is_file($filename)) {

            throw new RuntimeException(
                'D64 image not found.'
            );
        }


        $fp =
            fopen(
                $filename,
                'rb'
            );


        if ($fp === false) {

            throw new RuntimeException(
                'Unable to open D64.'
            );
        }


        return $fp;
    }


    private function readSector(
        $fp,
        int $track,
        int $sector
    ): string {

        $offset =
            $this->sectorOffset(
                $track,
                $sector
            );


        fseek(
            $fp,
            $offset
        );


        $data =
            fread(
                $fp,
                256
            );


        if (
            $data === false ||
            strlen($data) !== 256
        ) {

            throw new RuntimeException(
                'Unable to read D64 sector.'
            );
        }


        return $data;
    }


    private function sectorOffset(
        int $track,
        int $sector
    ): int {

        if (
            $track < 1 ||
            $track > 35
        ) {

            throw new RuntimeException(
                'Invalid D64 track.'
            );
        }


        if (
            $sector < 0 ||
            $sector >= $this->sectorsOnTrack($track)
        ) {

            throw new RuntimeException(
                'Invalid D64 sector.'
            );
        }


        $offset = 0;


        for (
            $t = 1;
            $t < $track;
            $t++
        ) {

            $offset +=
                $this->sectorsOnTrack($t)
                * 256;
        }


        $offset +=
            $sector * 256;


        return $offset;
    }


    private function sectorsOnTrack(
        int $track
    ): int {

        return match (true) {

            $track <= 17 => 21,

            $track <= 24 => 19,

            $track <= 30 => 18,

            default => 17
        };
    }
}

