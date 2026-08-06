<?php

declare(strict_types=1);

namespace App\Entity;

final class User
{
    private ?int $id = null;

    private int $roleId;

    private string $username = '';

    private string $email = '';

    private string $password = '';

    private ?string $firstName = null;

    private ?string $lastName = null;

    private string $theme = 'light';

    private string $language = 'sv';

    private ?string $lastLoginAt = null;

    private ?string $deletedAt = null;

    private ?int $deletedBy = null;

    private ?string $createdAt = null;

    private ?string $updatedAt = null;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function setId(
        ?int $id
    ): self {

        $this->id = $id;

        return $this;
    }


    public function getRoleId(): int
    {
        return $this->roleId;
    }


    public function setRoleId(
        int $roleId
    ): self {

        $this->roleId = $roleId;

        return $this;
    }


    public function getUsername(): string
    {
        return $this->username;
    }


    public function setUsername(
        string $username
    ): self {

        $this->username = $username;

        return $this;
    }


    public function getEmail(): string
    {
        return $this->email;
    }


    public function setEmail(
        string $email
    ): self {

        $this->email = $email;

        return $this;
    }


    public function getPassword(): string
    {
        return $this->password;
    }


    public function setPassword(
        string $password
    ): self {

        $this->password = $password;

        return $this;
    }


    public function getFirstName(): ?string
    {
        return $this->firstName;
    }


    public function setFirstName(
        ?string $firstName
    ): self {

        $this->firstName = $firstName;

        return $this;
    }


    public function getLastName(): ?string
    {
        return $this->lastName;
    }


    public function setLastName(
        ?string $lastName
    ): self {

        $this->lastName = $lastName;

        return $this;
    }


    public function getTheme(): string
    {
        return $this->theme;
    }


    public function setTheme(
        string $theme
    ): self {

        $this->theme = $theme;

        return $this;
    }


    public function getLanguage(): string
    {
        return $this->language;
    }


    public function setLanguage(
        string $language
    ): self {

        $this->language = $language;

        return $this;
    }


    public function getLastLoginAt(): ?string
    {
        return $this->lastLoginAt;
    }


    public function setLastLoginAt(
        ?string $lastLoginAt
    ): self {

        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }


    public function getDeletedAt(): ?string
    {
        return $this->deletedAt;
    }


    public function setDeletedAt(
        ?string $deletedAt
    ): self {

        $this->deletedAt = $deletedAt;

        return $this;
    }


    public function getDeletedBy(): ?int
    {
        return $this->deletedBy;
    }


    public function setDeletedBy(
        ?int $deletedBy
    ): self {

        $this->deletedBy = $deletedBy;

        return $this;
    }


    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }


    public function hasFirstName(): bool
    {
        return $this->firstName !== null
            && $this->firstName !== '';
    }


    public function hasLastName(): bool
    {
        return $this->lastName !== null
            && $this->lastName !== '';
    }


    public function getFullName(): string
    {
        return trim(
            ($this->firstName ?? '')
            . ' '
            . ($this->lastName ?? '')
        );
    }


    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }


    public function setCreatedAt(
        ?string $createdAt
    ): self {

        $this->createdAt = $createdAt;

        return $this;
    }


    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }


    public function setUpdatedAt(
        ?string $updatedAt
    ): self {

        $this->updatedAt = $updatedAt;

        return $this;
    }
}
