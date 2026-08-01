<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class D71Parser
{
    private const IMAGE_SIZE = 349696;

    private const BAM_TRACK = 18;
    private const BAM_SECTOR = 0;

    /**
     * Antal sektorer per spår.
     */
    private const SECTORS_PER_TRACK = [
        0,

        // Track 1-17
        21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,

        // Track 18-24
        19,19,19,19,19,19,19,

        // Track 25-30
        18,18,18,18,18,18,

        // Track 31-35
        17,17,17,17,17,

        // Track 36-52
        21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,

        // Track 53-59
        19,19,19,19,19,19,19,

        // Track 60-65
        18,18,18,18,18,18,

        // Track 66-70
        17,17,17,17,17
    ];


    /**
     * Läser D71 BAM-header.
     *
     * @return array<string,mixed>
     */
    public function readHeader(string $filename): array
    {
        $fp = $this->open($filename);

        try {

            $bam = $this->readSector(
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
                                144,
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
                                162,
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
                                165,
                                2
                            )
                        )
                    ),

                'format' => 'D71'

            ];

        } finally {

            fclose($fp);

        }
    }


    private function open(string $filename)
    {
        if (!is_file($filename)) {
            throw new RuntimeException(
                'D71 image not found.'
            );
        }


        if (filesize($filename) !== self::IMAGE_SIZE) {
            throw new RuntimeException(
                'Invalid D71 image size.'
            );
        }


        $fp = fopen(
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


    public function readSector(
        $fp,
        int $track,
        int $sector
    ): string {

        $offset =
            $this->trackSectorOffset(
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
                'Sector read failed.'
            );
        }


        return $data;
    }


    private function trackSectorOffset(
        int $track,
        int $sector
    ): int {

        $offset = 0;


        for (
            $i = 1;
            $i < $track;
            $i++
        ) {

            $offset +=
                self::SECTORS_PER_TRACK[$i]
                * 256;

        }


        return $offset + ($sector * 256);
    }
}

