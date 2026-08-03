<?php

declare(strict_types=1);

namespace App\Services;

final class C64DiskMapBuilder
{
    /**
     * Build a simple visual map of the disk.
     *
     * █ = Used
     * ▒ = Unreferenced
     * ░ = Free
     *
     * @param array<int,array> $bam
     * @param array<int,array> $comparison
     *
     * @return array<int,string>
     */
    public function build(
        array $bam,
        array $comparison
    ): array {

        $map = [];

        foreach ($bam as $track => $info) {

            if (!is_int($track)) {
                continue;
            }

            $used =
                (int) ($info['used'] ?? 0);

            $total =
                (int) ($info['total'] ?? 0);

            $orphan =
                (int) (
                    $comparison[$track]['extra_used']
                    ?? 0
                );

            $free =
                max(
                    0,
                    $total - $used
                );

            $line = '';

            $normalUsed =
                max(
                    0,
                    $used - $orphan
                );

            $line .= str_repeat(
                '█',
                $normalUsed
            );

            $line .= str_repeat(
                '▒',
                $orphan
            );

            $line .= str_repeat(
                '░',
                $free
            );

            $map[$track] = $line;
        }

        ksort($map);

        return $map;
    }
}

