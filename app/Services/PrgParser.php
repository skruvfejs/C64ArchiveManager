<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class PrgParser
{
    /**
     * Läser PRG-header och metadata.
     *
     * @return array<string,mixed>
     */
    public function parse(
        string $filename
    ): array {

        $fp = $this->open($filename);

        try {

            $header =
                fread(
                    $fp,
                    2
                );


            if (
                $header === false ||
                strlen($header) !== 2
            ) {

                throw new RuntimeException(
                    'Unable to read PRG header.'
                );
            }


            $loadAddress =
                unpack(
                    'v',
                    $header
                )[1];


            $size =
                filesize($filename);


            return [

                'format' => 'PRG',

                'load_address' =>
                    $loadAddress,

                'data_size' =>
                    $size - 2,

                'total_size' =>
                    $size

            ];


        } finally {

            fclose($fp);

        }
    }


    /**
     * Läser själva programdatan.
     */
    public function readData(
        string $filename
    ): string {

        $data =
            file_get_contents(
                $filename
            );


        if ($data === false) {

            throw new RuntimeException(
                'Unable to read PRG.'
            );
        }


        /*
         * Ta bort laddningsadress
         */

        return substr(
            $data,
            2
        );
    }


    private function open(
        string $filename
    ) {

        if (!is_file($filename)) {

            throw new RuntimeException(
                'PRG file not found.'
            );
        }


        $fp =
            fopen(
                $filename,
                'rb'
            );


        if ($fp === false) {

            throw new RuntimeException(
                'Unable to open PRG.'
            );
        }


        return $fp;
    }
}

