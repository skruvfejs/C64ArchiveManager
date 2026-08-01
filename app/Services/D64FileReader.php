<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class D64FileReader
{
    private const IMAGE_SIZE = 174848;

    private const SECTORS_PER_TRACK = [
        0,
        21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,
        19,19,19,19,19,19,19,
        18,18,18,18,18,18,
        17,17,17,17,17
    ];

    /**
     * Läser en fil från en D64-sektorkedja.
     */
    public function read(
        string $filename,
        int $startTrack,
        int $startSector
    ): string {

        $fp = $this->openImage($filename);

        try {

            $data = '';

            $track = $startTrack;
            $sector = $startSector;

            while ($track !== 0) {

                $sectorData = $this->readSector(
                    $fp,
                    $track,
                    $sector
                );

                $nextTrack = ord($sectorData[0]);
                $nextSector = ord($sectorData[1]);

                /*
                 * Om nästa track är 0 är detta sista sektorn.
                 * Byte 1 innehåller då antal giltiga bytes.
                 */
                if ($nextTrack === 0) {

                    $usedBytes = ord($sectorData[1]);

                    $data .= substr(
                        $sectorData,
                        2,
                        $usedBytes - 1
                    );

                    break;
                }

                $data .= substr(
                    $sectorData,
                    2,
                    254
                );

                $track = $nextTrack;
                $sector = $nextSector;
            }

            return $data;

        } finally {

            fclose($fp);

        }
    }


    private function openImage(string $filename)
    {
        if (!is_file($filename)) {
            throw new RuntimeException(
                'D64 image not found.'
            );
        }

        if (filesize($filename) !== self::IMAGE_SIZE) {
            throw new RuntimeException(
                'Unsupported D64 image size.'
            );
        }

        $fp = fopen($filename, 'rb');

        if ($fp === false) {
            throw new RuntimeException(
                'Unable to open D64 image.'
            );
        }

        return $fp;
    }


    private function readSector(
        $fp,
        int $track,
        int $sector
    ): string {

        $offset = $this->trackSectorOffset(
            $track,
            $sector
        );

        fseek($fp, $offset);

        $data = fread($fp, 256);

        if ($data === false || strlen($data) !== 256) {

            throw new RuntimeException(
                'Unable to read sector.'
            );
        }

        return $data;
    }


    private function trackSectorOffset(
        int $track,
        int $sector
    ): int {

        $offset = 0;

        for ($i = 1; $i < $track; $i++) {

            $offset +=
                self::SECTORS_PER_TRACK[$i] * 256;

        }

        return $offset + ($sector * 256);
    }
}

