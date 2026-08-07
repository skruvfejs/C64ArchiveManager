<?php

declare(strict_types=1);

namespace App\Entity;

final class ReleaseFile
{
    private ?int $id = null;

    private int $releaseId;

    private string $filename = '';

    private string $format = '';

    private ?string $diskName = null;

    private ?string $diskId = null;

    private ?string $entryTitle = null;

    private ?string $releaseNotes = null;

    private string $path = '';

    private int $size = 0;

    private ?string $crc32 = null;

    private ?string $md5 = null;

    private ?string $sha1 = null;

    private ?string $createdAt = null;

    private ?string $updatedAt = null;



    public function getId(): ?int
    {
        return $this->id;
    }


    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }



    public function getReleaseId(): int
    {
        return $this->releaseId;
    }


    public function setReleaseId(int $releaseId): self
    {
        $this->releaseId = $releaseId;

        return $this;
    }



    public function getFilename(): string
    {
        return $this->filename;
    }


    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }



    public function getFormat(): string
    {
        return $this->format;
    }


    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }



    public function getDiskName(): ?string
    {
        return $this->diskName;
    }


    public function setDiskName(?string $diskName): self
    {
        $this->diskName = $diskName;

        return $this;
    }



    public function getDiskId(): ?string
    {
        return $this->diskId;
    }


    public function setDiskId(?string $diskId): self
    {
        $this->diskId = $diskId;

        return $this;
    }



    public function getEntryTitle(): ?string
    {
        return $this->entryTitle;
    }


    public function setEntryTitle(?string $entryTitle): self
    {
        $this->entryTitle = $entryTitle;

        return $this;
    }
    public function getReleaseNotes(): ?string
    {
        return $this->releaseNotes;
    }


    public function setReleaseNotes(?string $releaseNotes): self
    {
        $this->releaseNotes = $releaseNotes;

        return $this;
    }



    public function getPath(): string
    {
        return $this->path;
    }


    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }



    public function getSize(): int
    {
        return $this->size;
    }


    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }



    public function getCrc32(): ?string
    {
        return $this->crc32;
    }


    public function setCrc32(?string $crc32): self
    {
        $this->crc32 = $crc32;

        return $this;
    }



    public function getMd5(): ?string
    {
        return $this->md5;
    }


    public function setMd5(?string $md5): self
    {
        $this->md5 = $md5;

        return $this;
    }



    public function getSha1(): ?string
    {
        return $this->sha1;
    }


    public function setSha1(?string $sha1): self
    {
        $this->sha1 = $sha1;

        return $this;
    }



    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }



    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }
}
