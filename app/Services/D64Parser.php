<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class D64Parser
{
    public function readHeader(
        string $filename
    ): array {

        if (!is_file($filename)) {

            throw new RuntimeException(
                'D64 file not found.'
            );
        }


        $data =
            file_get_contents($filename);


        if ($data === false) {

            throw new RuntimeException(
                'Unable to read D64 file.'
            );
        }


        /*
         * D64 disk header ligger i katalogspår:
         *
         * Track 18 sector 0
         *
         * Offset:
         * $90-$9F disk name
         * $A2-$A3 disk id
         */

        $sector =
            $this->readSector(
                $data,
                18,
                0
            );


        return [

            'disk_name' =>
                rtrim(
                    str_replace(
                        "\xA0",
                        ' ',
                        substr(
                            $sector,
                            0x90,
                            16
                        )
                    )
                ),

            'disk_id' =>
                substr(
                    $sector,
                    0xA2,
                    2
                ),

            'format' =>
                'D64'

        ];
    }


    private function readSector(
        string $data,
        int $track,
        int $sector
    ): string {

        $offset =
            $this->sectorOffset(
                $track,
                $sector
            );


        return substr(
            $data,
            $offset,
            256
        );
    }


    private function sectorOffset(
        int $track,
        int $sector
    ): int {

        /*
         * D64 sektorberäkning.
         */

        $offset =
            0;


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

