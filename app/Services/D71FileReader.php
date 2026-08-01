<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class D71FileReader
{
    private const IMAGE_SIZE = 349696;


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
     * Läser en fil från D71.
     */
    public function read(
        string $filename,
        int $startTrack,
        int $startSector
    ): string {

        $fp = $this->open($filename);

        try {

            $result = '';

            $track = $startTrack;
            $sector = $startSector;


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


                /*
                 * Sista sektorn:
                 *
                 * byte 1 = antal använda bytes
                 */

                if ($nextTrack === 0) {

                    $used =
                        $nextSector;


                    $result .=
                        substr(
                            $data,
                            2,
                            $used - 1
                        );


                    break;
                }


                /*
                 * Normal sektor
                 */

                $result .=
                    substr(
                        $data,
                        2,
                        254
                    );


                $track =
                    $nextTrack;

                $sector =
                    $nextSector;
            }


            return $result;


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


        if (
            filesize($filename)
            !== self::IMAGE_SIZE
        ) {

            throw new RuntimeException(
                'Invalid D71 image size.'
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


    private function readSector(
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


        return
            $offset +
            ($sector * 256);
    }
}

