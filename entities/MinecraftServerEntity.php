<?php

namespace CMW\Entity\Minecraft;


class MinecraftServerEntity
{
    private int $serverId;
    private string $serverName;
    private string $serverIp;
    private ?int $serverPort;
    private ?int $serverCMWLPort;
    private string $serverLastUpdate;
    private int $serverStatus;

    /**
     * @param int $serverId
     * @param string $serverName
     * @param string $serverIp
     * @param int|null $serverPort
     * @param int|null $serverCMWLPort
     * @param string $serverLastUpdate
     * @param int $serverStatus
     */
    public function __construct(int $serverId, string $serverName, string $serverIp, ?int $serverPort, ?int $serverCMWLPort, string $serverLastUpdate, int $serverStatus)
    {
        $this->serverId = $serverId;
        $this->serverName = $serverName;
        $this->serverIp = $serverIp;
        $this->serverPort = $serverPort;
        $this->serverCMWLPort = $serverCMWLPort;
        $this->serverLastUpdate = $serverLastUpdate;
        $this->serverStatus = $serverStatus;
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
     * @return int|null
     */
    public function getServerPort(): ?int
    {
        return ($this->serverPort ?? 25565);
    }

    /**
     * @return int|null
     */
    public function getServerCMWLPort(): ?int
    {
        return $this->serverCMWLPort ?? $this->serverPort;
    }

    /**
     * @return string
     */
    public function getServerLastUpdate(): string
    {
        return $this->serverLastUpdate;
    }

    /**
     * @return int
     */
    public function getServerStatus(): int
    {
        return $this->serverStatus;
    }

}