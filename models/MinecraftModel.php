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
        $sql = "SELECT minecraft_server_id, minecraft_server_name, minecraft_server_ip, minecraft_server_port, 
                DATE_FORMAT(minecraft_server_last_update, '%d/%m/%Y à %H:%i:%s') AS 'minecraft_server_last_update' 
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
            $res['minecraft_server_last_update'],
        );
    }

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

    public function addServer(string $serverName, string $serverIp, int|null $serverPort = null): ?MinecraftServerEntity
    {
        $var = array(
            "name" => $serverName,
            "ip" => $serverIp,
            "port" => $serverPort ?? null
        );

        $sql = "INSERT INTO cmw_minecraft_servers (minecraft_server_name, minecraft_server_ip, minecraft_server_port)
                VALUES (:name, :ip, :port)";

        $db = self::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $id = $db->lastInsertId();
            return $this->getServerById($id);
        }
        return null;
    }

    public function updateServer(int $id, string $name, string $ip, int|null $port = null): ?MinecraftServerEntity
    {
        $var = array(
            "id" => $id,
            "name" => $name,
            "ip" => $ip,
            "port" => $port ?? null
        );

        $sql = "UPDATE cmw_minecraft_servers SET minecraft_server_name = :name, minecraft_server_ip = :ip, 
                                 minecraft_server_port = :port WHERE minecraft_server_id = :id";

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