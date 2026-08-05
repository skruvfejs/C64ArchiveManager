<?php

declare(strict_types=1);

namespace App\Entity;

final class DirectoryEntry
{
    private ?int $id = null;

    private int $releaseFileId;

    private string $filename = '';

    private ?int $directoryPosition = null;

    private string $filetype = '';

    private ?int $startTrack = null;

    private ?int $startSector = null;

    private int $blocks = 0;

    private ?int $fileOffset = null;

    private ?int $fileSize = null;

    private bool $locked = false;

    private bool $closed = true;

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


    public function getReleaseFileId(): int
    {
        return $this->releaseFileId;
    }


    public function setReleaseFileId(
        int $releaseFileId
    ): self {

        $this->releaseFileId = $releaseFileId;

        return $this;
    }


    public function getFilename(): string
    {
        return $this->filename;
    }


    public function setFilename(
        string $filename
    ): self {

        $this->filename = $filename;

        return $this;
    }


    public function getDirectoryPosition(): ?int
    {
        return $this->directoryPosition;
    }


    public function setDirectoryPosition(
        ?int $directoryPosition
    ): self {

        $this->directoryPosition = $directoryPosition;

        return $this;
    }


    public function getFiletype(): string
    {
        return $this->filetype;
    }


    public function setFiletype(
        string $filetype
    ): self {

        $this->filetype = $filetype;

        return $this;
    }


    public function getStartTrack(): ?int
    {
        return $this->startTrack;
    }


    public function setStartTrack(
        ?int $startTrack
    ): self {

        $this->startTrack = $startTrack;

        return $this;
    }


    public function getStartSector(): ?int
    {
        return $this->startSector;
    }


    public function setStartSector(
        ?int $startSector
    ): self {

        $this->startSector = $startSector;

        return $this;
    }


    public function getBlocks(): int
    {
        return $this->blocks;
    }


    public function setBlocks(
        int $blocks
    ): self {

        $this->blocks = $blocks;

        return $this;
    }

    public function getFileOffset(): ?int
    {
        return $this->fileOffset;
    }


    public function setFileOffset(
        ?int $fileOffset
    ): self {

        $this->fileOffset = $fileOffset;

        return $this;
    }


    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }


    public function setFileSize(
        ?int $fileSize
    ): self {

        $this->fileSize = $fileSize;

        return $this;
    }



    public function isLocked(): bool
    {
        return $this->locked;
    }


    public function setLocked(
        bool $locked
    ): self {

        $this->locked = $locked;

        return $this;
    }


    public function isClosed(): bool
    {
        return $this->closed;
    }


    public function setClosed(
        bool $closed
    ): self {

        $this->closed = $closed;

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
