<?php

declare(strict_types=1);

namespace App\Services;

final class PetsciiDecoder
{
    public function decode(
        string $data
    ): string {

        $result = '';

        $length =
            strlen($data);


        for (
            $i = 0;
            $i < $length;
            $i++
        ) {

            $byte =
                ord($data[$i]);


            $result .=
                $this->decodeByte($byte);
        }


        return rtrim(
            $result
        );
    }


    private function decodeByte(
        int $byte
    ): string {

        return match (true) {


            /*
             * Space
             */
            $byte === 0x20 ||
            $byte === 0xA0
                => ' ',


            /*
             * A-Z PETSCII
             */
            $byte >= 0x41 &&
            $byte <= 0x5A
                => chr($byte),


            /*
             * a-z PETSCII
             */
            $byte >= 0x61 &&
            $byte <= 0x7A
                => chr($byte),


            /*
             * Numbers
             */
            $byte >= 0x30 &&
            $byte <= 0x39
                => chr($byte),


            /*
             * Common PETSCII symbols
             */
            $byte === 0x21
                => '!',

            $byte === 0x22
                => '"',

            $byte === 0x23
                => '#',

            $byte === 0x24
                => '$',

            $byte === 0x25
                => '%',

            $byte === 0x26
                => '&',

            $byte === 0x27
                => "'",

            $byte === 0x28
                => '(',

            $byte === 0x29
                => ')',

            $byte === 0x2A
                => '*',

            $byte === 0x2B
                => '+',

            $byte === 0x2C
                => ',',

            $byte === 0x2D
                => '-',

            $byte === 0x2E
                => '.',

            $byte === 0x2F
                => '/',


            $byte === 0x3A
                => ':',

            $byte === 0x3B
                => ';',

            $byte === 0x3F
                => '?',

            $byte === 0x40
                => '@',


            /*
             * Unknown PETSCII
             */
            default
                => ''
        };
    }
}

