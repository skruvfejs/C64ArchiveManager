<?php

declare(strict_types=1);

namespace App\Models;

final class EntryTag
{
    private int $entryId;

    private int $tagId;

    private ?string $createdAt = null;

    public function getEntryId(): int
    {
        return $this->entryId;
    }

    public function setEntryId(int $entryId): self
    {
        $this->entryId = $entryId;

        return $this;
    }

    public function getTagId(): int
    {
        return $this->tagId;
    }

    public function setTagId(int $tagId): self
    {
        $this->tagId = $tagId;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
