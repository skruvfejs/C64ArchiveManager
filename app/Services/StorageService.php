<?php

declare(strict_types=1);

namespace App\Services;

final class StorageService
{
    private string $importDirectory;


    public function __construct()
    {
        $this->importDirectory =
            dirname(__DIR__, 2)
            . '/storage/imports';
    }


    public function getImportDirectory(): string
    {
        return $this->importDirectory;
    }


    public function getFileCount(): int
    {
        if (!is_dir($this->importDirectory)) {
            return 0;
        }


        $files = scandir($this->importDirectory);


        if ($files === false) {
            return 0;
        }


        $count = 0;


        foreach ($files as $file) {

            if (
                $file === '.' ||
                $file === '..'
            ) {
                continue;
            }


            $path =
                $this->importDirectory
                . '/'
                . $file;


            if (is_file($path)) {
                $count++;
            }
        }


        return $count;
    }


    public function getUsedSpace(): int
    {
        if (!is_dir($this->importDirectory)) {
            return 0;
        }


        $files = scandir($this->importDirectory);


        if ($files === false) {
            return 0;
        }


        $size = 0;


        foreach ($files as $file) {

            if (
                $file === '.' ||
                $file === '..'
            ) {
                continue;
            }


            $path =
                $this->importDirectory
                . '/'
                . $file;


            if (is_file($path)) {
                $fileSize = filesize($path);


                if ($fileSize !== false) {
                    $size += $fileSize;
                }
            }
        }


        return $size;
    }


    public function getTotalSpace(): int
    {
        $space =
            disk_total_space(
                $this->importDirectory
            );


        return $space === false
            ? 0
            : (int) $space;
    }


    public function getFreeSpace(): int
    {
        $space =
            disk_free_space(
                $this->importDirectory
            );


        return $space === false
            ? 0
            : (int) $space;
    }


    public function formatBytes(
        int $bytes
    ): string {

        if ($bytes < 1024) {
            return $bytes . ' B';
        }


        if ($bytes < 1024 * 1024) {
            return number_format(
                $bytes / 1024,
                2
            ) . ' KB';
        }


        if ($bytes < 1024 * 1024 * 1024) {
            return number_format(
                $bytes / 1024 / 1024,
                2
            ) . ' MB';
        }


        if (
            $bytes <
            1024 * 1024 * 1024 * 1024
        ) {
            return number_format(
                $bytes / 1024 / 1024 / 1024,
                2
            ) . ' GB';
        }


        return number_format(
            $bytes /
            1024 /
            1024 /
            1024 /
            1024,
            2
        ) . ' TB';
    }
}
