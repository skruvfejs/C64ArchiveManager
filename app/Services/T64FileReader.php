<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class T64FileReader
{
    public function read(
        string $filename,
        int $offset,
        int $size
    ): string {

        if (!is_file($filename)) {

            throw new RuntimeException(
                'T64 file not found.'
            );
        }


        $fp =
            fopen(
                $filename,
                'rb'
            );


        if ($fp === false) {

            throw new RuntimeException(
                'Unable to open T64.'
            );
        }


        try {

            if (
                fseek(
                    $fp,
                    $offset
                ) !== 0
            ) {

                throw new RuntimeException(
                    'Unable to seek T64 data.'
                );
            }


            $data =
                fread(
                    $fp,
                    $size
                );


            if (
                $data === false ||
                strlen($data) !== $size
            ) {

                throw new RuntimeException(
                    'Unable to read T64 data.'
                );
            }


            return $data;


        } finally {

            fclose($fp);
        }
    }
}

