<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class ChecksumService
{
    public function crc32(string $filename): string
    {
        return $this->hash($filename, 'crc32b');
    }

    public function md5(string $filename): string
    {
        return $this->hash($filename, 'md5');
    }

    public function sha1(string $filename): string
    {
        return $this->hash($filename, 'sha1');
    }

    /**
     * Returnerar samtliga checksummor.
     *
     * @return array{
     *     crc32:string,
     *     md5:string,
     *     sha1:string
     * }
     */
    public function all(string $filename): array
    {
        return [
            'crc32' => $this->crc32($filename),
            'md5'   => $this->md5($filename),
            'sha1'  => $this->sha1($filename),
        ];
    }

    private function hash(string $filename, string $algorithm): string
    {
        if (!is_file($filename)) {
            throw new RuntimeException(
                sprintf('File not found: %s', $filename)
            );
        }

        if (!is_readable($filename)) {
            throw new RuntimeException(
                sprintf('File is not readable: %s', $filename)
            );
        }

        $hash = hash_file($algorithm, $filename);

        if ($hash === false) {
            throw new RuntimeException(
                sprintf(
                    'Unable to calculate %s for %s',
                    $algorithm,
                    $filename
                )
            );
        }

        return strtolower($hash);
    }
}
