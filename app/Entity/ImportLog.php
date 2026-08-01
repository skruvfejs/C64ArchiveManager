<?php

declare(strict_types=1);

namespace App\Entity;

final class ImportLog
{
    private ?int $id = null;

    private string $filename = '';

    private string $format = '';

    private string $status = 'RUNNING';

    private ?int $releaseId = null;

    private int $filesImported = 0;

    private ?string $message = null;

    private ?string $startedAt = null;

    private ?string $finishedAt = null;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function setId(?int $id): self
    {
        $this->id = $id;

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


    public function getFormat(): string
    {
        return $this->format;
    }


    public function setFormat(
        string $format
    ): self {

        $this->format = $format;

        return $this;
    }


    public function getStatus(): string
    {
        return $this->status;
    }


    public function setStatus(
        string $status
    ): self {

        $this->status = $status;

        return $this;
    }


    public function getReleaseId(): ?int
    {
        return $this->releaseId;
    }


    public function setReleaseId(
        ?int $releaseId
    ): self {

        $this->releaseId = $releaseId;

        return $this;
    }


    public function getFilesImported(): int
    {
        return $this->filesImported;
    }


    public function setFilesImported(
        int $filesImported
    ): self {

        $this->filesImported = $filesImported;

        return $this;
    }


    public function getMessage(): ?string
    {
        return $this->message;
    }


    public function setMessage(
        ?string $message
    ): self {

        $this->message = $message;

        return $this;
    }


    public function getStartedAt(): ?string
    {
        return $this->startedAt;
    }


    public function setStartedAt(
        ?string $startedAt
    ): self {

        $this->startedAt = $startedAt;

        return $this;
    }


    public function getFinishedAt(): ?string
    {
        return $this->finishedAt;
    }


    public function setFinishedAt(
        ?string $finishedAt
    ): self {

        $this->finishedAt = $finishedAt;

        return $this;
    }
}

