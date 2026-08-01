<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class D81FileReader
{
    private const IMAGE_SIZE = 819200;

    private const SECTORS_PER_TRACK = 40;


    /**
     * Läser en fil från en D81-sektorkedja.
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
                 * Sista sektorn
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
                 * Normal sektor:
                 * 254 bytes data
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
            (
                (($track - 1)
                *
                self::SECTORS_PER_TRACK)
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

