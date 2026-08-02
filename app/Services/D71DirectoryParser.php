<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\DirectoryEntry;
use RuntimeException;

final class D71DirectoryParser
{
    public function __construct(
        private D71Parser $parser,
        private PetsciiDecoder $decoder
    ) {
    }


    /**
     * Läser katalogposter från en D71.
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

            $track = 18;
            $sector = 1;


            while ($track !== 0) {

                $data =
                    $this->parser->readSector(
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


                    $fileType =
                        ord(
                            $data[$offset]
                        );


                    if (
                        ($fileType & 0x07) === 0
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


                    $entry
                        ->setFilename($name)

                        ->setFiletype(
                            $this->decodeType(
                                $fileType
                            )
                        )

                        ->setStartTrack(
                            ord(
                                $data[$offset + 1]
                            )
                        )

                        ->setStartSector(
                            ord(
                                $data[$offset + 2]
                            )
                        );


                    $entry
                        ->setBlocks($blocks)

                        ->setLocked(
                            ($fileType & 0x40) !== 0
                        )

                        ->setClosed(
                            ($fileType & 0x80) !== 0
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
                'D71 image not found.'
            );
        }


        $fp =
            fopen(
                $filename,
                'rb'
            );


        if ($fp === false) {

            throw new RuntimeException(
                'Unable to open D71.'
            );
        }


        return $fp;
    }
}

