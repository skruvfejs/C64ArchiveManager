<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\DirectoryEntry;
use RuntimeException;

final class D81DirectoryParser
{
    private const TRACK_START = 40;
    private const SECTOR_START = 3;


    public function __construct(
        private PetsciiDecoder $decoder
    ) {
    }


    /**
     * Läser katalogposter från D81.
     *
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
                self::TRACK_START;

            $sector =
                self::SECTOR_START;


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


                    $name =
                        $this->decoder->decode(
                            substr(
                                $data,
                                $offset + 3,
                                16
                            )
                        );


                    $blocks =
                        ord(
                            $data[$offset + 28]
                        )
                        |
                        (
                            ord(
                                $data[$offset + 29]
                            )
                            << 8
                        );


                    $entry =
                        new DirectoryEntry();


                    $entry->setDirectoryPosition(
                        $position
                    );


                    $entry->setFilename(
                        $name
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
                        $blocks
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
                'D81 image not found.'
            );
        }


        $fp =
            fopen(
                $filename,
                'rb'
            );


        if ($fp === false) {

            throw new RuntimeException(
                'Unable to open D81.'
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
            (
                (($track - 1) * 40)
                +
                $sector
            )
            *
            256;


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
                'Unable to read D81 sector.'
            );
        }


        return $data;
    }
}

