<?php

namespace CMW\Entity\Minecraft;


class MinecraftPingEntity
{
    private ?string $versionProtocol;
    private ?string $versionName;
    private int $playersOnline;
    private int $playersMax;
    /** @var \CMW\Entity\Minecraft\MinecraftPingPlayersEntity|\CMW\Entity\Minecraft\MinecraftPingPlayersEntity[] $playersEntity */
    private ?array $playersEntity;
    private ?string $favicon; //base64

    /**
     * @param ?string $versionProtocol
     * @param ?string $versionName
     * @param int $playersOnline
     * @param int $playersMax
     * @param \CMW\Entity\Minecraft\MinecraftPingPlayersEntity[]|null $playersEntity
     * @param ?string $favicon
     */
    public function __construct(?string $versionProtocol, ?string $versionName, int $playersOnline, int $playersMax, ?array $playersEntity, ?string $favicon)
    {
        $this->versionProtocol = $versionProtocol;
        $this->versionName = $versionName;
        $this->playersOnline = $playersOnline;
        $this->playersMax = $playersMax;
        $this->playersEntity = $playersEntity;
        $this->favicon = $favicon;
    }

    /**
     * @return ?string
     */
    public function getVersionProtocol(): ?string
    {
        return $this->versionProtocol;
    }

    /**
     * @return ?string
     */
    public function getVersionName(): ?string
    {
        return $this->versionName;
    }

    /**
     * @return int
     */
    public function getPlayersOnline(): int
    {
        return $this->playersOnline;
    }

    /**
     * @return int
     */
    public function getPlayersMax(): int
    {
        return $this->playersMax;
    }

    /**
     * @return \CMW\Entity\Minecraft\MinecraftPingPlayersEntity[]|null
     */
    public function getPlayersEntity(): ?array
    {
        return $this->playersEntity;
    }

    /**
     * @return ?string
     */
    public function getFavicon(): ?string
    {
        return $this->favicon;
    }
}