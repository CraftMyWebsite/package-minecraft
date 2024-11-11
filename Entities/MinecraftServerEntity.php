<?php

namespace CMW\Entity\Minecraft;

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractEntity;

class MinecraftServerEntity extends AbstractEntity
{
    private int $serverId;
    private string $serverName;
    private string $serverIp;
    private ?int $serverPort;
    private ?int $serverCMWLPort;
    private ?string $serverCMWToken;
    private ?int $serverCMLStatus;
    private string $serverLastUpdate;
    private int $serverStatus;
    private int $isServerFav;

    /**
     * @param int $serverId
     * @param string $serverName
     * @param string $serverIp
     * @param int|null $serverPort
     * @param int|null $serverCMWLPort
     * @param string|null $serverCMWToken
     * @param int|null $serverCMLStatus;
     * @param string $serverLastUpdate
     * @param int $serverStatus
     * @param int $isServerFav
     */
    public function __construct(int $serverId, string $serverName, string $serverIp, ?int $serverPort, ?int $serverCMWLPort, ?string $serverCMWToken, ?int $serverCMLStatus, string $serverLastUpdate, int $serverStatus, int $isServerFav)
    {
        $this->serverId = $serverId;
        $this->serverName = $serverName;
        $this->serverIp = $serverIp;
        $this->serverPort = $serverPort;
        $this->serverCMWLPort = $serverCMWLPort;
        $this->serverCMWToken = $serverCMWToken;
        $this->serverCMLStatus = $serverCMLStatus;
        $this->serverLastUpdate = $serverLastUpdate;
        $this->serverStatus = $serverStatus;
        $this->isServerFav = $isServerFav;
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
     * @return ?int
     */
    public function getServerPortFormatted(): ?int
    {
        return ($this->serverPort);
    }

    /**
     * @return int|null
     */
    public function getServerCMWLPort(): ?int
    {
        return $this->serverCMWLPort ?? $this->serverPort;
    }

    /**
     * @return string|null
     */
    public function getServerCMWToken(): ?string
    {
        return $this->serverCMWToken;
    }

    /**
     * @return int|null
     */
    public function getServerCMLStatus(): ?int
    {
        return $this->serverCMLStatus;
    }

    /**
     * @return string
     */
    public function getFormattedServerCMLStatus(): string
    {
        if ($this->serverCMLStatus == 0) {
            return '<i class="fa-solid fa-xmark fa-xl fa-beat" style="color: #bd1425;"></i>';
        } else {
            return '<i class="fa-solid fa-check fa-xl fa-beat" style="color: #31c913;"></i>';
        }
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

    /**
     * @return int
     */
    public function getIsServerFav(): int
    {
        return $this->isServerFav;
    }

    public function showServerStatusFormatted(): string
    {
        return match ($this->serverStatus) {
            1 => "<span class='text-success'>" . LangManager::translate('minecraft.servers.status.online') . '</span>',
            0 => "<span class='text-danger'>" . LangManager::translate('minecraft.servers.status.offline') . '</span>',
            -1 => "<span class='text-warning'>" . LangManager::translate('minecraft.servers.status.maintenance') . '</span>',
        };
    }
}
