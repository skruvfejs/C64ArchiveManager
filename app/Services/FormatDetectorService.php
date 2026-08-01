<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class FormatDetectorService
{
    public function detect(string $filename): string
    {
        if (!is_file($filename)) {
            throw new RuntimeException(
                'File not found.'
            );
        }


        /*
         * Kontrollera filändelse först.
         */

        $extension = strtolower(
            pathinfo(
                $filename,
                PATHINFO_EXTENSION
            )
        );


        return match ($extension) {

            'd64' => 'D64',
            'd71' => 'D71',
            'd81' => 'D81',
            't64' => 'T64',
            'prg' => 'PRG',
            'p00' => 'P00',

            default =>
                $this->detectByHeader($filename)
        };
    }


    private function detectByHeader(
        string $filename
    ): string {

        $fp = fopen($filename, 'rb');

        if ($fp === false) {
            throw new RuntimeException(
                'Unable to open file.'
            );
        }


        try {

            $header = fread($fp, 64);

            if ($header === false) {
                throw new RuntimeException(
                    'Unable to read header.'
                );
            }


            /*
             * T64 magic
             */

            if (
                str_starts_with(
                    $header,
                    "C64 tape image file"
                )
            ) {
                return 'T64';
            }


            /*
             * P00 format
             */

            if (
                substr($header, 0, 8)
                === "C64File\0"
            ) {
                return 'P00';
            }


            /*
             * D64 storlekar
             */

            $size = filesize($filename);


            return match ($size) {

                174848,
                175531,
                196608
                    => 'D64',

                349696,
                351062
                    => 'D71',

                819200
                    => 'D81',

                default
                    => 'UNKNOWN'
            };


        } finally {

            fclose($fp);

        }
    }
}

