<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class D64BamParser
{
    private const IMAGE_SIZE = 174848;

    private const BAM_TRACK = 18;
    private const BAM_SECTOR = 0;

    private const SECTORS_PER_TRACK = [
        0,
        21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,
        19,19,19,19,19,19,19,
        18,18,18,18,18,18,
        17,17,17,17,17
    ];

    /**
     * Läser BAM-information från en D64.
     *
     * @return array<string,mixed>
     */
    public function read(string $filename): array
    {
        $fp = $this->openImage($filename);

        try {
            $bam = $this->readSector(
                $fp,
                self::BAM_TRACK,
                self::BAM_SECTOR
            );

            return [
                'disk_name' => $this->readDiskName($bam),
                'disk_id'   => $this->readDiskId($bam),
                'dos_type'  => $this->readDosType($bam),
                'tracks'    => $this->readTracks($bam),
                'free_blocks' => $this->calculateFreeBlocks($bam),
            ];

        } finally {
            fclose($fp);
        }
    }

    private function readTracks(string $bam): array
    {
        $tracks = [];

        for ($track = 1; $track <= 35; $track++) {

            $offset = 4 + (($track - 1) * 4);

            $free = ord($bam[$offset]);

            $bitmap1 = ord($bam[$offset + 1]);
            $bitmap2 = ord($bam[$offset + 2]);
            $bitmap3 = ord($bam[$offset + 3]);

            $tracks[$track] = [
                'free_blocks' => $free,
                'bitmap' => [
                    $bitmap1,
                    $bitmap2,
                    $bitmap3,
                ],
            ];
        }

        return $tracks;
    }

    private function calculateFreeBlocks(array $bam): int
    {
        $total = 0;

        foreach ($bam as $track) {
            $total += $track['free_blocks'];
        }

        return $total;
    }

    private function readDiskName(string $bam): string
    {
        return rtrim(
            str_replace(
                "\xA0",
                ' ',
                substr($bam, 144, 16)
            )
        );
    }

    private function readDiskId(string $bam): string
    {
        return rtrim(
            str_replace(
                "\xA0",
                ' ',
                substr($bam, 162, 2)
            )
        );
    }

    private function readDosType(string $bam): string
    {
        return rtrim(
            str_replace(
                "\xA0",
                ' ',
                substr($bam, 165, 2)
            )
        );
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
            $offset += self::SECTORS_PER_TRACK[$i] * 256;
        }

        return $offset + ($sector * 256);
    }
}

