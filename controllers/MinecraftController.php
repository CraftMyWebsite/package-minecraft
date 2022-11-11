<?php

namespace CMW\Controller\Minecraft;

use CMW\Controller\Core\CoreController;
use CMW\Controller\Users\UsersController;
use CMW\Controller\Votes\VotesController;
use CMW\Entity\Minecraft\MinecraftPingEntity;
use CMW\Manager\Api\APIManager;
use CMW\Manager\Lang\LangManager;
use CMW\Model\Minecraft\MinecraftModel;
use CMW\Model\Users\UsersModel;
use CMW\Router\Link;
use CMW\Utils\Response;
use CMW\Utils\Utils;
use CMW\Utils\View;
use JsonException;
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

    public static function pingServerOld(string $host, ?int $port = 25565)
    {
        try {
            $query = new MinecraftPing($host, $port, 2);

            return $query->queryOldPre17();
        } catch (MinecraftPingException $e) {
            echo $e->getMessage();
        } finally {
            $query?->close();
        }
        return null;
    }

    public static function getTotalPlayersOnlines(): int
    {
        $toReturn = 0;
        foreach ((new MinecraftModel())->getServers() as $server) {
            $toReturn += self::pingServer($server->getServerIp(), $server->getServerPort())?->getPlayersOnline();
        }
        return $toReturn;
    }

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

            if($query->connect()){
                return $query->query();
            } else {
                return null;
            }

        } catch (MinecraftPingException $e) {
            echo $e->getMessage();
        } finally {
            $query?->close();
        }
        return null;
    }

    #[Link("/servers/delete", Link::POST, [], "/cmw-admin/minecraft")]
    public function adminServersDelete(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "minecraft.delete");

        $this->minecraftModel->deleteServer(filter_input(INPUT_POST, "serverId"));

        Response::sendAlert("success", "", LangManager::translate('minecraft.servers.toasters.server_delete'), true);

        header("Location: ../../minecraft/servers");
    }


    /*
     *
     * TOOLS FUNCTIONS
     *
     */

    #[Link(path: "/minecraft", method: Link::GET, scope: "/cmw-admin")]
    #[Link("/servers", Link::GET, [], "/cmw-admin/minecraft")]
    public function adminServers(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "minecraft.list");

        $servers = $this->minecraftModel->getServers();

        View::createAdminView("minecraft", "servers")
            ->addVariableList(["servers" => $servers])
            ->addScriptBefore("app/package/minecraft/views/resources/js/main.js")
            ->view();
    }

    #[Link("/servers/add", Link::POST, [], "/cmw-admin/minecraft")]
    public function adminServersAdd(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "minecraft.add");

        [$name, $ip, $status, $port, $cmwlPort] = Utils::filterInput("name", "ip", "status", "port", "cmwlPort");

        $server = $this->minecraftModel->addServer($name, $ip, $status, ($port === "" ? null : $port), ($cmwlPort === "" ? null : $cmwlPort));

        if(!empty($cmwlPort)){
            $this->sendFirstKeyRequest($server?->getServerId());
        }

        Response::sendAlert("success", "", LangManager::translate('minecraft.servers.toasters.server_add'), true);

        header("Location: ../servers");
    }

    //TODO Try to optimize that

    #[Link("/servers", Link::POST, [], "/cmw-admin/minecraft")]
    public function adminServersEdit(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "minecraft.edit");

        [$id, $name, $ip, $status, $port, $cmwlPort] = Utils::filterInput("serverId", "name", "ip", "status", "port", "cmwlPort");

        $this->minecraftModel->updateServer($id, $name, $ip, $status, ($port === "" ? null : $port), ($cmwlPort === "" ? null : $cmwlPort));

        Response::sendAlert("success", "", LangManager::translate('minecraft.servers.toasters.server_edit'), true);

        header("Location: servers");
    }

    #[Link("/servers/cmwl/test/:id", Link::GET, ["id" => "[0-9]+"], "/cmw-admin/minecraft")]
    public function checkCmwLConfig(int $serverId): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "minecraft.edit");

        try {
            $result = json_decode(@APIManager::getRequest("http://{$this->minecraftModel->getServerById($serverId)?->getServerIp()}:{$this->minecraftModel->getServerById($serverId)?->getServerCMWLPort()}"), true, 512, JSON_THROW_ON_ERROR);
            if ((int)$result['CODE'] === 200) {
                try {
                    print(json_encode("true", JSON_THROW_ON_ERROR));
                    Response::sendAlert("success", "", LangManager::translate('minecraft.servers.toasters.test_cmw_response_success'), true);
                } catch (JsonException) {
                }
            } else {
                try {
                    print(json_encode("false", JSON_THROW_ON_ERROR));
                    Response::sendAlert("error", "", LangManager::translate('minecraft.servers.toasters.test_cmw_response_error'), true);
                } catch (JsonException) {
                }
            }
        } catch (JsonException) {
        }
    }

    #[Link("/servers/list/", Link::GET, [], "/cmw-admin/minecraft")]
    public function getServersIdAndName(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "minecraft.list");

        $toReturn = [];

        foreach ($this->minecraftModel->getServers() as $server) {
            $toReturn[$server->getServerId()][] = $server->getServerName();
        }

        try {
            print (json_encode($toReturn, JSON_THROW_ON_ERROR));
        } catch (JsonException) {
        }
    }

    /**
     * @param int $serverId
     * @return void
     * @desc Send the first key request to instanciate the connexion between server and website.
     */
    private function sendFirstKeyRequest(int $serverId): void
    {
        try {
            $res = @APIManager::postRequest("http://{$this->minecraftModel->getServerById($serverId)?->getServerIp()}:{$this->minecraftModel->getServerById($serverId)?->getServerCMWLPort()}/core/generate/firstKey",
                ["key" => $this->generateCmwLinkPrivateKey(), "domain" => $_SERVER['HTTP_HOST']]);

            $code = json_decode($res, JSON_THROW_ON_ERROR, 512, JSON_THROW_ON_ERROR)['CODE'];

            // TODO TOASTERS WITH ERRORS
            switch ($code){
                case "200":
                    //good
                    break;
                case "401":
                    // header pas bon ou ip pas bon (non-authorized)
                    break;
                case "404":
                    // Route non trouvé
                    break;
                case "418":
                    // Erreur interne
                    break;
                default:
                    // Erreur non identifié
                    break;
            }

        } catch (JsonException) {
        }
    }

    /**
     * @return string
     * @desc Generate unique private key for your cmw link plugin (base64 encoded for better url support)
     */
    private function generateCmwLinkPrivateKey(): string
    {
        return base64_encode(password_hash(Utils::genId(15) . uniqid('', true), PASSWORD_BCRYPT));
    }


}