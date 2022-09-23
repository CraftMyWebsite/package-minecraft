<?php

namespace CMW\Entity\Minecraft;


class MinecraftServerEntity
{
    private int $serverId;
    private string $serverName;
    private string $serverIp;
    private ?string $serverPort;
    private string $serverLastUpdate;

    /**
     * @param int $serverId
     * @param string $serverName
     * @param string $serverIp
     * @param string|null $serverPort
     * @param string $serverLastUpdate
     */
    public function __construct(int $serverId, string $serverName, string $serverIp, ?string $serverPort, string $serverLastUpdate)
    {
        $this->serverId = $serverId;
        $this->serverName = $serverName;
        $this->serverIp = $serverIp;
        $this->serverPort = $serverPort;
        $this->serverLastUpdate = $serverLastUpdate;
    }

    /**
     * @return int
     */
    public function getServerId(): int
    {
        return $this->serverId;
    }

    /**
     * @return string
     */
    public function getServerName(): string
    {
        return $this->serverName;
    }

    /**
     * @return string
     */
    public function getServerIp(): string
    {
        return $this->serverIp;
    }

    /**
     * @return string|null
     */
    public function getServerPort(): ?string
    {
        return $this->serverPort;
    }

    /**
     * @return string
     */
    public function getServerLastUpdate(): string
    {
        return $this->serverLastUpdate;
    }



}