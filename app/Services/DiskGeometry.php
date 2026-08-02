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
}

