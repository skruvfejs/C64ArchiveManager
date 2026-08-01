<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class T64Parser
{
    private const HEADER_SIZE = 32;

    /**
     * Läser T64 metadata.
     *
     * @return array<string,mixed>
     */
    public function parse(string $filename): array
    {
        $fp = $this->open($filename);

        try {

            $header = fread($fp, self::HEADER_SIZE);

            if ($header === false || strlen($header) !== 32) {
                throw new RuntimeException(
                    'Unable to read T64 header.'
                );
            }


            if (
                !str_starts_with(
                    $header,
                    'C64'
                )
            ) {
                throw new RuntimeException(
                    'Invalid T64 file.'
                );
            }


            $version =
                unpack(
                    'v',
                    substr($header, 24, 2)
                )[1];


            $entries =
                unpack(
                    'v',
                    substr($header, 26, 2)
                )[1];


            return [
                'version' => $version,
                'entries' => $entries,
                'description' =>
                    rtrim(
                        str_replace(
                            "\x20",
                            ' ',
                            substr(
                                $header,
                                8,
                                24
                            )
                        )
                    )
            ];


        } finally {

            fclose($fp);

        }
    }


    /**
     * Läser filposter.
     *
     * @return array<int,array<string,mixed>>
     */
    public function readEntries(string $filename): array
    {
        $fp = $this->open($filename);

        try {

            fseek(
                $fp,
                32
            );


            $header = fread(
                $fp,
                32
            );


            $entries =
                unpack(
                    'v',
                    substr(
                        $header,
                        26,
                        2
                    )
                )[1];


            $result = [];


            for ($i = 0; $i < $entries; $i++) {

                $entry = fread(
                    $fp,
                    32
                );


                if (
                    $entry === false ||
                    strlen($entry) !== 32
                ) {
                    break;
                }


                $type =
                    ord($entry[0]);


                if ($type === 0) {
                    continue;
                }


                $startAddress =
                    unpack(
                        'v',
                        substr(
                            $entry,
                            2,
                            2
                        )
                    )[1];


                $endAddress =
                    unpack(
                        'v',
                        substr(
                            $entry,
                            4,
                            2
                        )
                    )[1];


                $offset =
                    unpack(
                        'V',
                        substr(
                            $entry,
                            8,
                            4
                        )
                    )[1];


                $name =
                    rtrim(
                        str_replace(
                            "\x20",
                            ' ',
                            substr(
                                $entry,
                                16,
                                16
                            )
                        )
                    );


                $result[] = [
                    'name' => $name,
                    'start_address' =>
                        $startAddress,
                    'end_address' =>
                        $endAddress,
                    'offset' =>
                        $offset,
                    'size' =>
                        $endAddress - $startAddress,
                ];
            }


            return $result;


        } finally {

            fclose($fp);

        }
    }


    private function open(string $filename)
    {
        if (!is_file($filename)) {

            throw new RuntimeException(
                'T64 file not found.'
            );
        }


        $fp = fopen(
            $filename,
            'rb'
        );


        if ($fp === false) {

            throw new RuntimeException(
                'Unable to open T64.'
            );
        }


        return $fp;
    }
}

