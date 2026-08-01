<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class P00Parser
{
    private const HEADER =
        "C64File\0";


    /**
     * Läser P00 metadata.
     *
     * @return array<string,mixed>
     */
    public function parse(
        string $filename
    ): array {

        $data =
            file_get_contents(
                $filename
            );


        if ($data === false) {

            throw new RuntimeException(
                'Unable to read P00 file.'
            );
        }


        if (
            substr(
                $data,
                0,
                8
            )
            !== self::HEADER
        ) {

            throw new RuntimeException(
                'Invalid P00 file.'
            );
        }


        /*
         * P00 header:
         *
         * 0-7   magic
         * 8-23  filename
         * 24    record type
         */


        $name =
            rtrim(
                str_replace(
                    "\0",
                    '',
                    substr(
                        $data,
                        8,
                        16
                    )
                )
            );


        return [

            'format' => 'P00',

            'name' =>
                $name,

            'type' =>
                ord(
                    $data[24]
                ),

            'data_offset' =>
                26,

            'data_size' =>
                strlen($data) - 26

        ];
    }


    /**
     * Returnerar PRG-delen.
     */
    public function extractPrg(
        string $filename
    ): string {

        $data =
            file_get_contents(
                $filename
            );


        if ($data === false) {

            throw new RuntimeException(
                'Unable to read P00.'
            );
        }


        return substr(
            $data,
            26
        );
    }
}

