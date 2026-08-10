<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ReleaseFileRepository;

final class MaintenanceService
{
    public function __construct(
        private readonly StorageService $storage,
        private readonly ReleaseFileRepository $files
    ) {
    }


    /**
     * Returnera aktuell status för importlagringen.
     *
     * @return array<string,mixed>
     */
    public function getStorageStatus(): array
    {
        $directory =
            $this->storage->getImportDirectory();


        $exists =
            is_dir($directory);


        return [
            'directory' =>
                $directory,

            'exists' =>
                $exists,

            'readable' =>
                $exists &&
                is_readable($directory),

            'writable' =>
                $exists &&
                is_writable($directory),

            'fileCount' =>
                $this->storage->getFileCount(),

            'usedSpace' =>
                $this->storage->formatBytes(
                    $this->storage->getUsedSpace()
                ),

            'totalSpace' =>
                $this->storage->formatBytes(
                    $this->storage->getTotalSpace()
                ),

            'freeSpace' =>
                $this->storage->formatBytes(
                    $this->storage->getFreeSpace()
                ),
        ];
    }


    /**
     * Returnera grundläggande databasstatus.
     *
     * @return array<string,mixed>
     */
    public function getDatabaseStatus(): array
    {
        return [
            'fileCount' =>
                $this->files->countDisks(),
        ];
    }


    /**
     * Kontrollera att databasen och importlagringen
     * innehåller samma fysiska filer.
     *
     * @return array<string,mixed>
     */
    public function getFileIntegrity(): array
    {
        $directory =
            $this->storage->getImportDirectory();


        $physicalFiles = [];


        if (is_dir($directory)) {

            $files =
                scandir($directory);


            if ($files !== false) {

                foreach ($files as $file) {

                    if (
                        $file === '.' ||
                        $file === '..'
                    ) {
                        continue;
                    }


                    $path =
                        $directory
                        . '/'
                        . $file;


                    if (is_file($path)) {
                        $physicalFiles[] =
                            $file;
                    }
                }
            }
        }


        sort($physicalFiles);


        $databaseFiles =
            $this->files->findAllDisks(
                'id',
                PHP_INT_MAX,
                0
            );


        $databasePaths = [];


        foreach ($databaseFiles as $databaseFile) {

            $path =
                $databaseFile->getPath();


            if (
                $path === null ||
                $path === ''
            ) {
                continue;
            }


            $filename =
                basename($path);


            if ($filename !== '') {
                $databasePaths[$filename] = true;
            }
        }


        $missingFiles = [];


        foreach ($databasePaths as $filename => $_) {

            if (
                !in_array(
                    $filename,
                    $physicalFiles,
                    true
                )
            ) {
                $missingFiles[] =
                    $filename;
            }
        }


        $orphanFiles = [];


        foreach ($physicalFiles as $filename) {

            if (
                !isset(
                    $databasePaths[$filename]
                )
            ) {
                $orphanFiles[] =
                    $filename;
            }
        }


        sort($missingFiles);
        sort($orphanFiles);


        return [
            'registeredCount' =>
                count($databaseFiles),

            'registeredUniqueCount' =>
                count($databasePaths),

            'physicalCount' =>
                count($physicalFiles),

            'missingCount' =>
                count($missingFiles),

            'orphanCount' =>
                count($orphanFiles),

            'missingFiles' =>
                $missingFiles,

            'orphanFiles' =>
                $orphanFiles,
        ];
    }


    /**
     * Ta bort en fysisk fil som inte är registrerad
     * i databasen.
     */
    public function deleteUnregisteredFile(
        string $filename
    ): bool {

        $filename =
            basename($filename);


        if ($filename === '') {
            return false;
        }


        $directory =
            $this->storage->getImportDirectory();


        if (!is_dir($directory)) {
            return false;
        }


        $path =
            $directory
            . '/'
            . $filename;


        if (!is_file($path)) {
            return false;
        }


        $integrity =
            $this->getFileIntegrity();


        if (
            !in_array(
                $filename,
                $integrity['orphanFiles'],
                true
            )
        ) {
            return false;
        }


        $directoryRealPath =
            realpath($directory);


        $fileRealPath =
            realpath($path);


        if (
            $directoryRealPath === false ||
            $fileRealPath === false
        ) {
            return false;
        }


        $directoryPrefix =
            rtrim(
                $directoryRealPath,
                DIRECTORY_SEPARATOR
            )
            . DIRECTORY_SEPARATOR;


        if (
            !str_starts_with(
                $fileRealPath,
                $directoryPrefix
            )
        ) {
            return false;
        }


        return unlink($fileRealPath);
    }


    /**
     * Kontrollera sökväg och filstorlek för
     * registrerade filer i databasen.
     *
     * @return array<string,mixed>
     */
    public function getDatabaseFileIntegrity(): array
    {
        $databaseFiles =
            $this->files->findAllDisks(
                'id',
                PHP_INT_MAX,
                0
            );


        $validPathCount = 0;
        $missingPathCount = 0;
        $sizeMismatchCount = 0;


        $missingPaths = [];
        $sizeMismatches = [];


        foreach ($databaseFiles as $databaseFile) {

            $path =
                $databaseFile->getPath();


            if (
                $path === null ||
                $path === '' ||
                !is_file($path)
            ) {

                $missingPathCount++;


                $missingPaths[] = [
                    'id' =>
                        $databaseFile->getId(),

                    'filename' =>
                        $databaseFile->getFilename(),

                    'path' =>
                        $path ?? '',
                ];


                continue;
            }


            $validPathCount++;


            $actualSize =
                filesize($path);


            $databaseSize =
                $databaseFile->getSize();


            /*
             * P00-filer innehåller en 26 byte lång
             * header före den extraherade PRG-datan.
             *
             * Databasens size avser PRG-datan,
             * medan filesize() avser hela P00-filen.
             */
            $extension =
                strtolower(
                    pathinfo(
                        $path,
                        PATHINFO_EXTENSION
                    )
                );


            if (
                $extension === 'p00' &&
                $actualSize !== false
            ) {

                if ($actualSize >= 26) {
                    $actualSize -= 26;
                }
            }


            if (
                $actualSize === false ||
                (int) $actualSize !==
                (int) $databaseSize
            ) {

                $sizeMismatchCount++;


                $sizeMismatches[] = [
                    'id' =>
                        $databaseFile->getId(),

                    'filename' =>
                        $databaseFile->getFilename(),

                    'path' =>
                        $path,

                    'databaseSize' =>
                        (int) $databaseSize,

                    'actualSize' =>
                        $actualSize === false
                            ? null
                            : (int) $actualSize,
                ];
            }
        }


        return [
            'registeredCount' =>
                count($databaseFiles),

            'validPathCount' =>
                $validPathCount,

            'missingPathCount' =>
                $missingPathCount,

            'sizeMismatchCount' =>
                $sizeMismatchCount,

            'missingPaths' =>
                $missingPaths,

            'sizeMismatches' =>
                $sizeMismatches,
        ];
    }
}
