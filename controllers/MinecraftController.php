<?php

namespace CMW\Controller\Minecraft;

use CMW\Controller\Core\CoreController;
use CMW\Entity\Minecraft\MinecraftPingEntity;
use CMW\Model\Minecraft\MinecraftModel;
use CMW\Router\Link;
use CMW\Utils\Utils;
use CMW\Utils\View;
use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;

require_once(getenv("DIR") . 'app/package/minecraft/vendors/MinecraftPing/MinecraftPing.php');
require_once(getenv("DIR") . 'app/package/minecraft/vendors/MinecraftPing/MinecraftPingException.php');


/**
 * Class: @MinecraftController
 * @package Minecraft
 * @author CraftMyWebsite Team <contact@craftmywebsite.fr>
 * @version 1.0
 */
class MinecraftController extends CoreController
{

    private MinecraftModel $minecraftModel;

    public function __construct($theme_path = null)
    {
        parent::__construct($theme_path);
        $this->minecraftModel = new MinecraftModel();
    }

    #[Link("/servers/delete/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/minecraft")]
    public function adminServersDelete(int $id): void
    {
        $this->minecraftModel->deleteServer($id);

        header("Location: ../../servers");
    }

    #[Link(path: "/minecraft", method: Link::GET, scope: "/cmw-admin")]
    #[Link("/servers", Link::GET, [], "/cmw-admin/minecraft")]
    public function adminServers(): void
    {

        $servers = $this->minecraftModel->getServers();

        View::createAdminView("minecraft", "servers")
            ->addVariableList(["servers" => $servers])
            ->view();
    }

    #[Link("/servers/add", Link::POST, [], "/cmw-admin/minecraft")]
    public function adminServersAdd(): void
    {
        [$name, $ip, $status, $port] = Utils::filterInput("name", "ip", "status", "port");

        $this->minecraftModel->addServer($name, $ip, $status, ($port === "" ? null : $port));

        header("Location: ../servers");
    }

    #[Link("/servers", Link::POST, [], "/cmw-admin/minecraft")]
    public function adminServersEdit(): void
    {
        [$id, $name, $ip, $status, $port] = Utils::filterInput("serverId", "name", "ip", "status", "port");

        $this->minecraftModel->updateServer($id, $name, $ip, $status, ($port === "" ? null : $port));

        header("Location: servers");
    }



    /*
     *
     * TOOLS FUNCTIONS
     *
     */


    /**
     * @param string $host
     * @param int|null $port
     * @return \CMW\Entity\Minecraft\MinecraftPingEntity|null
     * @desc Ping server and get some data
     */
    public static function pingServer(string $host, ?int $port = 25565): ?MinecraftPingEntity
    {
        try {
            $query = new MinecraftPing($host, $port, 2);

            return $query->Query();
        } catch (MinecraftPingException $e) {
            echo $e->getMessage();
        } finally {
            $query?->Close();
        }
        return null;
    }

    public static function pingServerOld(string $host, ?int $port = 25565)
    {
        try {
            $query = new MinecraftPing($host, $port, 2);

            return $query->QueryOldPre17();
        } catch (MinecraftPingException $e) {
            echo $e->getMessage();
        } finally {
            $query?->Close();
        }
        return null;
    }

    //TODO Try to optimize that
    public static function getTotalPlayersOnlines(): int
    {
        $toReturn = 0;
        foreach ((new MinecraftModel())->getServers() as $server){
            $toReturn += self::pingServer($server->getServerIp(), $server->getServerPort())?->getPlayersOnline();
        }
        return $toReturn;
    }


}