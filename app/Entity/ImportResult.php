<?php

declare(strict_types=1);

namespace App\Entity;


final class ImportResult
{
    private bool $duplicate = false;

    private ?array $duplicateData = null;


    public function __construct(
        private int $releaseId,
        private int $filesImported
    ) {
    }



    public function getReleaseId(): int
    {
        return $this->releaseId;
    }



    public function getFilesImported(): int
    {
        return $this->filesImported;
    }



    public function isDuplicate(): bool
    {
        return $this->duplicate;
    }



    public function setDuplicate(
        array $data
    ): self {

        $this->duplicate = true;

        $this->duplicateData = $data;

        return $this;
    }



    public function getDuplicateData(): ?array
    {
        return $this->duplicateData;
    }
}

