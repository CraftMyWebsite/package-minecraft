<?php

namespace CMW\Entity\Minecraft;

use CMW\Entity\Users\UserEntity;
use CMW\Manager\Package\AbstractEntity;
use CMW\Utils\Date;

/**
 * Class: @MinecraftHistoryEntity
 * @package Minecraft
 * @link https://craftmywebsite.fr/docs/fr/technical/creer-un-package/entities
 */
class MinecraftHistoryEntity extends AbstractEntity
{
   private int $id;
   private UserEntity $user;
   private string $serverName;
   private ?string $title;
   private ?string $description;
   private string $createdAt;
   private string $updatedAt;

    /**
     * @param int $id
     * @param \CMW\Entity\Users\UserEntity $user
     * @param string $serverName
     * @param string|null $title
     * @param string|null $description
     * @param string $createdAt
     * @param string $updatedAt
     */
    public function __construct(int $id, UserEntity $user, string $serverName, ?string $title, ?string $description, string $createdAt, string $updatedAt)
    {
        $this->id = $id;
        $this->user = $user;
        $this->serverName = $serverName;
        $this->title = $title;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): UserEntity
    {
        return $this->user;
    }

    public function getServerName(): string
    {
        return $this->serverName;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCreatedAt(): string
    {
        return Date::formatDate($this->createdAt);
    }

    public function getUpdatedAt(): string
    {
        return Date::formatDate($this->updatedAt);
    }
}
