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

    public function getServerById(int $id): ?MinecraftServerEntity
    {
        $sql = "SELECT minecraft_server_id, minecraft_server_name, minecraft_server_ip, minecraft_server_port, minecraft_server_cmwl_port,
                 minecraft_server_status, DATE_FORMAT(minecraft_server_last_update, '%d/%m/%Y à %H:%i:%s') AS 'minecraft_server_last_update' 
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
            $res['minecraft_server_last_update'],
            $res['minecraft_server_status']
        );
    }

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

}