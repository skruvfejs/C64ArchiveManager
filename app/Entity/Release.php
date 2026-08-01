<?php

declare(strict_types=1);

namespace App\Entity;

final class Release
{
    private ?int $id = null;

    private int $entryId;

    private string $name = '';

    private ?string $version = null;

    private ?string $notes = null;

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


    public function getEntryId(): int
    {
        return $this->entryId;
    }


    public function setEntryId(int $entryId): self
    {
        $this->entryId = $entryId;

        return $this;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getVersion(): ?string
    {
        return $this->version;
    }


    public function setVersion(?string $version): self
    {
        $this->version = $version;

        return $this;
    }


    public function getNotes(): ?string
    {
        return $this->notes;
    }


    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

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
