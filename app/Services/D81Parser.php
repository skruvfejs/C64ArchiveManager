<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class D81Parser
{
    private const IMAGE_SIZE = 819200;

    private const BAM_TRACK = 40;
    private const BAM_SECTOR = 0;

    private const SECTORS_PER_TRACK = 40;


    /**
     * Läser D81 diskheader.
     *
     * @return array<string,mixed>
     */
    public function readHeader(
        string $filename
    ): array {

        $fp = $this->open($filename);

        try {

            $bam =
                $this->readSector(
                    $fp,
                    self::BAM_TRACK,
                    self::BAM_SECTOR
                );


            return [

                'disk_name' =>
                    rtrim(
                        str_replace(
                            "\xA0",
                            ' ',
                            substr(
                                $bam,
                                4,
                                16
                            )
                        )
                    ),

                'disk_id' =>
                    rtrim(
                        str_replace(
                            "\xA0",
                            ' ',
                            substr(
                                $bam,
                                22,
                                2
                            )
                        )
                    ),

                'dos_type' =>
                    rtrim(
                        str_replace(
                            "\xA0",
                            ' ',
                            substr(
                                $bam,
                                38,
                                2
                            )
                        )
                    ),

                'format' => 'D81'
            ];


        } finally {

            fclose($fp);

        }
    }


    private function open(
        string $filename
    ) {

        if (!is_file($filename)) {

            throw new RuntimeException(
                'D81 image not found.'
            );
        }


        if (
            filesize($filename)
            !== self::IMAGE_SIZE
        ) {

            throw new RuntimeException(
                'Invalid D81 image size.'
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
                'Unable to read D81 sector.'
            );
        }


        return $data;
    }


    private function sectorOffset(
        int $track,
        int $sector
    ): int {

        return (
            (($track - 1)
            *
            self::SECTORS_PER_TRACK)
            +
            $sector
        )
        *
        256;
    }
}

