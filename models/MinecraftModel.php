<?php

namespace CMW\Model\Minecraft;


use CMW\Entity\Minecraft\MinecraftServerEntity;
use CMW\Manager\Database\DatabaseManager;

/**
 * Class: @MinecraftModel
 * @package Minecraft
 * @author CraftMyWebsite Team <contact@craftmywebsite.fr>
 * @version 1.0
 */
class MinecraftModel extends DatabaseManager
{

    /**
     * @return MinecraftServerEntity[] array
     */
    public function getServers(): array
    {
        $sql = "SELECT * FROM cmw_minecraft_servers";
        $db = self::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($server = $res->fetch()) {
            $toReturn[] = $this->getServerById($server['minecraft_server_id']);
        }
        return $toReturn;
    }

    public function getServerById(int $id): ?MinecraftServerEntity
    {
        $sql = "SELECT minecraft_server_id, minecraft_server_name, minecraft_server_ip, minecraft_server_port, minecraft_server_cmwl_port,
                 minecraft_server_cmwl_token, minecraft_server_status, minecraft_server_last_update, minecraft_server_is_fav
                FROM cmw_minecraft_servers WHERE minecraft_server_id = :server_id";

        $db = self::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute(array("server_id" => $id))) {
            return null;
        }

        $res = $res->fetch();

        return new MinecraftServerEntity(
            $res['minecraft_server_id'],
            $res['minecraft_server_name'],
            $res['minecraft_server_ip'],
            $res['minecraft_server_port'] ?? null,
            $res['minecraft_server_cmwl_port'] ?? null,
            $res['minecraft_server_cmwl_token'] ?? null,
            $res['minecraft_server_last_update'],
            $res['minecraft_server_status'],
            $res['minecraft_server_is_fav'],
        );
    }

    public function addServer(string $serverName, string $serverIp, int $serverStatus, int|null $serverPort = null, int|null $cmwlPort = null): ?MinecraftServerEntity
    {
        $var = array(
            "name" => $serverName,
            "ip" => $serverIp,
            "port" => $serverPort ?? null,
            "cmwlPort" => $cmwlPort ?? null,
            "status" => $serverStatus
        );

        $sql = "INSERT INTO cmw_minecraft_servers (minecraft_server_name, minecraft_server_ip, minecraft_server_port, 
                                                    minecraft_server_cmwl_port, minecraft_server_status)
                VALUES (:name, :ip, :port, :cmwlPort, :status)";

        $db = self::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $id = $db->lastInsertId();
            return $this->getServerById($id);
        }
        return null;
    }

    public function setServerToken(int $serverId, string $token): void
    {
        $var = [
            "server_id" => $serverId,
            "server_token" => $token
        ];

        $sql = "UPDATE cmw_minecraft_servers SET minecraft_server_cmwl_token = :server_token WHERE minecraft_server_id = :server_id";
        $db = self::getInstance();

        $req = $db->prepare($sql);

        $req->execute($var);
    }

    public function updateServer(int $id, string $name, string $ip, int $serverStatus, int|null $port = null, int|null $cmwlPort = null): ?MinecraftServerEntity
    {
        $var = array(
            "id" => $id,
            "name" => $name,
            "ip" => $ip,
            "port" => $port ?? null,
            "cmwlPort" => $cmwlPort ?? null,
            "status" => $serverStatus
        );

        $sql = "UPDATE cmw_minecraft_servers SET minecraft_server_name = :name, minecraft_server_ip = :ip, 
                                minecraft_server_port = :port, minecraft_server_cmwl_port = :cmwlPort, minecraft_server_status = :status
                                WHERE minecraft_server_id = :id";

        $db = self::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            return $this->getServerById($id);
        }
        return null;
    }

    public function deleteServer(int $id): void
    {
        $sql = "DELETE FROM cmw_minecraft_servers WHERE minecraft_server_id = :id";
        $db = self::getInstance();
        $req = $db->prepare($sql);
        $req->execute(array("id" => $id));
    }

    /**
     * @param int $serverId
     * @return void
     * @desc First we clear all old fav servers et we define the new fav server
     */
    public function setFav(int $serverId): void
    {
        if (!$this->isAlreadyFav($serverId)) {
            $this->deleteFavs();

            $sql = "UPDATE cmw_minecraft_servers SET minecraft_server_is_fav = 1 WHERE minecraft_server_id = :id";

            $db = self::getInstance();
            $req = $db->prepare($sql);

            $req->execute(array("id" => $serverId));
        } else {
            $this->deleteFavs();
        }
    }

    public function isAlreadyFav(int $serverId): bool
    {
        $sql = "SELECT minecraft_server_is_fav AS `fav` FROM cmw_minecraft_servers WHERE minecraft_server_id = :id";

        $db = self::getInstance();
        $req = $db->prepare($sql);

        $req->execute(array("id" => $serverId));

        $res = $req->fetch()['fav'];

        return $res === 1;
    }

    private function deleteFavs(): void
    {
        $sql = "UPDATE cmw_minecraft_servers SET minecraft_server_is_fav = 0 WHERE minecraft_server_is_fav = 1";

        $db = self::getInstance();
        $db->query($sql);
    }

    public function getFavServer(): ?MinecraftServerEntity
    {
        $sql = "SELECT * FROM cmw_minecraft_servers WHERE minecraft_server_is_fav = 1 LIMIT 1";

        $db = self::getInstance();
        $req = $db->prepare($sql);

        if (!$req->execute()) {
            return null;
        }

        $res = $req->fetch();

        return new MinecraftServerEntity(
            $res['minecraft_server_id'],
            $res['minecraft_server_name'],
            $res['minecraft_server_ip'],
            $res['minecraft_server_port'] ?? null,
            $res['minecraft_server_cmwl_port'] ?? null,
            $res['minecraft_server_cmwl_token'] ?? null,
            $res['minecraft_server_last_update'],
            $res['minecraft_server_status'],
            $res['minecraft_server_is_fav'],
        );
    }

}