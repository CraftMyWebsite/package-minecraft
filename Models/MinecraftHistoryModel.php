<?php

namespace CMW\Model\Minecraft;

use CMW\Entity\Minecraft\MinecraftHistoryEntity;
use CMW\Manager\Database\DatabaseManager;
use CMW\Manager\Package\AbstractModel;
use CMW\Model\Users\UsersModel;

/**
 * Class: @MinecraftHistoryModel
 * @package Minecraft
 * @link https://craftmywebsite.fr/docs/fr/technical/creer-un-package/models
 */
class MinecraftHistoryModel extends AbstractModel
{
    /**
     * @param ?int $id
     * @return MinecraftHistoryEntity|null
     */
    public function getHistoryById(?int $id): ?MinecraftHistoryEntity
    {
        if (is_null($id)) {
            return null;
        }

        $sql = "SELECT * FROM cmw_minecraft_history WHERE minecraft_history_id = :minecraft_history_id";

        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute(["minecraft_history_id" => $id])) {
            return null;
        }

        $res = $res->fetch();

        $user = UsersModel::getInstance()->getUserById($res["user_id"]);

        return new MinecraftHistoryEntity(
            $res["minecraft_history_id"],
            $user,
            $res["minecraft_history_server_name"],
            $res["minecraft_history_title"] ?? null,
            $res["minecraft_history_desc"] ?? null,
            $res["minecraft_history_created_at"],
            $res["minecraft_history_updated_at"],
        );
    }

    /**
     * @param int $userId
     * @return MinecraftHistoryEntity []
     */
    public function getFullHistoryByUserId(int $userId): array
    {
        $sql = "SELECT minecraft_history_id FROM cmw_minecraft_history WHERE user_id = :user_id ORDER BY minecraft_history_id DESC";
        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute(["user_id" => $userId])) {
            return [];
        }

        $toReturn = [];

        while ($history = $res->fetch()) {
            $toReturn[] = $this->getHistoryById($history["minecraft_history_id"]);
        }

        return $toReturn;
    }

    public function addHistory(int $userId, string $serverName, string $title, string $desc): ?MinecraftHistoryEntity
    {
        $data = [
            "user_id" => $userId,
            "minecraft_history_server_name" => $serverName,
            "minecraft_history_title" => $title,
            "minecraft_history_desc" => $desc,
        ];

        $sql = "INSERT INTO cmw_minecraft_history(user_id, minecraft_history_server_name, minecraft_history_title, minecraft_history_desc) VALUES (:user_id, :minecraft_history_server_name, :minecraft_history_title, :minecraft_history_desc)";


        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($data)) {
            $id = $db->lastInsertId();
            return $this->getHistoryById($id);
        }

        return null;
    }

    /**
     * @param int $userId
     * @return int
     */
    public function countRewardForUserId(int $userId): int
    {
        $var = ['user_id' => $userId];

        $sql = 'SELECT COUNT(*) AS rewards_count FROM cmw_minecraft_history WHERE user_id = :user_id;';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $res = $req->execute($var);

        if (!$res) {
            return 0;
        }

        return $req->fetch()['rewards_count'] ?? 0;
    }
}
