<?php

declare(strict_types=1);

namespace App\Services;


final class DiskGeometry
{
    public function totalBlocks(
        string $format
    ): int {

        return match (
            strtoupper($format)
        ) {

            'D64' => 683,

            'D71' => 1366,

            'D81' => 3200,

            default => 0
        };
    }



    public function tracks(
        string $format
    ): int {

        return match (
            strtoupper($format)
        ) {

            'D64' => 35,

            'D71' => 70,

            'D81' => 80,

            default => 0
        };
    }



    public function diskType(
        string $format
    ): string {

        return match (
            strtoupper($format)
        ) {

            'D64' => '1541',

            'D71' => '1571',

            'D81' => '1581',

            'T64' => 'Tape Image',

            default => 'Unknown'
        };
    }
}

