<?php

namespace CMW\Entity\Minecraft;

use CMW\Manager\Package\AbstractEntity;

class MinecraftPingPlayersEntity extends AbstractEntity
{
    private ?string $name;
    private ?string $id;

    /**
     * @param string|null $name
     * @param string|null $id
     */
    public function __construct(?string $name, ?string $id)
    {
        $this->name = $name;
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }
}
