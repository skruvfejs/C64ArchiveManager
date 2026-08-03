<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class DiskHeaderService
{
    public function __construct(
        private FormatDetectorService $formatDetector,
        private D64Parser $d64Parser,
        private D71Parser $d71Parser,
        private D81Parser $d81Parser
    ) {
    }



    /**
     * Hämtar diskens interna namn.
     *
     * Exempel:
     * D64/D71/D81 header:
     * "DISK 08"
     */
    public function getName(
        string $filename
    ): string {


        $format =
            $this->formatDetector
                 ->detect($filename);



        $header =
            match ($format) {


                'D64' =>
                    $this->d64Parser
                         ->readHeader($filename),


                'D71' =>
                    $this->d71Parser
                         ->readHeader($filename),


                'D81' =>
                    $this->d81Parser
                         ->readHeader($filename),


                default =>
                    throw new RuntimeException(
                        'Unable to read disk header for format: '
                        . $format
                    )
            };



        $name =
            trim(
                (string) (
                    $header['disk_name']
                    ?? ''
                )
            );



        /*
         * Om header saknar namn:
         * använd filnamnet som fallback
         */
        if ($name === '') {

            $name =
                pathinfo(
                    $filename,
                    PATHINFO_FILENAME
                );
        }



        return $this->normalize($name);
    }



    private function normalize(
        string $name
    ): string {


        $name =
            trim($name);



        /*
         * C64-namn kan innehålla
         * utfyllnadsmellanslag
         */
        $name =
            preg_replace(
                '/\s+/',
                ' ',
                $name
            );



        return strtoupper(
            trim($name)
        );
    }
}

